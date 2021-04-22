
<html>
<head>
    <!-- 외부 css 불러오기 -->
    <link rel="stylesheet" type="text/css" href="./whole.css" />
</head>
<body>
    <?php Include "./top.php"; ?>
<?php
    // 해당 파일 불러오기
    //$idx = $_GET['post']; 
    $idx=$_GET['idx'];

    // 데이터베이스 불러오기.
    $sql = mq("select * from challenge where idx={$idx}");
    // 만약, 데이터베이스에 존재하지 않는 idx라면 알림창/뒤로가기
    if($sql->num_rows==0){
        echo"<script>
        alert('삭제되었거나 없는 페이지입니다');
        history.back();
        </script>";
    }


    $sql = $sql->fetch_array();
    $title = $sql['title'];
    $startday = $sql['startday'];
    $endday = $sql['endday'];
    $entryfee = $sql['entryfee'];
    $additionaldescription = $sql['additionaldescription'];
    $thumbnail = $sql['thumbnail'];
    $frequency = $sql['frequency'];
    $writer = $sql['writer']; 
    $sort = $sql['sort'];
    $proofshotcount = $sql['proofshotcount'];
    $video = $sql['video']; // 비디오 인증 여부
    // 비디오 인증인지, 사진인증인지 구분
    if($video==0){ // 사진인증인 경우
        $videocheck= "사진인증";
    }else{//비디오 인증인 경우
        $videocheck= "비디오인증";
    }
    $starttime = $sql['starttime']; // 인증 시작 시간
    $endtime = $sql['endtime']; // 인증 끝 시간
