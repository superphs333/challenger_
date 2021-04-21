<?php
// 관리자 계정이 아니라면, 뒤로가기
if($_SESSION['user']!="admin@challenger.com"){
    echo "권한없음";
    echo"<script>
    alert('권한없는 페이지 입니다');
    history.back();
    </script>";
}

// 챌린지 목록 불러오기
$temp = "select * from challenge order by idx desc";
$sql = mq($temp);
while($row=$sql->fetch_array()){
    // 가져올 정보
        // idx(링크), 
    
}
?>