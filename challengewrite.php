
<html>
<head>
    <!-- 외부 css 불러오기 -->
    <link rel="stylesheet" type="text/css" href="./whole.css" />
</head>
<body id="challengewrite">
    <?php 
    Include "./top.php";
   
    // 관리자 계정이 아니라면, 뒤로가기
    if($_SESSION['user']!="admin@challenger.com"){
        echo "권한없음";
        echo"<script>
        alert('권한없는 페이지 입니다');
        history.back();
        </script>";
    }
    ?>
    <h1>챌린지 개설</h1>
    <hr>
    
    <!-- 개설 부분 -->
    <table id="challengewritetable">
        <tr>
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
                <input type="text" id="challengewritetitle">
            </td>
        </tr>
        <tr>
            <td class="title">
                썸네일<br>(선택하지 않으면 기본이미지로 대체됩니다.)
            </td>
            <td class="content">
                <input type="file" id="cwthumbnailfile">
                <img id="cwthumbnailimg" src="./upload/thumbnail/202006300556298f85517967795eeef66c225f7883bdcb.png">
            </td>
        </tr>
        <tr>
            <td class="title">시작일</td>
            <td class="content">
                <input type="date" id="cw_startday" value="<?php echo date('Y-m-d'); ?>">
            </td>
        </tr>
        <tr>
            <td class="title">기간</td>
            <td class="content">
                <input type="radio" class="weekhr" id="weekhigh" name="weekhr" value="weekhigh" checked>1주일 이상
                <input type="radio" class="weekhr" id="weekrow" name="weekhr" value="weekrow">1주일 이하     
                <br>
                <!-- 1주일 이상 선택했을 경우 -->                
                <input type="number" id="dayselect" max="6" min="1" value="3" onkeyup="this.value=minmax(this.value,1,6)">&nbsp
                <!-- 1주일 이하 선택했을 경우 -->
                <input type="number" id="weekselect" min="1" value="1" onkeyup="this.value=minmax(this.value,1,100)">&nbsp
                <c id="week">주</c> <c id="day">일</c>
                <input type="hidden" id="weekorday" value="week">
            </td>
        </tr>
        <!-- <tr>
            <td class="title">종료일</td>
            <td class="content">
                <input type="date" id="cw_endday">
            </td>
        </tr> -->
        <tr>
            <td class="title">참가비</td>
            <td class="content">
                <input type="number" id="cwentryfee" value=5000>원
                <br>(선택하지 않을 경우, 기본값은 5000원입니다)
            </td>
        </tr>
        <tr>
            <td class="title">동영상/사진인증</td>
            <td class="content">
                <input type="radio" name="video" value="1">동영상
                <input type="radio" name="video" value="0" checked>사진
            </td>
        </tr>
        <tr>
            <td class="title">인증샷 갯수</td>
            <td class="content">
                <input type="number" id="cwshotcount" value=1>개
                <br>(선택하지 않을 경우, 기본값은 1개입니다)
            </td>
        </tr>
        <tr>
            <td class="title">인증 시간</td>
            <td class="content">                
                <input type="time" id="starttime" value=00:00>~<input type="time" id="endtime" value=23:59>
                <br>
            </td>
        </tr>
        <!-- <tr id="frequencyselect">
            <td class="title">인증빈도 설정</td>
            <td class="content">
                <input type="radio" name="chk_info" value="7" checked>매일
                <input type="radio" name="chk_info" value="1">주1회
                <input type="radio" name="chk_info" value="2">주2회
                <input type="radio" name="chk_info" value="3">주3회
                <input type="radio" name="chk_info" value="4">주4회
                <input type="radio" name="chk_info" value="5">주5회
                <input type="radio" name="chk_info" value="6">주6회
                <br>(선택하지 않을 경우, 기본값은 "매일" 입니다)
            </td>
        </tr> -->
        <tr>
            <td class="title">설명</td>
            <td class="content"><textarea id="summernote"></textarea></td>
                
            
        </tr>
    </table>
    <button id="cw_submit">개설하기</button>
    

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

</body>
</html>