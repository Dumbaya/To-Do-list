<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "php_study";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM todolist WHERE todo_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$todos = [];
while ($row = $result->fetch_assoc()) {
    $todos[] = $row;
}

$stmt->close();
$conn->close();

// JSON 형식으로 반환
header('Content-Type: application/json');
echo json_encode($todos);
?>
