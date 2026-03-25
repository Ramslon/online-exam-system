<?php session_start(); include '../config/db.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $res = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'instructor') {
                header("Location: ../instructor/dashboard.php");
            } else {
                header("Location: ../student/dashboard.php");
            }
        }
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container d-flex justify-content-center align-items-center" style="height:100vh;">
<div class="card p-4 shadow" style="width:350px;">
<h4 class="text-center">Login</h4>
<form method="POST">
<input class="form-control mt-2" name="email" placeholder="Email" required>
<input class="form-control mt-2" type="password" name="password" placeholder="Password" required>
<button class="btn btn-primary w-100 mt-3">Login</button>
</form>
</div>
</div>