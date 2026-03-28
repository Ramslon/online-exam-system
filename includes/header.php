<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Online Exam System</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="https://cdn-icons-png.flaticon.com/512/3135/3135755.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f4f6f9; }
.card { border-radius: 12px; }
.card:hover {
    transform: scale(1.02);
    transition: 0.2s;
}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Exam System</a>
    <div>
      <?php if(isset($_SESSION['role'])) { ?>
        <span class="text-white me-3">Welcome, <?php echo $_SESSION['role']; ?></span>
        <a href="../auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
        <?php } ?>
    </div>
  </div>
</nav>

<div class="container mt-4">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>