<?php
    session_start();
    $conn = mysqli_connect("localhost", "root", "37LmrYOZzCH7", "YONSEIEDU_DB"); // check mariaDB password
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CDN -->
    <script src="https://kit.fontawesome.com/7cc77c19eb.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="../js/jquery.tablesorter.js" type="text/javascript"></script> <!-- Should check file directory -->

    <style>
        * {
            font-family: 'Noto Sans KR', sans-serif;
        }
    </style>
    <title>연세에듀수학</title>
</head>

<body>
    <?php
        if(!isset($_SESSION['name'])) {
            ?>
            <script type="text/javascript">
                alert("로그인을 해주세요. 선생님 이외에는 사용할 수 없습니다.");
                location.href="loginpage.php";
            </script>
            <?php
        } else {
    ?>
    <header>
        <nav class="navbar navbar-expand-md navbar-light bg-light">
            <a href="index.php" class="navbar-brand">연세에듀수학 관리</a> <!-- Should change file name to php -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controles="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a href="member.php" class="nav-link">원생관리 <i class="fas fa-users"></i></a> <!-- Should change file name to php -->
                    </li>
                    <li class="nav-item">
                        <a href="attendance.html" class="nav-link">출결관리 <i class="fas fa-clipboard-check"></i></a> <!-- Should change file name to php -->
                    </li>
                    <li class="nav-item">
                        <a href="lately.html" class="nav-link">최근출결 <i class="fas fa-user-check"></i></a> <!-- Should change file name to php -->
                    </li>
                    <li class="nav-item">
                        <a href="smspage.php" class="nav-link">단체문자 <i class="fas fa-sms"></i></a> <!-- Should change file name to php -->
                    </li>
                    <li class="nav-item">
                        <a href="tuition.php?page=1&grade=0" class="nav-link">교육비관리 <i class="fas fa-money-check-alt"></i></a> <!-- Should change file name to php -->
                    </li>
                    <li class="nav-item">
                        <a href="timetable.html" class="nav-link">시간표관리 <i class="fas fa-clock"></i></a> <!-- Should change file name to php -->
                    </li>
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link">로그아웃 <i class="fas fa-sign-out-alt"></i></a> <!-- Should change file name to php -->
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="text" name="name" placeholder="이름" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit" onclick="search();">원생검색</button>
                </form>
            </div>
        </nav>
    </header>
    <main role="main">
        <?php
            if(isset($_GET['num'])) {
                $sql = "SELECT * FROM STUDENT_TB WHERE stunum='{$_GET['num']}'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_array($result);
                if($row != NULL) {
        ?>
        <div class="container text-center h1 mt-3 mb-4">
            교육비 납부확인(<?php echo '<a href="memberinfo.php?num='.$_GET['num'].'">'.$row['name'].'</a>'; ?>)
        </div>
        <div class="container">
        <?php
            $date = date("Y-m");
            $sql = "SELECT * FROM TUITION_TB WHERE stunum = '{$_GET['num']}' AND date LIKE '{$date}%'";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) == 0) {
            ?>
                <form action="checktui.php?num=<?php echo $_GET['num']; ?>" method="post">
                    <div class="form-group row">
                        <label for="inputDate" class="col-sm-2 col-form-label">날짜</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="date" name="date" id="inputDate" required>
                        </div>
                    </div>
                    <fieldset class="form-group">
                        <div class="row">
                            <legend class="col-form-label col-sm-2 pt-0">결재방법</legend>
                            <div class="col-sm-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="met" id="method1" value="0" required>
                                    <label class="form-check-label" for="method1">
                                        현금
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="met" id="method2" value="1">
                                    <label class="form-check-label" for="method2">
                                        계좌이체
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="met" id="method3" value="2">
                                    <label class="form-check-label" for="method3">
                                        카드
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="met" id="method4" value="3">
                                    <label class="form-check-label" for="method4">
                                        기타
                                    </label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <button type="submit" class="btn btn-primary">완료</button>
                </form>
                <script type="text/javascript">
                    document.getElementById('inputDate').value = new Date().toISOString().substring(0, 10);
                </script>
            <?php
            }
            else {
                $row = mysqli_fetch_array($result);
            ?>
                <h5>이미 납부가 확인되었습니다.</h5>
                납부일 : <?php echo $row['date']; ?>
                <br>
                납부방법 : <?php $m = $row['method']; if($m == 0) echo "현금"; else if($m == 1) echo "계좌이체"; else if($m == 2) echo "카드"; else echo "기타"; ?>
            <?php
            }
        ?>
        </div>
        <?php
            } else {
                echo '<div class="container mt-4"><a href="#" onclick="history.back();">학생의 이름이나 전화번호가 잘못되었습니다.</a>선택한 전화번호 : '.$_GET['num'].'</div>';
            }
        } else {
            echo '<div class="container mt-4"><a href="#" onclick="history.back();">학생을 선택해주세요.</a></div>';
        }
        ?>
    </main>
    <?php
        }
    ?>
    <script type="text/javascript">
        function search(){
            var value=$('input[name=name]').val();

            if(value!=""){
                location.href="findmember.php?name="+value;
            }
        }
    </script>
</body>

</html>
