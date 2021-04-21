/*
node.js 어플리케이션 만들기
*/
//필요한 모듈 import하기
var http = require("http");
    // http모듈을 불러오고, 반환되는 http인스턴스를
    // http 변수에 저장한다

// 사용 할 서버 호스트 네임
const hostname = '127.0.0.1';
// 사용 할 서버 포트
const port = 3000; 

// 서버를 만든다 
const server = http.createServer((req, res) => { // 요청이 오면 실행되는 콜백 함수
    res.statusCode = 200; // 응답 상태값 설정
    res.setHeader('Content-Type', 'text/plain'); // 응답 헤더 중 Content-Type 설정
    res.end('Hello, World!\n'); // 응답 데이터 전송 
  });
  
  // 서버를 요청 대기 상태로 만든다 
  server.listen(port, hostname, () => { // 요청 대기가 완료되면 실행되는 콜백 함수 
    // 터미널에 로그를 기록한다 
    console.log(`Server running at http://${hostname}:${port}/`);
});