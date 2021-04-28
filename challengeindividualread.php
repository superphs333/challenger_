<?php

?>
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
Include "./top.php";
?>

<!-- 챌린지 자체에 대한 설명 --> 
<?php
    // 해당 파일 불러오기
    //$idx = $_GET['post']; 
    $idx=$_GET['idx'];


    // 데이터베이스 불러오기
    $sql = mq("select * from challenge where idx={$idx}");
    $sql = $sql->fetch_array();

    // 만약 없는 챌린지라면, 뒤로가기
    if($sql==null){
        echo"<script>
        alert('삭제되었거나 없는 페이지입니다');
        history.back();
        </script>";
    }

    $title = $sql['title']; // 제목
    $startday = $sql['startday']; // 시작일
    $endday = $sql['endday']; // 종료일
    $entryfee = $sql['entryfee']; // 참가비
    $additionaldescription = $sql['additionaldescription']; // 추가적인 설명
    $thumbnail = $sql['thumbnail']; // 썸네일 주소
    $frequency = $sql['frequency'];
    $writer = $sql['writer']; // 작성자
    $sort = $sql['sort']; // 카테고리
    $proofshotcount = $sql['proofshotcount']; // 인증샷수
    $video = $sql['video']; // 비디오 여부

    // 비디오 인증인지, 사진인증인지 구분
    if($video==0){ // 사진인증인 경우
        $videocheck= "사진인증";
    }else{//비디오 인증인 경우
        $videocheck= "비디오인증";
    }
    $starttime = $sql['starttime']; // 인증 시작 시간
    $endtime = $sql['endtime']; // 인증 끝 시간
?>
<input type="hidden" id="participant" value="<?php echo $_SESSION['user'] ?>">
    <!-- 이부분은 없어도 됨 -->
<input type="hidden" id="idx2" value="<?php echo $idx ?>">
    <!-- 이 부분도 -> 제이쿼리에서 get값을 가져오면 된다 -->
<!-- 내용 -->

<div id="cr_content">
    <img src="<?php echo $thumbnail ?>">
    <table>
        <tr>
            <td id="challengereadtitle" colspan="2">
                <?php echo $title ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <?php echo "분류 - ".$sort ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $startday."~".$endday ?>
            </td>                
        </tr>
        <tr>
            <td>참가비용 : </td>
            <td><?php echo $entryfee."원" ?></td>
            <input id="ef" type="hidden" value="<?php echo  $entryfee?>">
        </tr>
        <!-- <tr>
            <td>인증주기 : </td>
            <td>
                <?php
                if($frequency==7){
                    echo "매일 인증";
                }else{
                    echo "1주일에 ".$frequency."번" ;
                }
                    
                ?>
            </td>
        </tr> -->
        <tr>
                <td>인증 가능 시간 : </td>
                <td>
                <?php
                echo $starttime."~".$endtime;
                ?>
                </td>
            </tr>
        <tr>
            <td>하루에 필요한 인증샷 갯수 : </td>
            <td><?php echo $proofshotcount."개 (".$videocheck.")" ?></td>
            <!-- 사진인증인지, 비디오인증인지 체크해주는 부분 -->
            <input type="hidden" id="videocheck" value="<?php echo $videocheck ?>">
        </tr>
    </table>
    <br><br><br><br><br><br><br><br><br><br>
    <br><br> 
</div>

<!-- 설명은 접기가능하게 하기 -->
<div id="cr_detail">
    <details>
        <summary>설명보기</summary>
        <p>
            <?php echo $additionaldescription ?>
        </p>   
    </details> 
</div>
<hr>


<?php
/*
만약 인증가능한 기간이 아니라면,인증샷을 올릴 수 없게 한다.
*/
// 날짜
$today = date("Y-m-d"); // 오늘
$fromday = date($startday); // 시작일
$to_day = date($endday); // 종료일
// 날짜 비교를 위해
$today = strtotime($today); // 오늘
$fromday = strtotime($fromday); // 시작일
$to_day = strtotime($to_day); // 종료일


