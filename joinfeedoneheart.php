<?php
// db, phpfunction 파일 불러오기
include "./db.php";
include "./phpfunction.php";

// ajax로 받아온 정보 {shotidx:shotidx,check:check,category:category,sort:sort}
$category = $_POST['category'];
$check = $_POST['check'];
$idx = $_POST['shotidx'];
$sort = $_POST['sort'];
$user = $_SESSION['user'];

// 데이터베이스에 반영 / 게시글 하트수 반영
if($check==1){ // 내려야 하는 경우
$temp = "DELETE from {$category}_{$sort} where idx='{$idx}' and user='{$user}'";
}else{ // 올려야 하는 경우
$temp = "INSERT INTO {$category}_{$sort}(idx,user) values('{$idx}','{$user}')";
}
//echo $temp;
$sql = mq($temp);
if(!$sql){ // 반영 실패
    echo mysqli_error($db);
}else{ // 반영 성공 -> 북마크 수 반영
    $temp = "select * from {$category}_{$sort} where idx='{$idx}'";
    $sql = mq($temp);

    if(!$sql){ // sql 에러
        echo mysqli_error($db);
    }else{ // sql 에러x
        // 현재 북마크 수
        $heartcount = mysqli_num_rows($sql);
        echo $heartcount;

        if($category=="challengeshot" && $sort=="siren"){


            $temp = "UPDATE challengeshot set report={$heartcount} where idx={$idx}";
            $sql = mq($temp);
            if($sql){
                //echo "su";
            }else{
                echo mysqli_error($db);
            }
        }
    }
}
?>