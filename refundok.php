<?php
// db파일 불러오기
Include "db.php";

// 값 불러오기 : {sort:sort,refundidx:refundidx}
$refundidx = $_POST['refundidx'];
$sort = $_POST['sort'];

// 위 IDX값을 통해, refund fix값 고치기
if($sort=="수락"){
    $fit = 1;
}else if($sort=="거절"){ 
    $fit = 0;
}

// mysql 반영
$sql = mq("UPDATE refund set fit='{$fit}'  where idx={$refundidx}");

if($sql){
    echo "성공";
}else{
    echo "실패";
}
?>