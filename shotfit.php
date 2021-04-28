<?php
// db, phpfunction 파일 불러오기 
include "./db.php";
include "./phpfunction.php";

// ajax로 받아온 정보 {idx:idx,check:check}
$idx = $_POST['idx']; // idx
$check = $_POST['check']; // check(1:적합,0:부적합)
//echo "idx=".$idx;

// 데이터베이스에 반영
if($check=="1"){ // 적합인 경우
    $check = 0;
}else{ // 부적합인 경우
    $check = 1;
}

$temp = "UPDATE challengeshot SET fit='{$check}' WHERE idx={$idx}";
//echo $temp;
$sql = mq($temp);

if($sql){
    echo $check;
}else{
    echo mysqli_error($db);
}
?>