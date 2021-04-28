<?php
Include "./db.php";
Include "./phpinfo.php";
// user 
$user = $_SESSION['user'];
// 받아온 정보
$challengeidx = $_POST['challengeidx']; // 챌린지 idx
$challengeshotidx = $_POST['challengeshotidx']; //챌린지 shot idx
$content = $_POST['content']; // 내용
//echo "challengeidx=".$challengeidx."</br>"."challengeshotidx=".$challengeshotidx."</br>"."content=".$content."</br>";

// 데이터 저장 
$temp = "INSERT INTO `shotask`( `challengeidx`, `challengeshotidx`, `content`,`user`) VALUES ($challengeidx,$challengeshotidx,'$content','$user')";
$sql = mq($temp);
//echo $temp."<br>";
if($sql){ // sql문 성공
    // 창닫기
    echo "<script>
    alert('성공적으로 문의 작성');
    window.close()
    </script>";
}else{
    echo mysqli_error($db);
}
?>