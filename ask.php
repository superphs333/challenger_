
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
    <h1>인증샷 문의</h1>
    <hr>
    
    <!-- 개설 부분 -->
    <table id="challengewritetable">
        <tr>
            <td class="title">인증샷 </td>
            <td class="content">
                <input type="text" id="challengewritetitle">
            </td>
        </tr>

        <tr>
            <td class="title">설명</td>
            <td class="content"><textarea id="summernote"></textarea></td>
        </tr>
    </table>
    <button id="cw_submit">문의하기</button>
    

    <!-- 제이쿼리 -->
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>

    <!-- include libraries(jQuery, bootstrap) -->
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>

    <!-- include summernote css/js-->
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.js"></script>

    <!-- 자바스크립트 파일 삽입-->
    <script type="text/javascript" src="./challengewrite.js"></script>

</body>
</html>