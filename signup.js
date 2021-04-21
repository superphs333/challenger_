$(document).ready(function(){

    console.log("signup.js 시작");

    // 처음에는 문구가 보이지 않게 해야 한다.
    $("#alert-success").hide();
    $("#alert-danger").hide();
    $("#forcheck").attr("placeholder", "아무것도입력안함");

    // 이메일, 닉네임, 비밀번호 체크가 모두 true여야 회원가입 가능
    var nickok = false;
    var emailok = false;
    var pwok = false;

    // 이메일인증 버튼
    $("#signup_chkbtn").click(function(){
        // 이메일 입력칸의 값
        var signup_InputEmail = $("#signup_InputEmail").val();
        console.log("이메일 입력 값=" + signup_InputEmail); 

        // 만약, 입력값이 이메일 형식이 아니라면 클릭이벤트 중지
        console.log("이메일 정규식 결과="+verifyEmail());
        var emailre = verifyEmail(); // 이메일 정규식 결과
        if(emailre==true){ // 이메일 정규식 일 때
 
            /* 
            인증문자를 생성 -> emailchk.php이동 -> 등록 된 이메일인지 확인
            등록 된 이메일 x => 임시문자를 받아 이메일로 전송
            */
            $.ajax({
                type : "POST",
                url : "emailchk.php",
                data:{signup_InputEmail:signup_InputEmail},
                dataType:"text",
                success : function(text){
                    console.log("in signup->"+text);

                    /*
                    이미 존재하는 이메일이 있을 때 => 알림창
                    존재하는 이메일이 없을 때 => 메일로 임시문자 보내기
                    */
                   if(text=="이미 존재하는 이메일입니다."){
                        //alert(text);
                        console.log("이미 존재하는 이메일");
                        emailok = false;
                        alert(text);
                   }else{
                        // 이메일 보내기
                        EmailJS(signup_InputEmail,"Challenger","Challenger에서 보낸 이메일 인증문자입니다 :"+text);

                        // 인증문자를 tempemail에 입력해주어서, 새창에서 값을 가져 갈 수 있도록 한다
                        $("#tempemail").val(text);

                        // 새창을 띄어서 인증번호를 입력하도록 한다.
                        window.open("./emailtempinput.php","child","width=500,height=300");

                   }

                }
            });

        }else{//이메일 정규식이 아닐 때
            alert("올바른 이메일 형식을 입력하세요.");
        }
    });

    // 이메일이 바뀐다면, 다시 검사해야 한다
    $("#signup_InputEmail").on("keyup",function(){

        
        
        // 
        var getfromwindow = $("#getfromwindow").text();
        console.log("getfromwindow = "+getfromwindow);
        $("#signup_InputEmail").val();


        if(getfromwindow=="이메일이 인증되었습니다"){
            console.log("email 다시 검사");
            emailok = false;
            // email 확인값을 지운다
            $("#getfromwindow").text("");
        }else{
            console.log("다시 검사할 필요x");
        }
        
    });

    // 닉네임에 공백, 특수문자 입력 못하도록 하기
    // + 닉네임 중복체크 한 후에, 다시 닉네임을 입력했다면, 중복결과 체크를 없애주고 다시 검사하도록 해야 함
    $("#inputName").on("keyup",function(){
        console.log("닉네임 입력칸 타자..");

        // 입력 된 값을 받아오기
        var user_id = $(this).val();

        // 공백을 입력 할 수 없게 만든다.
        $(this).val(user_id.replace(/ /g, ''));
        
        // 특문자를 입력 할 수 없게 만들기
        user_id = $(this).val();
        user_id = regExp(user_id);
        $(this).val(user_id);


        /*
        닉네임 다시 입력시 중복체크
        */
        var nickduplicatechkwords = $("#nickduplicatechkwords").text();
        console.log("닉네임 중복체크 확인문구="+nickduplicatechkwords);
        
        // = 사용 가능한 닉네임입니다.의 경우 문구를 지워줘야 함
        $("#nickduplicatechkwords").text("");
            nickok=false;
        
    });

    /*
    닉네임 중복체크 버튼
    => nickduplicate.php로 이동해서, 닉네임 중복검사하기
    */
   $("#signup_ckbtn").click(function(){
       // 닉네임 입력칸에서 받아온 값
       var checknickname = $("#inputName").val();
       console.log("닉네임="+checknickname);

       // nickname이 중복인지 확인하기 위해 ajax를 사용해서 확인
       $.ajax({
           url : "nickduplicate.php",
           data : {checknickname:checknickname},
           type: "POST",
           dataType : "text",
           success: function(data){
            console.log(data);
               
            // 받아온 데이터를 idduplicatechkwords에 표시해준다
            // 공백인 경우 제외
            if(data=="닉네임을 입력해주세요"){
                alert(data);
            }else if(data=="이미 존재하는 닉네임입니다."){
                $("#nickduplicatechkwords").text(data);
            }else if(data=="사용 가능한 닉네임입니다."){
                $("#nickduplicatechkwords").text(data);
                nickok=true;
            }
           }
       });
   });

   


   /*
   비밀번호 체크(비밀번호=비밀번호 확인)
   */
   $("#inputPasswordCheck").keyup(function(){

    // 비밀번호, 비밀번호체크 입력값 가져오기
    var pw = $("#inputPassword").val();
    var pwcheck = $("#inputPasswordCheck").val();

    // 공백이라면, 아직 알려주는 문구를 보여주지 않는다.
    if(pwcheck==""){
        $("#alert-success").hide();
        $("#alert-danger").hide();
    }

    // 둘 중 한개라도 빈칸이 아니라면,
    if(pw != "" || pwcheck != ""){
        if(pw==pwcheck){ // 비밀번호 = 비첵
            $("#alert-success").show();
            $("#alert-danger").hide();
            $("#forcheck").attr("placeholder", "비밀번호=비책");
            pwok = true;
        }else{
            $("#alert-success").hide();
            $("#alert-danger").show();
            $("#forcheck").attr("placeholder", "비밀번호<>비책");
            pwok = false;
        }
    }
   });

   /*
   비밀번호 확인부분이 일치 한 후에, 비밀번호부분을 바꾸었다면 
   => 비밀번호확인 아래부분이 다시 일치하지 않다고 떠야함 + 다시 검사
   */
   $("#inputPassword").keyup(function(){

        

        // 비밀번호, 비밀번호체크 입력값 가져오기
        var pw = $("#inputPassword").val();
        var pwcheck = $("#inputPasswordCheck").val(); 

        // 비밀번호 정규식 체크
        $("#pwtext").text(pwregexp(pw));

        if(pw != "" || pwcheck != ""){
            $("#alert-success").hide();
            $("#alert-danger").hide();

            if(pwcheck ==""){
                $("#alert-success").hide();
                $("#alert-danger").hide();
            }else{
                if (pw == pwcheck) { // 비밀번호=비첵이 같다면
                    $("#alert-success").show();
                    $("#alert-danger").hide();
                    $("#forcheck").attr("placeholder", "비밀번호=비책");
                    pwok = true;
                } else { // 다르다면
                    $("#alert-success").hide();
                    $("#alert-danger").show();
                    $("#forcheck").attr("placeholder", "비밀번호<>비책");
                    pwok = false;
                }
            }
        }
   });


   /*
   최종 제출 버튼 :닉네임, 이메일, 비밀번호가 모두 true여야 한다.
   */
   $("#join-submit").click(function(){
       console.log("제출버튼 누름");
      
       // emailok이인지 값을 받아오기(string형)
       var emailoktext = $("#getfromwindow").text();
       console.log("emailoktext="+emailoktext);
       // 위의 값은 string이기 때문에 boolean형식으로 바꾸어 주기
       if(emailoktext=="이메일이 인증되었습니다"){
           emailok=true;
       }else{
           emailok = false;
           alert("이메일 인증을 확인해주세요");
           return false;
       }

       // 닉네임 확인
       if(nickok==true){ // 닉네임 확인 ok
            console.log("닉네임 ok");
       }else{ // 닉네임 확인 no
            console.log("닉네임 no");
            alert("닉네임 중복체크를 확인해주세요");
            return false;
       }

       

       // 비밀번호 확인
       var pw = $("#inputPassword").val();
       // pwregexp(pw)결과값
       var pwtext = $("#pwtext").text();
       console.log("pwtext="+pwtext);

       if(pwtext=="사용 가능한 비밀번호 입니다."){
            console.log("사용가능한 비밀번호");

            // 비밀번호=비밀번호 확인이 일치해야 한다.
            var pw = $("#inputPassword").val();
            var pwcheck = $("#inputPasswordCheck").val();

            if(pw!=pwcheck){// 일치하지 않는 경우
                alert("비밀번호와 비밀번호 확인은 일치해야 합니다.");
            }else{// 일치하는 경우
                console.log("모두통과");

                // 이메일, 닉네임, 비밀번호 받아오기
                var email = $("#signup_InputEmail").val();
                var nickname = $("#inputName").val();
                var pw = $("#inputPassword").val();

                // 입력받은 값을 데이터베이스로 보낸다.
                $.ajax({
                    url:"member_ok.php",
                    data:{email:email,nickname:nickname,pw:pw},
                    type:"POST",
                    dataType:"text",
                    success:function(text){
                        console.log(text);
                        
                        if(text=="s"){
                            console.log("데이터 저장 성공");
                            alert("회원가입이 완료되었습니다");              
                            
                            // login.php 페이지로 이동
                            location.href='login.php';
                        }else{
                            console.log("죄송합니다. 다시 시도해주세요");
                        }
                        
                        
                    }
                });
            }

       }else{
            console.log("사용 불가 비밀번호");
            alert("사용 불가능한 비밀번호입니다.");
            return false;
       }

       

       

       

       
   });

    





});



