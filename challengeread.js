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
    //console.log("member_chk="+member_chk);

    /*
    for 결제
    */
   // IMP 변수 초기화
   var IMP = window.IMP; // 생략해도 됨
   IMP.init("imp80487409"); // 최초1회 이루어져야 한다
        // 매개변수 : 부여받은 가맹점 식별코드 
    

    // 설명부분.
    // $('#cr_detail').summernote({
    //     airMode:true,
    //     width : 1000,
    //     height: 500
    // });

    // 글번호
    var idx = $("#idx2").val();

    // 북마크 체크 확인
    console.log($('#bookmarkcheck').val());
    
    // 북마크 체크 버튼 -> 클릭하면 색 반전
    $("#cr_bookmark").click(function(){
        console.log("북마크 체크 버튼 누름");

        if(member_chk==false){
            alert("로그인 및 회원가입을 해야 이용가능합니다");
            return;
        }

        /*
        데이터베이스 값 변화
        - 필요 => 글번호, bookmarkcheck.var
        */
        // 글번호
        var idx = $("#idx").val();
        console.log("글번호="+idx);

        // (서버에 보낼)check상태
        var check = "";
       
        if($('#bookmarkcheck').val()=="1"){//색있음, 체크값1
            console.log("색있음=>색없음");
            check = $('#bookmarkcheck').val();
            console.log("check값="+$('#bookmarkcheck').val());

            // 변화 
            $('#bookmarkcheck').val("0");
            $(this).css('background-color','transparent');
            
        }else{ // 색없음, 체크값x
            console.log("색없음=>색있음");
            check = $('#bookmarkcheck').val();
            console.log("check값="+$('#bookmarkcheck').val());

            // 변화
            $('#bookmarkcheck').val("1");
            $(this).css('background-color','skyblue');
        }

        console.log("bookmarkcount="+$("#cr_bookmark_count").text());
      
        $.ajax({
            url:"./challengebookmark.php",
            type:"post",
            data:{idx:idx,check:check,category:category},
            success:function(data){
                console.log("가져온 값="+data);
                $("#cr_bookmark_count").text(data);
            }
        });
    });



    // 참여하기 버튼 -> 클릭하면 색 반전
    $("#cr_join").click(function(){
        console.log("참여하기 버튼 누름");

        if(member_chk==false){
            alert("로그인 및 회원가입을 해야 이용가능합니다");
            return;
        }
        
        // 만약, 참여가능하지 않는 경우라면, 참여하지 못하도록 하기
        var joinable = $("#joinable").val();
        console.log(joinable);
        if(joinable==0){ // 참여불가능
            alert("참여 불가능한 챌린지입니다.");
            return;
        }

        // 참여하기 상태가 0인경우에만(참여하지 않았을 경우에만)
        if($('#joincheck').val()=="0"){

            // 정말로 참여 할 것인지 물어보기
            var result = confirm('정말로 참여하시겠습니까? 결제 후에는 참여취소가 불가능합니다.');
            if(result){ // 예를 클릭했을 경우
                //console.log("joincheck상태="+$('#joincheck').val());    
                
                // 결제
                Payment();
            }
           
        }else{
            alert("참여기간 동안은 취소 할 수 없습니다.");
        }
    });


    // 삭제하기 버튼 => 게시물 삭제
    $("#chdelete").click(function(){
        console.log("게시물 삭제 버튼 클릭");

        // 알림창에서 확인버튼을 눌렀을시에만, 게시물 삭제 파일로
        var check = confirm("정말로 삭제하시겠습니까?");
        if(check){
            //challengedelete.php?idx=<?php echo $idx ?>
            location.href = 'challengedelete.php?idx='+idx;
        }
    });

    // 수정하기 버튼 => 게시물 수정
    $("#chupdate").click(function(){
        console.log("게시물 수정 버튼 클릭");

        // 알림창에서 확인버튼을 눌렀을시에만, 게시물 삭제 파일로
        var check = confirm("수정하시겠습니까?");
        if(check){
            //challengedelete.php?idx=<?php echo $idx ?>
            location.href = 'challengeupdate.php?idx='+idx;
        }
    });
});

