<?php

    function random_char($length=8,$strength=8){
        $counter = ceil($length/4);

        // 0보다 작으면 안된다
        $counter = $counter > 0 ? $counter : 1;

        $charList = array( 
            array("0", "1", "2", "3", "4", "5","6", "7", "8", "9", "0"),
            array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"),
            array("!", "@", "#", "%", "^", "&", "*") 
        );

        $password = "";

        for($i = 0; $i < $counter; $i++)
        {
            $strArr = array();
            for($j = 0; $j < count($charList); $j++)
            {
                $list = $charList[$j];

                $char = $list[array_rand($list)];
                $pattern = '/^[a-z]$/';
                // a-z 일 경우에는 새로운 문자를 하나 선택 후 배열에 넣는다.
                if( preg_match($pattern, $char) ) array_push($strArr, strtoupper($list[array_rand($list)]));
                array_push($strArr, $char);
            } 
            // 배열의 순서를 바꿔준다.
            shuffle( $strArr );

            // password에 붙인다.
            for($j = 0; $j < count($strArr); $j++) $password .= $strArr[$j];
        }

        // 길이조정
        return substr($password, 0, $length);
    }

    // 날짜차이
    function datediff($startday,$endday){
        // 매개변수 : 시작일, 마지막일

        $date1 = new DateTime($startday);
        $date2 = new DateTime($endday);
        $interval = date_diff($date1,$date2)->format('%d days');
        return $interval;
    }

    // 암호화 함수
    function Encrypt($str, $secret_key='secret key', $secret_iv='secret iv')
    {
        $key = hash('sha256', $secret_key);
            /* hash(string $algo, string $data [,bool $raw_output=false])
            - algo = 해시 알고리즘(md5, sha256)
            - data = 해시 알고리즘을 적용 할 데이터
            - raw_output = true일 경우 바이너리 데이터로 결과 반환, false일 경우 소문자 hex값으로 반환
            */
        $iv = substr(hash('sha256', $secret_iv), 0, 32);
            /* substr(string, start [,length])
            = 문자열의 일부분을 추출하는 함수
            - string = 추출의 대상이 되는 문자열
            - start = 추출을 시작하는 위치
            - length = 추출할 문자의 개수(값이 없으면 문자열 끝까지 추출, 음수일 때는 위치를 뜻하고, 그 위치 앞까지의 문자를 추출한다)
            */

        return str_replace("=", "", base64_encode(
        openssl_encrypt($str, "AES-256-CBC", $key, 0, $iv))
        );
            /* str_replace(바껴서 없어질 문자, 변경 완료 될 문자, 대상문자열)
            */
            /* base64_encode(문자열) = 2진 데이터를 아스키 코드에 해당하는 문자열로 변경해주는 방식
            => 2진 데이터를 아스키 형태로 변경하는 것을 이 함수가 처리해주며(아스키 형태의 데이터를 2진 데이터로 복원하는 것을 base64_decode가 처리해 준다)
            */
            /* openssl_encrypt(내용, 암호화 종류, 키값, true, str_repelat(char(0),16))
            - 키값 = 암호화하거나 복호화 할 때 사용하는 비밀번호
            */
    }

    // 복호화 함수
    function Decrypt($str, $secret_key='secret key', $secret_iv='secret iv'){
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 32);

        return openssl_decrypt(base64_decode($str), "AES-256-CBC", $key, 0, $iv
        );
    }

    // 현재 존재하는 쿠키값이 있다면, 이 값을 복호화 하여 세션값으로 넣는다
    function inputsession(){
        if(isset($_COOKIE['user_id_cookie'])){
            // 현재 (암호화 된)쿠키값
            $idcookie = $_COOKIE['user_id_cookie'];

            // 암호화 된 쿠키값을 복호화하기
            $idcookie = Decrypt($idcookie);

            // 세션에 복호화 한 쿠키값 넣기
            $_SESSION['user'] = $idcookie;
        }
    }

    function onlyadmin(){
        // 관리자 계정이 아니라면, 뒤로가기
        
        // 데이터베이스에서 가져온다
        $forsort = mq("select * from members where email='{$_SESSION['user']}'");
        $forsort = $forsort->fetch_array();
        $membersort = $forsort['sort'];
        //echo $membersort;

        // if($membersort!="staff"){
        //     //echo "권한없음";
        //     echo"<script>
        //     alert('권한없는 페이지 입니다');
        //     history.back();
        //     </script>";
        // }
    }

    function membercheck(){
        if($_SESSION['user']==""){
            //echo "권한없음";
            echo"<script>
            alert('회원만 이용 가능한 페이지입니다');
            history.back();
            </script>";
        }
    }

    // 회원만 클릭 가능
    function member_able(){
        if($_SESSION['user']==""){
            echo"<script>
            alert('회원만 가능합니다. 회원가입 또는 로그인을 해주세요');
            </script>";
        }
    }

    // 페이징
    function paging($total_rows,$list,$b_pageNum_list){
        // 매개변수 : 총 레코드 수,sql문, 한 페이지에 몇 개의 글을 보여줄지,블록에 나타낼 페이지 번호 갯수      
        
  
        // 페이지 번호
        $pageNum=($_GET['page'])?$_GET['page']:1;
            // page값을 받아서, 있다면 그대로 $_GET['page'] 값을 사용하고,비어있다면1로 값을 지정하는 조건문

        // 한 페이지에 몇 개의 글을 보여줄지
        $list=($_GET['list']?$_GET['list']:9);

        // 현재 리스트의 블럭을 구한다
        $block = ceil($pageNum/$b_pageNum_list);

        // 현재 블럭에서 시작페이지 번호
        $b_start_page = (($block-1)*$b_pageNum_list)+1;

        // 현재 블럭에서 마지막 페이지 번호
        $b_end_page = $b_start_page+$b_pageNum_list-1;

        // 총 게시글의 페이지 수
        $total_page = ceil($total_rows/$list);

        // 블럭의 마지막 페이지가 총 페이지 수보다 클 때 숫자를 같게 해주는 조건
         if($b_end_page>$total_page){
            $b_end_page=$total_page;
        }

        $start_record = ($pageNum-1)*$list;
    }

?>