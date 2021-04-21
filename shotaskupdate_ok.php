<?php
Include "./db.php";
Include "./phpinfo.php";

// 받아온 정보
$shotaskidx = $_POST['shotaskidx']; // 인증샷 문의사항 번호
$content = $_POST['content']; // 내용

// 데이터 update
$temp = "UPDATE shotask SET content='{$content}' WHERE idx={$shotaskidx}";
$sql = mq($temp);
//echo $temp."<br>";
if($sql){ // sql문 성공
    // 창닫기
    echo "<script>
    alert('성공적으로 수정되었습니다.');
    window.close()
    </script>";
}else{
    echo mysqli_error($db);
}
?>