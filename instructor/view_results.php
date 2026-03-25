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

<!-- RESULTS TABLE -->
<div class="card p-4 shadow mb-4">
    <h5 class="mb-3">Results Table</h5>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Student ID</th>
                <th>Test ID</th>
                <th>Score</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $res = $conn->query("SELECT * FROM results ORDER BY submitted_at DESC");

            while ($row = $res->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['student_id']}</td>
                        <td>{$row['test_id']}</td>
                        <td>{$row['score']}</td>
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