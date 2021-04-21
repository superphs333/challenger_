
<html>
<head>
<!-- css -->
<link rel="stylesheet" href="./whole.css">
</head>
<body>
<?php
    include "./top.php";
?>
<div id="findpw">
    <h2>비밀번호 찾기</h2>
    <input id="inputemail" type="email" style="width:40%;"><br><br>
    가입하신 이메일을 입력하시면<br>
    임시 비밀번호를 이메일로 보내드리겠습니다<br>
    (3초 정도 기다려야 할 수 있습니다)<br><br>
    <button id="emailsend">전송하기</button>
</div>


<!-- 자바스크립트 파일 -->
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="./findpsw.js"></script>
<!-- 메일 -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/emailjs-com@2.3.2/dist/email.min.js"></script>
</body>
</html>