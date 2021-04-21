<?php
include "db.php";
include "phpfunction.php";

// 값 받기 {idx:idx}
$idx = $_POST['idx'];
echo $idx;
// merchant_uid 받아오기(by 챌린지 idx, 유저)
$temp = "select * from individualchallenge where joiner='{$_SESSION['user']}' and challengeidx={$idx}";
echo $temp;
$temp = mq($temp);
var_dump($temp);
$merchant_uid = $temp->fetch_array();
$merchant_uid = $merchant_uid['merchant_uid'];


// 데이터 반영하기
$sql = mq("insert into refund(challengeidx,user,merchant_uid) values({$idx},'{$_SESSION['user']}','{$merchant_uid}')");
if($sql){
    echo "su";
}else{
    echo mysqli_error($db);
}
?>