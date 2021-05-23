/*
모듈 가져오기
*/
// node.js 기본 내장 모듈 가져오기
const fs = require('fs');
// 설치한 express 모듈 불러오기
const express  = require('express');
// 설치한 socket.io모듈 불러오기
const socket = require('socket.io');
// node.js기본 내장 모듈 불러오기
const http = require('http');



// express 객체 생성
const app = express();

// express http 서버 생성
const server = http.createServer(app);

// 생성된 서버를 socket.io에 바인딩
const io = socket(server);

// css, html 불러오기
app.use('/css', express.static('./index.css'));
app.use('/js', express.static('./index.css'));


// get방식으로 / 경로에 접속하면 실행됨
    // get(경로, 함수)
    /*
    function(request, response)
    - request = 클라이언트에서 전달된 데이터와 정보들이 담겨있다
    - response = 클라이언트에게 응답을 위한 정보가 들어있따
    */
app.get('/',function(request, response){
    console.log('유저가 /으로 접속하였습니다!');
    response.send('Hello, Express Server!!');
        // response.send(전달데이터)
        // 전달할 데이터를 send
})

// 서버를 3142포트로 listen
    // listen(포트, 리스너)
server.listen(3142, function(){
    console.log('서버 실행중..')
})