<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include '../config/db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = strtolower(trim($_POST['email']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Check if user already exists
    $check = $conn->prepare("SELECT * FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $message = "User already exists!";
    } else {
        // Insert user
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $role);

        if ($stmt->execute()) {
            $message = "Registration successful! You can now login.";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="height:100vh;">
    <div class="card p-4 shadow" style="width:350px;">
        
        <h4 class="text-center mb-3">Register</h4>

        <!-- Message -->
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input class="form-control mt-2" name="name" placeholder="Full Name" required>
            <input class="form-control mt-2" name="email" placeholder="Email" required>
            <input class="form-control mt-2" type="password" name="password" placeholder="Password" required>

            <select class="form-control mt-2" name="role" required>
                <option value="">Select Role</option>
                <option value="student">Student</option>
                <option value="instructor">Instructor</option>
            </select>

            <button class="btn btn-success w-100 mt-3">Register</button>

            <p class="text-center mt-3">
                Already have an account?
                <a href="login.php">Login here</a>
            </p>
        </form>

    </div>
</div>

</body>
</html>