<?php 
include '../includes/header.php'; 

// Security check
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'instructor') {
    header("Location: ../auth/login.php");
    exit();
}
?>

<h2 class="mb-4">Instructor Dashboard</h2>

<div class="row">
  <div class="col-md-4">
    <div class="card shadow p-3">
      <h5>Create Test</h5>
      <a href="create_test.php" class="btn btn-primary">Go</a>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card shadow p-3">
      <h5>Add Questions</h5>
      <a href="add_question.php" class="btn btn-success">Go</a>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card shadow p-3">
      <h5>View Results</h5>
      <a href="view_results.php" class="btn btn-warning">Go</a>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>

