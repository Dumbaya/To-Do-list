<?php
session_start();
session_destroy();

echo "<script>alert('로그아웃되었습니다.'); window.location.href = '../homepage.php';</script>";
exit();
?>
