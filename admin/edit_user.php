<?php
session_start();
include '../config/db.php';
include '../includes/log.php';

if ($_SESSION['role'] != 'admin') {
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

    logAction($conn, $_SESSION['user_id'], "Updated user ID $id");

    header("Location: view_users.php");
    exit();
}
?>

<form method="POST">
<input value="<?php echo $user['name']; ?>" name="name">
<select name="role">
<option value="student">Student</option>
<option value="instructor">Instructor</option>
<option value="admin">Admin</option>
</select>
<button>Update</button>
</form>