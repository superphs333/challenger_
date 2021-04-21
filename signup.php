<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <!-- css -->
        <!-- 합쳐지고 최소화된 최신 CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
        <!-- 부가적인 테마 -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">

    </head>


    <body>
        <div id="inp"></div>
        <article class="container">
            <div class="page-header">
                <div class="col-md-6 col-md-offset-3">
                    <h3>회원가입</h3>
                </div>
            </div>

            <div class="col-sm-6 col-md-offset-3" 
            style="width: 80%;">
                
                    <div class="form-group">

                        <!-- 이메일 -->
                        <div class="form-group">
                            <lable for="InputEmail">이메일 주소</lable>
                            <input type="email" class="form-control" id="signup_InputEmail" placeholder="이메일 주소를 입력해주세요" name="email" required>
                            <button id="signup_chkbtn" style="margin-top:3px;">이메일 인증</button>
                            <!-- 임시문자 -->
                            <h6 id="tempemail"></h6>
                            <h6 id="getfromwindow"></h6>
                        </div>

                        <!-- 닉네임 -->
                        <label for="inputName">닉네임</label>
                        <input type="text" class="form-control" id="inputName" placeholder="닉네임을 입력해 주세요" name="nickname" required style="widt:70%;">
                        <!-- 중복체크시 확인문자 -->
                        <div id="nickduplicatechkwords">
                        </div>
                        <!-- 닉네임 중복체크 버튼 -->
                        <button id="signup_ckbtn">중복체크</button>

                        <!-- 비밀번호 -->
                        <div class="form-group">
                            <label for="inputPassword">비밀번호</label>
                            <input type="password" class="form-control" id="inputPassword" placeholder="영문(대소문자)포함, 숫자, 특수문자를 혼합하여 공백없이 8~20자를 입력해주세요" name="pw" requried>
                            <div id="pwtext"></div>
                        </div>

                        <!-- 비밀번호 확인 -->
                        <div class="form-group">
                            <label for="inputPasswordCheck">비밀번호 확인</label>
                             <input type="password" class="form-control" id="inputPasswordCheck"
                            placeholder="비밀번호 확인을 위해 다시한번  입력 해 주세요" name="pwcheck" required>
                            <input type="hidden" id="forcheck" placeholder="">

                            <!--2개의 비밀번호값이 일치할때는 "비밀번호가 일치합니다."라는 글을 보이게 하고, 다를때는 "비밀번호가 일치하지 않습니다."라는 글이 보이게 한다-->
                            <div class="alert alert-success" id="alert-success">
                                비밀번호가 일치합니다.
                            </div>
                            <div class="alert alert-danger" id="alert-danger">
                                비밀번호가 일치하지 않습니다.
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <button  id="join-submit" class="btn btn-primary">회원가입<i class="fa fa-check spaceLeft"></i></button>
                        </div>


                    </div>
                
            </div>
        </article>
    
    

    <!-- 제이쿼리 -->
    <script  src="https://code.jquery.com/jquery-latest.min.js"></script>
    <!-- 합쳐지고 최소화된 최신 자바스크립트 -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <!-- 메일 -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/emailjs-com@2.3.2/dist/email.min.js"></script>
    <script type="text/javascript" src="./signup.js"></script>
    </body>
</html>