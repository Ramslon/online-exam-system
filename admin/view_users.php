<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include '../includes/header.php';

$res = $conn->query("SELECT * FROM users");
$search = $_GET['search'] ?? '';

if ($search) {
    $res = $conn->query("SELECT * FROM users WHERE name LIKE '%$search%' OR email LIKE '%$search%'");
} else {
    $res = $conn->query("SELECT * FROM users");
}
?>

<h3 class="mb-3">All Users</h3>

<div class="card shadow p-3">
<form method="GET" class="mb-3">
<input name="search" class="form-control" placeholder="Search users...">
</form>
<table class="table table-bordered table-striped">
<tr>
<th>Name</th>
<th>Email</th>
<th>Role</th>
<th>Actions</th>
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
<td>
<a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
  onclick="return confirm('Delete this user?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>

</table>
</div>

<?php include '../includes/footer.php'; ?>