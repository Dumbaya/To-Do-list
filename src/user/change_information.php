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

$sql = "SELECT email FROM user WHERE user_id = ?";
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['new_email'])){
        $new_email = $_POST['new_email'];

        // 사용자 정보 확인
        $sql = "UPDATE user SET email=? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $new_email, $user_id);

            if ($stmt->execute()) {
                echo "<script>alert('이메일이 성공적으로 수정되었습니다.'); window.location.href = 'user_information.php';</script>";
            } else {
                echo "<script>alert('이메일 수정에 실패했습니다: " . $stmt->error . "'); window.history.back();</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('데이터베이스 오류: " . $conn->error . "'); window.history.back();</script>";
        }
    }else{
        echo "<script>alert('새 이메일을 입력하세요.'); window.history.back();</script>";
    }

}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>내 정보 수정</title>
</head>
<body>
    <form action="../homepage.php">
        <button type="submit">홈페이지</button>
    </form>
    <h2>사용자 프로필 수정</h2>
    <form method="post" action="">
        <label for="new_email">이메일 :</label>
        <input type="email" id="new_email" name="new_email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required><br><br>
        <button type="submit">내 정보 수정</button>
    </form>
</body>
</html>