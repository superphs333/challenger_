<?php
include "db.php";

// 받아온 값 : {textstring:textstring,imgsrcarr:imgsrcarr,idxarray:idxarray}
$textstring = $_POST['textstring'];
$imgsrcarr = $_POST['imgsrcarr'];
$idxarray = $_POST['idxarray'];
// echo "textstring=".$textstring.","."imgsrcarr=".$imgsrcarr.","."idxarray=".$idxarray;

// 문자열 -> 배열변환
$textstring=explode('№',$textstring);
// 이미지 -> 배열변환
$imgsrcarr = explode('№',$imgsrcarr);
// idx -> 배열변환
$idxarray = explode('№',$idxarray);

/*
데이터베이스에 반영하기
*/
for($i=0; $i<count($imgsrcarr); $i++){
    $text = $textstring[$i];
    $shot = $imgsrcarr[$i];
    $idx = $idxarray[$i];

    // 데이터베이스 수정
    $temp = "UPDATE challengeshot SET detail='{$text}', shot='{$shot}' WHERE idx={$idx}";
    $sql = mq($temp);
        if(!$sql){
        echo mysqli_error($db);
    }else{
        echo "su";
    }
}
?>