if($today>=$fromday && $today<=$to_day ){// 인증가능 기간 ?>

<?php
// 만약 이번주에 해당하는 인증샷을 모두 올렸다면,
// 이번주 인증샷은 모두 올렸습니다 표시
// 오늘 날짜 
//오늘 날짜 출력 ex) 2013-04-10 
$Time = explode(" ",microtime()); 
$s = mktime(0,0,0, date("m",$Time[1]), date("d",$Time[1]) - date("w",$Time[1]) +1/* 시작일 기준 */, date("Y",$Time[1])); // 
$e = mktime(0,0,0, date("m",$Time[1]), date("d",$Time[1]) - date("w",$Time[1]) +7/* 종료일 기준 */, date("Y",$Time[1]));
$begin = date("Y-m-d", $s);
$end = date("Y-m-d", $e);
$temp = "select * from challengeshot where challengeidx={$idx} and joiner='{$_SESSION['user']}' and date>='{$begin}' and date<='{$end}'";
$weekcheck = mq($temp);

// 이번주에 올린 갯수
$weekcheckcount = mysqli_num_rows($weekcheck);

// 이번주에 올려야 하는 갯수
$shotcountperweek = $frequency*$proofshotcount;

// 만약, 이번주에 올린 갯수가 이번주에 올려야 하는 갯수보다 
// if($weekcheckcount-1>=$shotcountperweek){
//     echo "이번 주 분량 완료";
// }else{
//     echo "아직 더 올림";
// }

?>

<!-- 인증샷 올리기(하루에 필요한 인증샷 갯수만큼 칸 생성) -->
<div id="shotinput">
    <h3>오늘의 인증샷을 올려주세요</h3>
<?php
// 오늘의 인증샷을 담은 배열
$shotarray = []; // 샷의 주소를 담는 배열
$detailarray = []; // 샷의 자세한 설명을 담은 배열
$shotidxarray = []; // 샷의 idx를 담는 배열
$buttoname = ""; // 오늘날짜의 인증샷이 없으면 제출, 있으면 수정
/*
만약 인증시간이 아니라면, 인증샷을 올릴 수 있는 시간이 아닙니다.
*/ 
// 현재시간
//echo "현재 시간 : ". date("H:i")."<br/>";
$nowtimestrtotime = strtotime(date("H:i")); // 현재시간
$startstrtotime = strtotime($starttime); // 시작시간
$endstrtotime = strtotime($endtime); // 종료시간
//echo "nowtimestrtotime=".$nowtimestrtotime." startstrtotime=".$startstrtotime." endstrtotime=".$endstrtotime;
if($nowtimestrtotime<$startstrtotime){// 인증가능시간x?>
    <div>
    아직 인증가능 한 시간이 아닙니다.
    </div>
<?php }else if($nowtimestrtotime>=$startstrtotime && $nowtimestrtotime<=$endstrtotime){ // 인증가능 ?>
    <table>
        <tr>
            <?php 
            /*
            만약, (오늘 날짜에)이미 올린 인증샷이 있다면 인증시간 내에는 수정 가능 하도록 함
            */
            // 오늘날짜
            $today = strtotime("Now");
            $today = date("Y-m-d",$today);
            //echo "현재 날짜 : ".date("Y-m-d",$today)."<br/>";
            
            // 오늘날짜에 데이터 있으면 미리 가져오기
            $temp = "select * from challengeshot where date='{$today}' and challengeidx={$idx} and joiner='{$_SESSION['user']}' ";
            $fordaysql = mq($temp);
            if($fordaysql->num_rows==$proofshotcount){ // 오늘 날짜의 인증샷 있는 상태 
                
                while($shots=$fordaysql->fetch_array()){
                    // 인증샷 담기
                    $shot2 = $shots['shot'];
                    array_push($shotarray,$shot2);
                    //$shotarray[] = $shot2;

                    // detail 담기
                    $detail2 = $shots['detail'];
                    array_push($detailarray,$detail2);
                    //$detailarray[] = $detail2;

                    // idx 담기($shotidxarray)
                    $shotidx2 = $shots['idx'];
                    array_push($shotidxarray,$shotidx2);
                    //$shotidxarray[] = $shotidx2;
                }      
                // 버튼이름 = 수정
                $buttoname = "수정";
                
            ?>
            <?php }else{  // 오늘 날짜의 인증샷 없는 상태 
                // 버튼이름 = 수정
                $buttoname = "제출";                
            } ?>  
            
            <!-- 존재하고 있는 인증샷 셋팅 -->
            <?php
            for($i=1; $i<=$proofshotcount; $i++){
                // 현재 존재하는 인증샷/디테일
                $todaysrc = $shotarray[$i-1];                   
                $todaydetail = $detailarray[$i-1];
                $todayidx = $shotidxarray[$i-1];
            ?>
                <td>
                    <input type="file" class="shotfile">
                    <?php
                    if($videocheck=="사진인증"){
                        if($todaysrc!=null){ // 이미지 src가 null이 아닌 경우
                            echo "<img class='shotimg' src='{$todaysrc}' >";
                        }else{ // 이미지 src가 null인 경우
                            echo "<img class='shotimg'>";
                        }
                    }else{
                        if($todaysrc!=null){ // 비디오 src가 null이 아닌 경우
                            echo "<video class='shotimg'  width='400' src='{$todaysrc}' controls autoplay>
                            해당 브라우저는 video 태그를 지원하지 않습니다.
                            </video>";
                        }else{ // 비디오 src가 null인 경우
                            echo "<video class='shotimg'  width='400' controls autoplay>
                            해당 브라우저는 video 태그를 지원하지 않습니다.
                            </video>";
                        }
                    }
                    ?>                    
                    <textarea class="showplus"><?php echo $todaydetail ?></textarea>
                    
                    <!-- 수정 할 때 필요한 인증샷 idx -->
                    <input class="shotidxinfo" type="hidden" value="<?php echo $todayidx ?>">
                </td>
            <?php }?>


        </tr>
    </table>


    <?php
    // 제출인 경우 => 두개의 이미지가 individualchallenge데이터베이스에 저장된다 -> 저장후에 인증샷 보드에 올라가게 됨
    // 수정인 경우  => 이미 올라간 이미지를 수정한다.
    if($buttoname=="제출"){
        echo "<button id='shotsubmit'>제출</button>";
    }else if($buttoname=="수정"){
        echo "<button id='shotsupdate'>수정</button>";
    }
    ?>

<?php }else{ // 인증 가능 시간 지남 ?>
    <div>
    인증 가능한 시간을 지났습니다
    </div>
<?php } ?>
</div>
<?php }else{ // 인증불가기간 ?>
    <div style="text-align:center">인증샷을 올릴 수 있는 기간이 아닙니다</div>
<?php } ?>



