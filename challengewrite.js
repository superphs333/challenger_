$(document).ready(function () {

    console.log("challengewrite.js");

  

    // 서머노트
    $('#summernote').summernote({
        // set editor height
        height : 600,
        minHeight: 600,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        
        lang: "ko-KR", // 한글 설정
        //placeholder: 'placeholder',
        callbacks: {
            onImageUpload: function (files, editor, welEditable) {
                // 올릴파일, 에디터
                console.log("이미지업로드함"+files);
                for(var i= files.length-1; i>=0; i--){
                    sendFile(files[i],editor,welEditable);
                }
                
            }
        }
    });



    //$("#cw_startday").val(new Date().toDateInputValue());
    // 시작일 선택시 => 오늘 날짜보다 이전 날짜이면, 오늘날짜로 초기화
    $("#cw_startday").change(function(){

        // 현재날짜
        var now = new Date();
        var year = now.getFullYear(); // 현재년도
        var month = now.getMonth()+1; // 현재 월
        // 월이 한자리 수인 경우 (예: 1, 3, 5) 앞에 0을 붙여주기 위해
        if((month+"").length<2){month = "0" + month;}
        var date = now.getDate(); // 현재 날짜 가져오기
        if((date+"").length<2){date = "0" + date;}
        // 오늘날짜
        var today = year +"" + month + "" + date;
        console.log("오늘날짜="+today);

        // 받아온 날짜
        var get = $(this).val();
        var get = get.split("-"); // - 로 잘라서 배열에 저장
        var getyear = get[0]; 
        var getmonth = get[1]; 
        var getday = get[2];
        var get = getyear + "" + getmonth+ "" + getday;
        console.log("받아온 날짜="+get);

        if(parseInt(get)<parseInt(today)){ // 불가한 경우
            console.log("불가");
            $("#cw_startday").val($.datepicker.formatDate('yymmdd', new Date()));
        }

    });

    /*
    1주일 이상 선택 => #weekselect보이게
    1주일 이하 선택 => #dayselect보이게
    */
    // 버튼이름이 수정일 경우 -> 기존값 셋팅하기
    var buttonname = $("#cw_submit").text();
    var weekorday = $("#weekorday").val();
    console.log("weekorday="+weekorday);
    if(buttonname=="수정하기"){
        // weeokorday에 따라서, 보이는 것이 다르게 해야 한다
        if(weekorday=="day"){ // 1~6일
            // weekrow라디오 버튼이 클릭되어있어야 함
            $("#weekrow").attr("checked","checked");
            $("#dayselect").show();
            $("#weekselect").hide();
            $("#week").hide(); $("#day").show(); 

            // 정확한 일수 기입
            var detailday = $("#detailday").val();
            $("#dayselect").val(detailday);
        }else{ // 7일 이상
            $("#weekhigh").attr("checked","checked");
            $("#weekselect").show();
            $("#dayselect").hide();
            $("#day").hide();  $("#week").show();

            // 정확한 일수 기입
            var detailday = $("#detailday").val();
            $("#weekselect").val(detailday);
        }
    }else{
        // 처음에는 ~일선택 보이지 않게(주가 기본)
        $("#dayselect").hide(); $("#day").hide(); 
    }



    $(".weekhr").change(function(e){
        
        var weekhr = $(this).val();
        console.log("weekhr값="+weekhr);
        
        if(weekhr=="weekhigh"){ // #weekselect보이게
            $("#weekselect").show();
            $("#dayselect").hide();
            $("#day").hide();  $("#week").show(); 
            $("#weekorday").val("week");

        }else{ //#datyselect보이게
            $("#dayselect").show();
            $("#weekselect").hide();
            $("#week").hide(); $("#day").show(); 
            $("#weekorday").val("day");
   
        }
    });

    // 썸네일 파일 선택 => 서버에 저장 => img에 미리보기
    $(document).on("change","#cwthumbnailfile",handleImgFileSelect);
    
    // 제출
    $("#cw_submit").click(function(){
        console.log("제출버튼 누름");

        // 분류
        var sort = $("#challengewritesort").val();

        // 제목
        var title = $("#challengewritetitle").val();
        var titlecheck = $.trim(title);

        // 썸네일 
        var thumbnail = $("#cwthumbnailimg").attr("src");

        // 시작일
        var startday = $("#cw_startday").val();
        var get = startday.split("-"); // - 로 잘라서 배열에 저장
        var getyear = get[0]; 
        var getmonth = get[1]; 
        var getday = get[2];
        var get = getyear + "" + getmonth+ "" + getday;

        // 오늘날짜
        var now = new Date();
        var year = now.getFullYear(); // 현재년도
        var month = now.getMonth()+1; // 현재 월
            // 월이 한자리 수인 경우 (예: 1, 3, 5) 앞에 0을 붙여주기 위해
        if((month+"").length<2){month = "0" + month;}
        var date = now.getDate(); // 현재 날짜 가져오기
        if((date+"").length<2){date = "0" + date;}
        var now = year +"" + month + "" + date;  // 오늘날짜

        // 시작일 < 오늘날짜 => 불가
        if(parseInt(get)<parseInt(now)){
            console.log("불가");
            alert("시작일을 확인해주세요");
            return;
        }

        // 기간 -> $("#weekorday").val()값에 따라서, 가져올 대상이 달라진다
        var weekorday = $("#weekorday").val();
        var period;
        console.log("weekorday="+weekorday);
        if(weekorday=="week"){
            period = $("#weekselect").val();
            period = period*7;
        }else{
            period = $("#dayselect").val();
        }
        console.log("period="+period);

        // 종료일
        var endday = $("#cw_endday").val();

        // 참가비
        var entryfee = $("#cwentryfee").val();

        // 동영상 인증 여부
        var video = $("input:radio[name=video]:checked").val();
        console.log("video="+video);

        // 인증샷 갯수
        var shotcount = $("#cwshotcount").val();

        // 인증시간
        var starttime = $("#starttime").val();
        var endtime = $("#endtime").val();
        console.log("시작시간="+starttime);
        console.log("끝시간="+endtime);

        // 인증시간 비교
        var startDateArr = starttime.split(':');
        console.log(startDateArr);

        // 인증빈도
        // var frequency = $("input:radio[name=chk_info]:checked").val();
        
        // 만약, 기간이 1주일 이하라면, 인증빈도 = ~일 
        if($("#weekorday").val()=="day"){
            frequency = $("#dayselect").val();
        }else{
            frequency = 7;
        }
        console.log("인증빈도="+frequency);

        // 설명
        var summernote = $("#summernote").val();
        var summernotetrim = $.trim(summernote);

        // 글번호
        var idx = $("#idx").val();

        // 빈값체크하기
        if(startday=="" || endday=="" || titlecheck =="" || summernotetrim==""){
            if(titlecheck ==""){
                alert("제목을 입력해주세요");
            }else if(startday==""){
                alert("시작일을 선택해주세요");
            }else if(endday==""){
                alert("종료일을 선택해주세요");
            }else{
                alert("설명을 입력해주세요");
            }
        }else{
            // 버튼값에 따라서 => 개설하기(challengewrite_ok.php"), 수정하기(challengeupdate_ok.php")
            var buttonname = $(this).text();
            console.log(buttonname);
            if(buttonname=="개설하기"){
            // challengewrite_ok.php 이동
            // 값보내기
                $.ajax({
                    data:{sort:sort,title:title,thumbnail:thumbnail,startday:startday,endday:endday,entryfee:entryfee,shotcount:shotcount,summernote:summernote,frequency:frequency,period:period,video:video,starttime:starttime,endtime:endtime},
                    type:"post",
                    cache:false,
                    url:"./challengewrite_ok.php",
                    success:function(echo){
                        console.log(echo);

                        // 알림
                        alert("게시글이 등록되었습니다.");

                        // 페이지 이동
                        location.href="challengeread.php?idx="+echo;
                    }
                });
                }else if(buttonname=="수정하기"){
                    // challengeupdate_ok.php 이동
                    $.ajax({
                        data:{sort:sort,title:title,thumbnail:thumbnail,startday:startday,endday:endday,entryfee:entryfee,shotcount:shotcount,summernote:summernote,frequency:frequency,idx:idx,period:period,video:video,starttime:starttime,endtime:endtime},
                        type:"post",
                        cache:false,
                        url:"./challengeupdate_ok.php",
                        success:function(echo){
                            console.log(echo);

                            // 수정알림
                            alert("게시글이 수정되었습니다");

                            // 페이지 이동
                            location.href="challengeread.php?idx="+idx;
                        }
                    });
                }




        }

        
        
    });

    // 인증빈도 초기값 설정
    var radiocheck = $("#radiocheck").val();
    console.log(radiocheck);
    $("input#"+radiocheck).prop('checked',true);
    
    // 데이터베이스에 있는 값으로 checked값 고정
    var option = $("#optioncheck").val();
    $("#"+option).attr('selected','selected');

    // 비디오 yes, no초기값 설정
    var videocheck = $("#videocheck").val();
    console.log("videocheck값="+videocheck);
    // 데이터베이스에 있는 값으로 cheked값 고정
    $("input#"+videocheck).prop('checked',true);



});

