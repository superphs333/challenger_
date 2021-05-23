
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
    
<?php
Include "./top.php";

// get으로 받아온 닉네임
$nickname = $_GET['joiner'];
?>

<!-- 해당 사용자의 정보 -->
<div id="joinerfeed_info">
    <span>
    <?php echo $nickname."님" ?>
    </span><br><br>
    
    <?php
    // 이 닉네임을 가진 멤버의 이메일 얻기
    $foremail = "select email from members where nickname='{$nickname}'";
    $foremail = mq($foremail);
    $email = $foremail->fetch_array();
    $email = $email['email'];

    // 게시물의 갯수
    $forcount = "select * from challengeshot where joiner='{$email}'";
    $forcount = mq($forcount);
    $count = $forcount->num_rows;
    ?>
    <div>
        <?php echo "총 인증샷 : ".$count."개";  ?>
    </div>
</div>
<hr>

<!-- 인증샷 피드 보여주는 부분 -->
<div id="shotfeed">
<?php
// 챌린지명, 챌린지 
$temp = "select c.title,c.video,cs.shot,cs.challengeidx,cs.idx from challengeshot as cs left join challenge as c on cs.challengeidx=c.idx where cs.joiner='{$email}'";
$sql = mq($temp);

/*
페이지 사전 설정
*/
$total_rows = mysqli_num_rows($sql);
// 페이지 번호
$pageNum=($_GET['page'])?$_GET['page']:1;
// page값을 받아서, 있다면 그대로 $_GET['page'] 값을 사용하고,비어있다면1로 값을 지정하는 조건문

// 한 페이지에 몇 개의 글을 보여줄지 
$list=($_GET['list']?$_GET['list']:9);
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

$sql = mq($temp." order by idx desc limit {$start_record},{$list}");


// 만약 아무것도 없다면, 아직 게시물이 없습니다.
if($sql->num_rows==0){
   echo "<div>아직 인증샷이 없습니다</div>";
}else{ ?>
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
            $src = $row['shot']; // 이미지 주소
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

            </td>
        <?php }
        echo "</tr>";
    }
    ?>
    </table>
<?php } ?>

<!-- 페이징 -->
<div class="paging" style="text-align:center">

</div>
<?php
    if($total_rows!=0){ // 게시물이 있는 경우에만
        if($pageNum<=1){//페이지번호가 1보다 작거나 같다면

            echo "<font size=2 color=red> [처음] </font>";
                // 링크없이 그냥 처음이라는 문자만 출력
        }else{ // 1보다 크다면
    
            echo "<font size=2><a href='joinerfeed.php?page=&list={$list}&joiner={$nickname}'> [처음] </a></font>";
        
        }
        
        if($block<=1){
            
            // block이 1보다 작거나 같다면 , 더 이상 거꾸로 갈 수 없으므로 아무 표시도 하지 않는다.
            echo "<font></font>";
        }else{  
            $insertpage = $b_start_page-1;
            echo "<font size=2><a href='joinerfeed.php?page={$insertpage}&list={$list}&joiner={$nickname}'> 이전 </a></font>";
        }
        
        for($j=$b_start_page; $j<=$b_end_page; $j++){
            if($pageNum==$j){
               
                // pageNum=j이면, 현재 페이지이므로,링크걸지않고 그냥 현재 페이지만 출력
                echo "<font size=2 color=red> {$j} </font>";
            }else{
               
                echo"<font size=2><a href='joinerfeed.php?page={$j}&list={$list}&joiner={$nickname}'> {$j} </a></font>";
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
            echo "<font size=2><a href='joinerfeed.php?page={$temp}&list={$list}&joiner={$nickname}'> 다음 </a></font>";
        }
    
        // 마지막 링크 버튼
        if($pageNum>=$total_page){
            // 페이지넘버 = 총페이지
            echo "<font size=2 color=red> [마지막] </font>";
        }else{
            //그게 아니라면
            echo "<font size=2><a href='joinerfeed.php?page={$total_page}&list={$list}&joiner={$nickname}'> [마지막] </a></font>";
        }
    }

?>
</div>





<!-- 제이쿼리 -->
<script  src="https://code.jquery.com/jquery-latest.min.js"></script>

<!-- 아래는 잘못쓴듯(그냥 남겨둠) -->
<script src="challengeindividualread.js"></script>
<script>


</script>
</body>
</html>