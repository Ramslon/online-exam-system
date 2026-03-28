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

<form method="GET" class="mb-3 d-flex">
    <input type="text" name="search" class="form-control me-2"
        placeholder="Search users..."
        value="<?php echo htmlspecialchars($search); ?>">
    <button class="btn btn-secondary">Search</button>
</form>

<div class="card shadow p-3">

<table class="table table-bordered table-striped">
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
<td><?= $row['id'] ?></td>
<td><?= $row['name'] ?></td>
<td><?= $row['email'] ?></td>

<td>
<span class="badge bg-<?=
$row['role']=='admin'?'dark':
($row['role']=='instructor'?'primary':'success')
?>">
<?= ucfirst($row['role']) ?>
</span>
</td>

<td>
<button class="btn btn-warning btn-sm editBtn"
data-id="<?= $row['id'] ?>"
data-name="<?= $row['name'] ?>"
data-role="<?= $row['role'] ?>">
Edit
</button>

<button class="btn btn-danger btn-sm deleteBtn"
data-id="<?= $row['id'] ?>"
data-name="<?= $row['name'] ?>">
Delete
</button>
</td>

</tr>

<?php endwhile; ?>
<?php else: ?>

<tr>
<td colspan="5" class="text-center">No users found</td>
</tr>

<?php endif; ?>

</tbody>
</table>
</div>

<!-- ================= EDIT MODAL ================= -->
<div class="modal fade" id="editModal">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST" action="update_user.php">

<div class="modal-header">
<h5>Edit User</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<input type="hidden" name="id" id="edit_id">

<label>Name</label>
<input class="form-control" name="name" id="edit_name" required>

<label class="mt-2">Role</label>
<select class="form-control" name="role" id="edit_role">
<option value="student">Student</option>
<option value="instructor">Instructor</option>
<option value="admin">Admin</option>
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

<!-- ================= DELETE MODAL ================= -->
<div class="modal fade" id="deleteModal">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST" action="delete_user.php">

<div class="modal-header">
<h5 class="text-danger">Delete User</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<p id="delete_text"></p>
<input type="hidden" name="id" id="delete_id">
</div>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
<button class="btn btn-danger">Delete</button>
</div>

</form>

</div>
</div>
</div>

<!-- ================= JAVASCRIPT ================= -->
<script>
const editModal = new bootstrap.Modal(document.getElementById('editModal'));
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

document.querySelectorAll('.editBtn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('edit_id').value = btn.dataset.id;
        document.getElementById('edit_name').value = btn.dataset.name;
        document.getElementById('edit_role').value = btn.dataset.role;
        editModal.show();
    });
});

document.querySelectorAll('.deleteBtn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('delete_id').value = btn.dataset.id;
        document.getElementById('delete_text').innerText =
            "Are you sure you want to delete " + btn.dataset.name + "?";
        deleteModal.show();
    });
});
</script>

<?php include '../includes/footer.php'; ?>