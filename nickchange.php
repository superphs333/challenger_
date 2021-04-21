<?php
    include "db.php";
    
    // 받아온 데이터 futurenickname:futurenickname
    $futurenickname = $_POST['futurenickname'];
    echo $futurenickname;

    // 닉네임 변경
    $sql = mq("update members set nickname='{$futurenickname}' where email='{$_SESSION['user']}'");

    // if($sql){
    //     echo "su";
    // }else{
    //     echo mysqli_error($db);
    // }
   
?>