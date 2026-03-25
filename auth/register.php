<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $conn->query("INSERT INTO users (name,email,password,role) VALUES ('$name','$email','$password','$role')");
    echo "Registered successfully";
}
?>

<form method="POST" class="container mt-5">
<input class="form-control" name="name" placeholder="Name" required>
<input class="form-control mt-2" name="email" placeholder="Email" required>
<input class="form-control mt-2" name="password" placeholder="Password" required>
<select class="form-control mt-2" name="role">
<option value="student">Student</option>
<option value="instructor">Instructor</option>
</select>
<button class="btn btn-success mt-3">Register</button>
</form>
