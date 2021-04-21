
<html>
<head>
</head>
<body>
<?php
// 좋아요 한 챌린지 불러오기 => chalenge + challengeshot_heart
$temp = "select * from challengeshot as cs left join challengeshot_heart as ch on cs.idx=ch.idx where ch.user='{$_SESSION['user']}' ";
//echo $temp;
$sql = mq($temp);
$count = mysqli_num_rows($sql);
//echo $count;

/*
페이징
*/
$total_rows = $count;
// 페이지 번호
$pageNum=($_GET['page'])?$_GET['page']:1;
// 한 페이지에 몇 개의 글을 보여줄지 
$list=($_GET['list']?$_GET['list']:3);
//블럭에 나타낼 페이지 번호 갯수
$b_pageNum_list = 4;
// 현재 리스트의 블럭을 구함
$block = ceil($pageNum/$b_pageNum_list);
// 현재 블럭에서 시작페이지 번호
$b_start_page = (($block-1)*$b_pageNum_list)+1;
// 현재 블럭에서 마지막 페이지 번호
$b_end_page = $b_start_page+$b_pageNum_list-1;
// 총 게시글의 페이지 수
$total_page = ceil($total_rows/$list);
// 블럭의 마지막 페이지가 총 페이지 수보다 클 때 숫자를 같게 해주는 조건
if($b_end_page>$total_page){
    $b_end_page=$total_page;
}
$start_record = ($pageNum-1)*$list;
$sql = mq($temp."limit {$start_record},{$list}");

if($count==0){
    echo "<div>아직 좋아요 한 인증샷이 없습니다</div>";
}
?>
<table id="joinerfeedtable">
<?php
for($i=0; $i<10; $i++){
    echo "<tr>";
    for($j=0; $j<3; $j++){
        $row=$sql->fetch_array();

        // 더이상 정보가 없다면 td만들지 x
        if($row==""){break;}

        // 가져오는 정보 : 챌린지명, 기간,이미지인지 사진인지 ,이미지or비디오 주소(0or1), (하트수, 댓글수), 신고수
        $title = $row['title']; // 챌린지명
        $challengelink = $row['challengeidx'];// 챌린지 idx(링크위해)
        $imgorvideo = $row['video']; //사진or비디오
        $src = $row['shot'];
        $shotidx = $row['idx']; // 샷의 idx
        ?>
        

        <td id="challengeitem" style=cursor:hand; onclick="window.open('joinfeedone.php?shotidx=<?php echo $shotidx ?>&nickname=<?php echo $nickname ?>','window_name','width=430,height=500,location=no,status=no,scrollbars=yes');">

            <!-- 챌린지 타이틀 -->
            <div id="challengeitem_title">
                <?php echo $title ?>
            </div><br>

            <!-- img or video -->
            <?php
            if($imgorvideo==0){  // 사진인증인 경우 ?>
            <img src="<?php echo $src ?>">
            <?php }else{//비디오 인증인 경우 ?>
            <video src="<?php echo $src ?>" controls>해당 브라우저는 video 태그를 지원하지 않습니다</video>
            <?php }  ?><br>

            <!-- 하트, 댓글, 신고 -->
            <!-- <div id="challengeitem_check">
            
                <span>하트</span>
            
                <span>댓글</span>
                
                <span>신고</span>
            </div> -->
        </td>
    <?php }
    echo "</tr>";
}
?>
</table>

<!-- 페이징 -->
<div style="text-align:center">
<?php
    if($pageNum<=1){//페이지번호가 1보다 작거나 같다면

        echo "<font size=2 color=red> [처음] </font>";
            // 링크없이 그냥 처음이라는 문자만 출력
    }else{ // 1보다 크다면

        echo "<font size=2><a class='pagelink' href='mypage.php?page=&list={$list}&joinable={$joinable}&mypage=heartshot'> [처음] </a></font>";
    
    }
    
    if($block<=1){
        
        // block이 1보다 작거나 같다면 , 더 이상 거꾸로 갈 수 없으므로 아무 표시도 하지 않는다.
        echo "<font></font>";
    }else{  
        $insertpage = $b_start_page-1;
        echo "<font size=2><a class='pagelink' href='mypage.php?page={$insertpage}&list={$list}&joinable={$joinable}&mypage=heartshot'> 이전 </a></font>";
    }
    
    for($j=$b_start_page; $j<=$b_end_page; $j++){
        if($pageNum==$j){
            
            // pageNum=j이면, 현재 페이지이므로,링크걸지않고 그냥 현재 페이지만 출력
            echo "<font class='pagelink' size=2 color=red> {$j} </font>";
        }else{
            
            echo"<font size=2><a class='pagelink' href='mypage.php?page={$j}&list={$list}&joinable={$joinable}&mypage=heartshot'> {$j} </a></font>";
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
        echo "<font size=2><a class='pagelink' href='mypage.php?page={$temp}&list={$list}&joinable={$joinable}&mypage=heartshot'> 다음 </a></font>";
    }

    // 마지막 링크 버튼
    if($pageNum>=$total_page){
        // 페이지넘버 = 총페이지
        echo "<font size=2 color=red> [마지막] </font>";
    }else{
        //그게 아니라면
        echo "<font size=2><a class='pagelink' href='mypage.php?page={$total_page}&list={$list}&joinable={$joinable}&mypage=heartshot'> [마지막] </a></font>";
    }
?>
</div>


</body>
</html>