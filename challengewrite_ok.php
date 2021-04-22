<?php
    /*
    가져온 데이터 : sort:sort,title:title,thumbnail:thumbnail,startday:startday,endday:endday,entryfee:entryfee,shotcount:shotcount,summernote:summernote,frequency:frequency,period:period,video:video,starttime:starttime,endtime:endtime
    */

    // db, phpfunction파일 불러오기.
    include "./db.php";
    include "./phpfunction.php";

    // 데이터 가져오기
    $sort = $_POST['sort']; // 카테고리
    $title = $_POST['title']; // 제목
    $thumbnail = $_POST['thumbnail']; // 썸네일 이미지 주소
    $startday = $_POST['startday']; // 시작일
    $endday; // 마지막날
    $period = $_POST['period']-1; // 기간
    // 컴퓨터가 인식할 수 있게 변환한 값에서 날짜(기간)를 더한다
    $tempstring = $startday."+".$period." days";
    $str_date = strtotime($tempstring);
    // 더한 값(컴퓨터값)을 일반날짜로 변환하고 endday에 저장
    $endday = date("Y-m-d",$str_date);
    $entryfee = $_POST['entryfee']; // 참가비
    $shotcount = $_POST['shotcount']; // 하루에 필요한 인증샷 갯수
    $summernote = $_POST['summernote']; // 자세한 설명
    $frequency = (int)$_POST['frequency']; // 인증빈도
    $writer = $_SESSION['user']; // 사용자
    $video = $_POST['video'];
    if($video=="1"){
        $video=1;
    }else{
        $video=0;
    }
    //echo $video;
    $starttime = $_POST['starttime'];
    $endtime = $_POST['endtime'];
    //echo "starttime=".$starttime."</br>"."endtime=".$endtime;
    $date = date('Y-m-d H:i:s');
    //echo $frequency;
    // echo "title=".$title."</br>"."thumbnail=".$thumbnail."</br>"."startday=".$startday."</br>"."endday=".$endday."</br>"."entryfee=".$entryfee."</br>"."shotcount=".$shotcount."</br>"."summernote=".$summernote."</br>";

    /*
    예외처리
    - 이름(공백제거하고) 공백x
    - 시작일
    - 종료일
    - 설명
    */

    
    // 데이터베이스에 내용 저장
    $temp="insert into challenge(sort,title,startday,endday,entryfee,additionaldescription,thumbnail,proofshotcount,frequency,writer,date,video,starttime,endtime) values('{$sort}','{$title}','{$startday}','{$endday}','{$entryfee}','{$summernote}','{$thumbnail}','{$shotcount}',{$frequency},'{$writer}','{$date}',{$video},'{$starttime}','{$endtime}')";
    //echo $temp;
    $sql = mq($temp);

    if($sql){
        //echo "su";

        // 이 글에 해당하는 idx값을 출력한다
        $temp = "select * from challenge where writer='{$writer}' and date='{$date}'";
        //echo $temp;
        $sql = mq($temp);
        $idx = $sql->fetch_array();
        $idx = $idx['idx'];
        echo $idx;
    }else{
        echo mysqli_error($db);
    }
?>