// 결제창 호출코드
function Payment(){

    // 필요한 정보 : 가격, 주문자, 챌린지 idx
    var ef = $("#ef").val(); // 참가비용
    var participant = $("#participant").val();
    var idx2 = $("#idx2").val();

    // merchant_uid 생성
    var merchant_uid = "challenge_"+idx2+"-"+new Date().getTime();
    console.log("merchant_uid="+merchant_uid);

    // 결제창 호출코드 추가하기(결체 요청)
        // 참고 : https://github.com/iamport/iamport-manual/blob/master/%EC%9D%B8%EC%A6%9D%EA%B2%B0%EC%A0%9C/README.md
    // IMP.request_pay(param, callback)호출
    IMP.request_pay({
        pg: "html5_inicis", // 다양하게 있음(html5_inicis=이니시스(웹표준결제))
        pay_method: "card", // 결제수단(card : 신용카드)
        merchant_uid: merchant_uid,//가맹점에서 생성/관리하는 고유 주문 번호
        name: "챌린지"+idx2, //주문명
        amount: ef,//결제할 금액
        buyer_email: participant //구매자 이메일   
    }, function(rsp){
        if(rsp.success){ // 결제 성공시 로직
            var msg = '결제가 완료되었습니다.';
            msg += '고유ID : ' + rsp.imp_uid;
                // 아임포트에서 부여하는 거래건 당 고유한 번호
            msg += '상점 거래ID : ' + rsp.merchant_uid;
            msg += '결제 금액 : ' + rsp.paid_amount;
            msg += '카드 승인번호 : ' + rsp.apply_num;
            //mst += '결제승인시각 :' + rsp.paid_at;
            console.log(msg);

            // 상점 거래 아이디
            var merchant_uid = rsp.merchant_uid;

            /*
            데이터베이스 값 변화
            - 필요 => 글번호, bookmarkcheck.var
            */
            // 글번호
            var idx = $("#idx").val();
            console.log("글번호="+idx);
            document.cookie="idx="+idx;
            // 카테고리
            var category = $("#category").val();
            console.log("카테고리="+category);
            document.cookie="category="+category;
            // merchant_uid
            document.cookie="merchant_uid="+merchant_uid;
            // check상태
            var check = "";
        
            if($('#joincheck').val()=="1"){//색있음, 체크값1
                console.log("색있음=>색없음");

                // 현재 상태
                check = $('#joincheck').val();
                document.cookie="check="+check;
                console.log("check값="+$('#joincheck').val());

                // 변화
                $('#joincheck').val("0");
                $(this).css('background-color','transparent');
                
            }else{ // 색없음, 체크값x
                console.log("색없음=>색있음");

                //현재상태
                check = $('#joincheck').val();
                document.cookie="check="+check;
                console.log("check값="+$('#joincheck').val());

                //변화
                $('#joincheck').val("1");

                // 데이터베이스에 적용하기
                $.ajax({
                    url:"./challengejoinmark.php",
                    type:"post",
                    contentType:false,
                    processData:false,
                    data:{idx:idx,check:check,category:"challenge",merchant_uid:merchant_uid},
                    success:function(data){
                        console.log("가져온 값="+data);

                        // 변화
                        $($("#cr_join")).css('background-color','skyblue');

                        // 참여자수 셋팅
                        $("#cr_join_count").text(data);
                        
                        // 나의 도전노트로 이동 할 것인지 물어보고, 예인 경우 => 해당 도전노트로 이동한다
                        var result = confirm("해당 나의 도전 노트로 이동하시겠습니까?");
                        if(result){
                            location.replace('challengeindividualread.php?idx='+idx);
                        };
                    },
                    error:function(request,status,error){
                        console.log("code = "+ request.status + " message = " + request.responseText + " error = " + error);
                    },
                });

            }

            console.log("bookmarkcount="+$("#cr_bookmark_count").text());
        


            

        }else{//결제 실패시 로직
            console.log("결제실패");
            console.log(rsp.error_msg);
        }
    });
    
}