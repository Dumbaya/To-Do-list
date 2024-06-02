<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "php_study";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $user_password = $_POST['password'];
    $email = $_POST['email'];

    $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO user (user_id, password, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $user_id, $hashed_password, $email);

    if ($stmt->execute()) {
        echo "<script>alert('회원가입 성공!'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('회원가입 실패: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>회원가입</title>
</head>
<body>
<h2>회원가입</h2>
<form method="post" action="">
    <label for="user_id">아이디:</label>
    <input type="text" id="user_id" name="user_id" required><br><br>
    <label for="password">비밀번호:</label>
    <input type="password" id="password" name="password" required><br><br>
    <label for="email">이메일:</label>
    <input type="email" id="email" name="email" required><br><br>
    <button type="submit" value="회원가입">
</form>
</body>
</html>
