<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include '../includes/header.php';

$res = $conn->query("SELECT * FROM users");
?>

<h3 class="mb-3">All Users</h3>

<div class="card shadow p-3">
<table class="table table-bordered table-striped">
<tr>
<th>Name</th>
<th>Email</th>
<th>Role</th>
</tr>

<?php while($row = $res->fetch_assoc()): ?>
<tr>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['email']; ?></td>
<td>
<span class="badge bg-<?php echo $row['role']=='admin'?'dark':($row['role']=='instructor'?'primary':'success'); ?>">
<?php echo $row['role']; ?>
</span>
</td>
</tr>
<?php endwhile; ?>

</table>
</div>

<?php include '../includes/footer.php'; ?>