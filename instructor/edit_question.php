<?php
session_start();
include '../config/db.php';

if ($_SESSION['role'] != 'instructor') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_id = $_POST['question_id'];
    $question_text = $_POST['question_text'];

    $stmt = $conn->prepare("UPDATE questions SET question_text = ? WHERE id = ?");
    $stmt->bind_param("si", $question_text, $question_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Question updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update question.";
    }
}

header("Location: dashboard.php");
exit();
