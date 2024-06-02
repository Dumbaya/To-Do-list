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

$sql = "SELECT user_id, email FROM user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
} else {
    echo "사용자 정보를 가져올 수 없습니다.";
    exit();
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>내 정보</title>
</head>
<body>
    <form action="../homepage.php">
        <button type="submit">홈페이지</button>
    </form>
    <h2>사용자 프로필</h2>
    <p>아이디: <?php echo htmlspecialchars($user_data['user_id']); ?></p>
    <p>이메일 : <?php echo htmlspecialchars($user_data['email']); ?></p>
    <form action="change_information.php">
        <button type="submit">내 정보 수정</button>
    </form>
    <form action="change_password.php">
        <button type="submit">비밀번호 수정</button>
    </form>
</body>
</html>