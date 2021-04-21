
<html>
<head>
</head>
<body>
<h4 style="margin-top:10px;">닉네임</h4>
<!-- 현재닉네임 -->
<?php
$sql = mq("select * from members where email='{$_SESSION['user']}'");

if($sql){
    $nickname = $sql->fetch_array();
    $nickname = $nickname['nickname'];
    //echo $nickname;
}else{ echo mysqli_error($db);}
?>
<input type="hidden" value="<?php echo $nickname ?>" id="nownickname">
<input type="text" id="inputNickname" placeholder="닉네임을 입력해주세요(공백과 특수문자는 입력 불가능합니다)" name="nickname"  value="<?php echo $nickname ?>" required>
<!-- 닉네임 중복 문구 체크해주는 부분 -->
<span id="nick_check" style="width:30%;"></span>
<button id="nickupdate" style="float:right; margin-top:10px;">변경하기</button>
</body>
</html>