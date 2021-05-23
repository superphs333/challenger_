<?php

?>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bootstrap 101 Template</title>
<!-- 합쳐지고 최소화된 최신 CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<!-- 부가적인 테마 -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
<!-- 외부 css 불러오기 -->
<link rel="stylesheet" type="text/css" href="./whole.css" />

</head>
<body>
    
<?php
Include_once "db.php";
Include_once "phpfunction.php";



// get으로 받아온 챌린지 idx
$shotidx = $_GET['shotidx'];

// 위의 정보로 사진, 닉네임, 내용 받아오기
$temp = "select * from challengeshot where idx={$shotidx}";
$sql = mq($temp);
$row = $sql->fetch_array();
$challengeidx = $row['challengeidx']; // 챌린지 idx 
$email = $row['joiner'];
$challengename = mq("select * from challenge where idx='{$challengeidx}'");
$challengename = $challengename->fetch_array();
$challengename = $challengename['title']; // 챌린지명
$nickname = $_GET['nickname'];
$date = $row['date'];
$imgorvideo = $row['video']; //사진or비디오
$src = $row['shot'];
?>
<input type="hidden" id="participant" value="<?php echo $_SESSION['user'] ?>">
<!-- 인증샷 번호 -->
<input type="hidden" value="<?php echo $shotidx ?>" id="shotidx">
<!-- 카테고리-->
<input type="hidden" value="challengeshot" id="category">

<div id="joinfeedone">
    <div id="nickanddate">
        <!-- 닉네임 -->
        <span class="left"><?php echo $nickname."님"; ?></span>
    
        <!-- 날짜 -->
        <span class="right"><?php echo $date ?></span>
    </div>
    <br>

    <!-- 챌린지명 -->
    <div id="challengetitle"> 
        챌린지 : <span><?php echo $challengename ?></span>
    </div>

    <hr>

    <!-- 사진 or 비디오 -->
    <?php
    if($imgorvideo==0){  ?>
    <img src="<?php echo $src ?>">
    <?php }else{//비디오 인증인 경우 ?>
    <video src="<?php echo $src ?>" controls>해당 브라우저는 video 태그를 지원하지 않습니다</video>
    <?php }  ?>
    <br><br>

    <!-- 하트 or 신고 -->
    <div id="check">
        <!-- 하트부분 -->
        <span>        
        <?php
        /*
        하트 체크 여부
        */
        $heartcheck = mq("select * from challengeshot_heart where idx={$shotidx} and user='{$_SESSION['user']}'");
        // 값이 1 이상이라면 => 이미 하트를 누른 사용자
        if(mysqli_num_rows($heartcheck)>=1){// 하트 체크 ok
            $heartsrc = "./upload/shots/fill_heart.png";
            $heartcheck = "1";
            // 하트체크 input#heartcheck 값 =1  
            echo"<script>
                $('#heartcheck').val('1');
            </script>";
        }else{ // 하트 체크 no
            $heartsrc = "./upload/shots/empty_heart.png";
            $heartcheck = "0";
            // 하트체크 input#heartcheck 값 = 0
            echo"<script>
            $('#heartcheck').val('0');
            </script>";
        }
        ?>
        <input type="hidden" id="heartcheck" value="<?php echo $heartcheck ?>">
        <img id="jo_heart" src="<?php echo $heartsrc ?>">
        

        <!-- 하트수 -->
        <?php
        $hearttemp = "select * from challengeshot_heart where idx={$shotidx}";
        $heartcount = mq($hearttemp);
        $heartcount = mysqli_num_rows($heartcount);
        ?>
            <b id="shotheartcount"><?php echo $heartcount ?></b>
        </span>

        <!-- 신고부분 -->
        <span>        
        <?php
        /*
        신고 체크 여부
        */
        $sirencheck = mq("select * from challengeshot_siren where idx={$shotidx} and user='{$_SESSION['user']}'");
        // 값이 1 이상이라면 => 이미 하트를 누른 사용자
        if(mysqli_num_rows($sirencheck)>=1){// 사이렌 체크 ok
            $sirensrc = "https://my3my3my.tk/challenger/upload/shots/fill_siren.png";
            $sirencheck = "1";
            // 사이렌체크 input#heartcheck 값 =1  
        }else{ // 사이렌 체크 no
            $sirensrc = "https://my3my3my.tk/challenger/upload/shots/empty_siren.png
            ";
            $sirencheck = "0";
            // 하트체크 input#heartcheck 값 = 0
        }
        ?>
        <input type="hidden" id="sirencheck" value="<?php echo $sirencheck ?>">
        <img id="jo_siren" src="<?php echo $sirensrc ?>">
        

        <!-- 신고수 -->
        <?php
        $sirentemp = "select * from challengeshot_siren where idx={$shotidx}";
        $sirencount = mq($sirentemp);
        $sirencount = mysqli_num_rows($sirencount);
        ?>
            <b id="shotsirencount"><?php echo $sirencount ?></b>
        </span>
    </div>

    

    <!-- 댓글 -->
    <div class="joinfeedonereply" id="reply">

        <!-- 댓글 -->
        <div id="replylist">
            <?php
            // 전체 행 가져오기
            $sqlreply = mq("select * from challengeshot_reply where con_num={$shotidx} order by idx asc");

            if($sqlreply){ // 쿼리문 성공
               while($reply=$sqlreply->fetch_array()){ ?>

                <!-- 댓글 한 개 -->
                <table class="replylist_tb">
                    <tr class="replylist_tr">
                        <!-- 닉네임, 댓글 -->
                        <td class="replylist_td_left">
                            <?php
                            // 데이터베이스에 저장되어 있는 email을 기반으로 닉네임을 알아낸다                            
                            $fornick = mq("select * from members where email='{$reply['email']}'");
                            $nick = $fornick->fetch_array();
                            $nick = $nick['nickname'];
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
            }else{
                // 쿼리문 실패 -> 경고문 + 뒤로가기
            }
            ?>
        </div>

        <!-- 댓글입력부분 -->
        <table id="replywrite">
            <tr>
                <td id="rp_write">
                    <!-- 글번호 -->
                    <input type="hidden" id="idx" value="<?php echo $shotidx ?>">
                    <!-- 카테고리 -->
                    <input type="hidden" id="category" value="challengeshot">
                    <!-- 댓글입력 -->
                    <textarea id="replyinput" placeholder="댓글을 입력해주세요"></textarea>
                </td>
                <td id="rp_button">
                    <button id="replybutton">입력</button>
                </td>
            </tr>
        </table>
    </div>


</div>





<!-- 제이쿼리 -->
<script  src="https://code.jquery.com/jquery-latest.min.js"></script>
<script src="joinfeedone.js"></script>
<script type="text/javascript" src="./comment.js"></script>
<script>


</script>
</body>
</html>