// 이메일 보내기 함수
function EmailJS(emailforid, from_name, message_html) {
    (function () {
        console.log("이메일 보내기 함수(EmailJS)");
        var emailC = {
            give_men: emailforid,
            from_name: from_name,
            message_html: message_html
        }
        emailjs.init("user_Hxl2f3zXlrmUpqReFkyIK");
        emailjs.send("superphs1214_gmail_com", "template_woxdz8Hj", emailC)
            .then(function (response) {
                console.log("SUCCESS. status=%d, text=%s", response.status, response.text);
                //alert("이메일이 전송 되었습니다.");
            }), function (err) {
                console.log("FAILED. error=", err);
            }
    })();
}

// 이메일 체크 함수
function verifyEmail(){
    console.log("이메일 정규식 체크 함수");
    var signup_InputEmail = $("#signup_InputEmail").val();

    // 검증에 사용 할 정규식 변수 regExp에 저장
    var regExp = /^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*.[a-zA-Z]{2,3}$/i;

    
    // 체크하기
    if (signup_InputEmail.match(regExp) != null) {
        console.log("이메일 정규식 통과");
        return true;
    } else {
        console.log("이메일 정규식 X");
        return false;
    }
}


// 이메일 인증 함수
function emailcheck(){
    // 이메일 입력칸의 값
    var signup_InputEmail = $("#signup_InputEmail").val();
    console.log("이메일 입력 값=" + signup_InputEmail); 

    // 만약, 입력값이 이메일 형식이 아니라면 클릭이벤트 중지
    console.log("이메일 정규식 결과="+verifyEmail());
    var emailre = verifyEmail(); // 이메일 정규식 결과
    if(emailre==true){ // 이메일 정규식 일 때

        /* 
        인증문자를 생성 -> emailchk.php이동 -> 등록 된 이메일인지 확인
        등록 된 이메일 x => 임시문자를 받아 이메일로 전송
        */
        $.ajax({
            type : "POST",
            url : "emailchk.php",
            data:{signup_InputEmail:signup_InputEmail},
            dataType:"text",
            success : function(text){
                console.log("in signup->"+text);

                /*
                이미 존재하는 이메일이 있을 때 => 알림창
                존재하는 이메일이 없을 때 => 메일로 임시문자 보내기
                */
               if(text=="이미 존재하는 이메일입니다."){
                    //alert(text);
                    console.log("이미 존재하는 이메일");
                    emailok = false;
               }else{
                    // 이메일 보내기
                    EmailJS(signup_InputEmail,"Challenger","Challenger에서 보낸 이메일 인증문자입니다 :"+text);

                    // 인증문자를 tempemail에 입력해주어서, 새창에서 값을 가져 갈 수 있도록 한다
                    $("#tempemail").val(text);

                    // 새창을 띄어서 인증번호를 입력하도록 한다.
                    window.open("./emailtempinput.php","child","width=500,height=300");

               }

            }
        });

    }else{//이메일 정규식이 아닐 때
        alert("올바른 이메일 형식을 입력하세요.");
    }
}

// 특수문자 입력 x
function regExp(string){
    // 특수문자 검증 start
    var regExp = /[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]/gi

    if(regExp.test(string)){
        // 특수문자 제거
        var t = string.replace(regExp,"");
        return t;
    }else{
        return string;
    }
}

// 비밀번호 정규식 확인 함수
function pwregexp(pw){
    // 정규식용 변수
    var num = pw.search(/[0-9]/g);
    var eng = pw.search(/[a-z]/ig);
    var spe = pw.search(/[`~!@@#$%^&*|₩₩₩'₩";:₩/?]/gi);

    // 결과 변수
    var text = "";

    // 위의 변수를 토대로 검사하기
    if (pw.length < 8 || pw.length > 20) {
        text= "8자리 ~ 20자리 이내로 입력해주세요.";
        pwok=false;
    } else if (pw.search(/\s/) != -1) {
        text= "공백 없이 입력해주세요.";
        pwok=false;
    } else if (num < 0 || eng < 0 || spe < 0) {
        text= "영문,숫자, 특수문자를 혼합하여 입력해주세요.";
        pwok=false;
    }else{
        text= "사용 가능한 비밀번호 입니다.";
        
    }

    return text;
}