<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Get logs with user names
$res = $conn->query("
    SELECT l.*, u.name 
    FROM activity_logs l
    JOIN users u ON l.user_id = u.id
    ORDER BY l.created_at DESC
");

// Optional: analytics in logs page
$totalUsers = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$students = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='student'")->fetch_assoc()['total'];
$instructors = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='instructor'")->fetch_assoc()['total'];
?>

<h3 class="mb-4">Activity Logs</h3>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card shadow p-3 text-center">
            <h6>Total Users</h6>
            <h3><?php echo $totalUsers; ?></h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow p-3 text-center">
            <h6>Students</h6>
            <h3><?php echo $students; ?></h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow p-3 text-center">
            <h6>Instructors</h6>
            <h3><?php echo $instructors; ?></h3>
        </div>
    </div>
</div>

<div class="card shadow p-3">
<table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
<th>User</th>
<th>Action</th>
<th>Time</th>
</tr>
</thead>
<tbody>
<?php if ($res->num_rows > 0): ?>
    <?php while($row = $res->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['action']; ?></td>
            <td><?php echo $row['created_at']; ?></td>
        </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr>
        <td colspan="3" class="text-center">No activity logs yet</td>
    </tr>
<?php endif; ?>
</tbody>
</table>
</div>

<?php include '../includes/footer.php'; ?>