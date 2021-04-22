$(document).ready(function(){

    // 어떤 데이터베이스에 저장할 것이지 : challengeshot_reply / challenge_reply
    var category = $("#category").val();
    console.log("카테고리="+category);

    // 댓글입력버튼 -> 댓글 저장하고 -> 댓글을 붙여준다 
    $("#replybutton").click(function(){
        // db에 저장할 데이터를 변수에 저장함 : 글번호, 댓글내용
        var bno = $("#idx").val(); // 글번호
        var content = $("#replyinput").val(); // 댓글내용
        console.log("글번호="+bno);
        console.log("댓글내용="+content);

        // replysave.php로 이동해서 저장
        $.ajax({
            url:"./replysave.php",
            type:"POST",
            data:{bno:bno,content:content,category:category},
            success:function(data){
                console.log(data);

                $("#replylist").append(data);
                alert("댓글이 입력되었습니다.");

                // 댓글입력창 초기화
                $("#replyinput").val("");
            }
        });
    });

    // 댓글 삭제 버튼 누름
    $(document).on("click",".delete",function(){
        console.log("댓글 삭제 버튼");

        // 정말로 삭제할 것인지 묻기
        var confirmflag = confirm("정말로 삭제하시겠습니까?");

        if(confirmflag){// 확인버튼 -> true
            var thisvar = $(this);
            var number = thisvar.prev().prev().val();
                // prev() = 이전요소
                // .reply_num의 값이 나온다(댓글idx)
            console.log("삭제 될 댓글번호="+number);

            // 데이터베이스도 삭제하기
            $.ajax({
                url:"./replydelete.php",
                type:"POST",
                data:{number:number,category:category},
                success:function(data){
                    console.log(data);
                    thisvar.parent().parent().parent().parent().remove();
                }
            });
        }
    });

    // 댓글 수정버튼 .updateok
    $(document).on("click",".update",function(){
        console.log("댓글 수정 버튼");

        var thisvar = $(this);

        // 작성자
        var writer = thisvar.next().next().val(); 
        console.log("작성자="+writer);
        // 내용
        var content = thisvar.next().next().next().val(); 
        console.log("내용="+content);
        // 댓글번호
        var number = $(this).prev().val(); 
        console.log("댓글번호="+number);

        // 원래 댓글표시에서 -> 댓글수정폼으로 바꾸기
        $.ajax({
            url:"./replyupdate.html",
            type:"POST",
            data :{writer:writer,content:content,number:number,category:category},
            success:function(data){
                console.log(data);                
                thisvar.parent().parent().parent().parent().html(data);
            }
        });
    });

    // 댓글 수정 등록 버튼
    $(document).on("click",".updateok",function(){
        console.log("댓글 수정 등록 버튼");
        var thisvar = $(this);

        // 작성자
        thisvar.next().next().css("background-color","blue");  
        var writer = thisvar.next().next().val(); 
        console.log("작성자="+writer);

        // 내용
        var content = $(".update_ta").val(); 
        console.log("1내용="+content);

        // 댓글번호
        var number = $(this).prev().val(); 
        console.log("댓글번호="+number);

        // 댓글 수정 반영
        $.ajax({
            url:"./reply_updateok.php",
            type:"POST",
            data :{number:number,writer:writer,content:content,category:category},
            success:function(data){
                console.log(data);
                thisvar.parent().parent().parent().parent().html(data);
                alert("댓글이 수정 되었습니다.");
            }
        });

    });

    // 댓글 수정 취소 버튼
    $(document).on("click",".updateno",function(){
        console.log("댓글 수정 취소 버튼");

        var thisvar = $(this);

        // 댓글번호
        var number = $(this).prev().prev().val();
        console.log("댓글번호="+number);

        $.ajax({
            url:"./reply_updateno.php",
            type:"POST",
            data:{number:number,category:category},
            success:function(data){
                console.log(data);
                thisvar.parent().parent().parent().parent().html(data);
            }
        });
    });
    
    
   

});