<hr>
<!-- 인증샷 보드 -->
<!-- 
여지까지 올렸던 인증샷을 바둑판 형식으로 보여준다.
부적합한 인증샷의 경우 부적합(빨간색)표시를 해줌 
아래 날짜 표시
 -->
<div id="shotboard">
    <h3>인증샷 보드</h3>
<?php
$temp = "select * from challengeshot where challengeidx=$idx and joiner='{$_SESSION['user']}' order by idx";
$sql = mq($temp);
$shotcount = mysqli_num_rows($sql);

// 달성률
// 총 몇 주인가? = (날짜차이 + 1)/7
$wholeweeks = datediff($startday,$endday)+1;
$wholeweeks = $wholeweeks/7;
if($wholeweeks<1){// 1~6일 인 경우
    $wholeweeks = 1;
}
// 총 필요한 인증샷 갯수 : (한 주당 필요한 인증샷 갯수*하루 필요한 인증샷 갯수)*총 몇 주
$requireshots = ($frequency*$proofshotcount)*$wholeweeks;

// 만약 7일 이하라면, 그 기간만큼 인증샷 있어야 함
if(datediff($startday,$endday)+1<7){
    $requireshots = $proofshotcount*(datediff($startday,$endday)+1);
}
// 달성률 : 현재 올린 인증샷(적합판정을 받은)/총 필요한 인증샷*100
$forrate = mq("select * from challengeshot where challengeidx=$idx and joiner='{$_SESSION['user']}' and fit='1'");
$forratecount = mysqli_num_rows($forrate);
$achievementrate = round($forratecount/$requireshots*100); // 달성률
echo "달성률 : ".round($forratecount/$requireshots*100)."%"."</br>";
echo "(부적합 판정 받은 인증샷의 경우, 색부분을 클릭하면 관리자에게 문의가능합니다).<br>";
// 만약, 달성률이 100%라면, 환급신청
// 만약, 환급신청을 이미 했을 경우에는, 환급신청 대기중
$refundcheck = "select * from refund where user='{$_SESSION['user'] }' and challengeidx={$idx}";
$refundcheck = mq($refundcheck);
// 환급 신청 결과
$refundfit = $refundcheck->fetch_array();
$refundfit = $refundfit['fit'];

