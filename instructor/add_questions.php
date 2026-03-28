<?php
session_start();
include '../config/db.php';

if ($_SESSION['role'] != 'instructor') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch all tests for dropdown
$tests = $conn->query("SELECT id, title FROM tests");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $test_id = $_POST['test_id'];
    $question_text = $_POST['question_text'];
    $options = $_POST['options']; // array of 4 options
    $correct = $_POST['correct']; // index of correct option (0-3)

    // Insert question
    $stmt = $conn->prepare("INSERT INTO questions (test_id, question_text) VALUES (?, ?)");
    $stmt->bind_param("is", $test_id, $question_text);
    $stmt->execute();
    $question_id = $conn->insert_id;

    // Insert options
    for ($i = 0; $i < 4; $i++) {
        $is_correct = ($i == $correct) ? 1 : 0;
        $stmt_opt = $conn->prepare("INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)");
        $stmt_opt->bind_param("isi", $question_id, $options[$i], $is_correct);
        $stmt_opt->execute();
    }

    $_SESSION['success'] = "Question added successfully!";
    header("Location: dashboard.php");
    exit();
}

include '../includes/header.php';
?>

<div class="container mt-4">
    <h3>➕ Add Question</h3>

    <?php if(isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Select Test</label>
            <select class="form-select" name="test_id" required>
                <option value="">-- Choose Test --</option>
                <?php while($test = $tests->fetch_assoc()): ?>
                    <option value="<?= $test['id'] ?>"><?= $test['title'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Question</label>
            <textarea class="form-control" name="question_text" placeholder="Enter the question" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Options</label>
            <?php for($i=0; $i<4; $i++): ?>
            <div class="input-group mb-2">
                <span class="input-group-text">
                    <input type="radio" name="correct" value="<?= $i ?>" required>
                </span>
                <input type="text" class="form-control" name="options[]" placeholder="Option <?= $i+1 ?>" required>
            </div>
            <?php endfor; ?>
            <small class="text-muted">Select the radio button corresponding to the correct option.</small>
        </div>

        <button class="btn btn-primary">Add Question</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>