<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
include '../includes/header.php';
?>

<h3 class="mb-4">Admin Dashboard</h3>

<div class="row">

    <div class="col-md-4">
        <div class="card shadow p-3 text-center">
            <h5>➕ Create User</h5>
            <a href="create_user.php" class="btn btn-primary mt-2">Go</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow p-3 text-center">
            <h5>👥 View Users</h5>
            <a href="view_users.php" class="btn btn-success mt-2">Go</a>
        </div>
    </div>

</div>

<?php include '../includes/footer.php'; ?>