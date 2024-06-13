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

    $user_id = $_SESSION['user_id'];

    $data = json_decode(file_get_contents("php://input"));
    $todo_num = $data->todo_num;

    // 할 일 삭제 쿼리
    $sql = "DELETE FROM todolist WHERE todo_num = ? AND todo_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $todo_num, $user_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "삭제 실패: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "잘못된 접근입니다.";
}
?>