?>
    <!-- 
    수정하기, 삭제하기 버튼(작성자에게만 보이도록)
    -->
    <?php
    if($_SESSION['user']==$writer){ ?>
    <div id="cr_updateordelete">
        <a id="chupdate">수정하기</a> |
        <a id="chdelete">삭제하기</a>
    </div>        
    <?php } ?>
    <hr>

    <input type="hidden" id="participant" value="<?php echo $_SESSION['user'] ?>">
    <input type="hidden" id="idx2" value="<?php echo $idx ?>">

    <!-- 내용 -->
    <div id="cr_content">
        <img src="<?php echo $thumbnail ?>">
        <table>
            <tr>
                <td id="challengereadtitle" colspan="2">
                    <?php echo $title ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php echo "분류 - ".$sort ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $startday."~".$endday ?>
                </td>                
            </tr>
            <tr>
                <td>참가비용 : </td>
                <td><?php echo $entryfee."원" ?></td>
                <input id="ef" type="hidden" value="<?php echo  $entryfee?>">
            </tr>
            <!-- <tr>
                <td>인증주기 : </td>
                <td>
                    <?php
                    if($frequency==7){
                        echo "매일 인증";
                    }else{
                        echo "1주일에 ".$frequency."번" ;
                    }
                     
                    ?>
                </td>
            </tr> -->
            <tr>
                <td>인증 가능 시간 : </td>
                <td>
                <?php
                echo $starttime."~".$endtime;
                ?>
                </td>
            </tr>
            <tr>
                <td>하루에 필요한 인증샷 갯수 : </td>
                <td>
                <?php echo $proofshotcount."개 (".$videocheck.")" ?>
                </td>
            </tr>
        </table>
        <br><br><br><br><br><br><br><br><br><br>
        <br><br> 
    </div>
    
    <!-- 상세설명 -->
    <div id="cr_detail"><?php echo $additionaldescription ?></div>
    
    <br>
    <div id="check">
        <!-- 북마크 부분 -->
        <span class="checkspan" id="cr_bookmark">
            북마크
            <!-- 이 게시글을 북마크 한 사용자 수 -->
            <?php 
            $temp = "select * from challenge_bookmark where idx='{$idx}'";
            $sql = mq($temp);
            $bookmarkcount= mysqli_num_rows($sql);
            ?>
            <b id="cr_bookmark_count"><?php echo $bookmarkcount; ?></b>
        </span>
        <input type="hidden" type="text" id="bookmarkcheck">

        <?php
        // 만약, 참여 불가능하다면(기간이 지나서) 참여하기 비활성화
        $today = date("Y-m-d");
        $fromday = date($startday);
        $to_day = date($endday);
        $today = strtotime($today);
        $fromday = strtotime($fromday);
        $to_day = strtotime($to_day);
        if($today>$fromday){  // 참여불가
            $joinable = 0;
        }else{ // 참여가능
            $joinable = 1;
        }
        //echo $joinable;
        ?>
        <!-- 참여 부분 -->
        <span class="checkspan" id="cr_join" disalbed>
            참여하기
            <!-- 이 챌린지에 참여한 사용자 수 -->
            <?php 
                $temp = "select * from challenge_join where idx='{$idx}'";
                $sql = mq($temp);
                $joincount= mysqli_num_rows($sql);
                ?>
            <b id="cr_join_count"><?php echo $joincount; ?></b>            
        </span>
        <input type="hidden" id="joinable" value="<?php echo $joinable ?>"></input>
        <input type="hidden" id="joincheck"></input>
    </div>
    
    <!-- 
    표시 : 현재 로그인 상태인 사용자가 체크했는지 / 이 게시물에 체크 한 사용자수
    - 체크 한 게시물이라면 => 하늘색
    -->
    <?php
    // 로그인 한 유저
    $loginuser = $_SESSION['user'];
    
    // 북마크 체크 여부
    $bookmarkcheck = mq("select * from challenge_bookmark where idx='{$idx}' and user='{$loginuser}'");
    // 참가 체크 여부
    $joincheck = mq("select * from challenge_join where idx='{$idx}' and user='{$loginuser}'");

    // 값이 1 이상이라면 => 이미 북마크를 누른 사용자
    if(mysqli_num_rows($bookmarkcheck)>=1){ //북마크 체크ok
        echo "<script>
        $('#cr_bookmark').css('background-color','skyblue');
        $('#bookmarkcheck').val('1');
        </script>";
    }else{
        echo "<script>        
        $('#bookmarkcheck').val('0');
        </script>";
    }

    // 값이 1이상이라면 => 이미 참여하기를 누른 사용자
    if(mysqli_num_rows($joincheck)>=1){ // 참여중
        echo "<script>
        $('#cr_join').css('background-color','skyblue');
        $('#joincheck').val('1');
        </script>";
    }else{ // 참여하고 있지 않음
        echo "<script>        
        $('#joincheck').val('0');
        </script>";
    }
    ?>
    <br>

    <!-- 
        여지까지 모인금액, 달성률을 시각화해서 보여주기 
    -->
    <div id="visual">
        <!-- <span>모인금액 : 0</span>
        <span>환급 성공률 : 0</span> <br> -->
        <button id="otherchallenger" onclick="window.open('otherchallenger.php?idx=<?php echo $idx; ?>','참가자 목록','width=430,height=500,location=no,status=no,scrollbars=yes');">다른 참가자 확인</button>
    </div>


 
    
    <!-- 
    다른 사람의 인증샷 보기
    -->
    <div id="joinershots">
        <!-- 
        5장 정도를 보여주고
        - 클릭하면 => 새창
        - 아래에 => 인증샷 모두 보기
         -->
        <h3>인증샷 모음</h3>
        <?php
        // 인증샷 갯수
        $joinershots = mq("select * from challengeshot left join members ON challengeshot.joiner=members.email where challengeidx={$idx} order by idx desc limit 5");
        $joinerount = mysqli_num_rows($joinershots);
        //echo "joinercount=".$joinerount;

        // 필요정보
        // $joinershots = mq("select * from challengeshot where challengeidx={$idx} order by idx desc limit 5");
        // $joinercount = $joinershots->fetch_array();
        
        if($joinercount=0){ // 인증샷이 없는 경우
           echo "<div>아직 인증샷이 없습니다</div>";
        }else{ // 인증샷 있는 경우?>
            
        <table id="read_shots">
            <tr>
            <?php

            while($row=$joinershots->fetch_array()){

                // 가져올 정보 : idx, shot, date,fit, joiner
                $challengeidx=$row['idx']; // challengeidx
                $shot=$row['shot']; // 이미지 주소
                $joiner=$row['joiner']; // 작성자(샷작성자)
                $nickname=$row['nickname']; // 작성자 닉네임
                $date=$row['date']; // 찍은 날짜
                $fit=$row['fit']; // 적절여부
                ?>   
                
                <!-- td클릭하면 해당-->
                <td>
                    <!-- shot -->
                    <?php
                    if($video==0){  // 사진인증인 경우 ?>
                    <img src="<?php echo $shot ?>">
                    <?php }else{//비디오 인증인 경우 ?>
                    <video src="<?php echo $shot ?>" controls>해당 브라우저는 video 태그를 지원하지 않습니다</video>
                    <?php }  ?> <br>

                    <!-- 닉네임 -->
                    <!-- 닉네임 클릭하면, 개인 페이지로 이동 -->
                    <?php 
                    echo $nickname;
                    ?>
                    
                    <!-- 적합성
                    fit = 1이면 파란색
                    fit = 0이면 빨간색
                    -->
                    <?php 
                    if($fit=1){// 달성시
                        $color = "skyblue";
                        $icon = "https://twemoji.maxcdn.com/2/72x72/1f60a.png";
                    }else{ // 미달성시
                        $color = "#FF0040";
                        $icon="https://twemoji.maxcdn.com/2/72x72/1f97a.png";
                    }
                    if($idx==""){
                        $icon="https://twemoji.maxcdn.com/2/72x72/1f64f.png";
                    }
                    ?>
                    <div class="fitcolor" style="background-color:<?php echo $color ?>; min-width:100%;"><img style="width:20px; height:20px;" src="<?php echo $icon; ?>"></div>

                    <!-- 날짜 -->
                    <div><?php echo $date ?></div>      
                </td>
                
            <?php } ?>
            </tr>
        </table>
        <!-- <button>더 많은 인증샷 보기</button> -->
        <!-- 클릭하면, 해당 인증샷을 모두 보여주는 페이지로 이동한다. -->
        <?php } ?>
        

    </div>
    

    <!-- 댓글 -->
    <div id="reply">

        <!-- 댓글입력부분 -->
        <table id="replywrite">
            <tr>
                <td id="rp_write">
                    <!-- 글번호 -->
                    <input type="hidden" id="idx" value="<?php echo $idx ?>">
                    <!-- 카테고리 -->
                    <input type="hidden" id="category" value="challenge">
                    <!-- 댓글입력 -->
                    <textarea id="replyinput" placeholder="댓글을 입력해주세요"></textarea>
                </td>
                <td id="rp_button">
                    <button id="replybutton">입력</button>
                </td>
            </tr>
        </table>
       

        <!-- 댓글 -->
        <div id="replylist">
            <?php
            // 전체 행 가져오기
            $temp  = "select idx, con_num, challenge_reply.email, content, date, nickname from challenge_reply left join members on challenge_reply.email=members.email where con_num={$idx} order by idx asc";

            $sqlreply = mq($temp);
            // "select * from challenge_reply where con_num={$idx} order by idx asc";
            if($sqlreply){ // 쿼리문 성공
               while($reply=$sqlreply->fetch_array()){ ?>

                <!-- 댓글 한 개 -->
                <table class="replylist_tb">
                    <tr class="replylist_tr">
                        <!-- 닉네임, 댓글 -->
                        <td class="replylist_td_left">
                            <?php
                            $nick = $reply['nickname'];
                            echo $nick."님";
                            ?>&nbsp;&nbsp;&nbsp;
                            <?php
                            // 날짜
                            echo $reply['date'];
                            ?>
                        </td>
                        <!-- 댓글 수정, 삭제 -->
                        <td class="replylist_td_right">
                            <!-- 댓글번호정보 -->
                            <input type="hidden" class="reply_num" value="<?php echo $reply['idx'] ?>">
                            <!-- 댓글 수정, 삭제
                            : 로그인 사용자의 id인 경우에만, 수정/삭제 버튼이 보이도록 함 -->
                            <?php 
                            if($_SESSION['user']==$reply['email']){
                                
                                echo "
                                <a class='update'>수정</a> 
                                <a class='delete'>삭제</a>
                                ";
                            }else{
                                
                                echo "
                                <input type='hidden' class='update'></input> 
                                <input type='hidden' class='delete'></input>
                                ";
                            }                            
                            ?>
                            <!-- 기타 정보 -->
                            <!-- 댓글 작성자(email) -->
                            <input type="hidden" class="reply_email" value="<?php echo $reply['email']; ?>">
                            <!-- 댓글 내용 -->
                            <input type="hidden" class="reply_email" value="<?php echo $reply['content']; ?>">
                        </td>
                    </tr>
                    <!-- 댓글 내용 -->
                    <tr class="replylist_tr">
                        <td class="replylist_contenttd" colspan=2><?php echo $reply['content']; ?></td>
                    </tr>
                </table>
            <?php  }
            }
            ?>
        </div>
    </div>
    

    <!-- 제이쿼리 -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>
  


    <!-- 자바스크립트 파일 삽입-->
    <script type="text/javascript" src="./comment.js"></script>
    <script type="text/javascript" src="./challengeread.js"></script>

    <!-- 결제 -->
    <script type="text/javascript" src="https://cdn.iamport.kr/js/iamport.payment-1.1.5.js"></script>
    <script
    src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script><!-- jQuery CDN --->
</body>
</html>