<?php

?>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bootstrap 101 Template</title>
<!-- 합쳐지고 최소화된 최신 CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<!-- 부가적인 테마 -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">

</head>
<body>

<?php
// 만약, 쿠키가 있으면, 바로 Home.php로 이동한다
if(isset($_COOKIE["user_id_cookie"])){
    Header("Location:./Home.php");
}
?>
    
<div class="container">

    <form class="form-signin" action="logincheck.php" method="post">
        <h1 style="text-align:center;">Challenger</h1>
        <h2 class="form-signin-heading">Please sign in</h2>

        <!-- 이메일 입력 -->
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" id="inputEmail" class="form-control" placeholder="Email address" name="inputEmail" required autofocus>

        <!-- 패스워드 입력 -->
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required>

        <!-- 자동로그인 -->
        <div class="checkbox">
            <label>
            <input type="checkbox" name="remainlogin" value="yes" <?php if(isset($_COOKIE["user_id_cookie"])){echo"checked";} ?> > 로그인 유지
            </label>
        </div>

        <!-- 로그인 버튼 -->
        <button class="btn btn-lg btn-primary btn-block" type="submit">로그인</button>
    </form>

    <!-- 로그인 -->
    <div style="margin-bottom:5px;"><a href="signup.php">회원가입</a></div>
    
    <a href="findpw.php">비밀번호 찾기</a>
</div> <!-- /container -->







<!-- 제이쿼리 -->
<script  src="https://code.jquery.com/jquery-latest.min.js"></script>
<!-- 합쳐지고 최소화된 최신 자바스크립트 -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

</body>
</html>