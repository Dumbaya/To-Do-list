<?php
// MySQL 서버 정보 설정
$servername = "localhost"; // MySQL 호스트 주소
$username = "root"; // MySQL 사용자 이름
$password = "1234"; // MySQL 비밀번호
$dbname = "php_study"; // 사용할 데이터베이스 이름

// MySQL 연결 생성
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
