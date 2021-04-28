<?php
Include "./db.php";
Include "./phpinfo.php";

// 받아온 정보
$shotaskidx = $_POST['shotaskidx']; // 인증샷 문의사항 번호
$challengeshotidx = $_POST['challengeshotidx'];// 인증샷 번호 정보
echo $challengeshotidx;
$answer = $_POST['answer']; // 내용
$fit = $_POST['fit']; // 적합처리
if($fit==""){$fit=0;} // null일 때는 여전히 부적합 처리
//echo $fit;
// 데이터 update
$temp = "UPDATE shotask SET answercontent='{$answer}',answer='1' ,handle='{$fit}' WHERE idx={$shotaskidx}";
$sql = mq($temp);
//echo $temp."<br>";
if($sql){ // sql문 성공

    // 인증샷의 적합/부적합 변경하기
    if($fit=="1"){
        //echo "적합/부적합 여부 변경됨";
        $temp = "UPDATE challengeshot set fit='{$fit}' where idx={$challengeshotidx}";
        $sql = mq($temp);
        if($sql){
            echo "성공";
        }else{echo mysqli_error($db);}
    }

    //창닫기
    echo "<script>
    alert('성공적으로 답변이 작성되었습니다');
    window.close()
    </script>";
}else{
    echo mysqli_error($db);
}
?>