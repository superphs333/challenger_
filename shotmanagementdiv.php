<!-- 
인증샷 테이블
-> 보이는 것은 인증샷 기간이 끝나고+3일까지인 인증샷만 표시가 된다.
-->
<div id="shotmanagement_shots">
    <?php

    include_once "db.php";
    include_once "phpfunction.php";
     
    // 인증샷 불러오기(마지막날+3일까지 판별 가능) 
    $temp = "select c.title,c.additionaldescription,c.video,cs.shot,cs.challengeidx,cs.idx,cs.joiner,cs.fit,c.endday,c.startday,cs.handleok,cs.report from challengeshot as cs left join challenge as c on cs.challengeidx=c.idx where date_add(c.endday,INTERVAL 3 day)>now()";

    // 미완료 처리만
    $handleok = $_POST['handleok'];
    //ECHO $handleok."</br>";
    if($handleok=="0"){ // 미완료 된 처리
        $handletemp = " and cs.handleok='0' ";
        $temp = $temp.$handletemp;
    }

    // 신고된 처리만
    $sirenok = $_POST['sirenok'];
    //echo $sirenok;
    if($sirenok=="1"){
        $sirentemp = " and cs.report>=1 ";
        $temp = $temp.$sirentemp;
    }

    // 순서로 받아온 값
    $shotmanagementorderby= $_POST['shotmanagementorderby'];
    //echo $shotmanagementorderby;
    if($shotmanagementorderby=="challengesort"){ // 챌린지순
        $orderby = " order by cs.challengeidx";
    }else if($shotmanagementorderby=="siren"){ // 신고많은순
        $orderby = " order by cs.report desc";
    }else{ // 최신순
        $orderby = " order by idx desc";
    }
    $temp = $temp.$orderby;
    //echo $temp."<br>";

    $sql = mq($temp);

    /*
    페이징
    */
    $total_rows = mysqli_num_rows($sql);
     // 페이지 번호
     $pageNum=($_GET['page'])?$_GET['page']:1;
     // page값을 받아서, 있다면 그대로 $_GET['page'] 값을 사용하고,비어있다면1로 값을 지정하는 조건문

    // 한 페이지에 몇 개의 글을 보여줄지 
    $list=($_GET['list']?$_GET['list']:3);
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
        <table>
            <thead>
                <tr>
                    <th>번호</th>
                    <th>인증샷</th>
                    <th>챌린지</th>
                    <th>유저</th>
                    <th>신고개수</th>
                    <th>적합/부적합</th>
                    <th>처리완료</th>
                </tr>
            </thead>
            <?php
            while($row = $sql->fetch_array()){ 

            /*
            정보 가져오기
            */
            $idx =$row['idx']; // shot 고유번호
            $imgorvideo = $row['video']; //사진or비디오
            $src = $row['shot'];  // 샷의 주소
            $title = $row['title'];  // 챌린지 제목
            $challengeidx = $row['challengeidx'];  // 챌린지 idx            
            $startday = $row['startday'];  // 기간 시작일
            $endday  = $row['endday'];  // 기간 
            $joiner  = $row['joiner'];  // 샷주인
            // 신고 개수
            $sirencount = "select * from challengeshot_siren where idx={$idx}";
            $sirencount = mq($sirencount);
            $sirencount = $sirencount->num_rows;
            $fit = $row['fit']; // 적합 or 부적합
            $handleok  = $row['handleok'];  // 처리완료 여부
            //var_dump($fit);
            ?>
            <tbody>
                <tr>
                    <!-- 인증샷 번호 -->
                    <td class="shotidx"><?php echo $idx; ?></td>
                    <!-- 인증샷 -->
                    <td>
                    <?php
                    if($imgorvideo==0){  // 사진인증인 경우 ?>
                    <img src="<?php echo $src ?>">
                    <?php }else{//비디오 인증인 경우 ?>
                    <video src="<?php echo $src ?>" controls>해당 브라우저는 video 태그를 지원하지 않습니다</video>
                    <?php }  ?>
                    </td>
                    <!-- 챌린지 정보 -->
                    <td>
                    <?php 
                        // 챌린지 링크
                        $url = "challengeread.php?idx=".$challengeidx;                       
                    ?>
                    <a onclick="window.open('<?php echo $url ?>','챌린지','width=300,height=300')">
                        <b><?php echo $title ?></b>
                    </a><br>
                    <c style='font-size:x-small'><?php echo $startday."~".$endday ?></c>
                    </td>
                    <!-- 글쓴이 -->
                    <td><?php echo $joiner; ?></td>
                    <!-- 신고개수 -->
                    <td><?php echo $sirencount; ?></td>
                    <!-- 적합/부적합 -->
                    <?php
                    if($fit=="1"){
                        $fitcolor = "green";
                        
                    }else{
                        $fitcolor = "red";
                    }
                    ?>
                    <td>
                        <div class="okno" style="background-color:<?php echo $fitcolor ?>"></div>
                        <input class="oknotext" type="hidden" value="<?php echo $fit ?>">
                    </td>
                    <!-- 처리 완료 여부 -->
                    <td>
                        <input type="checkbox"  name="box[]" class="shot_checkbox" <?php
                         if($handleok=="1"){echo "checked";}
                         ?> value="<?php echo $idx?>">

                    </td>
                </tr>
            </tbody>
            <?php } ?>
            
        </table>
        <button id="handleok">처리완료</button>
    <?php } ?>
    
    <?php
    if($pageNum<=1){//페이지번호가 1보다 작거나 같다면

        echo "<font size=2 color=red> [처음] </font>";
            // 링크없이 그냥 처음이라는 문자만 출력
    }else{ // 1보다 크다면

        echo "<font size=2><a class='pagelink' href='challengemanagement.php?page=&list={$list}'> [처음] </a></font>";
    
    }
    
    if($block<=1){
        
        // block이 1보다 작거나 같다면 , 더 이상 거꾸로 갈 수 없으므로 아무 표시도 하지 않는다.
        echo "<font></font>";
    }else{  
        $insertpage = $b_start_page-1;
        echo "<font size=2><a class='pagelink' href='challengemanagement.php?page={$insertpage}&list={$list}'> 이전 </a></font>";
    }
    
    for($j=$b_start_page; $j<=$b_end_page; $j++){
        if($pageNum==$j){
            
            // pageNum=j이면, 현재 페이지이므로,링크걸지않고 그냥 현재 페이지만 출력
            echo "<font size=2 color=red> {$j} </font>";
        }else{
            
            echo"<font size=2><a class='pagelink' href='challengemanagement.php?page={$j}&list={$list}'> {$j} </a></font>";
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
        echo "<font size=2><a class='pagelink' href='challengemanagement.php?page={$temp}&list={$list}'> 다음 </a></font>";
    }

    // 마지막 링크 버튼
    if($pageNum>=$total_page){
        // 페이지넘버 = 총페이지
        echo "<font size=2 color=red> [마지막] </font>";
    }else{
        //그게 아니라면
        echo "<font size=2><a class='pagelink' href='challengemanagement.php?page={$total_page}&list={$list}'> [마지막] </a></font>";
    }
    ?>
</div>