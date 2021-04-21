/*
Node.js Application 만들기
*/

// 1. 필요한 모듈 import하기
    // 필요한 모듈 불러오기 = require 명령어
var http = require('http');
    // http모듈을 불러오고 반환되는 http인스턴스를 http변수에 저장

// 2. 서버 생성하기
http.createServer(function(request,response){
    /*
    HTTP 헤더 전송
    HTTP Status : 200 : oxk
    content type : text/plain
    */
   response.writeHead(200,{'Content-Type': 'text/plain'});

   /*
   response body를 "hello world"로 설정
   */
    response.end("Hello World\n");
}).listen(8081);

console.log("Server running at /http://13.209.234.165:8081");
