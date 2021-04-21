<?php
    // db, phpfunction 파일 불러오기
    include "./db.php";
    include "./phpfunction.php";

    // 받은 값 불러오기 {nowPassword:nowPassword,pw:pw}
    $nowPassword = $_POST['nowPassword'];
    $pw = $_POST['pw'];
    //echo "nowPassword=".$nowPassword."</br>"."pw=".$pw."</br>";

    // 현재 로그인 되어 있는 정보 불러오기
    $sql = mq("select * from members where email='{$_SESSION['user']}'");

    if($sql){ // sql문 성공
        $nowrealpw=$sql->fetch_array();
        $nowrealpw=$nowrealpw['pw']; // 현재 유저의 비밀번호

        /*
        현재 비밀번호와 입력값으로 받아온 비밀번호가 같은 경우에만, 비밀번호 비교 -> 비밀번호 변경
        */
        if(password_verify($nowPassword,$nowrealpw)){// 일치하는 경우
            $pw = password_hash($pw,PASSWORD_DEFAULT);
            $sql=mq("update members set pw='{$pw}' where email='{$_SESSION['user']}'");

            if($sql){
                echo "비밀번호가 성공적으로 변경되었습니다";
            }else{
                echo "오류가 발생하였습니다";
            }

        }else{ // 일치하지 않는 경우 => 알림창
            echo "비밀번호가 일치하지 않습니다";
        }
    }else{ // sql문 실패
        // 오류출력
        echo mysqli_error($db);
    }

?>