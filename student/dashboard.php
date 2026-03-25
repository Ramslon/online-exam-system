<?php include '../includes/header.php'; 
if ($_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
}
?>
<h2>Student Dashboard</h2>
<a href="take_test.php" class="btn btn-primary">Take Test</a>
<?php include '../includes/footer.php'; ?>