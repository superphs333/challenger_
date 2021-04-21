<?php
    // 카테고리 : 써머노트 / 썸네일 이미지
    //echo $_POST['category'];
        
        if($_FILES['file']['name']){
            /*
            $_FILES['file']['name'] = 클라이언트 머신에 존재하는 파일의 원래 이름
            */
            

            if(!$_FILES['file']['error']){ // 에러가 없다면,
    
                // 파일 이름 정하기
                $name = date("Ymdhis");
                $temp = md5(rand(100,200));
                $name = $name.$temp;
                
                // 파일 형식 알아내기
                $ext = explode('.',$_FILES['file']['name']);
    
                // 지정한파일이름.파일형식으로 파일네임 정하기
                $filename = $name.'.'.$ext[1];
    
                // 경로지정
                if($_POST['category']=="thumbnail"){
                    $destination = './upload/thumbnail/'.$filename;
                }else if($_POST['category']=="shots"){
                    $destination = './upload/shots/'.$filename;
                }else{
                    $destination = './upload/cwupload/'.$filename;
                }
    
                // 서버에 저장된 업로드된 파일의 임시 파일 이름
                $location = $_FILES['file']['tmp_name'];
                    /*$_FILES['file']['tmp_name']
                    = 서버에 저장 된 업로드 된 파일의 임시 파일 이름
                    */
                $move = move_uploaded_file($location,$destination);
    
                // if($move){
                //     echo "성공";
                // }else{
                //     echo "에러=====>".$_FILES['file']['error'];
                // }
    
                echo $destination; 
                    // ./upload/cwupload/20200627080641.jpg
            }
        }
  
?>