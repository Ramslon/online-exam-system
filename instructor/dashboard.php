<?php include '../includes/header.php'; ?>
<?php if ($_SESSION['role'] != 'instructor') { header("Location: ../auth/login.php"); exit(); } ?>

<h3 class="mb-4">Instructor Dashboard</h3>
<div class="row g-3">
<div class="col-md-4"><div class="card p-3 shadow"><h5>Create Test</h5><a href="create_test.php" class="btn btn-primary">Open</a></div></div>
<div class="col-md-4"><div class="card p-3 shadow"><h5>Add Questions</h5><a href="add_question.php" class="btn btn-success">Open</a></div></div>
<div class="col-md-4"><div class="card p-3 shadow"><h5>Results</h5><a href="view_results.php" class="btn btn-warning">Open</a></div></div>
</div>

<?php include '../includes/footer.php'; ?>