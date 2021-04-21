<?php
    // 회원가입에 입력 한 정보를 db테이블에 기록해주는 목적

    include "./db.php";

    // 가져온 값들을 변수에 넣어준다(이메일, 닉네임, 비밀번호)
        // {email:email,nickname:nickname,pw:pw}
    $email = $_POST['email'];
    $nickname = $_POST['nickname'];
    $pw =  password_hash($_POST['pw'],PASSWORD_DEFAULT);

    // 받아온 값 확인
    // echo "nickname=".$nickname."<br/>"."email=".$email."<br/>"."pw=".$pw."<br/>";

    // db에 값 저장하기
    $sql = mq("insert into members(nickname,email,pw) values('{$nickname}','{$email}','{$pw}')");

    if($sql){
        echo "s";
    }else{
        echo mysqli_error($db);
    } 
?>