<?php
include '../includes/header.php';
include '../config/db.php';

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
GROUP BY t.id
");

// Fetch tests with questions for management table
$testsWithQuestions = $conn->query("
SELECT t.id as test_id, t.title, q.id as question_id, q.question_text
FROM tests t
LEFT JOIN questions q ON t.id = q.test_id
ORDER BY t.id, q.id
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
            <h6>Quick Actions</h6>
            <a href="create_test.php" class="btn btn-primary mt-2 w-100">Create Test</a>
            <a href="view_results.php" class="btn btn-success mt-2 w-100">View Results</a>
            <a href="add_questions.php" class="btn btn-warning mt-2 w-100">Add Questions</a>
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

<!-- Questions Management Table -->
<div class="card shadow p-3 mb-4">
    <h5>✏️ Manage Questions</h5>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Test</th>
                <th>Question</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $testsWithQuestions->fetch_assoc()): ?>
            <tr>
                <td><?= $row['title'] ?></td>
                <td><?= $row['question_text'] ?: 'No questions yet' ?></td>
                <td>
                    <?php if($row['question_id']): ?>
                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editQuestionModal<?= $row['question_id'] ?>">Edit</button>
                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteQuestionModal<?= $row['question_id'] ?>">Delete</button>

                    <!-- Edit Modal -->
<div class="modal fade" id="editQuestionModal<?= $row['question_id'] ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="edit_question.php">
        <div class="modal-header">
          <h5 class="modal-title">Edit Question</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <input type="hidden" name="question_id" value="<?= $row['question_id'] ?>">

          <!-- Question -->
          <div class="mb-3">
            <label>Question</label>
            <textarea class="form-control" name="question_text" required><?= $row['question_text'] ?></textarea>
          </div>

          <!-- Options -->
          <?php
          $opts = $conn->query("SELECT * FROM options WHERE question_id={$row['question_id']}");
          $i = 0;
          while($opt = $opts->fetch_assoc()):
          ?>
          <div class="input-group mb-2">
            <span class="input-group-text">
              <input type="radio" name="correct" value="<?= $i ?>" <?= $opt['is_correct'] ? 'checked' : '' ?> required>
            </span>
            <input type="text" class="form-control" name="options[]" value="<?= $opt['option_text'] ?>" required>
            <input type="hidden" name="option_ids[]" value="<?= $opt['id'] ?>">
          </div>
          <?php $i++; endwhile; ?>

          <small class="text-muted">Select the correct option</small>

        </div>

        <div class="modal-footer">
          <button class="btn btn-primary">Save Changes</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>

      </form>
    </div>
  </div>
</div>
                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteQuestionModal<?= $row['question_id'] ?>" tabindex="-1">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <form method="POST" action="delete_question.php">
                          <div class="modal-header">
                            <h5 class="modal-title">Delete Question</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <div class="modal-body">
                            <p>Are you sure you want to delete this question?</p>
                            <input type="hidden" name="question_id" value="<?= $row['question_id'] ?>">
                          </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Delete</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                          </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    <?php else: ?>
                        <span class="text-muted">—</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = [
<?php while($c = $chartData->fetch_assoc()) echo "'".$c['title']."',"; ?>
];

const data = [
<?php
$chartData->data_seek(0);
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