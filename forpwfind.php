<?php
    include "./db.php";
    include "./phpfunction.php";

    // 입력 받은 정보{email:email}
    $email = $_POST['email'];
    //echo $email;
    // 이메일이 존재하는지 확인한다
    $sql = mq("select * from members where email='{$email}'");
    if(mysqli_num_rows($sql)!=0){ // 이메일 존재
        // 임시 비밀번호 생성
        $pw = random_char();

        // 데이터베이스에는 해당 비밀번호로 업데이트
        $hashpw = password_hash($pw,PASSWORD_DEFAULT);

        // 데이터베이스에 비밀번호 변경하기
        $forupdatesql = mq("update members set pw='{$hashpw}' where email='{$email}'");

        // 쿼리문 성공 여부
        if($forupdatesql){ // 쿼리문 성공
            echo $pw;
        }else{ // 쿼리문 실패
            echo mysqli_error($db);
        }
    }else{ // 이메일 존재 x
        echo "해당 이메일은 존재하지 않습니다.";
    }
?>