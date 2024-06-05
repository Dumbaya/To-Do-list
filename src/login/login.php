<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "php_study";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $user_password = $_POST['password'];

    // 사용자 정보 확인
    $sql = "SELECT user_id, password FROM user WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($user_password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            echo "<script>alert('로그인 성공!'); window.location.href = '../homepage.php';</script>";
        } else {
            echo "<script>alert('잘못된 비밀번호입니다.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('존재하지 않는 사용자입니다.'); window.history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>로그인</title>
</head>
<body>
<h2>로그인</h2>
<form method="post" action="">
    <label for="user_id">아이디:</label>
    <input type="text" id="user_id" name="user_id" required><br><br>
    <label for="password">비밀번호:</label>
    <input type="password" id="password" name="password" required><br><br>
    <button type="submit">로그인
</form>
<form action="signup.php">
    <button type="submit">회원가입</button>
</form>
</body>
</html>
