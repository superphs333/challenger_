<?php
    // db, phpfunction 파일 불러오기
    include "./db.php";
    include "./phpfunction.php";

    // post로 가져온 값
    $category = $_POST['category'];
    $number = $_POST['number'];

    // number을 통해 원래의 값 가져오기
    $info = mq("select * from ".$category."_reply where idx='{$number}'");
    $row = $info->fetch_array();
    $writer = $row['email'];
    $content = $row['content'];
    $date = $row['date'];
    // 데이터베이스에 저장되어 있는 email을 기반으로 닉네임을 알아낸다
    $fornick = mq("select * from members where email='{$writer}'");
    $nick = $fornick->fetch_array();
    $nick = $nick['nickname'];

    // $nick, $date, $replyidx, $content, $email
    $replyidx = $number;
    $email = $writer;

    include "./comment.php"
?>