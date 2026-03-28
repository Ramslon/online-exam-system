<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// Fetch available tests
$tests = $conn->query("SELECT * FROM tests");

// Fetch results
$results = $conn->query("SELECT * FROM results WHERE student_id = $student_id");

// Analytics
$totalTaken = $conn->query("SELECT COUNT(*) as total FROM results WHERE student_id = $student_id")->fetch_assoc()['total'];
$avgScore = $conn->query("SELECT AVG(score) as avg FROM results WHERE student_id = $student_id")->fetch_assoc()['avg'] ?? 0;
?>

<h3 class="mb-4">🎓 Student Dashboard</h3>

<!-- STATS -->
<div class="row mb-4">

<div class="col-md-4">
<div class="card shadow p-3 text-center">
<h6>Tests Taken</h6>
<h3><?= $totalTaken ?></h3>
</div>
</div>

<div class="col-md-4">
<div class="card shadow p-3 text-center">
<h6>Average Score</h6>
<h3><?= round($avgScore,2) ?>%</h3>
</div>
</div>

<div class="col-md-4">
<div class="card shadow p-3 text-center">
<h6>Status</h6>
<h3 class="text-success">Active</h3>
</div>
</div>

</div>

<!-- AVAILABLE TESTS -->
<div class="card shadow p-3 mb-4">
<h5>📚 Available Tests</h5>

<table class="table table-bordered mt-2">
<tr>
<th>Test</th>
<th>Action</th>
</tr>

<?php while($test = $tests->fetch_assoc()): ?>

<?php
$check = $conn->query("SELECT * FROM results WHERE student_id=$student_id AND test_id={$test['id']}");
?>

<tr>
<td><?= $test['title'] ?></td>

<td>
<?php if($check->num_rows > 0): ?>
<span class="badge bg-success">Completed</span>
<?php else: ?>
<a href="take_test.php?id=<?= $test['id'] ?>" class="btn btn-primary btn-sm">Start</a>
<?php endif; ?>
</td>

</tr>

<?php endwhile; ?>

</table>
</div>

<!-- RESULTS -->
<div class="card shadow p-3 mb-4">
<h5>📊 My Results</h5>

<table class="table table-striped mt-2">
<tr>
<th>Test</th>
<th>Score</th>
<th>Date</th>
</tr>

<?php if ($results->num_rows > 0): ?>
<?php while($row = $results->fetch_assoc()): ?>
<tr>
<td>
<?php
$t = $conn->query("SELECT title FROM tests WHERE id={$row['test_id']}")->fetch_assoc();
echo $t['title'];
?>
</td>

<td>
<span class="badge bg-<?=
$row['score'] >= 70 ? 'success' :
($row['score'] >= 50 ? 'warning' : 'danger')
?>">
<?= $row['score'] ?>%
</span>
</td>

<td><?= $row['created_at'] ?></td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="3" class="text-center">No results yet</td></tr>
<?php endif; ?>

</table>
</div>

<!-- PERFORMANCE CHART -->
<div class="card shadow p-3">
<h5 class="text-center">📈 Performance Overview</h5>
<canvas id="performanceChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const scores = [
<?php
$resChart = $conn->query("SELECT score FROM results WHERE student_id=$student_id");
while($r = $resChart->fetch_assoc()){
    echo $r['score'] . ",";
}
?>
];

new Chart(document.getElementById('performanceChart'), {
    type: 'line',
    data: {
        labels: scores.map((_, i) => "Test " + (i+1)),
        datasets: [{
            label: 'Scores',
            data: scores,
            borderColor: '#0d6efd',
            fill: false
        }]
    }
});
</script>

<?php include '../includes/footer.php'; ?>