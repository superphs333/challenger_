<?php
// db, phpfunction 파일 불러오기
include "./db.php";
include "./phpfunction.php";

// ajax로 받아온 정보 : {idx:idx,check:check,category:category}
$category = $_POST['category'];
$check = $_POST['check'];
$idx = $_POST['idx'];
$user = $_SESSION['user'];
//echo "check=".$check."idx=".$idx."user=".$user."idx=".$idx;

/*
데이터베이스에 반영하기 / 게시글 하트수 반영
*/
if($check==1){ // 내려야 하는 경우
    //echo "내려야하는경우";
    $temp = "DELETE from {$category}_bookmark where idx='{$idx}' and user='{$user}'";
    //echo $temp;
    $sql = mq($temp);
    
}else if($check==0){ // 올려야 하는 경우
    //echo "올려야하는경우";
    $temp = "INSERT INTO {$category}_bookmark(idx,user) values('{$idx}','{$user}')";
    //echo $temp;
    $sql = mq($temp);
}

/*
북마크 수 
*/
$temp = "select * from {$category}_bookmark where idx='{$idx}'";
$sql = mq($temp);

if($sql){ // sql에러 x
// 현재 북마크 수
$bookmarkcount= mysqli_num_rows($sql);
echo $bookmarkcount;
}else{ // sql 에러 o
echo mysqli_error($db);
}

?>