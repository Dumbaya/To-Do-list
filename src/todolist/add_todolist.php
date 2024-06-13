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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $todo = $_POST['todo'];
    $date = $_POST['date'];

    $sql = "INSERT INTO todolist (todo_user, todo, date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $user_id, $todo, $date);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
