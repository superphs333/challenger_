
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
<body id="mychallengenote">
    
<?php
Include_once "./top.php";

// 회원만 이용 가능한 페이지
membercheck();
?>

<h2>나의 도전노트</h2>

<!-- 
    분류
    - 참여중인, 참여종료(달성여부 확인 가능), 참여예정
 -->
<div id="selectsearch">
    <!-- 참여중인, 참여종료, 참여예정 -->
    <select id="join" style="width:30%; height:30px;">
        <option id="ing" value="ing">참여중인</option>
        <option id="end" value="end">참여종료</option>
        <option id="future" value="future">참여예정</option>
    </select>
</div>

<!-- 
    내용
-->
<?php
$user = $_SESSION['user'];
// join_challenge의 idx값 = challenge의 idx값이 같은 것
$temp = "SELECT c.idx,c.thumbnail,c.title,c.sort,c.startday,c.endday,c.frequency,c.proofshotcount,c.starttime,c.endtime from challenge as c left join challenge_join as cj on c.idx=cj.idx where cj.user='{$user}'";

// 참여 중/예정/종료에 따라
$join = $_GET['join'];
if($join=="" || $join=="ing"){
    $tempjoin = " and c.startday<=DATE(NOW()) and c.endday>=DATE(NOW())";
}else if($join=="end"){
    $tempjoin = " and c.endday<DATE(NOW())";
}else if($join=="future"){
    $tempjoin = " and c.startday>DATE(NOW())";
}
$temp = $temp.$tempjoin;
//echo $temp."</br>";
$sql = mq($temp);

// 데이터 수 가져오기
$count = mysqli_num_rows($sql);

/*
페이징
*/
$total_rows = $count;
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


if(!$sql){// 데이터베이스 가져오기 실패
    echo mysqli_error($db);
}else{?> 

    <!-- 데이터가 없는 경우, 데이터 없다고 표시-->
    <?php
    if($count==0){ 
        echo "<div style='width:100%; text-align:center'>데이터가 존재하지 않습니다</div>";
    }
    ?>
    <table id="challengeviewtable"> 
    <?php
            for($i=0; $i<3; $i++){
                echo "<tr>";
                for($j=0; $j<3; $j++){
                    $row = $sql->fetch_array();

                    // 더이상 정보가 없다면 td만들지x
                    if($row==""){break;}

                    $idx = $row['idx']; //글번호
                    $thumbnail = $row['thumbnail']; //썸네일
                    $title = $row['title']; //타이틀 
                    $startday=$row['startday']; // 시작일
                    $endday=$row['endday']; // 종료일
                   
                    $frequency=$row['frequency'];
                    $proofshotcount=$row['proofshotcount'];
                    $sort=$row['sort'];
                    $starttime = $row['starttime']; // 인증 시작 시간
                    $endtime = $row['endtime']; // 인증 끝 시간
                    ?>
                    <td id="challengeitem" style=cursor:hand; onclick=location.href="./challengeindividualread.php?idx=<?php echo $idx; ?>">
                        <!-- 썸네일 이미지 부분 -->
                        <img src="<?php echo $thumbnail ?>">
                        <table>
                            <!-- 분류 -->
                            <tr>
                                <td id="item_sort"><?php echo $sort ?></td>
                            </tr>
                            <!-- 제목 -->
                            <tr>
                                <td id="item_title" ><?php echo $title ?></td>
                            </tr> 
                            <!-- 기간 -->
                            <?php
                            // 기간
                            $days = datediff($startday,$endday)+1;
                            $wholeweeks = $days/7;
                            ?>
                            <tr>
                                <td>                        
                                    <span id="item_period"><?php
                                    echo $startday."~".$endday."(";

                                    if($wholeweeks<1){
                                        
                                        // 1주일 미만
                                        echo $days."일";
                                    }else{
                                        // 1주일 이상
                                        echo $wholeweeks."주";
                                    }
                                    echo ")";
                                    ?></span>
                                </td>                            
                            </tr>    
                            <!-- 인증주기  --> 
                            <!-- <tr>
                                <td>
                                    
                                    <span id="item_frequency">
                                    <?php
                                    if($frequency==7){
                                        echo "매일 인증";
                                    }else{
                                        echo "1주일에 ".$frequency."번" ;
                                    }                                    
                                    ?>
                                    </span>
                                </td>
                                
                            </tr>     -->
                            <!-- 인증시간 -->
                            <tr>
                                <td>                                
                                    <!-- 참여중인 챌린지의 경우, 현재 참여 가능한 시간이라면, 표시해준다 -->
                                    <?php
                                    if($join=="" || $join=="ing"){
                                    $nowtimestrtotime = strtotime(date("H:i"));
                                    $startstrtotime = strtotime($starttime);
                                    $endstrtotime = strtotime($endtime);
                                    
                                    if($nowtimestrtotime<$startstrtotime){ // 인증가능 x
                                     
                                        $timecolor = "red";
                                    }else if($nowtimestrtotime>=$startstrtotime && $nowtimestrtotime<=$endstrtotime){ // 인증가능 o
                                        $timecolor = "green";
                                    }else{
                                        $timecolor = "red";
                                    }
                                    
                                    }  ?>

                                    <span id="item_frequency" style="background-color:<?php echo $timecolor ?>">
                                    <?php
                                    echo $starttime."~".$endtime;
                                    ?>
                                    </span>
                                    
                                </td>
                            </tr>
                            <!-- 현재 달성률 -->
                            <tr>
                                <td>
                                    
                                    <span id="item_achievementrate">
                                    달성률 :
                                    <?php 
                                    // 현재 올린 인증샷 갯수
                                    $temppercent = "select * from challengeshot where challengeidx={$idx} and joiner='{$_SESSION['user']}' order by idx";
                                    $temppercent=mq($temppercent);
                                    $shotcount=mysqli_num_rows($temppercent);

                                    // 달성률 구하기
                                    // 총 몇 주인가?
                                    $wholeweeks = datediff($startday,$endday)+1;
                                    $wholeweeks = $wholeweeks/7;
                                    // 총 필요한 인증샷 갯수 :(한 주당 필요한 인증샷 갯수*하루 필요한 인증샷 갯수)*총 몇주
                                    $requireshots = ($frequency*$proofshotcount)*$wholeweeks;

                                    // 만약 7일이하라면, 그 기간만큼 인증샷 있어야 함
                                    if(datediff($startday,$endday)+1<7){
                                        $requireshots = $proofshotcount*(datediff($startday,$endday)+1);
                                    }
                                    echo round($shotcount/$requireshots*100);
                                    ?>
                                    %
                                    </span>
                                </td>
                                
                            </tr>


                            <!-- 환급신청 결과 있다면, 환급결과 표시해주기 -->
                            <tr>
                            <?php
                            $refundtemp = "select * from refund where challengeidx={$idx} and user='{$_SESSION['user']}'";
                            $refundsql = mq($refundtemp);
                            // 환급 신청 이력이 있는지 확안
                            $refundask = mysqli_num_rows($refundsql);
                            $refundfit = $refundsql->fetch_array();
                            $refundfit = $refundfit['fit'];
                            if($refundask==1){
                                if($refundfit=="1"){
                                    $refundresult = "수락";
                                }else if($refundfit=="0"){
                                    $refundresult = "거절";
                                }else{
                                    $refundresult = "심사중";
                                }
                            }
                            if($refundsql && $refundask==1){?>
                                <td >
                                    <span id="item_refundresult">
                                    환급 신청 결과 :
                                    <?php
                                    echo $refundresult;
                                    ?>
                                    </span>
                                </td>
                            <?php } ?>
                            </tr>
                        </table>
                    </td>
                <?php }
                echo "</tr>";
            }
            ?> 
    </table>

<?php } ?>

