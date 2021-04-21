
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bootstrap 101 Template</title>
<!-- 합쳐지고 최소화된 최신 CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<!-- 부가적인 테마 -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
<!-- 외부 css 불러오기 -->
<link rel="stylesheet" type="text/css" href="./whole.css" />

</head>
<body>

<!-- 
    분류
    : 신고 된 챌린지만/

    순서
    : 신고 많은 순, 마감일 순, 챌린지별로 보기
 -->
<div id="shotmanagement_sort">
    <!-- 
    대기 중인 챌린지만 보기 
    - 만약 refundfit="대기"라면 체크박스 체크
    -->
    <?php 
    // 대기중인
    $refundfit = $_GET['refundfit']; 
    //echo $refundfit;

    // 순서
    $refundorder = $_GET['refundorder']; 
    //echo $refundorder;
    ?>
    <input id="refundfit" type="checkbox" <?php if($refundfit=="대기"){echo "checked";} ?>>대기 중인 인증샷만 보기
  
    <!-- 
    순서 
    : 챌린지 별로 보기, 최신순 보기, 
    -->
    <select id="refundorder" style="margin-left:10px;">
        <option id="refundlastest" value="refundlastest">최신순</option>
        <option id="refundchallengesort" value="refundchallengesort">챌린지 별로 보기</option>
    </select>
    <br><br>
</div>

