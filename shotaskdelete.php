<?php
Include "./db.php";
Include "./phpinfo.php";

// 받아온 정보
$shotaskidx = $_POST['idx']; // 인증샷 문의사항 번호

// 데이터 삭제
$temp = "DELETE FROM shotask WHERE idx={$shotaskidx}";
$sql = mq($temp);
if($sql){ // 삭제성공?>

<table id="myshotasktable">
    <thead>
        <tr>
            <th>문의번호</th>
            <th>인증샷</th>
            <th>챌린지명</th>
            <th>내용</th>
            <th>답변여부</th>
            <th>수정/삭제</th>
        </tr>
    </thead>
    <?php
    $sql = mq("select * from shotask where user='{$_SESSION['user']}'");
    while($row = $sql->fetch_array()){ 

    /*
    정보 가져오기
    */
    $idx = $row['idx']; // 문의내역넘버
    $challengeshotidx = $row['challengeshotidx']; // 챌린지샷 idx
    $challengeidx = $row['challengeidx']; // 챌린지 idx
    $content = $row['content']; // 나의 문의 내역
    $answer = $row['answer']; // 답변 여부
    ?>
    <tbody>
        <tr>
            <!-- 문의번호 -->
            <td class="shotaskidxinfo"><?php echo $idx ?></td>
                
            <!-- 인증샷 -->
            <td>
            <?php
                // 챌린지 idx로 => 인증샷 src 가져오기
                $forsrc = mq("select * from challengeshot where idx={$challengeshotidx}");
                $forsrc = $forsrc->fetch_array();
                $video = $forsrc['video']; // 사진or비디오
                $src = $forsrc['shot']; // 샷주소

                if($imgorvideo==0){  // 사진인증인 경우 ?>
                <img src="<?php echo $src ?>">
                <?php }else{//비디오 인증인 경우 ?>
                <video src="<?php echo $src ?>" controls>해당 브라우저는 video 태그를 지원하지 않습니다</video>
                <?php }
                
            ?>
            </td>

            <!-- 챌린지명 -->
            <td>
                <?php
                // 챌린지 idx => 챌린지명 알아내기
                $forchallengename = mq("select * from challenge where idx={$challengeidx}");
                $forchallengename = $forchallengename->fetch_array();
                $challengename = $forchallengename['title'];                    
                ?>
                <a href="challengeread.php?idx={$challengeidx}"><?php echo $challengename; ?></a>
            </td>

            <!-- 내용 -->
            <td>
                <?php
                if(strlen($content)>10)
                { 
                    //title이 30을 넘어서면 ...표시
                    $content=str_replace($content,mb_substr($content,0,10,"utf-8")."...",$content);
                }
                echo $content;
                ?>
            </td>

            <!-- 답변여부 -->
            <td>
            <?php
            if($answer==0){
                echo "답변대기중";
            }else{
                echo "답변완료";
            }
            ?>
            </td>

            <!-- 수정, 삭제 -->
            <td>
                <button class="myshotaskupdate">수정</button>
                <button class="myshotaskdelete">삭제</button>
            </td>
        </tr>
    </tbody>
    <?php } ?>
            
</table>
    
<?php }else{ // 삭제 실패
    echo $temp;
    echo mysqli_error($db);
}
?>