$(document).ready(function () {

    /*
    값 고정시키기 -> get값에 따라서.
    */
   // 페이지 번호
   var page = getParameterByName("page");
   page =1;
   // 분류 
   var challengeselect = getParameterByName("challengeselect");
   if(challengeselect==""){ // null이면 전체로 고정
        $('#전체').attr('selected','selected');
   }else{ // get값 있다면, 그 값으로 고정
        $('#'+challengeselect).attr('selected','selected');
   }
   // 검색값
   var search = getParameterByName("search");
   console.log(search);
   if(!search==""){ //null이 아닌경우, 검색값으로 
        console.log("값이 들어와야 함");
        $('#search').val(search);
   }
   // joinable 
   var joinable = getParameterByName("joinable");
   console.log(joinable);
   if(joinable==""){
        $("#joinable").prop("checked",false);
   }else{
         $("#joinable").prop("checked",true);
   }

    // 분류선택
    $("#challengeselect").change(function () {
        console.log("select 변경");

        // 분류값
        var challengeselect = $("#challengeselect").val();
        console.log("분류=" + challengeselect);

        // 페이지 이동
        location.href = 'togetherchallenge.php?challengeselect=' + challengeselect+'&search='+search+'&joinable='+joinable+"&page="+1;
    });

    // 참여 가능한 이벤트만 보기
    $("#jointime").change(function () {

        // 현재 체크 상태 
        var jointime = $("#jointime").val();
        console.log("이벤트 시기="+jointime);

        // 페이지 이동
        location.href = 'togetherchallenge.php?challengeselect=' + challengeselect+'&search='+search+'&joinable='+joinable+"&page="+1;
        
    });

    // 검색
    $("#searchbtn").click(function () {
        console.log("검색버튼 클릭");

        // 검색값
        var search = $("#search").val();
        console.log("검색값=" + search);

        // 페이지 이동
        location.href = 'togetherchallenge.php?challengeselect=' + challengeselect+'&search='+search+'&joinable='+joinable+"&page="+1;
        
    });

    // 참여 가능한 챌린지
    $("#joinable").change(function(){

        // var joinable = $("#joinable").val();
        // console.log("joinable="+joinable);

        if($(this).is(":checked")){
            console.log("체크박스 체크했음!");

            joinable="yes";

            // 페이지 이동
            location.href = 'togetherchallenge.php?challengeselect=' + challengeselect+'&search='+search+'&joinable='+joinable+"&page="+1;
        }else{
            console.log("체크박스 체크 해제!");
            joinable="";

            // 페이지 이동
            location.href = 'togetherchallenge.php?challengeselect=' + challengeselect+'&search='+search+'&joinable='+joinable+"&page="+1;
        }
    });


});

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/     \+/g, " "));
}



