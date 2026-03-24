<?php
include '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $duration = $_POST['duration'];
    $created_by = $_SESSION['user_id'];

    $conn->query("INSERT INTO tests (title, duration, created_by) VALUES ('$title','$duration','$created_by')");
    echo "Test created";
}
?>

<form method="POST">
<input name="title" placeholder="Test Title" required>
<input name="duration" placeholder="Duration (seconds)" required>
<button>Create</button>
</form>