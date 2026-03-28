<?php
session_start();
include '../config/db.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_POST['id'];

$conn->query("DELETE FROM users WHERE id=$id");

// Log
$conn->query("INSERT INTO activity_logs (user_id, action) VALUES ({$_SESSION['user_id']}, 'Deleted user ID $id')");

header("Location: view_users.php");
exit();