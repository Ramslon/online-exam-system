<?php
session_start();
include '../config/db.php';
include '../includes/header.php';
include '../includes/permissions.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if (!hasPermission($conn, $_SESSION['role'], 'manage_users')) {
    die("Access Denied");
}

// Analytics queries
$totalUsers = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$students = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='student'")->fetch_assoc()['total'];
$instructors = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='instructor'")->fetch_assoc()['total'];
$totalLogs = $conn->query("SELECT COUNT(*) as total FROM activity_logs")->fetch_assoc()['total'];
?>

<h3 class="mb-4">Admin Dashboard</h3>

<div class="row mb-4">
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
<div class="row mb-4">
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

<!-- Charts -->
<div class="row">
    <div class="col-md-6">
        <div class="card shadow p-3">
            <h6 class="mb-3 text-center">User Roles Distribution</h6>
            <canvas id="rolesChart"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow p-3">
            <h6 class="mb-3 text-center">Student vs Instructor Growth</h6>
            <canvas id="growthChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const rolesData = {
    labels: ['Students', 'Instructors'],
    datasets: [{
        label: 'Users',
        data: [<?php echo $students; ?>, <?php echo $instructors; ?>],
        backgroundColor: ['#0d6efd', '#198754'],
    }]
};
new Chart(document.getElementById('rolesChart'), { type: 'doughnut', data: rolesData });

const growthData = {
    labels: ['Total Users', 'Students', 'Instructors'],
    datasets: [{
        label: 'Count',
        data: [<?php echo $totalUsers; ?>, <?php echo $students; ?>, <?php echo $instructors; ?>],
        backgroundColor: ['#6c757d', '#0d6efd', '#198754']
    }]
};
new Chart(document.getElementById('growthChart'), { type: 'bar', data: growthData, options:{ responsive:true, plugins:{legend:{display:false}} } });
</script>

<?php include '../includes/footer.php'; ?>