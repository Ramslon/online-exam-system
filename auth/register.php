<?php include '../config/db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $sql = "INSERT INTO users (name,email,password,role) 
            VALUES ('$name','$email','$password','$role')";

    echo "<script>
    alert('Registered successfully');
    window.location.href='login.php';
    </script>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }

?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container d-flex justify-content-center align-items-center" style="height:100vh;">
<div class="card p-4 shadow" style="width:350px;">
<h4 class="text-center">Register</h4>
<form method="POST">
<input class="form-control mt-2" name="name" placeholder="Name" required>
<input class="form-control mt-2" name="email" placeholder="Email" required>
<input class="form-control mt-2" name="password" placeholder="Password" required>
<select class="form-control mt-2" name="role">
<option value="student">Student</option>
<option value="instructor">Instructor</option>
</select>
<button class="btn btn-success w-100 mt-3">Register</button>
<div class="mt-3">
    <a href="login.php" class="btn btn-outline-primary w-100">
        Already have an account? Login
    </a>
</div>
</form>
</div>
</div>