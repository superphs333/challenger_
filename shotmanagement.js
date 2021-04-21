$(document).ready(function(){


    // 구분
    var management = getParameterByName("management");
    if(management=="" || management=="shotmanagement"){
        openCity($("#shotmanagementlink").event,"shotmanagement");
    }else if(management=="shotask"){
        openCity($("#shotasklink").event,"shotask");
    }else if(management=="refund"){
        openCity($("#refund").event,"refund");
    }
    // 페이지 링크
    // $(document).on("click",".pagelink",function(){
    //     console.log("페이지 버튼");
    //     console.log($(this).text());
    // });

    /*
    값 고정시키기 -> get값에 따라서
    */
    // 순서
    var shotmanagementorderby = getParameterByName("shotmanagementorderby");
    console.log("shotmanagementorderby="+shotmanagementorderby);
    $('#'+shotmanagementorderby).attr('selected','selected');

    // 미완료 처리
    var handleok2 = getParameterByName("handleok2");
    if(handleok2=="0"){
        $("#handle").prop("checked",true);        
    }else{
        $("#handle").prop("checked",false);
    }
    // 신고 인증샷
    var sirenok = getParameterByName("sirenok");
    console.log(sirenok);
    if(sirenok=="1"){ // 널아닌경우
        console.log("널아님");
        $("#siren").prop("checked",true);
    }else{ // 널인경우
        console.log("널");
        $("#siren").prop("checked",false);
    }



    /*
    shotmanagement.php
    */
    // 순서
    $("#shotmanagementorderby").change(function(){
        
        // 순서값 받아오기
        var shotmanagementorderby= $("#shotmanagementorderby").val();
        console.log("#shotmanagementorderby="+shotmanagementorderby);

        // 미완료/완료 체크여부
        var handleok;
        if($("#handle").is(":checked")){
            console.log("미완료 처리만 체크");
            handleok = "0"; // 이렇게 처리되어 있는 값만 가져오기
        }else{
            console.log("미완료 처리만 체크 해제");
        }
        console.log("handleok="+handleok);

        // 신고 된 인증샷만 보기 체크
        var sirenok;
        if($("#siren").is(":checked")){
            console.log("신고 된 인증샷만 체크");
            sirenok = "1"; // 이렇게 처리되어 있는 값만 가져오기
        }
        console.log("sirenok="+sirenok);

        // 페이지 이동
        location.href = 'challengemanagement.php?shotmanagementorderby=' + shotmanagementorderby+'&sirenok='+sirenok+'&handleok2='+handleok+"&page="+1+'&management=shotmanagement';

        // ajax로 적당한 값 받아오기
        $.ajax({
            url:"shotmanagementdiv.php",
            type:"POST",
            data:{shotmanagementorderby:shotmanagementorderby,handleok:handleok,sirenok:sirenok},
            success:function(text){
                $("#shotmanagement_shots").html(text);
            }
        });
    });

    // 미완료 처리만 불러오기
    $("#handle").change(function(){

        // 순서값 받아오기
        var shotmanagementorderby= $("#shotmanagementorderby").val();

        // 미완료/완료 체크여부
        var handleok;
        if($(this).is(":checked")){
            console.log("미완료 처리만 체크");
            handleok = "0"; // 이렇게 처리되어 있는 값만 가져오기
        }else{
            console.log("미완료 처리만 체크 해제");
        }
        console.log("handleok="+handleok);

        // 신고 된 인증샷만 보기 체크
        var sirenok;
        if($("#siren").is(":checked")){
            console.log("신고 된 인증샷만 체크");
            sirenok = "1"; // 이렇게 처리되어 있는 값만 가져오기
        }
        console.log("sirenok="+sirenok);

        // 페이지 이동
        location.href = 'challengemanagement.php?shotmanagementorderby=' + shotmanagementorderby+'&sirenok='+sirenok+'&handleok2='+handleok+"&page="+1+'&management=shotmanagement';

        // ajax로 적당한 값 받아오기
        $.ajax({
            url:"shotmanagementdiv.php",
            type:"POST",
            data:{shotmanagementorderby:shotmanagementorderby,handleok:handleok,sirenok:sirenok},
            success:function(text){
                $("#shotmanagement_shots").html(text);
            }
        });

    });

    // 신고 처리만 불러오기
    $("#siren").change(function(){

        // 순서값 받아오기
        var shotmanagementorderby= $("#shotmanagementorderby").val();

        // 미완료/완료 체크여부
        var handleok;
        if($("#handle").is(":checked")){
            console.log("미완료 처리만 체크");
            handleok = "0"; // 이렇게 처리되어 있는 값만 가져오기
        }else{
            console.log("미완료 처리만 체크 해제");
        }
        console.log("handleok="+handleok);

        // 신고 된 인증샷만 보기 체크
        var sirenok;
        if($("#siren").is(":checked")){
            console.log("신고 된 인증샷만 체크");
            sirenok = "1"; // 이렇게 처리되어 있는 값만 가져오기
        }
        console.log("sirenok="+sirenok);

        // 페이지 이동
        location.href = 'challengemanagement.php?shotmanagementorderby=' + shotmanagementorderby+'&sirenok='+sirenok+'&handleok2='+handleok+"&page="+1+'&management=shotmanagement';

        // ajax로 적당한 값 받아오기
        $.ajax({
            url:"shotmanagementdiv.php",
            type:"POST",
            data:{shotmanagementorderby:shotmanagementorderby,handleok:handleok,sirenok:sirenok},
            success:function(text){
                $("#shotmanagement_shots").html(text);
            }
        });

            

    });


    $("#shotmanagement_shots").on("click",".okno",function(){
        console.log("적합/부적합 클릭");

        // 인덱스
        var index = $(".okno").index(this);
        console.log("인덱스="+index);

        // shot 번호
        var idx = $(".shotidx").eq(index).text();
        console.log("shot번호="+idx);

        // 적합, 부적합 상태
        var check = $(".oknotext").eq(index).val();
        console.log("상태="+check);

        if(check=="0"){ // 부적합일때
            console.log("부적합->적합");

        }else if(check=="1"){ // 적합일 때
            console.log("적합->부적합");
        }

        // 처리완료
        $(".shot_checkbox").eq(index).prop("checked", true); 
        

        //데이터베이스에 반영하기
        $.ajax({
            url:"./shotfit.php",
            type:"post",
            data:{idx:idx,check:check},
            success:function(data){
                console.log(data);

                // data값 = fit값
                if(data==1){ // 적합
                    console.log("적합상태");
                    $(".oknotext").eq(index).val("1");
                    $(".okno").eq(index).css("background-color","green");
                }else if(data==0){ // 부적합
                    console.log("부적합상태");
                    $(".oknotext").eq(index).val("0");
                    $(".okno").eq(index).css("background-color","red");
                }
            }
        });
    });

    // 처리완료 표시
    $("#shotmanagement_shots").on("click","#handleok",function(){
        console.log("처리완료 버튼");

        // 체크표시 된 샷 가져오기
        var send_array = Array();
        var send_cnt = 0;
        var chkbox = $(".shot_checkbox");

        for(i=0;i<chkbox.length;i++){
            if(chkbox[i].checked==true){
                send_array[send_cnt] = chkbox[i].value;
                send_cnt++;
            }
        }

        // 배열 출력
        console.log(send_array);

        // 배열 -> tostring
        checkboxstring = send_array.join("№");
        console.log("checkboxstring배열.tostring()="+checkboxstring);

        // 데이터베이스 보내기
        $.ajax({
            data:{checkboxstring:checkboxstring},
            type:"post",
            url:"./shothandleok.php",
            success : function(echo){
                console.log(echo);
                alert('성공적으로 처리되었습니다');
            }
        });
    });
    $("#handleok").click(function(){

    });

    /*
    shotask.php
    */
    // 미답변 된 질문만 보기
    $("#askok").change(function(){
        var askok;
        if($(this).is(":checked")){
            console.log("미답변 된 처리만 체크");
            askok = "0"; // 이렇게 처리되어 있는 값만 가져오기 
        }else{
            console.log("미답변 된 처리만 체크 해제");
        }
        console.log("askok="+askok);

        // 페이지 이동
        location.href = 'challengemanagement.php?askok=' + askok+"&page="+1+'&management=shotask';
    });

    /*
    refundmanagement.php
    */
    // 값고정시키기
    var refundorder = getParameterByName("refundorder");
    $('#'+refundorder).attr('selected','selected');

    //대기중인 챌린지만 보기
    $("#refundfit").change(function(){
        var refundfit;
        if($(this).is(":checked")){
            console.log("대기 중인 챌린지만 보기 체크");
            refundfit = "대기"; // 이렇게 처리되어 있는 값만 가져오기 
        }else{
            console.log("대기 중인 챌린지만 보기 체크 해제");
        }
        console.log("refundfit="+refundfit);

        // 순서값
        var refundorder = getParameterByName("refundorder");

        // 페이지 이동
        location.href = 'challengemanagement.php?refundfit=' + refundfit+"&refundorder="+refundorder+"&page="+1+'&management=refund';
    })

    // 순서(refundorder)
    $("#refundorder").change(function(){
        
        // 값 가져오기
        var refundorder = $("#refundorder").val();
        console.log("refundorder="+refundorder);

        // 미완료 체크값
        var refundfit = getParameterByName("refundfit");

        // 페이지 이동
        location.href = 'challengemanagement.php?refundfit=' + refundfit+"&refundorder="+refundorder+"&page="+1+'&management=refund';
    });

});

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/     \+/g, " "));
}