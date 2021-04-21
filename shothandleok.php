<?php
include "db.php";
include "phpfunction.php";

// 가져온 정보
$checkboxstring = $_POST['checkboxstring'];
//echo $checkboxstring;

// string -> 배열
$checkboxarray = explode("№",$checkboxstring);
//var_dump($checkboxarray);

// 위의 배열을 따로 배열에 넣어준다(모든 인증샷을 다 돌수는 없으닌까)
    // ex) a->checkboxarray[0], ....
$arr = array();
for($i=0; $i<count($checkboxarray); $i++){
    $arr[$i] = $checkboxarray[$i];
}
//var_dump($arr);

// 데이터베이스에 반영하기
for($i=0; $i<count($arr); $i++){
$temp = "UPDATE challengeshot SET handleok='1' WHERE idx=$arr[$i]";
echo $temp."<br>";

$sql = mq($temp);

if($sql){
    echo "su</br>";
}else{
    echo mysqli_error($db)."</br>";
}
}
?>