<!-- 
환급 신청 테이블
-> 보이는 것은 인증샷 기간이 끝나고+3일까지인 인증샷만 표시가 된다.
-->
<div id="shotmanagement_shots" >
    <?php
    // 가져올 테이블 => 챌린지(challenge), individualchallenge, refund
    $temp = "select r.idx,r.challengeidx,r.user,r.merchant_uid,r.fit, c.title from refund as r left join challenge as c on c.idx=r.idx ";

    // 만약 대기중인 인증샷만 보기 체크라면, 조건 붙여줌
    if($refundfit=="대기"){
        $temprefundfit = " where r.fit is NULL";
        $temp = $temp.$temprefundfit;
    }

    // 순서
    if($refundorder=="refundchallengesort"){ // 챌린지별
        $temporder = " order by r.challengeidx desc";
        $temp = $temp.$temporder;
    }else{ // 빈 값 or 최신순
        $temporder = " order by r.idx desc";
        $temp = $temp.$temporder;
    }

    //echo $temp;
    $sql = mq($temp);

    /*
    페이징
    */
    $total_rows = mysqli_num_rows($sql); 

    // 페이지 번호
    $pageNum=($_GET['page'])?$_GET['page']:1;
    // page값을 받아서, 있다면 그대로 $_GET['page'] 값을 사용하고,비어있다면1로 값을 지정하는 조건문

    // 한 페이지에 몇 개의 글을 보여줄지 
    $list=($_GET['list']?$_GET['list']:4);
        // page default = 50
        // 한 페이지에 50개의 글 목록
    
    //블럭에 나타낼 페이지 번호 갯수
    $b_pageNum_list = 4;

    // 현재 리스트의 블럭을 구함
    $block = ceil($pageNum/$b_pageNum_list);
    //echo $block;

    // 현재 블럭에서 시작페이지 번호
    $b_start_page = (($block-1)*$b_pageNum_list)+1;
    //echo $b_start_page; 

    // 현재 블럭에서 마지막 페이지 번호
    $b_end_page = $b_start_page+$b_pageNum_list-1;
    //echo $b_end_page; 

    // 총 게시글의 페이지 수
    $total_page = ceil($total_rows/$list);

    // 블럭의 마지막 페이지가 총 페이지 수보다 클 때 숫자를 같게 해주는 조건
    if($b_end_page>$total_page){
        $b_end_page=$total_page;
    }

    $start_record = ($pageNum-1)*$list;

    $sql = mq($temp." limit {$start_record},{$list}");

    if(!$sql){ // sql문 오류
        echo mysqli_error($db);
    }else{ // sql문 성공 ?> 
        <div style="text-align:center" <?php if($total_rows!=0){echo "hidden";} ?>>
        데이터가 존재하지 않습니다
        </div>
        <table  <?php if($total_rows==0){echo "hidden";} ?>>
            <thead>
                <tr>
                    <th>번호</th>                    
                    <!-- <th>챌린지</th> -->
                    <th>유저</th>
                    <th>주문번호</th>
                    <th>수락/거절</th>
                </tr>
            </thead>
            <?php
            while($row = $sql->fetch_array()){ 

            /*
            정보 가져오기
            */
            $idx = $row['idx']; // 환급 넘버
            $title = $row['title']; // 챌린지명
            $merchant_uid = $row['merchant_uid']; // 주문번호
            $user = $row['user']; // 환급 요청자
            $challengeidx=  $row['challengeidx'];
            $fit = $row['fit']; // 수락or거절
            ?>
            <tbody>
                <tr>
                    <!-- 환불번호 -->
                    <td>
                        <?php echo $idx ?>
                    </td>


                    <!-- 챌린지명 -->
                    <!-- <td>
                        <?php echo $title  ?>
                    </td> -->

                    <!-- 
                    유저 
                    : (관리자용)유저의 개인 readpage로 이동
                    = challengeindividualmanagement.php?idx=챌린지명&user=유저
                    -->
                    <td>            
                        <a
                        onclick="window.open('challengeindividualmanagement.php?idx=<?php echo $challengeidx ?>&user=<?php echo $user ?>&refund=<?php echo $idx ?>','width=200px,height=200px,toolbars=no,scrollbars=no')"
                        >유저 챌린지 페이지</a>
                    </td>

                    <!-- 주문번호 -->
                    <td>
                        <?php echo $merchant_uid; ?>
                    </td>

                    <!-- 
                        수락 or 거절
                        : 
                    -->
                    <td>
                    <?php
                    if($fit==null){ // 널인 상태
                        echo "대기";
                    }else if($fit==1){ // 수락
                        echo "수락";
                    }else if($fit==0){ // 거절
                        echo "거절";
                    }
                    ?>
                    </td>
                </tr>
            </tbody>
            <?php } ?>
            
        </table>
        
    <?php } ?>

    <div style="text-align:center; margin-top:10px;" <?php if($total_rows==0){echo "hidden";} ?>>
    <?php
    // 페이징
    if($pageNum<=1){//페이지번호가 1보다 작거나 같다면

        echo "<font size=2 color=red> [처음] </font>";
            // 링크없이 그냥 처음이라는 문자만 출력
    }else{ // 1보다 크다면

        echo "<font size=2><a class='pagelink' href='challengemanagement.php?page=&list={$list}&handleok2={$handleok2}&sirenok={$sirenok}&shotmanagementorderby={$shotmanagementorderby}&management=refund'> [처음] </a></font>";
    
    }
    
    if($block<=1){
        
        // block이 1보다 작거나 같다면 , 더 이상 거꾸로 갈 수 없으므로 아무 표시도 하지 않는다.
        echo "<font></font>";
    }else{  
        $insertpage = $b_start_page-1;
        echo "<font size=2><a class='pagelink' href='challengemanagement.php?page={$insertpage}&list={$list}&handleok2={$handleok2}&sirenok={$sirenok}&shotmanagementorderby={$shotmanagementorderby}&management=refund'> 이전 </a></font>";
    }
    
    for($j=$b_start_page; $j<=$b_end_page; $j++){
        if($pageNum==$j){
           
            // pageNum=j이면, 현재 페이지이므로,링크걸지않고 그냥 현재 페이지만 출력
            echo "<font class='pagelink' size=2 color=red> {$j} </font>";
        }else{
           
            echo"<font size=2><a class='pagelink' href='challengemanagement.php?page={$j}&list={$list}&handleok2={$handleok2}&sirenok={$sirenok}&shotmanagementorderby={$shotmanagementorderby}&management=refund'> {$j} </a></font>";
                // 현재 페이지를 제외한 나머지 페이지 번호를 링크를 달아 출력하기

                
        }
        
    }

    // 블럭의 총 갯수
    $total_block = ceil($total_page/$b_pageNum_list);

    if($block>=$total_block){
        
        // block과 총block의 갯수가 값이 같다면, 맨 마지막 블록이므로 다음 링크버튼이 필요없어 보여주지 않는다.
        echo "<font></font>";
    }else{
        // 그게아니라면, 다음 링크버튼을 걸어 보여준다
        $temp = $b_end_page+1;
        echo "<font size=2><a class='pagelink' href='challengemanagement.php?page={$temp}&list={$list}&handleok2={$handleok2}&sirenok={$sirenok}&shotmanagementorderby={$shotmanagementorderby}&management=refund'> 다음 </a></font>";
    }

    // 마지막 링크 버튼
    if($pageNum>=$total_page){
       
        // 페이지넘버 = 총페이지
        if($total_page!=1){
            
            echo "<font size=2 color=red> [마지막] </font>";
        }
        
    }else{
        //그게 아니라면

        if($total_page!=1){
            echo "<font size=2><a class='pagelink' href='challengemanagement.php?page={$total_page}&list={$list}&handleok2={$handleok2}&sirenok={$sirenok}&shotmanagementorderby={$shotmanagementorderby}&management=refund'> [마지막] </a></font>";
        }
        
    }

    ?>
    </div>
    
</div>




<!-- 제이쿼리 -->
<script  src="https://code.jquery.com/jquery-latest.min.js"></script>
<!-- 합쳐지고 최소화된 최신 자바스크립트 -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>


</body>
</html>