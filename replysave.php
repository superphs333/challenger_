<?php
    // db, phpfunction 파일 불러오기.
    include "./db.php";
    include "./phpfunction.php";

    // post로 받아온 값
    $category = $_POST['category']; // 카테고리
    $bno = $_POST['bno']; // 글번호
    $content = $_POST['content']; // 댓글내용 
    //echo "category=".$category."bno=".$bno."content=".$content;

    // 지금 session에 맞는 닉네임 찾기(글쓴이)
    $email = $_SESSION['user'];
    $fornick = mq("select * from members where email='{$email}'"); 
    $nick = $fornick->fetch_array();
    $nick = $nick['nickname'];
    
    // 날짜
    $date = date("Y-m-d H:i:s");

    /*
    데이터 넣기
    */
    $sql = mq("insert into ".$category."_reply(con_num, email,content, date)
    values('{$bno}','{$email}','{$content}','{$date}')");
    if(!$sql){ // 데이터베이스 삽입 실패
        echo mysqli_error($db);
    }else{ //데이터베이스 삽입 성공
        // 성공한 쿼리를 기반으로, 댓글의 idx값을 얻어온다(email, 시간바탕)
        $foridx = mq("select * from ".$category."_reply where email='{$email}' and date='{$date}'"); 
        $replyidx = $foridx->fetch_array();
        $replyidx = $replyidx['idx'];
        //echo "댓글번호=".$replyidx;
    }

include "comment.php";
?>