$refundcheck = mysqli_num_rows($refundcheck);
if($refundcheck>=1){
    $refundbtnname = "환급신청심사중";
    
    if($refundfit=="1"){
        $refundbtnname = "환급신청수락";
    }else if($refundfit=="0"){
        $refundbtnname = "환급신청거절";
    }
}else{
    $refundbtnname = "환급신청";
}
if($achievementrate==100){ ?>
   <button id="refund" <?php if($refundcheck>=1){echo "disabled";} ?>><?php echo $refundbtnname; ?></button>
<?php }
if(!$sql){ // 실패
    echo mysqli_error($db);
}else{ // 성공 ?> 
    <table>
        <?php
        // td칸은 총 필요한 인증샷 갯수(requireshots)만큼 생겨야 한다
        $count = 1;
        if($requireshots==1){$count = 0;}
        for($i=0; $i<10; $i++){
            echo "<tr>";
            if($count==$requireshots){
                
                break;         
            }
            for($j=0; $j<4; $j++){
                $row = $sql->fetch_array();
                
                if($requireshots==1 && $count==1){break;}
                // 더 이상 정보가 없다면 td만들지 x
                // if($row==""){break;}
                
                
                /*
                가져 올 정보 : idx, shot, detail, date, fit
                */
                $idx=$row['idx'];
                $shot=$row['shot'];
                $detail=$row['detail'];
                if($detail==""){$detail="&nbsp&nbsp";}
                $date=$row['date'];
                $fit=$row['fit']; 
                ?>
                <?php
                if($idx==""){ // 인증샷이 아직 등록되지 않음(근데 아래서 처리함..이 아래 else문)  ?> 
                    
                    <td class="shotitem" style="width:80px; word-break:reak-all">
                        <?php 
                         
                        $shot="https://steamuserimages-a.akamaihd.net/ugc/943954717955415548/17EC665540C2964BEE530021D011DAD4685FCA16/";
                        $detail="&nbsp&nbsp";
                        $date="&nbsp&nbsp";
                        
                        ?>                  
                        <!-- 날짜  -->
                        <div><?php  echo $date ?></div>
                        <!-- 인증샷 or 동영상 -->
                        <img src="<?php echo $shot ?>">
                        <br>
                        <!-- 자세한 설명 -->
                        <div><?php echo $detail; ?></div>
                        <!-- 적합성
                        fit = 1이면 파란색
                        fit = 0이면 빨간색
                        -->
                        <?php 
                        $color = "white";
                        $icon="https://twemoji.maxcdn.com/2/72x72/1f64f.png";
                        ?>
                        <div class="fitcolor" style="background-color:<?php echo $color ?>; min-width:100%;">
                        &nbsp
                        </div>
                        
                    </td>
                <?php }else{ ?>
                <td class="shotitem" style="width:80px; word-break:reak-all">
                    <?php 
                    //echo $count;  
                    // 아직 입력되지 않는 칸이라면 => Wait
                    if($idx==""){
                        $shot="https://steamuserimages-a.akamaihd.net/ugc/943954717955415548/17EC665540C2964BEE530021D011DAD4685FCA16/";
                        $detail="&nbsp&nbsp";
                        $date="&nbsp&nbsp";
                    }
                    ?>
                    
                    <!-- 날짜  -->
                    <div><?php echo $date; ?></div>
                    <!-- 인증샷 or 동영상 -->
                    <?php
                    if($video==0){  // 사진인증인 경우 ?>
                    <img src="<?php echo $shot ?>">
                    <?php }else{//비디오 인증인 경우 ?>
                    <video src="<?php echo $shot ?>" controls>해당 브라우저는 video 태그를 지원하지 않습니다</video>
                    <?php }  ?>
                    
                    <br>
                    <!-- 자세한 설명 -->
                    <div><?php echo $detail; ?></div>
                    
                    <!-- 적합성
                    fit = 1이면 파란색
                    fit = 0이면 빨간색
                    -->
                    <?php 
                    if($fit==1){// 달성시
                        $color = "green";
                        $icon = "https://twemoji.maxcdn.com/2/72x72/1f60a.png";
                    }else{ // 미달성시
                        $color = "#FF0040";
                        $icon="https://twemoji.maxcdn.com/2/72x72/1f97a.png";
                    }
                    if($idx==""){
                        $icon="https://twemoji.maxcdn.com/2/72x72/1f64f.png";
                    }
                    ?>
                    <div class="fitcolor" style="background-color:<?php echo $color ?>; min-width:100%;">
                        <img style="width:20px; height:20px;" src="<?php echo $icon; ?>">
                    </div>
                    <?php
                        // 만약, 이미 문의한 인증샷이라면, 이미 문의했다는 표시해주기(답변후)
                        
                        $askok = mq("select * from shotask where user='{$_SESSION['user']}' and challengeshotidx={$idx}");
                        $askyesno = mysqli_num_rows($askok);
                        //echo $askyesno;
                        //$askok = $askok->fetch_array();
                        //$answer
                        //if($askyesno>=1 &&)
                    ?>
                    <!-- 적합/부적합 표시 -->
                    <input class="fitinfo" type="hidden" value="<?php echo $fit ?>">
                    <!-- 이미 문의한 인증샷인지/아닌지 확인 -->
                    <input class="askyesno" type="hidden" value="<?php echo $askyesno ?>">
                    <!-- shotidx 정보 -->
                    <input class="idxinfo" type="hidden" value="<?php echo $idx ?>">
                    <!-- shotask 정보 -->
                    
                </td>
                
                <?php }
                ?>
                <?php 
                
                if($count==$requireshots){break;}
                $count=$count+1;
                ?>
        <?php }
            echo "</tr>";
        }
        ?>
    </table>

<?php } ?>

</div>


<!-- 제이쿼리  -->
<script  src="https://code.jquery.com/jquery-latest.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="challengeindividualread.js"></script>
</body>
</html>