<?php
    session_start();

   error_reporting(0);

    // utf-8인코딩
    header('Content-Type: text/html; charset=utf-8');

    // mysql connect
    $host = '127.0.0.1';
    $db = new mysqli($host,"dingpong98","41Asui!@","challenger2");
    $db->set_charset("utf8");

   // error check
    if($db){
        //echo "success";
    }

function mq($sql){
    global $db;
        // global = 외부에서 선언 된 sql을 함수내에서 쓸 수 있도록
    return $db->query($sql); // mysqli_result
}
?>