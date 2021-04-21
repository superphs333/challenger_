
<html>
<head>
</head>
<body>
<!-- 선택 -->
<div id="sort">
    <!-- 참여가능한 이벤트만 보기 -->
    <input id="joinable" type="checkbox" value="yes" <?php if($_GET['joinable']=="1"){echo "checked";} ?>>참여 가능한 이벤트만 보기
</div>
<?php
// 북마크 한 챌린지 불러오기 => chalenge + challenge_bookmark
$temp = "select * from challenge as c left join challenge_bookmark as cb on c.idx=cb.idx where cb.user='{$_SESSION['user']}'";

if($_GET['joinable']=="1"){
    $tempjoinable = " and c.startday>=DATE(NOW()) ";
    $temp = $temp.$tempjoinable;
    $joinable = "1";
}

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

// 만약 아무것도 없으면, 북마크 한 챌린지가 없습니다
if($count==0){
    echo "<div style='text-align:center'>북마크 한 챌린지가 없습니다</div>";
}
?>

<div id="tc_tablewrap">
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
                $startday=$row['startday'];
                $endday=$row['endday'];
                $entryfee=$row['entryfee'];
                $frequency=$row['frequency'];
                $proofshotcount=$row['proofshotcount'];
                $sort=$row['sort'];

                // 참여자수
                $joincount = "select * from challenge_join where idx='{$idx}'";
                $sqljoincount = mq($joincount);
                $joincount = mysqli_num_rows($sqljoincount);

                // 기간
                $days = datediff($startday,$endday)+1;
                $wholeweeks = $days/7;

                ?>
                <td id="challengeitem" style=cursor:hand; 
                onclick="window.open('./challengeread.php?idx=<?php echo $idx; ?>','챌린지','width=100%,height=100%,location=no,status=no,scrollbars=yes')"
                >
                    <!-- 썸네일 이미지 부분 -->
                    <img src="<?php echo $thumbnail ?>">
                    <table>
                        <tr>
                            <td id="item_sort"><?php echo $sort ?></td>
                        </tr>
                        <tr>
                            <td id="item_title" ><?php echo $title ?></td>
                        </tr>   
                        <tr>
                            <td>
                                <!-- 기간 -->
                                <span id="item_period">
                                    <?php
                                    echo $startday."~".$endday."(";

                                    if($wholeweeks<1){
                                        
                                        // 1주일 미만
                                        echo $days."일";
                                    }else{
                                        // 1주일 이상
                                        echo $wholeweeks."주";
                                    }
                                    echo ")";
                                    ?>
                                </span>
                            </td>                            
                        </tr>  
                        <tr>
                            <td>
                                <!-- 참가비용 -->
                                <span id="item_entryfee"><?php echo $entryfee."원" ?></span>
                            </td>
                        </tr>    
                        <tr>
                            <td>
                                <!-- 인증주기  -->
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
                            
                        </tr>   
                        <tr>
                            <td>
                                <!-- 참가자수 -->
                                <span id="item_joincount"><?php echo $joincount."명 참가" ?></span>
                            </td>
                        </tr> 
                                                                                        <!-- 참여 가능한 이벤트인 경우,
                        초록색 네모-->
                        <?php
                        // 오늘날짜 <= 챌린지 시작일
                        $timenow = date("Y-m-d");
                        $str_now = strtotime($timenow);
                        $str_target = strtotime($startday);
                        if($str_now<=$str_target){
                        // 참여가능한 경우
                        $color = "green";
                        }else{
                            $color = "red";
                        }
                        ?>
                        <tr>
                            <td>
                                <br>
                                <span id="item_joinable" style="background-color:<?php echo $color ?>">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span>
                            </td>
                        </tr>               
                    </table>
                </td>
            <?php }
            echo "</tr>";
        }
        ?>
    </table>
</div>

<!-- 페이징 -->
<div style="text-align:center">
<?php
    if($pageNum<=1){//페이지번호가 1보다 작거나 같다면

        echo "<font size=2 color=red> [처음] </font>";
            // 링크없이 그냥 처음이라는 문자만 출력
    }else{ // 1보다 크다면

        echo "<font size=2><a class='pagelink' href='mypage.php?page=&list={$list}&joinable={$joinable}&mypage=Bookmark'> [처음] </a></font>";
    
    }
    
    if($block<=1){
        
        // block이 1보다 작거나 같다면 , 더 이상 거꾸로 갈 수 없으므로 아무 표시도 하지 않는다.
        echo "<font></font>";
    }else{  
        $insertpage = $b_start_page-1;
        echo "<font size=2><a class='pagelink' href='mypage.php?page={$insertpage}&list={$list}&joinable={$joinable}&mypage=Bookmark'> 이전 </a></font>";
    }
    
    for($j=$b_start_page; $j<=$b_end_page; $j++){
        if($pageNum==$j){
            
            // pageNum=j이면, 현재 페이지이므로,링크걸지않고 그냥 현재 페이지만 출력
            echo "<font class='pagelink' size=2 color=red> {$j} </font>";
        }else{
            
            echo"<font size=2><a class='pagelink' href='mypage.php?page={$j}&list={$list}&joinable={$joinable}&mypage=Bookmark'> {$j} </a></font>";
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
        echo "<font size=2><a class='pagelink' href='mypage.php?page={$temp}&list={$list}&joinable={$joinable}&mypage=Bookmark'> 다음 </a></font>";
    }

    // 마지막 링크 버튼
    if($pageNum>=$total_page){
        // 페이지넘버 = 총페이지
        echo "<font size=2 color=red> [마지막] </font>";
    }else{
        //그게 아니라면
        echo "<font size=2><a class='pagelink' href='mypage.php?page={$total_page}&list={$list}&joinable={$joinable}&mypage=Bookmark'> [마지막] </a></font>";
    }
?>
</div>



</body>
</html>