<!-- 페이징 -->
<div style="text-align:center" <?php if($total_rows==0){echo "hidden";} ?>>
<?php
if($pageNum<=1){//페이지번호가 1보다 작거나 같다면

    echo "<font size=2 color=red> [처음] </font>";
        // 링크없이 그냥 처음이라는 문자만 출력
}else{ // 1보다 크다면

    echo "<font size=2><a href='mychallengenote.php?page=&list={$list}&join={$join}'> [처음] </a></font>";

}

if($block<=1){
    
    // block이 1보다 작거나 같다면 , 더 이상 거꾸로 갈 수 없으므로 아무 표시도 하지 않는다.
    echo "<font></font>";
}else{  
    $insertpage = $b_start_page-1;
    echo "<font size=2><a href='mychallengenote.php?page={$insertpage}&list={$list}&join={$join}'> 이전 </a></font>";
}

for($j=$b_start_page; $j<=$b_end_page; $j++){
    if($pageNum==$j){
       
        // pageNum=j이면, 현재 페이지이므로,링크걸지않고 그냥 현재 페이지만 출력
        echo "<font size=2 color=red> {$j} </font>";
    }else{
       
        echo"<font size=2><a href='mychallengenote.php?page={$j}&list={$list}&join={$join}'> {$j} </a></font>";
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
    echo "<font size=2><a href='mychallengenote.php?page={$temp}&list={$list}&join={$join}'> 다음 </a></font>";
}


// 마지막 링크 버튼
if($total_page>=2){
    if($pageNum>=$total_page){
        // 페이지넘버 = 총페이지
        echo "<font size=2 color=red> [마지막] </font>";
    }else{
        //그게 아니라면
        echo "<font size=2><a href='mychallengenote.php?page={$total_page}&list={$list}&join={$join}'> [마지막] </a></font>";
    }
}

?>
</div>




<!-- 제이쿼리 -->
<script  src="https://code.jquery.com/jquery-latest.min.js"></script>
<!-- 합쳐지고 최소화된 최신 자바스크립트 -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<!-- 자바스크립트 파일 삽입-->
<script type="text/javascript" src="./mychallengenote.js"></script>
</body>
</html>