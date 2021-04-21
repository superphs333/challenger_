
<html>
<head>
</head>
<body>


<?php
$sql = mq("select * from members where email='{$_SESSION['user']}'");

if($sql){
    $pw = $sql->fetch_array();
    $pw = $pw['pw'];
}else{ echo mysqli_error($db);}
?>

<!-- 현재 비밀번호 입력 -->
<div class="form-group">
<label for="PasswordNow">현재 비밀번호</label>
<input type="password" class="form-control" id="nowPassword"
    placeholder="현재 비밀번호를 입력해주세요" required>
</div>
<!-- 새 비밀번호 -->
<div class="form-group">
    <label for="inputPassword">비밀번호</label>
    <input type="password" class="form-control" id="inputPassword"
        placeholder="영문(대소문자)포함, 숫자, 특수문자를 혼합하여 공백없이 8~20자를 입력해주세요" name="pw" required>
    <!-- 비밀번호 정규식 체크 부분 -->
    <div id="pwtext"></div>
</div>

<!-- 비밀번호 확인 -->
<div class="form-group">
    <label for="inputPasswordCheck">비밀번호 확인</label>
    <input type="password" class="form-control" id="inputPasswordCheck"
        placeholder="비밀번호 확인을 위해 다시한번 입력 해 주세요" name="pwcheck" required>
    <input type="hidden" id="forcheck" placeholder="">
    <!--2개의 비밀번호값이 일치할때는 "비밀번호가 일치합니다."라는 글을 보이게 하고, 다를때는 "비밀번호가 일치하지 않습니다."라는 글이 보이게 한다-->
    <div class="alert alert-success" id="alert-success">
        비밀번호가 일치합니다.
    </div>
    <div class="alert alert-danger" id="alert-danger">
        비밀번호가 일치하지 않습니다.
    </div>
</div>

<button  id="pwchangebtn" class="btn btn-primary">비밀번호 변경<i class="fa fa-check spaceLeft"></i></button>
</body>
</html>