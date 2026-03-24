<?php
include '../config/db.php';
session_start();

$answers = $_POST['answers'];
$score = 0;

foreach ($answers as $qid => $oid) {
    $res = $conn->query("SELECT is_correct FROM options WHERE id=$oid");
    $row = $res->fetch_assoc();
    if ($row['is_correct']) $score++;
}

echo "Your Score: " . $score;
?>