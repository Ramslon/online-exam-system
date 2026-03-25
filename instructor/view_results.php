<?php 
include '../includes/header.php'; 
include '../config/db.php';

// Security check
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'instructor') {
    header("Location: ../auth/login.php");
    exit();
}
?>

<h3 class="mb-4">Student Results & Analytics</h3>
  <?php
  $avg = $conn->query("SELECT AVG(score) as avg_score FROM results")->fetch_assoc();
   ?>

  <div class="alert alert-secondary">
    Average Score: <?php echo round($avg['avg_score'], 2); ?>
  </div>

<!-- RESULTS TABLE -->
<div class="table-responsive">
    <h5 class="mb-3">Results Table</h5>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
        <tr>
          <th>Student Name</th>
          <th>Email</th>
          <th>Test</th>
          <th>Score</th>
          <th>Date</th>
        </tr>
        </thead>
        <tbody>
            <?php
            $res = $conn->query("
              SELECT 
                r.*, 
                u.name, 
                u.email, 
                t.title AS test_title
              FROM results r
              JOIN users u ON r.student_id = u.id
              JOIN tests t ON r.test_id = t.id
              ORDER BY r.submitted_at DESC
            ");
            if ($res->num_rows == 0) {
             echo "<div class='alert alert-info'>No results available yet.</div>";
             }
            while ($row = $res->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['test_title']}</td>
                        <td><span class='badge bg-success'>{$row['score']}</span></td>
                        <td>{$row['submitted_at']}</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- ANALYTICS CHART -->
<div class="card p-4 shadow">
    <h5 class="mb-3">Performance Analytics</h5>

    <?php
    $scores = [];
    $query = $conn->query("SELECT score FROM results");

    while ($row = $query->fetch_assoc()) {
        $scores[] = $row['score'];
    }
    ?>

    <canvas id="chart"></canvas>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const chartData = {
    labels: [<?php echo implode(',', range(1, count($scores))); ?>],
    datasets: [{
        label: 'Scores',
        data: [<?php echo implode(',', $scores); ?>],
        borderWidth: 2
    }]
};

new Chart(document.getElementById('chart'), {
    type: 'bar',
    data: chartData
});
</script>

<?php include '../includes/footer.php'; ?>