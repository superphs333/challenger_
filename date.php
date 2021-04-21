<?php
$timestamp = strtotime("Now");
echo "현재 일시 : ".date("Y-m-d H:i:s", $timestamp)."<br/>";

$timestamp = strtotime("+14 days");
echo "현재로부터 1일 뒤 : ".date("Y-m-d", $timestamp)."<br/>";
?>