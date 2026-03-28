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
    $options = $_POST['options'];
    $option_ids = $_POST['option_ids'];
    $correct = $_POST['correct'];

    // 1. Update question text
    $stmt = $conn->prepare("UPDATE questions SET question_text=? WHERE id=?");
    $stmt->bind_param("si", $question_text, $question_id);
    $stmt->execute();

    // 2. Update options
    for ($i = 0; $i < count($options); $i++) {
        $is_correct = ($i == $correct) ? 1 : 0;

        $stmt = $conn->prepare("UPDATE options SET option_text=?, is_correct=? WHERE id=?");
        $stmt->bind_param("sii", $options[$i], $is_correct, $option_ids[$i]);
        $stmt->execute();
    }

    $_SESSION['success'] = "Question updated successfully!";
}

header("Location: dashboard.php");
exit();