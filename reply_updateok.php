<?php
    // db, phpfunction 파일 불러오기 
    include "./db.php";
    include "./phpfunction.php";

    // 가져온 값
    $category = $_POST['category'];
    $number = $_POST['number'];
    $info = mq("select * from ".$category."_reply where idx='{$number}'");
    $row = $info->fetch_array();
    $writer = $row['email'];
    $content = $_POST['content'];
    $date = $row['date'];
    // 데이터베이스에 저장되어 있는 email을 기반으로 닉네임을 알아낸다
    $fornick = mq("select * from members where email='{$writer}'");
    $nick = $fornick->fetch_array();
    $nick = $nick['nickname'];

    // 데이터베이스에 수정 된 정보 반영하기
    $updatecontnet = mq("update ".$category."_reply set content='{$content}' where idx={$number}");

    // $nick, $date, $replyidx, $content, $email
    $replyidx = $number;
    $email = $writer;

    include "./comment.php"
?>