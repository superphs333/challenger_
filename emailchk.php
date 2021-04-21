<?php
    // 이메일 중복을 확인하고, 임시문자를 보내주는 파일
    Include './db.php';
    Include './phpfunction.php';

    // 받은 정보 {signup_InputEmail : signup_InputEmail}
    $email = $_POST['signup_InputEmail'];
    
    // 이메일이 존재하는지 확인하기
    $sql = mq("select * from members where email='{$email}'");
    $emailduplicatechk = $sql->num_rows;
        // 1 => 이미 존재하는 이메일
        // 0 => 사용 가능한 이메일
    if($emailduplicatechk==1){
        echo "이미 존재하는 이메일입니다.";
   
    }else if($emailduplicatechk==0){
        // 임시문자
        $temp = random_char();
        echo $temp;
    }
?>