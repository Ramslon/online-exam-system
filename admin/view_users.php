<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Search
$search = $_GET['search'] ?? '';

if ($search) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE name LIKE ? OR email LIKE ?");
    $searchParam = "%$search%";
    $stmt->bind_param("ss", $searchParam, $searchParam);
    $stmt->execute();
    $res = $stmt->get_result();
} else {
    $res = $conn->query("SELECT * FROM users");
}
?>

<h3 class="mb-4">User Management</h3>

<!-- Search Bar -->
<form method="GET" class="mb-3 d-flex">
    <input type="text" name="search" class="form-control me-2" placeholder="Search by name or email..." value="<?php echo htmlspecialchars($search); ?>">
    <button class="btn btn-secondary">Search</button>
</form>

<div class="card shadow p-3">

<table class="table table-bordered table-striped align-middle">
<thead class="table-dark">
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Role</th>
<th>Actions</th>
</tr>
</thead>

<tbody>

<?php if ($res->num_rows > 0): ?>
<?php while($row = $res->fetch_assoc()): ?>

<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['email']; ?></td>

<td>
<span class="badge bg-<?php echo $row['role']=='admin'?'dark':($row['role']=='instructor'?'primary':'success'); ?>">
<?php echo ucfirst($row['role']); ?>
</span>
</td>

<td>
<!-- Edit Button -->
<button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">
    Edit
</button>

<!-- Delete Button -->
<button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $row['id']; ?>">
    Delete
</button>
</td>
</tr>

<!-- ✏️ EDIT MODAL -->
<div class="modal fade" id="editModal<?php echo $row['id']; ?>">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST" action="update_user.php">

<div class="modal-header">
<h5 class="modal-title">Edit User</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

<label>Name</label>
<input class="form-control" name="name" value="<?php echo $row['name']; ?>" required>

<label class="mt-2">Role</label>
<select class="form-control" name="role">
    <option value="student" <?php if($row['role']=='student') echo 'selected'; ?>>Student</option>
    <option value="instructor" <?php if($row['role']=='instructor') echo 'selected'; ?>>Instructor</option>
    <option value="admin" <?php if($row['role']=='admin') echo 'selected'; ?>>Admin</option>
</select>

</div>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
<button class="btn btn-primary">Update</button>
</div>

</form>

</div>
</div>
</div>

<!-- ❌ DELETE MODAL -->
<div class="modal fade" id="deleteModal<?php echo $row['id']; ?>">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST" action="delete_user.php">

<div class="modal-header">
<h5 class="modal-title text-danger">Delete User</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<p>Are you sure you want to delete <strong><?php echo $row['name']; ?></strong>?</p>
<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
</div>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
<button class="btn btn-danger">Delete</button>
</div>

</form>

</div>
</div>
</div>

<?php endwhile; ?>
<?php else: ?>

<tr>
<td colspan="5" class="text-center">No users found</td>
</tr>

<?php endif; ?>

</tbody>
</table>

</div>

<?php include '../includes/footer.php'; ?>