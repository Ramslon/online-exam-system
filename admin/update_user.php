<?php
session_start();
include '../config/db.php';
include '../includes/permissions.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
} 

if (!hasPermission($conn, $_SESSION['role'], 'update_user')) {
    die("Access Denied");
}

$id = $_POST['id'];
$name = $_POST['name'];
$role = $_POST['role'];

$stmt = $conn->prepare("UPDATE users SET name=?, role=? WHERE id=?");
$stmt->bind_param("ssi", $name, $role, $id);
$stmt->execute();

// Log
$conn->query("INSERT INTO activity_logs (user_id, action) VALUES ({$_SESSION['user_id']}, 'Updated user ID $id')");

header("Location: view_users.php");
exit();