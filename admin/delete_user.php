<?php
session_start();
include '../config/db.php';
include '../includes/log.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'];

$conn->query("DELETE FROM users WHERE id=$id");

logAction($conn, $_SESSION['user_id'], "Deleted user ID $id");

header("Location: view_users.php");
exit();
?>