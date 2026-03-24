<?php
session_start();
if ($_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
}
?>
<h1>Student Dashboard</h1>
<a href="take_test.php">Take Test</a>
