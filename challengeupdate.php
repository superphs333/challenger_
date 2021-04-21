
<html>
<head>
    <!-- 외부 css 불러오기 -->
    <link rel="stylesheet" type="text/css" href="./whole.css" />
</head>
<body id="challengewrite">
    <?php
    // db파일, phpfunction파일 불러오기
    Include "./top.php";

    onlyadmin();

    // idx값 가져오기
    $idx = $_GET['idx'];
    $sql = mq("select * from challenge where idx={$idx}");
    if($sql->num_rows==0){
        echo"<script>
        alert('삭제되었거나 없는 페이지입니다');
        history.back();
        </script>";
    }
    $sql = $sql->fetch_array();
    $title = $sql['title'];
    $startday = $sql['startday'];
    $endday = $sql['endday'];
    $entryfee = $sql['entryfee'];
    $additionaldescription = $sql['additionaldescription'];
    $thumbnail = $sql['thumbnail'];  
    if($thumbnail==""){
        $thumbnail = "./upload/thumbnail/202006300556298f85517967795eeef66c225f7883bdcb.png";
    }  
    $frequency = $sql['frequency'];
    $writer = $sql['writer']; 
    $sort = $sql['sort'];
    $proofshotcount = $sql['proofshotcount'];
    $video = $sql['video'];
    $starttime = $sql['starttime'];
    $endtime = $sql['endtime'];
    // 문자열을 시간으로 변경
    // $starttime = strtotime($starttime);
    // echo $starttime;
    // echo date("h:i",$starttime);
    
    ?>
    <h1>챌린지 수정</h1>
    <hr>
    
    <!-- 개설 부분 -->
    <table id="challengewritetable">
        <tr>
            <input type="hidden" id="optioncheck" value="<?php echo $sort ?>">
            <td class="title">분류</td>
            <td class="content">
                <select id="challengewritesort">
                    <option id="건강운동" value="건강운동" checked>건강/운동</option>
                    <option id="생활습관" value="생활습관">생활습관</option>
                    <option id="자기계발" value="자기계발">자기계발</option>
                    <option id="감정관리" value="감정관리">감정관리</option>
                    <option id="기타" value="기타">기타</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="title">이름</td>
            <td class="content">
                <input type="text" id="challengewritetitle" value="<?php echo $title ?>">
            </td>
        </tr>
        <tr>
            <td class="title">썸네일</td>
            <td class="content">
                <input type="file" id="cwthumbnailfile">
                <img id="cwthumbnailimg" src=<?php echo $thumbnail ?>>
            </td>
        </tr>
        <tr>
            <td class="title">시작일</td>
            <td class="content">
                <input type="date" id="cw_startday" value="<?php echo $startday ?>">
            </td>
        </tr>
        <tr> 
            <td class="title" >기간</td>
            <?php
            /*
            기간 계산
            날짜 차이가 7일 미만이면 => 1주일 이하에 체크 버튼
            , 날짜 차이가 7일 이상이면 => 1주일 이상에 체크 버튼
            */
            // 날짜차이
            $wholeweeks = datediff($startday,$endday)+1;
            //echo "날짜차이=".$wholeweeks;
            $wholeweeks = $wholeweeks/7;
            if($wholeweeks<1){// 1~6일 인 경우
                $weekorday = "day";
                $detailday = datediff($startday,$endday)+1;
            }else{// 7일 이상인 경우
                $weekorday = "week";
                $detailday = $wholeweeks;
            }
            ?>
            <td class="content">
                <input type="radio" class="weekhr" id="weekhigh" name="weekhr" value="weekhigh" checked>1주일 이상
                <input type="radio" class="weekhr" id="weekrow" name="weekhr" value="weekrow">1주일 이하     
                <br>
                <!-- 1주일 이하 선택했을 경우 -->                
                <input type="number" id="dayselect" max="6" min="1" value="3" onkeyup="this.value=minmax(this.value,1,6)">&nbsp
                <!-- 1주일 이상 선택했을 경우 -->
                <input type="number" id="weekselect" min="1" value="1" onkeyup="this.value=minmax(this.value,1,100)">&nbsp
                <c id="week">주</c> <c id="day">일</c>
                <input type="hidden" id="weekorday" value="<?php echo $weekorday?>">
                <input type="hidden" id="detailday" value="<?php echo $detailday?>">
            </td>
        </tr>
        <!-- <tr>
            <td class="title">종료일</td>
            <td class="content">
                <input type="date" id="cw_endday" value="<?php echo $endday ?>">
            </td>
        </tr> -->
        <tr>
            <td class="title">참가비</td>
            <td class="content"><input type="number" id="cwentryfee" value="<?php echo $entryfee ?>">원</td>
        </tr>
        <tr>
            <td class="title">동영상/사진인증</td>
            <td class="content">
                <?php
                // $video값이 1이면 => videoyes, 반대면 videono
                if($video==1){
                    $video="videoyes";
                }else{
                    $video="videono";
                }
                ?>
                <input type="hidden" name="video" id="videocheck" value="<?php echo $video?>">
                <input type="radio" name="video" id="videoyes" value="1">동영상
                <input type="radio" name="video" id="videono" value="0" checked>사진
            </td>
        </tr>
        <tr>
            <td class="title">인증샷 갯수</td>
            <td class="content"><input type="number" id="cwshotcount" value="<?php echo $proofshotcount ?>">개</td>
        </tr>
        <tr>
            <td class="title">인증 시간</td>
            <td class="content">                
                <input type="time" id="starttime" value="<?php echo $starttime ?>">~<input type="time" id="endtime" value="<?php echo $endtime ?>">
                <br>(기본값 :  24시간 인증 가능합니다)
            </td>
        </tr>
        <!-- <tr id="frequencyselect">
            <td class="title">인증빈도 설정</td>
            <input type="hidden" id="radiocheck" value="<?php echo $frequency ?>">
            <td class="content">
                
                <input type="radio" value="7" name="chk_info" id="7">매일
                <input type="radio" value="1" name="chk_info" id="1">주1회
                <input type="radio" value="2" name="chk_info" id="2">주2회
                <input type="radio" value="3" name="chk_info" id="3">주3회
                <input type="radio" value="4" name="chk_info" id="4">주4회
                <input type="radio" value="5" name="chk_info" id="5">주5회
                <input type="radio" value="6" name="chk_info" id="6">주6회
            </td>
        </tr> -->
        <tr>
            <td class="title">설명</td>
            <td class="content"><textarea id="summernote"><?php echo $additionaldescription ?></textarea></td>
        </tr>
    </table>
    <input type="hidden" value="<?php echo $idx ?>" id="idx">
    <button id="cw_submit">수정하기</button>
    

    <!-- 제이쿼리 -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>

    <!-- include libraries(jQuery, bootstrap) -->
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>

    <!-- include summernote css/js-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.js"></script>

    <!-- 자바스크립트 파일 삽입-->
    <script type="text/javascript" src="./challengewrite.js"></script>
    
    <script>

    </script>
   

</body>
</html>