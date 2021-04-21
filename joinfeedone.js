$(document).ready(function(){
    var category = $("#category").val();
    console.log("카테고리="+category);

    // 인증샷 idx
    var shotidx = $("#shotidx").val();
    console.log("인증샷 idx="+shotidx);

    // 하트체크버튼 => 클릭하면 이미지 바뀜
    $("#jo_heart").click(function(){
        console.log("하트 체크 버튼 누름");

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
            $(this).attr('src','https://challengersactivity.tk/challenger/upload/shots/2020071208534242a0e188f5033bc65bf8d78622277c4e.png');
       }else{ // 색없음, 체크값 x
            console.log("색없음=>색있음");
            check = $("#heartcheck").val();
            console.log("check값="+$("#heartcheck").val());

            // 변화 한 후 체크 값/하트상태
            $("#heartcheck").val("1");
            $(this).attr('src','https://challengersactivity.tk/challenger/upload/shots/20200712085440698d51a19d8a121ce581499d7b701668.png');
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
            $(this).attr('src','https://challengersactivity.tk/challenger/upload/shots/20200712112039fa7cdfad1a5aaf8370ebeda47a1ff1c3.png');
        }else{ // 색없음, 체크값 x
            console.log("색없음=>색있음");
            check = $("#sirencheck").val();
            console.log("check값="+$("#sirencheck").val());

            // 변화 한 후 체크 값/사이렌상태
            $("#sirencheck").val("1");
            $(this).attr('src','https://challengersactivity.tk/challenger/upload/shots/20200712114339d1f491a404d6854880943e5c3cd9ca25.png');
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

