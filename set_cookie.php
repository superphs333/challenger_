<?php
    include "./db.php";
    include "./phpfunction.php";

    if(isset($_GET['id'])){ 
        $user = $_GET['id'];

        // 이미 http헤더가 전송되었다면, true를 반환한다
        if(headers_sent($file,$line)){
            echo "쿠키를 생성 할 수 없습니다";
        }else{
            // id 암호화하기
            $encryptedid = Encrypt($user);

            // 쿠키에는 암호화 된 id를 저장함
            setcookie("user_id_cookie",$encryptedid,time()+3600*24*7,"/");

            // Home으로 이동
            echo "<script>
            location.href='togetherchallenge.php'
            </script>";
        }
    }else{ // 쿠키 없는상태
        echo "<script>alert('fail');location.href='./login.php';</script>";
    }
?>