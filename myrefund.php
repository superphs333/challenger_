
<html>
<head>

</head>
<body>
    <!-- 분류 -->
    <div id="shotask_sort" style="top-margin:10px; bottom-margin:10px">
        <!-- 답변완료 된 문의 보기 -->
        <input id="askok_myask" type="checkbox" value="yes" <?php if($_GET['askok']=="1"){echo "checked";} ?> >완료 답변만 보기
    </div>

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

        $temp = "select * from shotask where user='{$_SESSION['user']}'";

        if($_GET['askok']=="1"){
            $temp = "select * from shotask where user='{$_SESSION['user']}' and answer='1'";
        }

        $sql = mq($temp);

        /*
        페이징
        */
        $total_rows = mysqli_num_rows($sql);
        // 페이지 번호
        $pageNum=($_GET['page'])?$_GET['page']:1;
        // page값을 받아서, 있다면 그대로 $_GET['page'] 값을 사용하고,비어있다면1로 값을 지정하는 조건문

        // 한 페이지에 몇 개의 글을 보여줄지 
        $list=($_GET['list']?$_GET['list']:1);
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
                    echo "답변완료</br>";
                    $answerurl = "shotaskanswerread.php?idx={$idx}";
                    ?>
                    <a style="font-size:smaller" onclick="window.open('<?php echo $answerurl ?>','인증샷 답변 보기','width=600,height=600')">결과보기</a>
                <?php } ?>
                
                </td>

                <!-- 수정, 삭제 -->
                <td>
                    <button class="myshotaskupdate" <?php if($answer!=0){
                    echo "disabled";
                } ?> >수정</button>
                    <button class="myshotaskdelete">삭제</button>
                </td>
            </tr>
        </tbody>
        <?php } ?>
            
    </table>

    <!-- 페이징 -->
    <?php
        if($pageNum<=1){//페이지번호가 1보다 작거나 같다면

            echo "<font size=2 color=red> [처음] </font>";
                // 링크없이 그냥 처음이라는 문자만 출력
        }else{ // 1보다 크다면
    
            echo "<font size=2><a class='pagelink' href='mypage.php?page=&list={$list}&askok={$askok}&mypage=shotask'> [처음] </a></font>";
        
        }
        
        if($block<=1){
            
            // block이 1보다 작거나 같다면 , 더 이상 거꾸로 갈 수 없으므로 아무 표시도 하지 않는다.
            echo "<font></font>";
        }else{  
            $insertpage = $b_start_page-1;
            echo "<font size=2><a class='pagelink' href='mypage.php?page={$insertpage}&list={$list}&askok={$askok}&mypage=shotask'> 이전 </a></font>";
        }
        
        for($j=$b_start_page; $j<=$b_end_page; $j++){
            if($pageNum==$j){
               
                // pageNum=j이면, 현재 페이지이므로,링크걸지않고 그냥 현재 페이지만 출력
                echo "<font class='pagelink' size=2 color=red> {$j} </font>";
            }else{
               
                echo"<font size=2><a class='pagelink' href='mypage.php?page={$j}&list={$list}&askok={$askok}&mypage=shotask'> {$j} </a></font>";
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
            echo "<font size=2><a class='pagelink' href='mypage.php?page={$temp}&list={$list}&askok={$askok}&mypage=shotask'> 다음 </a></font>";
        }
    
        // 마지막 링크 버튼
        if($pageNum>=$total_page){
            // 페이지넘버 = 총페이지
            echo "<font size=2 color=red> [마지막] </font>";
        }else{
            //그게 아니라면
            echo "<font size=2><a class='pagelink' href='mypage.php?page={$total_page}&list={$list}&askok={$askok}&mypage=shotask'> [마지막] </a></font>";
        }
    ?>

</html>