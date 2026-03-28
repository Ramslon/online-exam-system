<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'];
$res = $conn->query("SELECT * FROM users WHERE id=$id");
$user = $res->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET name=?, role=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $role, $id);
    $stmt->execute();

    $conn->query("INSERT INTO activity_logs (user_id, action) VALUES ({$_SESSION['user_id']}, 'Updated user ID $id')");

    header("Location: view_users.php");
    exit();
}
?>

<h3 class="mb-3">Edit User</h3>

<div class="card shadow p-4" style="max-width:500px;">
<form method="POST">

<label class="mt-2">Name</label>
<input class="form-control" value="<?php echo $user['name']; ?>" name="name" required>

<label class="mt-3">Role</label>
<select class="form-control" name="role" required>
    <option value="student" <?php if($user['role']=='student') echo 'selected'; ?>>Student</option>
    <option value="instructor" <?php if($user['role']=='instructor') echo 'selected'; ?>>Instructor</option>
    <option value="admin" <?php if($user['role']=='admin') echo 'selected'; ?>>Admin</option>
</select>

<button class="btn btn-primary w-100 mt-4">Update User</button>

</form>
</div>

<?php include '../includes/footer.php'; ?>