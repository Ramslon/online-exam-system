<?php
session_start();
include '../config/db.php';
include '../includes/permissions.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if (!hasPermission($conn, $_SESSION['role'], 'manage_users')) {
    die("Access Denied");
}

$id = $_POST['id'];

$conn->query("DELETE FROM users WHERE id=$id");

// Log
$conn->query("INSERT INTO activity_logs (user_id, action) VALUES ({$_SESSION['user_id']}, 'Deleted user ID $id')");

header("Location: view_users.php");
exit();