
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

<body id="challengemanagement"> 
<?php
include_once "db.php";
include_once "phpfunction.php";
onlyadmin();
?>


<h1>관리 페이지</h1>
<a href="togetherchallenge.php">홈으로 이동</a>
<nav class="w3-sidenav w3-light-grey w3-card-2" style="width:130px">

    <!-- 회원 -->
  <!-- <div class="w3-container">
    <h5>회원</h5>
  </div> -->
  <!-- <a href="#" class="tablink" id="Challengelink"  onclick="openCity(event, 'Challenge')">챌린지</a>		
  <a href="#" class="tablink" id="memberlink"  onclick="openCity(event, 'member')">회원</a>		 -->
  <a href="#" class="tablink" id="shotmanagementlink"  onclick="openCity(event, 'shotmanagement')">인증샷 관리</a>
  <a href="#" class="tablink" id="shotasklink"  onclick="openCity(event, 'shotask')">인증샷 문의</a>		
  <a href="#" class="tablink" id="refundlink"  onclick="openCity(event, 'refund')">환급신청</a>		
  <!-- <a href="#" class="tablink" onclick="openCity(event, 'Tokyo')">Tokyo</a> -->
</nav>


<div style="margin-left:130px">

 
  <!-- <div id="Challenge" class="w3-container city">
    <h2>챌린지</h2>
    <p>챌린지를 생성하고, 관리</p>
    <?php //include_once "challenge_management.php" ?>
  </div> -->

  
  <!-- <div id="member" class="w3-container city">
    <h2>회원관리</h2>
    <p>(임시)신고횟수...</p> 
    <?php //include_once "member_management.php" ?>
  </div> -->

  <div id="shotmanagement" class="w3-container city">
    <h2>인증샷 관리</h2>
    <p>인증샷 보기, 적합/부적합 판단</p>
    <?php include_once "shotmanagement.php" ?>
  </div>

  <div id="shotask" class="w3-container city">
    <h2>인증샷 문의 관리</h2>
    <p>인증샷 보기, 적합/부적합 판단</p>
    <?php include_once "shotaskmanagement.php" ?>
  </div>

  <div id="refund" class="w3-container city">
    <h2>환급 신청 관리</h2>
    <p>거절/수락</p>
    <?php include_once "refundmanagement.php" ?>
  </div>
</div>



<!-- 제이쿼리 -->
<script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>
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

//$("#shotmanagementlink").click();




</script>
<!-- 자바스크립트 -->
<script type="text/javascript" src="./shotmanagement.js"></script>
</body>
</html>