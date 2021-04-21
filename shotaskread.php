
<html>
<head>
    <!-- 외부 css 불러오기 -->
    <link rel="stylesheet" type="text/css" href="./whole.css" />
</head>
<body>
    <?php 
    Include "./db.php";
    Include "./phpfunction.php";

    // 관리자가 아니라면 권한없는 페이지 입니다.
    onlyadmin();

    // 가져온 shotask idx값
    $shotaskidx = $_GET['idx']; // 인증샷 문의 번호
    //echo "shotaskidx="+$shotaskidx."<br>";

    // 인증샷 idx 불러오기
    $sql = mq("select * from shotask where idx={$shotaskidx}");
    $sql = $sql->fetch_array();
    $shotidx = $sql['challengeshotidx']; // 챌린지 인증샷 idx
    //echo "shotidx="+$shotidx;
    $content=$sql['content']; // 내용
    
    
    // 해당 인증샷의 정보 불러오기
    $temp = "select c.title,c.video,cs.shot,cs.challengeidx,cs.idx,cs.joiner,cs.fit,c.endday,c.startday,cs.handleok from challengeshot as cs left join challenge as c on cs.challengeidx=c.idx where cs.idx={$shotidx}";
    $sql = mq($temp);
    $row = $sql->fetch_array();
    $video = $row['video']; // 비디오 or 사진
    $shot = $row['shot']; // 주소
    $challengetitle = $row['title']; // 챌린지 이름
    $challengeidx = $row['challengeidx']; // 챌린지의 idx
    ?>
    <h1>인증샷 문의 답변</h1>
    <hr>
    
    <form action="shotaskanswer.php" method="post">
        <!-- 개설 부분 -->
        <table id="shotask">

            <!-- 인증샷 번호 -->
            <tr>
                <td class="title">인증샷 번호</td>
                <td class="content">
                    <!-- 인증샷 번호 정보 -->
                    <input type="hidden" name="challengeshotidx" value="<?php echo $shotidx ?>">
                    <!-- 챌린지 번호 정보 -->
                    <input type="hidden" name="challengeidx" value="<?php echo $challengeidx ?>">
                    <!-- 문의사항 번호 정보 -->
                    <input type="hidden" name="shotaskidx" value="<?php echo $shotaskidx ?>">
                    <?php 
                    echo $shotidx."번";
                    ?>                
                </td>
            </tr>
            <tr>
                <td class="title">인증샷</td>
                <td class="content">
                    <?php
                    if($video==0){  // 사진인증인 경우 ?>
                    <img style="width:100px; height:100px" src="<?php echo $shot ?>">
                    <?php }else{//비디오 인증인 경우 ?>
                    <video style="width:100px; height:100px" src="<?php echo $shot ?>" controls>해당 브라우저는 video 태그를 지원하지 않습니다</video>
                    <?php }  ?>
                </td>
            </tr>
            <tr>
                <td class="title">챌린지명</td>
                <td class="content">
                    <?php 
                    // 챌린지 주소
                    $url = "challengeread.php?idx=".$challengeidx;
                    ?>
                    <a target="_blank" href="<?php echo $url ?>"><?php echo $challengetitle ?></a>
                </td>
            </tr>
            <tr>
                <td class="title">설명</td>
                <td class="content">
                    <?php echo $content ?>
                </td>
            </tr>

            <!-- 처리 -->
            <tr>
                <td class="title">처리</td>
                <td class="content">
                    <input type="checkbox" name="fit" value="1">적합처리                
                </td>                
            </tr>

            <!-- 답변 -->
            <tr>
                <td class="title">답변</td>
                <td class="content">
                    <textarea name="answer" placeholder="답변을 입력하세요"></textarea>
                </td>                
            </tr>
        </table>
        <input type="submit" value="답변하기">
    </form>

    

    <!-- 제이쿼리 -->
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>

    
</body>
</html>