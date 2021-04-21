<?php
$upfile_path = "/upload/"; // 화일업로드 디렉토리 권한은 707 이여야 합니다.
$tmp_file =  $_FILES[upfile][tmp_name]; // 업로드된 화일의 임시이름
$filename  = $_FILES[upfile][name]; // 업로드 하려한 화일명(원래 이름)
$filepath = urlencode($filename); // 한글화일명을 대비해 urlencode 로 감는다.
$dest_file = $upfile_path ."/". $filepath; // 화일 위치와 화일명을 지정

 if(is_uploaded_file($tmp_file)) { // 업로드 화일이 존재하는지 체크
  $error_code = move_uploaded_file($tmp_file, $dest_file); // 임시화일을 실제 업로드 디렉토리로 옮긴다.
  chmod($dest_file, 0606); // 권한 606 을 준다.
 }

  echo "업로드가 완료되었습니다. 플레이 주소 : "
  ?>