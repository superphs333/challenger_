
<html>
<head>
    <!-- 외부 css 불러오기 -->
    <link rel="stylesheet" type="text/css" href="./whole.css" />
</head>
<body id="togetherlenge">
    <?php include_once "./top.php"; ?>

    <br>
    <h1 style="text-align:center">챌린지</h1>

    <!-- 게시물 sort : 카테고리/검색값/체크박스(참여가능한) -->
    <div id="selectsearch">
        
        <!-- 카테고리 선택 -->
        <select id="challengeselect">
            <option id="전체" value="전체">전체</option>
            <option id="건강운동" value="건강운동">건강/운동</option>
            <option id="생활습관" value="생활습관">생활습관</option>
            <option id="자기계발" value="자기계발">자기계발</option>
            <option id="감정관리" value="감정관리">감정관리</option>
            <option id="기타" value="기타">기타</option>
        </select>

        <!-- 현재 참여 가능한 이벤트만 보기 -->
        <!-- <select id="jointime">
            <option id="ing" value="ing">진행가능한 챌린지</option>
            <option id="future" value="future">진행 예정인 챌린지</option>
            <option id="past" value="past">지난챌린지</option>
        </select> -->

        <!-- 검색 -->
        <input type="text" id="search">
        <button id="searchbtn">검색</button>

        <br>
        <!-- 현재 참여 가능한 이벤트만 보기 -->
        <input id="joinable" type="checkbox" value="yes" >참여 가능한 이벤트만 보기

    </div>

    <!-- 개설하기 버튼 : 관리자 계정이 아니면, 개설하기/수정하기는 할 수 없음 -->
    <?php
    // 데이터베이스에서 가져온다
    $forsort = mq("select * from members where email='{$_SESSION['user']}'");
    $forsort = $forsort->fetch_array();
    $membersort = $forsort['sort'];
    //echo $membersort;

    if($membersort=="staff"){
        echo <<<EOT
        <div id="writebuttonwrap">
            <button onClick="location.href='challengewrite.php'">개설하기</button>
        </div>
EOT;
    }
    ?>

  
    


    <!-- 내용 테이블 -->
    <div id="tc_tablewrap">
    <?php
       /*
        3가지 조건 => 
        - 조건이 아예 없다면 => where을 붙이지 않는다
        - 1가지라도 값이 있는 경우 => where 붙임
        - 2가지인 경우 => 
        */
        // challenge데이터베이스에 있는 값들을 불러온다
            // 썸네일, 타이틀, 참여자, startday, endday..
        // 분류(where) => 카테고리, 검색, 참여가능(기간내)
        // 순서(order by) => 참여자순, 북마크순, 
        // get값 받아오기 : 카테고리(challengeselect), 검색값(search), 참여가능(joinable), 순서
        $challengeselect=$_GET['challengeselect']; // 카테고리
        if($challengeselect=="전체"){$challengeselect="";}
        $search = $_GET['search']; // 검색
        $joinable = $_GET['joinable']; // joinable선택

        // 참여가능 or 무관
        if($joinable==""){ // 무관
            $temp = "select * from challenge where sort like '%{$challengeselect}%' and title like '%{$search}%' ";
        }else{ // 참여 가능한 이벤트만
            $temp = "select * from challenge where sort like '%{$challengeselect}%' and title like '%{$search}%' and startday>=DATE(NOW())";
        }
                
     
        //echo $temp."<br>";
        $sql = mq($temp." order by idx desc");

        // 총갯수
        $total_rows = mysqli_num_rows($sql);
        echo "총 게시물 : ".$total_rows;

        /*
        페이지 사전 설정
        - 한 페이지에 몇 개의 레코드(자료)를 출력 할 것인가?
        - 한 화면에 페이지번호를 몇개를 출력 할 것인가
        - 현재 보고자 하는 페이지가 몇번째 페이지인가
        */
        // 페이지 번호
        $pageNum=($_GET['page'])?$_GET['page']:1;
            // page값을 받아서, 있다면 그대로 $_GET['page'] 값을 사용하고,비어있다면1로 값을 지정하는 조건문

        // 한 페이지에 몇 개의 글을 보여줄지 
        $list=($_GET['list']?$_GET['list']:9);
            // page default = 50
            // 한 페이지에 50개의 글 목록
        
        //블럭에 나타낼 페이지 번호 갯수
        $b_pageNum_list = 4;

        // 현재 리스트의 블럭을 구함
        $block = ceil($pageNum/$b_pageNum_list);
        //echo $block;

        // 현재 블럭에서 시작페이지 번호
        $b_start_page = (($block-1)*$b_pageNum_list)+1;
        //echo $b_start_page; 

        // 현재 블럭에서 마지막 페이지 번호
        $b_end_page = $b_start_page+$b_pageNum_list-1;
        //echo $b_end_page; 

        // 총 게시글의 페이지 수
        $total_page = ceil($total_rows/$list);

        // 블럭의 마지막 페이지가 총 페이지 수보다 클 때 숫자를 같게 해주는 조건
        if($b_end_page>$total_page){
            $b_end_page=$total_page;
        }

        $start_record = ($pageNum-1)*$list;

        $sql = mq($temp." order by idx desc limit {$start_record},{$list}");

        if(!$sql){ // sql문 실패 
            echo mysqli_error($db);
        }else{ //sql문 성공 ?> 

            <!-- 3*3형 -->
            <table id="challengeviewtable">
                <?php
                for($i=0; $i<3; $i++){
                    echo "<tr>";
                    for($j=0; $j<3; $j++){
                        $row = $sql->fetch_array(); // 계속 다음 정보를 가져옴

                        // 더이상 정보가 없다면 td만들지x(더 이상 가져올 정보가 x)
                        if($row==""){break;}

                        $idx = $row['idx']; //글번호
                        $thumbnail = $row['thumbnail']; //썸네일
                        $title = $row['title']; //타이틀 
                        $startday=$row['startday']; // 시작일
                        $endday=$row['endday']; // 종료일
                        $entryfee=$row['entryfee']; // 참가비
                        $frequency=$row['frequency']; 
                        $proofshotcount=$row['proofshotcount'];
                        $sort=$row['sort']; // 카테고리

                        // 참여자수
                        $joincount = "select * from challenge_join where idx='{$idx}'";
                        $sqljoincount = mq($joincount);
                        $joincount = mysqli_num_rows($sqljoincount);

                        // 기간표시(2일 or 1주...)
                        $days = datediff($startday,$endday)+1;
                        $wholeweeks = $days/7;
                        ?>
                        <td id="challengeitem" style=cursor:hand; onclick=location.href="./challengeread.php?idx=<?php echo $idx; ?>">
                        

                            <!-- 썸네일 이미지 부분 -->
                            <img src="<?php echo $thumbnail ?>">
                            <table>
                                <tr>
                                    <td id="item_sort"><?php echo $sort ?></td>
                                </tr>
                                <tr>
                                    <td id="item_title" ><?php echo $title ?></td>
                                </tr>   
                                <tr>
                                    <td>
                                        <!-- 기간 -->
                                        <span id="item_period">
                                            <?php
                                            echo $startday."~".$endday."(";

                                            if($wholeweeks<1){
                                               
                                                // 1주일 미만
                                                echo $days."일";
                                            }else{
                                                // 1주일 이상
                                                echo $wholeweeks."주";
                                            }
                                            echo ")";
                                            ?>
                                        </span>
                                    </td>                            
                                </tr>  
                                <tr>
                                    <td>
                                        <!-- 참가비용 -->
                                        <span id="item_entryfee"><?php echo $entryfee."원" ?></span>
                                    </td>
                                </tr>   
                                <!-- 인증주기  --> 
                                <!-- <tr>
                                    <td>
                                        
                                        <span id="item_frequency">
                                        <?php
                                        // if($frequency==7){
                                        //     echo "매일 인증";
                                        // }else{
                                        //     echo "1주일에 ".$frequency."번" ;
                                        // }                                    
                                        ?>
                                        </span>
                                    </td>
                                    
                                </tr>    -->
                                <tr>
                                    <td>
                                        <!-- 참가자수 -->
                                        <span id="item_joincount"><?php echo $joincount."명 참가" ?></span>
                                    </td>
                                </tr> 

                                <!-- 참여 가능한 이벤트인 경우,
                               초록색 네모-->
                               <?php
                               // 오늘날짜 <= 챌린지 시작일
                               $timenow = date("Y-m-d"); // 오늘날짜
                               $str_now = strtotime($timenow); // 오늘날짜
                               $str_target = strtotime($startday);
                               if($str_now<=$str_target){ // 날짜 비교
                                // 참여가능한 경우
                                $color = "green";
                               }else{
                                   $color = "red";
                               }
                               ?>
                                <tr>
                                    <td>
                                        <br>
                                        <span id="item_joinable" style="background-color:<?php echo $color ?>">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span>
                                    </td>
                                </tr>               
                            </table>
                        </td>
                    <?php }
                    echo "</tr>";
                }
                ?>
            </table>

        

        <!-- 
            페이지 : 만약 total_rows(총 게시물수)=0이라면 페이지 관련 텍스트가 보이지 않아야 함
         -->
        <?php
        
        if($total_rows!=0){
            if($pageNum<=1){//페이지번호가 1보다 작거나 같다면

                echo "<font size=2 color=red> [처음] </font>";
                    // 링크없이 그냥 처음이라는 문자만 출력
            }else{ // 1보다 크다면
        
                echo "<font size=2><a href='togetherchallenge.php?page=&list={$list}&challengeselect={$challengeselect}&search={$search}&joinable={$joinable}'> [처음] </a></font>";
            
            }
            
            if($block<=1){
                
                // block이 1보다 작거나 같다면 , 더 이상 거꾸로 갈 수 없으므로 아무 표시도 하지 않는다.
                echo "<font></font>";
            }else{  
                $insertpage = $b_start_page-1;
                echo "<font size=2><a href='togetherchallenge.php?page={$insertpage}&list={$list}&challengeselect={$challengeselect}&search={$search}&joinable={$joinable}'> 이전 </a></font>";
            }
            
            for($j=$b_start_page; $j<=$b_end_page; $j++){
                if($pageNum==$j){
                   
                    // pageNum=j이면, 현재 페이지이므로,링크걸지않고 그냥 현재 페이지만 출력
                    echo "<font size=2 color=red> {$j} </font>";
                }else{
                   
                    echo"<font size=2><a href='togetherchallenge.php?page={$j}&list={$list}&challengeselect={$challengeselect}&search={$search}&joinable={$joinable}'> {$j} </a></font>";
                        // 현재 페이지를 제외한 나머지 페이지 번호를 링크를 달아 출력하기
    
                        
                }
                
            }
        
            // 블럭의 총 갯수
            $total_block = ceil($total_page/$b_pageNum_list);
        
            if($block>=$total_block){
                
                // block과 총block의 갯수가 값이 같다면, 맨 마지막 블록이므로 다음 링크버튼이 필요없어 보여주지 않는다.
                echo "<font></font>";
            }else{
                // 그게아니라면, 다음 링크버튼을 걸어 보여준다
                $temp = $b_end_page+1;
                echo "<font size=2><a href='togetherchallenge.php?page={$temp}&list={$list}&challengeselect={$challengeselect}&search={$search}&joinable={$joinable}'> 다음 </a></font>";
            }
        
            // 마지막 링크 버튼
            if($pageNum>=$total_page){
                // 페이지넘버 = 총페이지
                echo "<font size=2 color=red> [마지막] </font>";
            }else{
                //그게 아니라면
                echo "<font size=2><a href='togetherchallenge.php?page={$total_page}&list={$list}&challengeselect={$challengeselect}&search={$search}&joinable={$joinable}'> [마지막] </a></font>";
            }
        }


        }
            
        ?>
    </div>


  
    


    <!-- 제이쿼리 -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>

    <!-- 자바스크립트 파일 삽입-->
    <script type="text/javascript" src="./togetherchallenge.js"></script>
</body>
</html>