<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
include '../includes/header.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = strtolower(trim($_POST['email']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        $message = "User created successfully!";
    } else {
        $message = "Error creating user!";
    }
}
?>

<h3>Create User</h3>

<?php if ($message): ?>
<div class="alert alert-info"><?php echo $message; ?></div>
<?php endif; ?>

<div class="card p-4 shadow" style="max-width:500px;">
<form method="POST">

<input class="form-control mt-2" name="name" placeholder="Full Name" required>
<input class="form-control mt-2" name="email" placeholder="Email" required>
<input class="form-control mt-2" type="password" name="password" placeholder="Password" required>

<select class="form-control mt-2" name="role" required>
    <option value="">Select Role</option>
    <option value="student">Student</option>
    <option value="instructor">Instructor</option>
</select>

<button class="btn btn-primary w-100 mt-3">Create User</button>

</form>
</div>

<?php include '../includes/footer.php'; ?>