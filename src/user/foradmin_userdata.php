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


$sql = "SELECT id, user_id, email, roll FROM user";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html>
<head>
    <title>관리자 전용 유저데이터</title>
</head>
<body>
<form action="../homepage.php">
    <button type="submit">홈페이지</button>
</form>
<h2>사용자 프로필</h2>
<table>
    <thead>
        <tr>
            <th>유저 번호</th>
            <th>아이디</th>
            <th>이메일</th>
            <th>역할</th>
        </tr>
    </thead>
    <tbody>
        <?php if($result->num_rows>0){
            while($user_data=$result->fetch_assoc()){
                echo "<tr>";
                echo "<td>" . htmlspecialchars($user_data['id'] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($user_data['user_id'] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($user_data['email'] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($user_data['roll'] ?? '') . "</td>";
                echo "</tr>";
            }
            }else{
                echo "<tr><td colspan='4'>사용자 정보를 가져올 수 없습니다.</td></tr>";
            }
            $stmt->close();
            $conn->close();
        ?>
    </tbody>
</table>

</body>
</html>