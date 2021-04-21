<?php
    // db, phpfunction 파일 불러오기
    include "./db.php";
    include "./phpfunction.php";

    // 카테고리
    $category = $_POST['category'];

    // 댓글번호
    $idx = $_POST['number'];

    // 댓글삭제하기
    $sql = mq("delete from ".$category."_reply where idx={$idx}");

    if($sql){//삭제성공
        echo "su";
    }else{//삭제실패
        echo mysqli_error($db);
    }
?>