<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// Fetch tests
$tests = $conn->query("SELECT * FROM tests");

// Fetch results
$results = $conn->query("SELECT * FROM results WHERE student_id=$student_id");

// Analytics
$totalTaken = $conn->query("SELECT COUNT(*) as total FROM results WHERE student_id=$student_id")->fetch_assoc()['total'];
$avgScore = $conn->query("SELECT AVG(score) as avg FROM results WHERE student_id=$student_id")->fetch_assoc()['avg'] ?? 0;

// Next test
$nextTest = $conn->query("SELECT * FROM tests ORDER BY created_at DESC LIMIT 1")->fetch_assoc();

// Ranking
$topStudents = $conn->query("
SELECT u.name, AVG(r.score) as avg_score
FROM results r
JOIN users u ON r.student_id = u.id
GROUP BY r.student_id
ORDER BY avg_score DESC
LIMIT 5
");

// Notifications
$notifications = $conn->query("SELECT * FROM notifications ORDER BY created_at DESC LIMIT 5");
?>

<h3 class="mb-4">🎓 Student Dashboard</h3>

<!-- Countdown -->
<div class="card shadow p-3 mb-4 text-center">
<h5>⏱️ Next Exam Countdown</h5>
<h3 id="countdown">Loading...</h3>
</div>

<!-- Stats -->
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

<!-- PDF Button -->
<a href="export_result.php" class="btn btn-danger mb-3">📥 Download Results PDF</a>

<!-- Available Tests -->
<div class="card shadow p-3 mb-4">
<h5>📚 Available Tests</h5>

<table class="table table-bordered">
<tr><th>Test</th><th>Action</th></tr>

<?php while($test = $tests->fetch_assoc()): ?>
<?php $check = $conn->query("SELECT * FROM results WHERE student_id=$student_id AND test_id={$test['id']}"); ?>

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

<!-- Results -->
<div class="card shadow p-3 mb-4">
<h5>📊 My Results</h5>

<table class="table table-striped">
<tr><th>Test</th><th>Score</th><th>Date</th></tr>

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
$row['score']>=70?'success':($row['score']>=50?'warning':'danger')
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

<!-- Ranking -->
<div class="card shadow p-3 mb-4">
<h5>🏆 Top Students</h5>
<table class="table">
<tr><th>#</th><th>Name</th><th>Avg</th></tr>
<?php $rank=1; while($row=$topStudents->fetch_assoc()): ?>
<tr>
<td>#<?= $rank++ ?></td>
<td><?= $row['name'] ?></td>
<td><?= round($row['avg_score'],2) ?>%</td>
</tr>
<?php endwhile; ?>
</table>
</div>

<!-- Notifications -->
<div class="card shadow p-3 mb-4">
<h5>🔔 Notifications</h5>
<ul class="list-group">
<?php while($note=$notifications->fetch_assoc()): ?>
<li class="list-group-item">
<?= $note['message'] ?><br>
<small class="text-muted"><?= $note['created_at'] ?></small>
</li>
<?php endwhile; ?>
</ul>
</div>

<!-- Chart -->
<div class="card shadow p-3">
<h5 class="text-center">📈 Performance</h5>
<canvas id="chart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const scores = [<?php
$resChart = $conn->query("SELECT score FROM results WHERE student_id=$student_id");
while($r=$resChart->fetch_assoc()) echo $r['score'].",";
?>];

new Chart(document.getElementById('chart'), {
type:'line',
data:{
labels:scores.map((_,i)=>"Test "+(i+1)),
datasets:[{label:'Scores',data:scores,borderColor:'#0d6efd'}]
}
});

// Countdown
const examDate = new Date("<?= $nextTest['created_at'] ?>").getTime();

setInterval(()=>{
const now=new Date().getTime();
const diff=examDate-now;

if(diff<=0){
document.getElementById("countdown").innerHTML="Exam Available Now!";
return;
}

const m=Math.floor(diff/(1000*60));
const s=Math.floor((diff%(1000*60))/1000);

document.getElementById("countdown").innerHTML=m+"m "+s+"s";
},1000);
</script>

<?php include '../includes/footer.php'; ?>