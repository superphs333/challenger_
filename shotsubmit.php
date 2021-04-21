<?php
include "db.php";

// 받아온 값 : {idx:idx,textstring:textstring,imgsrcarr:imgsrcarr,dates:dates,fits:fits,videocheck:videocheck}
$idx = $_POST['idx'];
$textstring = $_POST['textstring'];
$imgsrcarr = $_POST['imgsrcarr'];
$dates = $_POST['dates'];
$fits = $_POST['fits'];
//echo "idx=".$idx.","."textstring=".$textstring.","."imgsrcarr=".$imgsrcarr;

/*
공통사항 : challengeidx, joiner, bigorder, date
*/
// 날짜, 작성자
$date = date("Y-m-d");// 날짜
$joiner = $_SESSION['user'];//작성자
// bigorder을 알아야 함 -> 현재 저장되어 있는 값중에 가장 큰 값+1
$temp = "select MAX(bigorder) from challengeshot where challengeidx={$idx} and joiner='{$joiner}'";
//echo $temp;
$sql = mq($temp);
//if(!$sql){echo mysqli_error($db);}else{echo "su";}
$sql = $sql->fetch_array();
var_dump($sql);
$bigorder = $sql['MAX(bigorder)'];
if($bigorder==""){
    $bigorder = 0;
}else{
    $bigorder = $bigorder+1;
    echo "bigorder=".$bigorder;
}



/*
차이 : smallorder, shot, detail, fit
*/
// 문자열 -> 배열 변환
$textstring=explode('№',$textstring);
//var_dump($textstring);
// 이미지 -> 배열변환
$imgsarray = explode('№',$imgsrcarr);
//var_dump($imgsarray);

/*
데이터베이스에 저장
*/
for($i=0; $i<count($textstring); $i++){
    $smallorder = $i;
    $text = $textstring[$i];
    $shot = $imgsarray[$i];
    
    // 데이터베이스에 삽입
    $temp = "INSERT INTO challengeshot(challengeidx,joiner,bigorder,smallorder,detail,shot,date) VALUES({$idx},'{$joiner}',{$bigorder},{$smallorder},'{$text}','{$shot}','{$date}')";
    echo $temp;
    $sql = mq($temp);
    if(!$sql){
        echo mysqli_error($db);
    }else{
        echo "su";
    }
} 





// // 총아이템수 만큼 데이터베이스에 인증샷을 저장한다.
// $count = count($textstring);
// echo $count;
// for($i=0; $i<$count; $i++){
    
// }

// 데이터베이스에 수정하기(해당값 가져오기)
// $sql = "select * from individualchallenge where challengeidx={$idx} and joiner='{$joiner}'";
// $sql = "update individualchallenge set shots='{$imgsrcarr}', shotdetails='{$textstring}',shotdates='{$dates}',shotjudgments='{$fits}'";
// $sql = mq($sql);
// if(!$sql){
//     echo mysqli_error($db);
// }else{
//     echo "su";
// }
?>