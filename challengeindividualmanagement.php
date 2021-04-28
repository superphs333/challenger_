
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
Include "./db.php";
Include "phpfunction.php";
?>
<?php
// 관리자만 접근 가능
onlyadmin();
?>
<!-- 챌린지 자체에 대한 설명 -->
<?php
    // 해당 파일 불러오기
    //$idx = $_GET['post']; 
    // $idx=$_GET['idx']; // 챌린지 idx
    // $user = $_GET['user']; // 유저
    $refundidx = $_GET['refund']; // 환급번호 idx
    $position = $_GET['position']; // 부모창 클릭한 위치


    // 환급번호로 챌린지idx, user알아내기
    $for_info_temp = "select challengeidx, user from refund where idx={$refundidx}";
    $sql_info = mq($for_info_temp);
    $result_info = $sql_info->fetch_array();
    $idx = $result_info['challengeidx'];
    $user = $result_info['user'];


    // 데이터베이스 불러오기
    $sql = mq("select * from challenge where idx={$idx}");
    $sql = $sql->fetch_array();
    $title = $sql['title'];
    $startday = $sql['startday'];
    $endday = $sql['endday'];
    $entryfee = $sql['entryfee'];
    $additionaldescription = $sql['additionaldescription'];
    $thumbnail = $sql['thumbnail'];
    $frequency = $sql['frequency'];
    $writer = $sql['writer']; 
    $sort = $sql['sort'];
    $proofshotcount = $sql['proofshotcount'];
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
<input type="hidden" id="participant" value="<?php echo $user ?>">
<input type="hidden" id="participant" value="<?php echo $user ?>">
<input type="hidden" id="position" value="<?php echo $position ?>">
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
        <tr>
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
        </tr>
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


<hr>
<!-- 인증샷 보드 -->
<!-- 
여지까지 올렸던 인증샷을 인스타그램 형식으로 보여준다.
부적합한 인증샷의 경우 부적합(빨간색)표시를 해줌 
아래 날짜 표시
 -->
<div id="shotboard">
    <h3>인증샷 보드</h3>
<?php
$temp = "select * from challengeshot where challengeidx=$idx and joiner='{$user}' order by idx";
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
$forrate = mq("select * from challengeshot where challengeidx=$idx and joiner='{$user}' and fit='1'");
$forratecount = mysqli_num_rows($forrate);
$achievementrate = round($forratecount/$requireshots*100); // 달성률
echo "달성률 : ".round($forratecount/$requireshots*100)."%"."</br>";



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
            if($count==$requireshots){break;}
            for($j=0; $j<4; $j++){
                $row = $sql->fetch_array();

                // 더 이상 정보가 없다면 td만들지 x
                // if($row==""){break;}
                if($requireshots==1 && $count==1){break;}
                
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
                if($idx==""){?>
                    <td class="shotitem" style="width:80px; word-break:reak-all">
                        <?php 
                        $shot="https://steamuserimages-a.akamaihd.net/ugc/943954717955415548/17EC665540C2964BEE530021D011DAD4685FCA16/";
                        $detail="&nbsp&nbsp";
                        $date="&nbsp&nbsp";
                        ?>                  
                        <!-- 날짜  -->
                        <div><?php echo $date; ?></div>
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
                        $color = "skyblue";
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
                    <input class="fitinfo" type="hidden" value="<?php echo $fit ?>">
                    <input class="idxinfo" type="hidden" value="<?php echo $idx ?>">
                    
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

<!-- 수락 or 거절 -->
<?php
// 수락 or 거절값이 있다면(fit = 1 or 0)알려준다
$tempforfit = "select * from refund where idx={$_GET['refund']}";
$tempforfit = mq($tempforfit);
$forfit = $tempforfit->fetch_array();
$forfit = $forfit['fit'];
$fitstatus="";
if($forfit=="1"){
    $fitstatus="수락상태";
}else if($forfit=="0"){
    $fitstatus="거절상태";
}else{
    $fitstatus="대기";
}
?>
<div style="text-align:center"><?php echo $fitstatus ?></div>
<div style="text-align:center" >
    <input id="refundidx" type="hidden" value="<?php echo $refundidx ?>">
    <!-- merchant_uid -->
    <?php 
    $merchant_uid = mq("select * from refund where idx='{$refundidx}'");
    $merchant_uid = $merchant_uid->fetch_array();
    $merchant_uid = $merchant_uid['merchant_uid'];
    //echo $merchant_uid;

    // 가격 알아내기 by challengeidx
    $cost = mq("select * from refund where idx='{$refundidx}'");
    $cost = $cost->fetch_array();
    $cost = $cost['challengeidx'];
   
    ?>
    <!-- $refundidx 정보 -->
    <input type="hidden" id="refundidx" value="<?php echo $refundidx ?>">
    <!-- "merchant_uid 정보 -->
    <input type="hidden" id="merchantuid" value="<?php echo $merchant_uid ?>">
    <!-- 가격 -->
    <input type="hidden" id="cost" value="<?php echo $cost?>">
    <button class="refundfit">수락</button>

    <button class="refundfit">거절</button>
</div>



<!-- 제이쿼리 -->
<!-- 결제 -->
<script type="text/javascript" src="https://cdn.iamport.kr/js/iamport.payment-1.1.5.js"></script>
<script
src="https://code.jquery.com/jquery-3.3.1.min.js"
integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
crossorigin="anonymous"></script><!-- jQuery CDN --->
<script  src="https://code.jquery.com/jquery-latest.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script src="challengeindividualmanagement.js"></script>
</body>
</html>