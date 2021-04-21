
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- <title>Bootstrap 101 Template</title> -->
<!-- 합쳐지고 최소화된 최신 CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<!-- 부가적인 테마 -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
<!-- w3 css 불러오기 -->
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3.css">
 <!-- 외부 css 불러오기 -->
 <link rel="stylesheet" type="text/css" href="./whole.css" />
<style>
.city {display:none;}
</style>

</head>
<body>
<?php
    Include "top.php";
    // 현재 $_SESSION['user']에 맞는 닉네임을 가져온다
    if(!empty($_SESSION['user'])){
        $sql = mq("select * from members where email='{$_SESSION['user']}'");
        $row = $sql->fetch_array();
        $nickname = $row['nickname'];
    }
?>
<!-- <a href="logout.php">로그아웃</a>	 -->
<nav class="w3-sidenav w3-light-grey w3-card-2" style="width:130px">

    <a href="#" id="nicknamechangelink" class="tablink" onclick="openCity(event, 'nicknamechange')">닉네임 변경</a>		
    <a href="#" id="pwchangelink" class="tablink" onclick="openCity(event, 'pwchange')">비밀번호 변경</a>		


    <a href="#" id="shotasklink" class="tablink" onclick="openCity(event, 'shotask')">인증샷 문의</a>

    

    <a href="#" id="Bookmarklink" class="tablink" onclick="openCity(event, 'Bookmark')">북마크 한 챌린지</a>

    <a href="#" id="heartshotlink" class="tablink" onclick="openCity(event, 'heartshot')">좋아요 한 인증샷</a>


    <hr>
    <a href="logout.php">로그아웃</a>
</nav>

<div style="margin-left:130px;">

  <div id="nicknamechange" class="w3-container city">
    <h2>닉네임 변경</h2>
    <?php Include "nicknamechange.php" ?> 
  </div>

  <div id="pwchange" class="w3-container city">
    <h2>비밀번호 변경</h2>
    <?php Include "pwchange.php" ?> 
  </div>


  <div id="shotask" class="w3-container city">
    <h2>인증샷 문의</h2>
    <p>내가 보낸 인증샷 항의?????</p>
    <?php Include "myshotask.php" ?> 
  </div>



  <div id="Bookmark" class="w3-container city">
    <h2>북마크 한 챌린지</h2>
    <p></p>
    <?php Include "mybookmark.php" ?> 
  </div>

  <div id="heartshot" class="w3-container city">
    <h2>좋아요 한 인증샷</h2>
    <p></p>
    <?php Include "myloveshot.php" ?> 
  </div>

</div>









<!-- 제이쿼리 -->
<script  src="https://code.jquery.com/jquery-latest.min.js"></script>
<!-- 합쳐지고 최소화된 최신 자바스크립트 -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script>
function openCity(evt, cityName) {
  var i, x, tablinks;
  x = 
  document.getElementsByClassName("city");
  for (i = 0; i < x.length; i++) {
     x[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablink");
  for (i = 0; i < x.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" w3-red", ""); 
  }
  document.getElementById(cityName).style.display="block";
  //evt.currentTarget.className += " w3-red";
}
</script>
<!-- 자바스크립트 -->
<script type="text/javascript" src="./mypage.js"></script>

</body>
</body>
</html>