<?php
session_start();
if ($_SESSION['role'] != 'instructor') {
    header("Location: ../auth/login.php");
}
?>
<h1>Instructor Dashboard</h1>
<a href="create_test.php">Create Test</a>