// number최소값, 최대값 지정
function minmax(value,min,max){
    if(parseInt(value) < min || isNaN(parseInt(value))){
        // 최소값 보다 더 작아지면 => 최소값을 RETURN함
        return min;
    }else if(parseInt(value) > max){
        // 최대값 보다 더 커지면 => 최대값을 RETURN함
        return max; 
    }else{
        // 그 외의 경우에는 입력한 값을 그대로 RETURN함
        return value;
    }

    /* 문법
    - pareseInt(string,n)
    : string을 n진법일 때의 값으로 바꾼다(기본값 : 10)
    + 소수 부분은 버린다.
    - isNaN => 숫자가 아닌 값을 찾는 함수
    */
}

// 서머노트에서 이미지 업로드시 실행 할 함수
function sendFile(file,editor,welEditable){
    console.log("upload함수 실행");

    var data = new FormData();
    data.append("file",file);
    data.append("category","summernote");

    $.ajax({
        data:data,
        type:"post",
        cache:false,
        contentType:false,
        processData:false,
        url:'./cwupload.php',
        data:data,
        success:function(url){
            console.log(url);
            $('#summernote').summernote('insertImage',url);
        },
        error:function(){
            console.log("error");
        }
    });
}

// 이미지 업로드 시, 이미지를 서버에 저장
function sendFile2(file,selector){
    console.log("sendFile2함수 실행");

    var data = new FormData();
    data.append("file",file);
        /* FormData.append() = FormData객체안에 이미 키가 존재하면, 
        그 키에 새로운 값을 추가하고, 키가 없으면 추가한다.
        */
    data.append("category","thumbnail"); // 어디에 저장될 파일인지 알려줌
    

    // 서버에 보내서, 저장
    $.ajax({
        data : data,
        type : "post",
        cache : false,
        contentType : false,
        processData : false,
        url : './cwupload.php',
        data : data,
        success : function(url){
            tempurl = url;
            console.log("url="+url); // 선택한 이미지의 경로           
            selector.attr("src",url); // <img>에 셋팅
        } 
    });
}

