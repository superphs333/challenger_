//Jquery 화면을 불러오자마자 실행  
$(document).ready(function () {
    console.log("challengeindividualread.js시작");

    // 파일선택(change) => 서버에 저장 => img에 미리보기 
    $(document).on("change", ".shotfile", function () {
        console.log("파일선택 버튼 클릭=>파일변화");

        // 현재 인덱스 번호 알기
        var index = $(".shotfile").index(this);
        console.log("index=" + index);



        // 서버에 이미지 저장, <img>에 미리보기 
        var imgsrc = handleImgFileSelect(event, index);
    });


    // 인증샷 제출 버튼
    $("#shotsubmit").click(function () {
        console.log("인증샷 제출 버튼");

        
        

        //////////////////////////////////////////////////

        // 포함되어야 할 정보 : 글번호, 인증샷, 인증샷 설명

        // (0) 글번호
        var idx =getParameterByName("idx");
        console.log("글번호="+idx);

        // (1) 올라와 있는 아이템의 갯수
        var itemcount = $('#shotinput td').length;
        console.log("itemcount=" + itemcount);

        // (2) 이미지의 src를 배열에 담기
        var imgsrcarr = new Array();
        for (var i = 0; i < itemcount; i++) {
            imgsrcarr[i] = $('.shotimg').eq(i).attr("src");
                // eq() = 선택한 요소의 인덱스 번호에 해당하는 요소를 찾는다

            // 만약 공백이면 빠져나오기
            if(imgsrcarr[i] == "" || imgsrcarr[i] == null){
                alert("제출하신 인증샷 갯수를 확인해주세요");
                return;
            }
            console.log(imgsrcarr[i]);
        }



        // (3) text의 src를 배열에 담기
        var text = new Array();
        for (var i = 0; i < itemcount; i++) {
            text[i] = $(".showplus").eq(i).val();
        }

        // (4)오늘날짜
        var dates = new Array();
        for(var i = 0; i < itemcount; i++){
            dates[i] = getTodayType2();
        }
        // (5)적합여부.
        var fits  = new Array();
        for(var i = 0; i < itemcount; i++){
            fits[i] = "적합";
        }


        // (6) 배열 -> 문자열 변환(데이터베이스에 삽입하기 위해).
        // 텍스트
        var textstring = text.toString;
        textstring = text.join("№");
        console.log("textarea배열.tostring()=" + textstring);
        // 이미지
        var imgsrcarr = imgsrcarr.join("№");
        console.log("imgsrcarr배열.tostring()=" + imgsrcarr);
        // 오늘날짜
        var dates = dates.join("№");
        console.log("dates배열.tostring()=" + dates);
        // 적합여부
        var fits = fits.join("№");
        console.log("fits배열.tostring()=" + fits);

        // 인증샷 적합여부
        //////////////////////////////////////////////////

        // 데이터베이스에 저장하기
        $.ajax({
            data: {idx:idx,textstring:textstring,imgsrcarr:imgsrcarr,dates:dates,fits:fits},
            type: "post",
            cache: false,
            datType: text,
            url: './shotsubmit.php',
            success: function (echo) {
                console.log(echo);
                alert("인증샷이 등록되었습니다");
                location.reload();
            }
        });
    });

    // 인증샷 수정 버튼
    $("#shotsupdate").click(function () {
        console.log("인증샷 수정 버튼");

        //////////////////////////////////////////////////

        // 포함되어야 할 정보 : 인증샷


        // (1) 올라와 있는 아이템의 갯수
        var itemcount = $('#shotinput td').length;
        console.log("itemcount=" + itemcount);

        // (2) 이미지의 src를 배열에 담기
        var imgsrcarr = new Array();
        for (var i = 0; i < itemcount; i++) {
            imgsrcarr[i] = $('.shotimg').eq(i).attr("src");
        }

        // (3) text를 배열에 담기
        var text = new Array();
        for (var i = 0; i < itemcount; i++) {
            text[i] = $(".showplus").eq(i).val();
        }

        // (4)인증샷의 idx를 담기
        var idxs = new Array();
        for(var i = 0; i < itemcount; i++){
            idxs[i] = $(".shotidxinfo").eq(i).val();
        }

        // (6) 배열 -> 문자열 변환(데이터베이스에 삽입하기 위해)
        // 텍스트
        var textstring = text.toString;
        textstring = text.join("№");
        console.log("textarea배열.tostring()=" + textstring);
        // 이미지
        var imgsrcarr = imgsrcarr.join("№");
        console.log("imgsrcarr배열.tostring()=" + imgsrcarr);
        // idx
        var idxarray = idxs.join("№");
        console.log("idx배열.tostring()=" + idxarray);
   
        // 인증샷 적합여부
        //////////////////////////////////////////////////

        //데이터베이스에 저장하기
        $.ajax({
            data: {textstring:textstring,imgsrcarr:imgsrcarr,idxarray:idxarray},
            type: "post",
            cache: false,
            datType: text,
            url: './shotupdate.php',
            success: function (echo) {
                console.log(echo);
                alert("인증샷이 수정되었습니다");
                location.reload();
            }
        });
    });

    // fitcolor를 누르면(빨간색인 경우) => 문의하기?가 나오도록
    $(document).on("click",".fitcolor",function(){
        console.log("fitcolor 클릭");


        // 인덱스
        var index = $(".fitcolor").index(this);
        console.log(index);

        // 만약 부적합 판정받은 인증샷이라면
        var fitinfo = $(".fitinfo").eq(index).val();
        console.log("fitinfo="+fitinfo);
        
        if(fitinfo=="0"){ // 부적합 판정 인증샷

            // 이미 문의한 인증샷이라면, 경고창
            var askyesno = $(".askyesno").eq(index).val();
            console.log("askyesno="+askyesno);
            if(askyesno=="1"){
                alert("이미 문의중인 인증샷입니다");
                return;
            }

            // 이 인덱스를 가진 아이템의 challengeidx구하기
            var idx = $(".idxinfo").eq(index).val();
            console.log("idx="+idx);
            

            // 새창(인증샷 문의)
            var url = "shotask.php?idx="+idx;
            window.open(url,"","width=600,height=600,left=600");
        }



       
    })

    // 환급신청 버튼
    $("#refund").click(function(){
        var result = confirm('환급신청을 하시겠습니까?');
        if(result){
            console.log("환급신청 yes");

            // 챌린지 idx
            var idx = getParameterByName("idx");

            // 데이터 적용
            $.ajax({
                data: {idx:idx},
                type: "post",
                url: './refund.php',
                success: function (echo) {
                    console.log(echo);
                    alert("환급신청이 완료되었습니다!");
                    location.reload();
                }
            });
        }
    });

    // 수락, 거절버튼
    $("#refundaccept").click(function(){ // 수락버튼
        
        // 환급번호
        var refundidx = $("#refundidx").val();
        console.log("refundidx="+refundidx);

        // 데이터 적용
    });


});

