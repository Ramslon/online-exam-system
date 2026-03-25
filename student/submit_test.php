<?php include '../config/db.php'; session_start(); ?>

<?php
$answers = $_POST['answers'];
$score = 0;
$student_id = $_SESSION['user_id'];
$test_id = 1;

foreach ($answers as $qid => $oid) {
    $res = $conn->query("SELECT is_correct FROM options WHERE id=$oid");
    $row = $res->fetch_assoc();
    if ($row['is_correct']) $score++;
}

$conn->query("INSERT INTO results (student_id,test_id,score) VALUES ('$student_id','$test_id','$score')");

echo "<h2>Your Score: $score</h2>";
?>