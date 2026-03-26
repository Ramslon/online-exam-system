<?php include '../includes/header.php';
if ($_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
}
?>

<h3>Student Dashboard</h3>
<a href="take_test.php" class="btn btn-primary">Take Test</a>

<?php include '../includes/footer.php'; ?>