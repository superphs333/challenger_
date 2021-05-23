<?php
    Include_once 'db.php';
    Include_once 'phpfunction.php';

    // 현재 쿠키값이 있다면, 그 값을 세션값으로 넣어준다
    inputsession();

    // 현재 $_SESSION['user']에 맞는 닉네임을 가져온다
    if(!empty($_SESSION['user'])){
        $sql = mq("select * from members where email='{$_SESSION['user']}'");
        $row = $sql->fetch_array();
        $nickname = $row['nickname'];
    }
    
?>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- <title>Bootstrap 101 Template</title> -->
<!-- 합쳐지고 최소화된 최신 CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<!-- 부가적인 테마 -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
 <!-- 외부 css 불러오기 -->
 <link rel="stylesheet" type="text/css" href="./whole.css" />
<style>

</style>

</head>
<body>
    
<div>
    <nav class="navbar navbar-inverse" role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="togetherchallenge.php" class="navbar-brand easysLogo">Challenger</a>
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <!-- 만약 session값이 없다면, 나의 도전 노트 안보이기 -->
            <?php 
        
            if(isset($_SESSION['user'])){
                echo "userㅌ";
            }else{
                echo "no";
            }
            ?>
            
            <ul class="nav navbar-nav">
                <li><a href="mychallengenote.php">나의도전노트</a></li>
                <!-- <li><a href="#">다른사람도전노트</a></li> -->
                <!-- <li><a href="togetherchallenge.php">같이도전</a></li> -->
                <!-- <li><a href="ask.php">문의사항</a></li> -->
                <?php 
                if($_SESSION['user']=="admin@challenger.com"){?>
                    <li><a href="challengemanagement.php">관리페이지</a></li>
                <?php } ?>    
                <!-- <?php 
                if($_SESSION['user']!=""){?>
                    <li><a href="https://13.209.234.165:3000/?name=<?php echo $nickname ?>">채팅</a></li> 
                <?php } ?>  -->
                                 
            </ul>
        </div>
        <!-- SESSION['user']존재하면, ~님 환영합니다
        존재하지 않다면, 로그인 | 회원가입 -->
        <div id="topdiv">
            <?php
                if(empty($_SESSION['user'])){ ?>
                <a href="login.php">로그인</a> | <a href="signup.php">회원가입</a>
            <?php }else{ ?>
                <a href="mypage.php"><?php echo $nickname; ?></a>
                님 환영합니다
            <?php } ?>
        </div>
        
    </nav>
</div>


















<!-- 제이쿼리 -->
<script  src="https://code.jquery.com/jquery-latest.min.js"></script>
<!-- 합쳐지고 최소화된 최신 자바스크립트 -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
</body>
</html>