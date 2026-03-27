<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
?>

<h2>Admin Dashboard</h2>

<a href="create_user.php">➕ Add User</a><br><br>
<a href="view_users.php">👥 View Users</a>