// 파일 저장
function handleImgFileSelect(e){
    console.log("썸네일 파일 변화");
    console.log(e);

    var sel_files;

    /* 
    선택한 파일의 File객체 취득 
    : e.target.files를 통해 업로드 된 파일의 FileList객체를 반환 -> 자름(Array.prototype.slice.call(files)) -> 파일을 얻는다
    */
    // file프로퍼티는 업로드 된 파일의 FileList 객체를 반환
    var files = e.target.files;
    console.log("files"+files);
        //files[object FileList]

    var filesArr = Array.prototype.slice.call(files);        
        /* 
        = 이 코드가 존재하는 함수의 매개변수로 넘어온 값들을 array로 변환하겠다.  
        prototype
        - 함수는 모두 prototype객체를 포함하고 있다.
        - 객체 인스턴스가 생성자 함수의 prototype속상을 상속받을 수 있도록 지원해 줌
        */

    console.log("filesArr="+filesArr);
        // filesArr=[object File]
    console.log(filesArr[0]);
        /* ex
        File {name: "994BEF355CD0313D05.png", lastModified: 1591653024171, lastModifiedDate: Tue Jun 09 2020 06:50:24 GMT+0900 (대한민국 표준시), webkitRelativePath: "", size: 24572, …}
        */
    
    filesArr.forEach(function(f){

        // 이미지 확장자만 가능하도록 한다
        if(!f.type.match("image.*")){
            alert("확장자는 이미지 확장자만 가능합니다.");
            return;
        }

        sel_files = f;
        console.log("f.name="+f.name); // 파일 이름 출력

        // 파일읽기
        var reader = new FileReader();
        reader.readAsDataURL(f);

        // 서버에 파일을 저장하고, 이미지에 미리보기
        sendFile2(f,$("#cwthumbnailimg"));
    })

}