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
    $first_password = $_POST['first_password'];
    $second_password = $_POST['second_password'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $sql = "SELECT user_id, password FROM user WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($first_password, $hashed_password)) {
            if($first_password==$second_password){
                $sql = "UPDATE user SET password=? WHERE user_id = ?";
                $stmt1 = $conn->prepare($sql);

                if ($stmt1) {
                    $stmt1->bind_param("ss", $new_password, $user_id);

                    if ($stmt1->execute()) {
                        echo "<script>alert('비밀번호가 성공적으로 수정되었습니다.'); window.location.href = 'user_information.php';</script>";
                    } else {
                        echo "<script>alert('비밀번호 수정에 실패했습니다: " . $stmt1->error . "'); window.history.back();</script>";
                    }

                    $stmt1->close();
                }
            } else {
                echo "<script>alert('데이터베이스 오류: " . $conn->error . "'); window.history.back();</script>";
            }
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
    <title>비밀번호 수정</title>
</head>
<body>
    <form action="../homepage.php">
        <button type="submit">홈페이지</button>
    </form>
    <h2>비밀번호 수정</h2>

    <form method="post" action="">
        <label for="first_password">비밀번호 :</label>
        <input type="password" id="first_password" name="first_password" required><br><br>

        <label for="second_password">비밀번호 확인 :</label>
        <input type="password" id="second_password" name="second_password" required><br><br>

        <label for="new_password">새 비밀번호 :</label>
        <input type="password" id="new_password" name="new_password" required><br><br>

        <button type="submit">내 정보 수정</button>
    </form>
</body>
</html>