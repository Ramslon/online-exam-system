<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Analytics queries
$totalUsers = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$students = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='student'")->fetch_assoc()['total'];
$instructors = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='instructor'")->fetch_assoc()['total'];

// Optional: get number of logs
$totalLogs = $conn->query("SELECT COUNT(*) as total FROM activity_logs")->fetch_assoc()['total'];
?>

<h3 class="mb-4">Admin Dashboard</h3>

<div class="row mb-4">
    <!-- Quick action cards -->
    <div class="col-md-3">
        <div class="card shadow p-3 text-center">
            <h5>➕ Create User</h5>
            <a href="create_user.php" class="btn btn-primary mt-2 w-100">Go</a>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow p-3 text-center">
            <h5>👥 View Users</h5>
            <a href="view_users.php" class="btn btn-success mt-2 w-100">Go</a>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow p-3 text-center">
            <h5>🧾 Activity Logs</h5>
            <a href="logs.php" class="btn btn-dark mt-2 w-100">Go</a>
        </div>
    </div>
</div>

<h4 class="mb-3">Analytics</h4>
<div class="row">
    <div class="col-md-3">
        <div class="card p-3 shadow text-center">
            <h6>Total Users</h6>
            <h3><?php echo $totalUsers; ?></h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 shadow text-center">
            <h6>Students</h6>
            <h3><?php echo $students; ?></h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 shadow text-center">
            <h6>Instructors</h6>
            <h3><?php echo $instructors; ?></h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 shadow text-center">
            <h6>Total Logs</h6>
            <h3><?php echo $totalLogs; ?></h3>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>