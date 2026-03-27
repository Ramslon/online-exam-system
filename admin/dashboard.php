<?php
session_start();
include '../config/db.php'; // ✅ ADD THIS

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Analytics queries
$totalUsers = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$students = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='student'")->fetch_assoc()['total'];
$instructors = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='instructor'")->fetch_assoc()['total'];

include '../includes/header.php';
?>

<h3 class="mb-4">Admin Dashboard</h3>

<div class="row">

    <div class="col-md-4">
        <div class="card shadow p-3 text-center">
            <h5>➕ Create User</h5>
            <a href="create_user.php" class="btn btn-primary mt-2">Go</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow p-3 text-center">
            <h5>👥 View Users</h5>
            <a href="view_users.php" class="btn btn-success mt-2">Go</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow p-3 text-center">
            <h5>🧾 Activity Logs</h5>
            <a href="logs.php" class="btn btn-dark mt-2">View Logs</a>
        </div>
    </div>

</div>

<div class="row mt-4">

<div class="col-md-4">
<div class="card p-3 shadow text-center">
<h5>Total Users</h5>
<h3><?php echo $totalUsers; ?></h3>
</div>
</div>

<div class="col-md-4">
<div class="card p-3 shadow text-center">
<h5>Students</h5>
<h3><?php echo $students; ?></h3>
</div>
</div>

<div class="col-md-4">
<div class="card p-3 shadow text-center">
<h5>Instructors</h5>
<h3><?php echo $instructors; ?></h3>
</div>
</div>

</div>

<?php include '../includes/footer.php'; ?>