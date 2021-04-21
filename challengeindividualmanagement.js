//Jquery 화면을 불러오자마자 실행
$(document).ready(function () {

   

    // refundidx 받아오기
    var refundidx = $("#refundidx").val();
    console.log("refundidx="+refundidx);

    // merchantuid받아오기
    var merchantuid = $("#merchantuid").val();
    console.log("merchantuid="+merchantuid);

    // challenge의 cost받아오기
    var cost = $("#cost").val();
    console.log("cost="+cost);
   
    // 수락버튼(refundaccept)
    $(".refundfit").click(function(){
        
        // 버튼 이름 : 수락/거절
        console.log("button="+$(this).text());
        var sort = $(this).text();

        $.ajax({
          data:{sort:sort,refundidx:refundidx},
          type:"post",
          url:"./refundok.php",
          success : function(echo){
              console.log(echo);
          }
      });

        //cancelPay();
      
    });
    


});

function test(){
    location.href='http://13.209.234.165:3000/form?';
}

// 환불하기
function cancelPay(){
    jQuery.ajax({
        "url":"./refundmoney.php",
        "type":"POST",
        "contentType" : "application/json",
        "data":JSON.stringify({
            "dataType":"json",
            "merchant_uid":merchantuid, // 주문번호
            "cancel_request_amount": cost,// 환불금액
            "reason":"환급" // 환불사유
        }),
        "dataType": "json",
        success:function(data){
          console.log("hi");
          console.log(data);
        },
        error : function(error){
          console.log(error);
        }
    });

    //const axios = require('axios');

  // 인증 토큰 발급 받기
  // axios({
  //   url: "https://api.iamport.kr/users/getToken",
  //   method: "post", // POST method
  //   headers: { "Content-Type": "application/json" }, // "Content-Type": "application/json"
  //   data: {
  //     imp_key: "2076470322170720", // REST API키
  //     imp_secret: "NII6wlAMrnrT7shmGr4IbmK1sdSdGUatDTA9eaWFynzFQQ2wh6etsZuUcn8TijmVcX5r716FpFlfwnMN" // REST API Secret
  //   }
  // })
  // .then(function (response) {
  //   console.log(response);
  //   })
}


