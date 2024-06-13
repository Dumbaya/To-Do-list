<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 데이터베이스 연결 설정
    $servername = "localhost";
    $username = "root";
    $password = "1234";
    $dbname = "php_study";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 사용자 세션에서 사용자 ID 가져오기
    $user_id = $_SESSION['user_id'];

    // POST로 받은 할 일과 날짜 데이터 가져오기
    $todo = $_POST['todo'];
    $date = $_POST['date'];

    // 다음 할 일 번호 조회
    $next_todo_num = getNextTodoNum($conn, $user_id);

    // 할 일 추가 쿼리
    $sql = "INSERT INTO todolist (todo_num, todo_user, todo, date) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $next_todo_num, $user_id, $todo, $date);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "추가 실패: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "잘못된 접근입니다.";
}

// 다음 할 일 번호를 조회하는 함수
function getNextTodoNum($conn, $user_id) {
    // 마지막 할 일 번호 조회 쿼리
    $sql = "SELECT MAX(todo_num) as max_todo_num FROM todolist WHERE todo_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $next_todo_num = 1; // 기본 값

    if ($row = $result->fetch_assoc()) {
        $next_todo_num = $row['max_todo_num'] + 1;
    }

    $stmt->close();

    return $next_todo_num;
}
?>
