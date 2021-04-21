<?php
    include "./db.php";

    // 받아온 값 {checknickname:checknickname}
    

    if($_POST['checknickname']==NULL){ // 빈값인 경우
        echo "닉네임을 입력해주세요";
    }else{ // 빈값이 아닌 경우 -> 중복체크
        $sql = mq("SELECT * FROM members WHERE nickname='{$_POST['checknickname']}'");

        $sql=$sql->fetch_array();

        if($sql>=1){
            echo "이미 존재하는 닉네임입니다.";
        }else{
            echo "사용 가능한 닉네임입니다.";
        }
    }


    
?>