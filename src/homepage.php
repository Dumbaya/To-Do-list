<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>홈페이지</title>
</head>
<body>
<?php if (isset($_SESSION['user_id'])): ?>
    <h2>환영합니다, <?php echo htmlspecialchars($_SESSION['user_id']); ?>님!</h2>
    <p>이곳은 로그인 후 접근 가능한 홈페이지입니다.</p>
    <form action="login/logout.php">
        <button type="submit">로그아웃</button>
    </form>
    <form action="user/user_information.php">
        <button type="submit">내 정보</button>
    </form>
    <form action="todolist/todolist.php">
        <button type="submit">To Do List</button>
    </form>
<?php else: ?>
    <h2>로그인이 필요합니다</h2>
    <p>로그인 후에 이용할 수 있습니다.</p>
    <form action="login/login.php">
        <button type="submit">로그인</button>
    </form>
<?php endif; ?>
</body>
</html>