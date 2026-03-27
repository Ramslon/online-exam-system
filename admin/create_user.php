<?php
session_start();
include '../config/db.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

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

<?php if ($message) echo $message; ?>

<form method="POST">
<input name="name" placeholder="Name" required><br>
<input name="email" placeholder="Email" required><br>
<input type="password" name="password" placeholder="Password" required><br>

<select name="role">
    <option value="student">Student</option>
    <option value="instructor">Instructor</option>
</select><br><br>

<button>Create User</button>
</form>