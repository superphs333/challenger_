<?php
// db, phpfunction 파일 불러오기
include "./db.php";
include "./phpfunction.php";

// idx값 받아오기
$idx = $_GET['idx'];
//echo $idx;

// 만약, 관리자가 아니라면, 접근불가
onlyadmin();

// 해당 idx에 해당하는 row삭제
$sql = mq("DELETE FROM challenge where idx={$idx}");

if($sql){ // sql문 성공시 => 커뮤니티 게시판으로 돌아가기
    //echo "su";
    echo "<script>alert('삭제가 완료되었습니다.');
    location.href='./togetherchallenge.php'
    </script>";
}else{ // sql문 실패시 =>communityread.php게시판으로 돌아가기
    echo "<script>
    alert('삭제 실패했습니다.');
    history.back();</script>";
}
?>