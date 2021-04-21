$(document).ready(function(){
    
    // mychallengenote

    /*
    값 고정시키기 -> get값에 따라서
    */
   // 참여 ing/end/future
   var join = getParameterByName("join");
   if(join==""){ // 기본값 = 참여중인
       $('#ing').attr('selected','selected');
   }else{ // get값이 있다면 그 값으로 고정
        $("#"+join).attr('selected','selected');
   }

   // 참여 선택
   $("#join").change(function(){
       console.log("join select 변경");

       // 분류값
       var join = $("#join").val();

       // 페이지 이동
       location.href = 'mychallengenote.php?join='+join;
   });
});

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/     \+/g, " "));
}