<?php
    session_start();
    unset($_SESSION['user']);

    // 쿠키삭제하기
    setcookie("user_id_cookie","",time()-3600,"/");
?>
<meta http-equiv="refresh" content="0; url=togetherchallenge.php" />