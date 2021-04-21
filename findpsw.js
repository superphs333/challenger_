
$(document).ready(function () {

    console.log("findpsw.js시작");

    // 임시 비밀번호 전송 버튼
    $("#emailsend").click(function(){
        console.log("임시비밀번호 전송 버튼");

        // 입력 된 이메일
        var email = $("#inputemail").val();
        console.log("입력 된 이메일="+email);

        // 입력 된 이메일이 존재한다면, 해당 이메일로 임시 문자를 보내준다.
        $.ajax({
            type:"POST",
            url:"forpwfind.php",
            data:{email,email},
            dataType:"text",
            success:function(text){
                console.log(text);

                if(text=="해당 이메일은 존재하지 않습니다."){
                    console.log("존재x");
                    alert(text);
                }else{
                    console.log(text);
                    EmailJS(email,"Challenger","임시비밀번호입니다. : "+text);

                    // 페이지 이동
                    // var result = confirm("임시비밀번호가 전송되었습니다.로그인 페이지로 이동하시겠습니까?");
                    // if(result){
                    //     location.replace('login.php');
                    // }
                }
            }
        });
    });

});

// 이메일 보내기 함수
function EmailJS(emailforid, from_name, message_html) {
    (function () {
        var emailC = {
            give_men: emailforid,
            from_name: from_name,
            message_html: message_html
        }
        emailjs.init("user_Hxl2f3zXlrmUpqReFkyIK");
        emailjs.send("superphs1214_gmail_com", "template_woxdz8Hj", emailC)
            .then(function (response) {
                console.log("SUCCESS. status=%d, text=%s", response.status, response.text);
                alert("임시비밀번호가 전송되었습니다");
                // 로그인 페이지로 이동할까요 ?
            }, function (err) {
                console.log("FAILED. error=", err);
                alert("죄송합니다. 다시 한 번 시도해주세요.");
            });

    })();
}