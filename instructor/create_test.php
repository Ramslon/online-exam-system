<?php 
include '../includes/header.php'; 
include '../config/db.php';
include '../includes/permissions.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'instructor') {
    header("Location: ../auth/login.php");
    exit();
}

if (!hasPermission($conn, $_SESSION['role'], 'create_test')) {
    die("Access Denied");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $duration = $_POST['duration'];
    $created_by = $_SESSION['user_id'];

    $conn->query("INSERT INTO tests (title, duration, created_by) 
                  VALUES ('$title','$duration','$created_by')");

    $test_id = $conn->insert_id;
    echo "<div class='alert alert-success'>
    Test created! Your Test ID is: <strong>$test_id</strong>
    </div>";
}
?>

<h3 class="mb-4">Create New Test</h3>

<div class="card p-4 shadow">
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Test Title</label>
            <input type="text" name="title" class="form-control" placeholder="Enter test title" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Duration (seconds)</label>
            <input type="number" name="duration" class="form-control" placeholder="e.g. 120" required>
        </div>

        <button class="btn btn-primary">Create Test</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>