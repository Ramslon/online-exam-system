<?php
session_start();
include '../config/db.php';

if ($_SESSION['role'] != 'instructor') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_id = $_POST['question_id'];

    // Delete associated options first
    $stmt1 = $conn->prepare("DELETE FROM options WHERE question_id = ?");
    $stmt1->bind_param("i", $question_id);
    $stmt1->execute();

    // Delete the question
    $stmt2 = $conn->prepare("DELETE FROM questions WHERE id = ?");
    $stmt2->bind_param("i", $question_id);

    if ($stmt2->execute()) {
        $_SESSION['success'] = "Question deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete question.";
    }
}

header("Location: dashboard.php");
exit();