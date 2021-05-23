$(document).ready(function(){
    var category = $("#category").val();
    console.log("카테고리="+category); 

    // 멤버인지 확인
    var member_chk;
    if($("#participant").val()==""){
        member_chk = false;
    }else{
        member_chk = true;
    }

    // 인증샷 idx
    var shotidx = $("#shotidx").val();
    console.log("인증샷 idx="+shotidx);

    // 하트체크버튼 => 클릭하면 이미지 바뀜
    $("#jo_heart").click(function(){
        console.log("하트 체크 버튼 누름");

        if(member_chk==false){
            alert("로그인 및 회원가입을 해야 이용가능합니다");
            return;
        }

        // sort
        var sort = "heart";

        /*
        데이터베이스 값 변화
        - 필요 => 인증샷번호, heartcheck.var
        */

       // check 상태
       var check = "";

       if($("#heartcheck").val()=="1"){ // 색있음, 체크값1
            console.log("색있음=>색없음");
            check = $("#heartcheck").val();
            console.log("check값="+$("#heartcheck").val());

            // 변화 한 후 체크값/하트상태
            $("#heartcheck").val("0");
            var test = 'https://my3my3my.tk/challenger/upload/shots/empty_heart.png';
            $(this).attr('src','https://my3my3my.tk/challenger/upload/shots/empty_heart.png');
       }else{ // 색없음, 체크값 x
            console.log("색없음=>색있음");
            check = $("#heartcheck").val();
            console.log("check값="+$("#heartcheck").val());

            // 변화 한 후 체크 값/하트상태
            $("#heartcheck").val("1");
            $(this).attr('src','https://my3my3my.tk/challenger/upload/shots/fill_heart.png');
       }

       // 데이터베이스에 반영
       $.ajax({
           url:"joinfeedoneheart.php", 
           type:"post",
           data:{shotidx:shotidx,check:check,category:category,sort:sort},
           success:function(data){
               console.log("가져온 값="+data);
                
               // 하트수 반영
               $("#shotheartcount").text(data);
           }
       });
    });

    // 사이렌체크버튼 => 클릭하면 이미지 바뀜
    $("#jo_siren").click(function(){
        console.log("사이렌 체크 버튼 누름");

        if(member_chk==false){
            alert("로그인 및 회원가입을 해야 이용가능합니다");
            return;
        }

        /*
        데이터베이스 값 변화
        - 필요 => 인증샷번호, sirencheck.var
        */
        
        // check 상태
        var check = "";

        // sort
        var sort = "siren";

        if($("#sirencheck").val()=="1"){ // 색있음, 체크값1
            console.log("색있음=>색없음");
            check = $("#sirencheck").val();
            console.log("check값="+$("#sirencheck").val());

            // 변화 한 후 체크값/사이렌상태
            $("#sirencheck").val("0");
            $(this).attr('src','https://my3my3my.tk/challenger/upload/shots/empty_siren.png');
        }else{ // 색없음, 체크값 x
            console.log("색없음=>색있음");
            check = $("#sirencheck").val();
            console.log("check값="+$("#sirencheck").val());

            // 변화 한 후 체크 값/사이렌상태
            $("#sirencheck").val("1");
            $(this).attr('src','https://my3my3my.tk/challenger/upload/shots/fill_siren.png');
        }

        // 데이터베이스에 반영
        $.ajax({
            url:"joinfeedoneheart.php", 
            type:"post",
            data:{shotidx:shotidx,check:check,category:category,sort:sort},
            success:function(data){
                console.log("가져온 값="+data);
                
                // 하트수 반영
                $("#shotsirencount").text(data);
            }
        });
    });

});

