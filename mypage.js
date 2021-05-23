$(document).ready(function(){

    console.log("mypage.js 시작"); 

    /*
    구분
    */
    var mypage = getParameterByName("mypage");
    if(mypage=="" || mypage=="nicknamechange"){
        openCity($("#nicknamechangelink").event,"nicknamechange");
    }else if(mypage=="pwchange"){
        openCity($("#pwchangelink").event,"pwchange");
    }else if(mypage=="shotask"){
        openCity($("#shotasklink").event,"shotask");
    }else if(mypage=="Bookmark"){
        openCity($("#Bookmarklink").event,"Bookmark");
    }else if(mypage=="heartshot"){
        openCity($("#heartshotlink").event,"heartshot");
    }else if(mypage=="refund"){
        openCity($("#heartshotlink").event,"heartshot");
    } 

    /*
    닉네임 변경
    */
    var nicknameok = false;
    var pwcheck = false;

    $("#nick_check").text("현재 사용중이신 닉네임입니다.");

    // 비밀번호 확인 - 처음에는 두 문구가 모두 보이지 않게 해야 한다.
    $("#alert-success").hide();
    $("#alert-danger").hide();

    $("#inputNickname").on("keyup",function(){
        // input의 name 속성값이 id인 요소에 입력을 감지
        
        // 공백인 경우에는 닉네임 체크 부분 문구 제거해줌
        if($(this).val()==""){$("#nick_check").text("");}
        $.nickcheck(); 
        //$.submitdiabledcheck();
    });

    $.nickcheck = function(){
        
        // 입력 된 값을 받아온다
        var user_nickname = $("#inputNickname").val();
        console.log(user_nickname);

        // 공백을 입력 할 수 없게 만든다
        $("#inputNickname").val(user_nickname.replace(/ /g,''));
        
        // 공백이 제거 된 문자열에서 특수문자를 입력 할 수 없게 만들기
        user_nickname = $("#inputNickname").val();
        user_nickname = regExp(user_nickname);
        $("#inputNickname").val(user_nickname);


        var checknickname = user_nickname;

        // 공백이 아닐 경우에만 다른 페이지로 이동 후 값을 받아온다
        if(user_nickname!=""){
            $.ajax({
                url:"nickduplicate.php",
                data : {checknickname:checknickname},
                type : "POST",
                dataType:"text",
                success:function(data){
                    $("#nick_check").text(data);

                    

                    if(data=="사용가능한 닉네임입니다."){           
                        nicknameok = true;
                    }else if(data="이미 존재하는 닉네임입니다."){

                        /*
                        현재 사용중인 닉네임이라면 => 현재 사용중인 닉네임 입니다
                        */
                       var nownickname= $("#nownickname").val();
                       console.log("현재닉네임="+nownickname);
                       if(nownickname==checknickname){
                            $("#nick_check").text("현재 사용중이신 닉네임입니다.");
                       }
                        
                        nicknameok = false;
                    }
                },
            //    error:function(request,status,error){
            //         $("#id_check").text("code="+request.status+" message="+request.responseText+" error="+error);
            //    }
            });
        }else{
            console.log("공백");
        }

    }


    // 닉네임 변경
    $("#nickupdate").click(function(){
        console.log("비밀번호 변경 버튼");

        // 사용 가능한 닉네임일 경우에만
        var nickok = $("#nick_check").text();
        console.log(nickok);
        if(nickok=="사용 가능한 닉네임입니다."){

            // 수정 된 닉네임
            var futurenickname = $("#inputNickname").val();
            console.log(futurenickname);
            $.ajax({
                url:"nickchange.php",
                data : {futurenickname:futurenickname},
                type : "POST",
                dataType : "text",
                success:function(data){
                    console.log(data);
                    alert("닉네임이 변경되었습니다!");
                    location.href="./mypage.php?mypage=nicknamechange";
                    location.reload();
                }
            });
        }else{
            alert(nickok);
        }
    });

    // 비밀번호 = 비밀번호 확인 체크
    $("#inputPasswordCheck").keyup(function(){
        var pw = $("#inputPassword").val();
        var pwcheck = $("#inputPasswordCheck").val();

        // 만약 비밀번호 체크가 공백이라면, 알림문 둘 다 숨기기
        if(pwcheck==""){
            $("#alert-success").hide();
            $("#alert-danger").hide();
            pwcheck = false;
        }

        // 둘 중 한개라도 빈칸이 아닐 때
        if (pw != "" || pwcheck != "") {

            if (pwcheck == "") {
                $("#alert-success").hide();
                $("#alert-danger").hide();
                pwcheck = false;
            } else {
                if (pw == pwcheck) { // 비밀번호=비첵이 같다면
                    $("#alert-success").show();
                    $("#alert-danger").hide();
                    $("#forcheck").attr("placeholder", "비밀번호=비책");
                    pwcheck = true;
                } else { // 다르다면
                    $("#alert-success").hide();
                    $("#alert-danger").show();
                    $("#forcheck").attr("placeholder", "비밀번호<>비책");
                    pwcheck = false;
                }
            }
        }
    });

    // 비밀번호 확인부분이 일치 한 후에, 비밀번호부분을 바꾸었다면 => 비밀번호 확인 알림문구 부분이 다시 일치하지 않는다고 떠야 한다, 그 후 다시 검사 + 비밀번호 정규식 체크
    $("#inputPassword").keyup(function(){
        var pw = $("input[name='pw']").val();
        var pwcheck = $("input[name='pwcheck']").val();

        // 비밀번호 정규식 체크
        $("#pwtext").text(pwregexp(pw));

        // 둘 중 한개라도 빈칸이 아닐 때
        if (pw != "" || pwcheck != "") {

            if (pwcheck == "") {
                $("#alert-success").hide();
                $("#alert-danger").hide();
            } else {
                if (pw == pwcheck) { // 비밀번호=비첵이 같다면
                    $("#alert-success").show();
                    $("#alert-danger").hide();
                    $("#forcheck").attr("placeholder", "비밀번호=비책");
                } else { // 다르다면
                    $("#alert-success").hide();
                    $("#alert-danger").show();
                    $("#forcheck").attr("placeholder", "비밀번호<>비책");
                }
            }
        }
    });

    // 비밀번호 변경 버튼
    $("#pwchangebtn").click(function(){
        console.log("비밀번호 변경 버튼");

        var nowPassword = $("#nowPassword").val(); 
        var pw = $("input[name='pw']").val();
        var pwcheck = $("input[name='pwcheck']").val();

        // 현재비밀번호가 공백이 아니여야 다음으로 넘어감
        if(nowPassword==undefined || nowPassword==""){
            alert("현재 비밀번호 입력칸이 공백입니다.");
            return;
        }

        // 비밀번호가 공백이 아니어야 다음으로 넘어감
        if(pw==""){
            alert("비밀번호 입력칸이 공백입니다.");
            return;
        }

        // 비밀번호가 정규식 형태여야 한다
        var pwtext = pwregexp(pw);
        console.log("pwtext="+pwtext);
        if(pwtext!="사용 가능한 비밀번호 입니다."){
            alert("비밀번호 형태를 제대로 입력해주세요");
            return;
        }

        // 비밀번호 확인이 공백이 아니어야 다음으로 넘어감
        if(pwcheck==""){
            alert("비밀번호 확인 입력칸이 공백입니다.");
            return;
        }

        // 비밀번호 = 비밀번호 확인이어야 한다.
        if(pw!=pwcheck){
            alert("비밀번호 일치여부를 확인하세요");
            return;
        }

        // 비밀번호 수정하기
        $.ajax({
            url:"pwchange_ok.php",
            data:{nowPassword:nowPassword,pw:pw},
            type:"POST",
            dataType:"text",
            success:function(data){
                console.log(data);
                alert(data);

                if(data=="비밀번호가 성공적으로 변경되었습니다"){
                    // 모든칸을 빈칸으로 만든다
                    $("#nowPassword").val("");
                    $("#inputPassword").val("");
                    $("#inputPasswordCheck").val("");

                    // 알림창 안보이기
                    $("#alert-success").hide();
                    $("#alert-danger").hide();
                    $("#pwtext").hide();

                    location.href="./mypage.php?mypage=pwchange";

                }
            }
        });
        
    });

    /*
    myshotask.php
    */

    // 답변 된 질문만 보기
    $("#askok_myask").change(function(){
        var askok;

        if($(this).is(":checked")){ // 체크된 상태라면
            console.log("답변 된 질문만 보기 체크");
            askok = "1"; // 이렇게 처리되어 있는 값만 가져오기
        }

        // 페이지 이동
        location.href = 'mypage.php?askok=' + askok+"&page="+1+'&mypage=shotask';
    });

    // 문의사항 수정하기
    $("#myshotasktable").on("click","button.myshotaskupdate",function(){
        console.log("수정하기 버튼 누름");

        // 인덱스
        var index = $("button.myshotaskupdate").index(this);
        console.log("index="+index);

        // 해당 문의사항 번호
        var idx = $(".shotaskidxinfo").eq(index).text();
        console.log("문의사항번호="+idx);

        // 문의사항 수정하기 페이지
        var url = "shotaskupdate.php?idx="+idx;
        window.open(url,"","width=600,height=600,left=600");
        
    });

    // 문의사항 삭제하기()
    $("#myshotasktable").on("click","button.myshotaskdelete",function(){
        console.log("삭제하기 버튼 누름");

        // 확인
        var result = confirm("정말로 삭제하시겠습니까?");
        if(result){ // 삭제확인
            // 인덱스
            var index = $("button.myshotaskdelete").index(this);
            console.log("index="+index);

            // 해당 문의사항 번호
            var idx = $(".shotaskidxinfo").eq(index).text();
            console.log("문의사항번호="+idx);

            // 문의사항 수정하기 페이지
            $.ajax({
                data: {idx:idx},
                type: "post",
                url: './shotaskdelete.php',
                success: function (echo) {
                    console.log(echo);
                    alert("삭제되었습니다");
                    $("#myshotasktable").html(echo);
                }
            });
        }
    });


    /*
    mybookmark.php
    */
    $("#joinable").change(function(){

        // 현재 상태
        var joinable = $(this).val();
        
        if($(this).is(":checked")){ // 체크상태
            joinable = "1";

        }

        
        // 페이지 이동
        location.href = 'mypage.php?joinable=' + joinable+'&mypage=Bookmark'+"&page="+1;
    });

    
});

// 특수문자 입력x
function regExp(string){
    // 특수문자 검증 start
    var regExp = /[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]/gi;

    if(regExp.test(string)){
        // 특수문자 제거
        var t = string.replace(regExp,"");
        return t;
    }else{
        return string;
    }
}

// 비밀번호 정규식 체크
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

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/     \+/g, " "));
}



