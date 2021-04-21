<?php
//    Include './db.php'
?>
<html>
<head>
    <!-- jquery -->
    
</head>
<body>
    이메일 인증문자
    <input type="text" id="tempforcheck">
    <input type="hidden" id="tempforcheck2">
    <button id="tempcheckbtn">확인</button>

    <!-- jQuery -->
    <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
    $(document).ready(function(){
        console.log("emailinput.php열림");

        // 부모창에서 받아온 임시문자
        var parentValue = opener.document.getElementById("tempemail").value;

        // 확인버튼
        $("#tempcheckbtn").click(function(){
            // 사용자가 입력한 임시문자
            var tempforcheck = $("#tempforcheck").val();
            
            /*
            parentValue, tempforcheck가 같지 않다면, 
            같지 않다는 알림창 
            같다면, true값을 부모창으로 보내준다.
            */
            if(parentValue!=tempforcheck){
                alert("임시문자를 다시 확인해주세요");
            }else{
                alert("이메일 인증이 되었습니다.");

                // 부모창(id=tempemail)값 변경
                $("#getfromwindow",parent.opener.document).text("이메일이 인증되었습니다");

                // 팝업창 닫기
                window.self.close();
            }
        });

    });
    </script>
</body>
</html>