<?php
include_once './db.php';



// 세션이 존재하지 않으면 접근불가(멤버만 사용 가능)
if($_SESSION['user']==""){
    echo"<script>
    alert('회원만 이용 가능한 페이지입니다');
    history.back();
    </script>";
}

// 회원정보 삭제하기
$sql = mq("DELETE FROM members WHERE email='{$_SESSION['user']}'");
if($sql){
        
    // 세션삭제
    unset($_SESSION['user']);

    // 쿠키삭제하기
    setcookie("user_id_cookie","",time()-3600,"/");

    echo <<<HTML
    <script>
        alert("정상적으로 회원탈퇴가 되었습니다.");
        location.href = './togetherchallenge.php';
    </script>
HTML;
}else{   
    echo <<<HTML
    <script>
        alert("오류가 발생하였습니다");
    </script>
HTML;
}


?>