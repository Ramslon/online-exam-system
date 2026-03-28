<?php
include '../includes/header.php';
include '../config/db.php';

// Ensure instructor access
if ($_SESSION['role'] != 'instructor') {
    header("Location: ../auth/login.php");
    exit();
}

// Stats
$totalTests = $conn->query("SELECT COUNT(*) as total FROM tests")->fetch_assoc()['total'];
$totalStudents = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='student'")->fetch_assoc()['total'];
$totalAttempts = $conn->query("SELECT COUNT(*) as total FROM results")->fetch_assoc()['total'];

// Recent activity (latest 5 submissions)
$activities = $conn->query("
SELECT r.*, u.name, t.title 
FROM results r
JOIN users u ON r.student_id = u.id
JOIN tests t ON r.test_id = t.id
ORDER BY r.id DESC LIMIT 5
");

// Chart data (Average score per test)
$chartData = $conn->query("
SELECT t.title, AVG(r.score) as avg_score
FROM results r
JOIN tests t ON r.test_id = t.id
GROUP BY r.id
");
?>

<h3 class="mb-4">📘 Instructor Dashboard</h3>

<!-- Stats Cards -->
<div class="row mb-4">

<div class="col-md-3">
<div class="card shadow p-3 text-center">
<h6>Total Tests</h6>
<h3 id="totalTests"><?= $totalTests ?></h3>
</div>
</div>

<div class="col-md-3">
<div class="card shadow p-3 text-center">
<h6>Total Students</h6>
<h3 id="totalStudents"><?= $totalStudents ?></h3>
</div>
</div>

<div class="col-md-3">
<div class="card shadow p-3 text-center">
<h6>Total Attempts</h6>
<h3 id="totalAttempts"><?= $totalAttempts ?></h3>
</div>
</div>

<div class="col-md-3">
<div class="card shadow p-3 text-center">
<h6>➕ Quick Actions</h6>
<a href="create_test.php" class="btn btn-primary mt-2">Create Test</a>
<a href="view_results.php" class="btn btn-success mt-2">View Results</a>
<a href="add_questions.php" class="btn btn-warning mt-2">Add Questions</a>
</div>
</div>

</div>

<!-- Recent Student Activity -->
<div class="card shadow p-3 mb-4">
<h5>🧠 Recent Student Activity</h5>
<ul class="list-group" id="activityFeed">
<?php while($row = $activities->fetch_assoc()): ?>
<li class="list-group-item">
<strong><?= $row['name'] ?></strong> took 
<strong><?= $row['title'] ?></strong> → 
<span class="badge bg-info"><?= $row['score'] ?>%</span>
</li>
<?php endwhile; ?>
</ul>
</div>

<!-- Chart: Average Score per Test -->
<div class="card shadow p-3 mb-4">
<h5 class="text-center">📈 Test Performance</h5>
<canvas id="chart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = [
<?php while($c = $chartData->fetch_assoc()) echo "'".$c['title']."',"; ?>
];

const data = [
<?php
$chartData->data_seek(0); // reset pointer
while($c = $chartData->fetch_assoc()) echo $c['avg_score'].",";
?>
];

new Chart(document.getElementById('chart'), {
type: 'bar',
data: {
labels: labels,
datasets: [{
label: 'Average Score',
data: data,
backgroundColor: '#0d6efd'
}]
}
});
</script>

<!-- Real-time stats -->
<script>
setInterval(() => {
fetch('live_data.php')
.then(res => res.json())
.then(data => {
document.getElementById('totalTests').innerText = data.tests;
document.getElementById('totalStudents').innerText = data.students;
document.getElementById('totalAttempts').innerText = data.attempts;
});
}, 5000);
</script>

<!-- Real-time activity feed -->
<script>
setInterval(() => {
fetch('live_activity.php')
.then(res => res.text())
.then(html => {
document.getElementById('activityFeed').innerHTML = html;
});
}, 5000);
</script>

<?php include '../includes/footer.php'; ?>