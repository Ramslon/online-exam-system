<?php
include '../includes/header.php';
include '../config/db.php';

if ($_SESSION['role'] != 'instructor') {
    header("Location: ../auth/login.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $test_id = $_POST['test_id'];
    $question = $_POST['question'];
    $options = [$_POST['option1'], $_POST['option2'], $_POST['option3'], $_POST['option4']];
    $correct = $_POST['correct'];

    // Insert question
    $stmt = $conn->prepare("INSERT INTO questions (test_id, question_text, correct_option) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $test_id, $question, $correct);
    $stmt->execute();
    $question_id = $stmt->insert_id;

    // Insert options
    $stmt_opt = $conn->prepare("INSERT INTO options (question_id, option_text) VALUES (?, ?)");
    foreach($options as $opt) {
        $stmt_opt->bind_param("is", $question_id, $opt);
        $stmt_opt->execute();
    }

    $success = "Question added successfully!";
}

// Fetch all tests by this instructor
$tests = $conn->query("SELECT * FROM tests");
?>

<div class="container mt-4">
<h3>➕ Add Questions</h3>

<?php if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

<form method="POST" class="shadow p-3 rounded bg-white">
<div class="mb-3">
<label>Test</label>
<select class="form-control" name="test_id" required>
<?php while($t = $tests->fetch_assoc()): ?>
<option value="<?= $t['id'] ?>"><?= $t['title'] ?></option>
<?php endwhile; ?>
</select>
</div>

<div class="mb-3">
<label>Question</label>
<textarea class="form-control" name="question" required></textarea>
</div>

<div class="mb-3">
<label>Options</label>
<input type="text" class="form-control mb-1" name="option1" placeholder="Option 1" required>
<input type="text" class="form-control mb-1" name="option2" placeholder="Option 2" required>
<input type="text" class="form-control mb-1" name="option3" placeholder="Option 3" required>
<input type="text" class="form-control mb-1" name="option4" placeholder="Option 4" required>
</div>

<div class="mb-3">
<label>Correct Option (1-4)</label>
<input type="number" class="form-control" name="correct" min="1" max="4" required>
</div>

<button class="btn btn-primary">Add Question</button>
</form>
</div>

<?php include '../includes/footer.php'; ?>