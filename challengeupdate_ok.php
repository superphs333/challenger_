<?php
    /*
    가져온 데이터 : title:title,thumbnail:thumbnail,startday:startday,endday:endday,entryfee:entryfee,shotcount:shotcount,summernote:summernote,idx:idx
    */

    // db, phpfunction파일 불러오기
    include "./db.php";
    include "./phpfunction.php";

    // 데이터 가져오기
    $sort = $_POST['sort']; // 카테고리
    $title = $_POST['title']; // 제목
    $thumbnail = $_POST['thumbnail']; // 썸네일 이미지 src
    $startday = $_POST['startday']; // 시작일
    $endday; // 종료일
    $period = $_POST['period']-1; // 기간
    // 컴퓨터가 인식할 수 있게 변환한 값에서 날짜(기간)를 더한다
    $tempstring = $startday."+".$period." days";
    $str_date = strtotime($tempstring);
    // 더한 값(컴퓨터값)을 일반날짜로 변환하고 endday에 저장
    $endday = date("Y-m-d",$str_date);
    $entryfee = $_POST['entryfee']; // 등록비
    $shotcount = $_POST['shotcount']; // 인증샷 갯수
    $summernote = $_POST['summernote']; // 자세설명
    $frequency = (int)$_POST['frequency']; // 인증빈도
    $video = $_POST['video'];
    if($video=="1"){
        $video=1;
    }else{
        $video=0;
    }
    $starttime = $_POST['starttime'];// 인증가능시작시간
    $endtime = $_POST['endtime'];//인증가능끝시간
    $idx = $_POST['idx']; // 글번호
    echo $idx;
    // echo "title=".$title."</br>"."thumbnail=".$thumbnail."</br>"."startday=".$startday."</br>"."endday=".$endday."</br>"."entryfee=".$entryfee."</br>"."shotcount=".$shotcount."</br>"."summernote=".$summernote."</br>";

    // 데이터베이스에 내용 저장
    $temp = "UPDATE challenge set sort='{$sort}',title='{$title}',startday='{$startday}',endday='{$endday}',entryfee={$entryfee},additionaldescription='{$summernote}',thumbnail='{$thumbnail}',proofshotcount={$shotcount},frequency={$frequency},video={$video},starttime='{$starttime}',endtime='{$endtime}' WHERE idx={$idx}";
    //echo $temp;
    $sql = mq($temp);

    if($sql){
        //echo "su";
        // 페이지 이동 -> 해당 idx값 read
    }else{
        //echo mysqli_error($db);
    }
?>