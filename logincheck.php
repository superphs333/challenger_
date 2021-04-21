<?php
    include "./db.php";

    // 각 변수에 받아온 값들을 저장
    $email = $_POST['inputEmail'];
    $pw = $_POST['inputPassword'];
    //echo "email=".$email." pw=".$pw;

    // sql문으로 통해 post로 받아온 이메일이 있는지 확인
    $sql = mq("select * from members where email='{$email}'");

    // 받아온 이메일이 존재하는지 확인하기 위한 변수
    $checkexist = $sql->num_rows;

    // 받아온 이메일 존재하는지 확인
    if($checkexist==0){ // 존재하지 않음 => 다시 로그인페이지로
        //echo "해당 이메일 존재x";
        echo "
        <script>
            alert('존재하지 않는 이메일입니다.'); 
            history.back();
        </script>
        ";
    }else{// 존재함 => 홈페이지로
        // 해당 이메일의 닉네임, pw를 받는다
        $row = $sql->fetch_array();
        $nickname = $row['id']; // 닉네임
        $rightpw = $row['pw']; // 패스워드

        // 해당 이메일의 패스워드 = 로그인페이지에서 입력한 패스워드 일치 여부 확인
        if(password_verify($pw,$rightpw)){//로그인 성공
            //echo "비밀번호 일치";

            // 세션 생성
            $_SESSION['user'] = $row['email'];
            //echo $_SESSION['user'];

            //로그인 유지의 경우
            if($_POST['remainlogin']=="yes"){
                echo"<script>
                console.log('로그인 유지의 경우');
                location.href='./set_cookie.php?id={$_SESSION['user']}';
                </script>";
            }

            echo "
            <script>
                alert('로그인되었습니다.');
                location.href='./togetherchallenge.php';
            </script>
            ";

        }else{
            //echo "비밀번호 일치x";
            echo "
            <script>
                alert('비밀번호를 다시 입력하세요.'); history.back();
            </script>
            ";
        }
    }

?>