// 날짜구하기
function getTodayType2() {
    var date = new Date();
    return date.getFullYear() + "-" + ("0"+(date.getMonth()+1)).slice(-2) + "-" + ("0"+date.getDate()).slice(-2);
}

// 파일저장 
function handleImgFileSelect(e, index) {
    console.log("썸네일 파일 변화");

    var sel_files;

    /*
    선택한 파일의 File객체 취득
    : e.target.files를 통해 얿로드 된 파일의 FileList객체를 변환 -> 자름(Array.prototype.slice.call(files)) -> 파일을 얻는다
    */
    // file프로퍼티는 업로드 된 파일의 FileList 객체를 반환
    var files = e.target.files;
    var filesArr = Array.prototype.slice.call(files);
    /*
    = 이 코드가 존재하는 함수의 매개변수로 넘어온 값들을 array로 변환하겠다. 
    - 함수는 모두 protototype객체를 포함하고 있다
    - 객체 인스턴스가 생성자 함수의 prototype속성을 상속받을 수 있도록 지원해 줌
     */

    // 파일 1개
    filesArr.forEach(function (f) {

        // 확장자 구분 : 비디오인지 사진인지
        var videocheck = $("#videocheck").val();
        if(videocheck=="사진인증"){
            console.log("사진인증인 경우");
            // 이미지 확장자만 가능하도록 한다
            if (!f.type.match("image.*")) {
                alert("확장자는 이미지 확장자만 가능합니다.");
                return;
            }
        }else{
            console.log("동영상인증인 경우"); 
            // 이미지 확장자만 가능하도록 한다
            if (!f.type.match("video.*")) {
                alert("확장자는 동영상 확장자만 가능합니다.");
                return;
            }
        }


        sel_files = f;
        console.log("f.name=" + f.name); //파일 이름 출력
        console.log("f.size=" + f.size); //파일 사이즈 출력
        console.log("f.lastModified=" + f.lastModified); //파일 시간 출력
        // 이미지를 서버에 저장
        var data = new FormData();
        data.encytp
        data.append("file", f); // 보낼파일
        data.append("category", "shots"); //카테고리

        // 서버에 보내서 저장
        $.ajax({
            data: data,
            type: "post",
            enctype: 'multipart/form-data',
            cache: false,
            contentType: false,
            processData: false,
            cache: false,
            url: './cwupload.php',
            data: data,
            success: function (url) {
                //tempurl = url;
                console.log("url=" + url);

                // 받은 url을 해당 img태그에 미리보기 이미지로 넣기
                $(".shotimg").eq(index).attr("src", url);

            }
        });
    });
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/     \+/g, " "));
}




