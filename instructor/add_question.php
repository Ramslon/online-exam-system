<?php include '../config/db.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question = $_POST['question'];
    $test_id = $_POST['test_id'];

    $conn->query("INSERT INTO questions (test_id,question) VALUES ('$test_id','$question')");
    $qid = $conn->insert_id;

    for ($i=1; $i<=4; $i++) {
        $opt = $_POST['option'.$i];
        $correct = ($_POST['correct'] == $i) ? 1 : 0;
        $conn->query("INSERT INTO options (question_id,option_text,is_correct) VALUES ('$qid','$opt','$correct')");
    }
    echo "Question added";
}
?>

<form method="POST" class="container">
<input name="test_id" class="form-control" placeholder="Test ID" required>
<textarea name="question" class="form-control mt-2" placeholder="Question" required></textarea>

<input name="option1" class="form-control mt-2" placeholder="Option 1">
<input name="option2" class="form-control mt-2" placeholder="Option 2">
<input name="option3" class="form-control mt-2" placeholder="Option 3">
<input name="option4" class="form-control mt-2" placeholder="Option 4">

<select name="correct" class="form-control mt-2">
<option value="1">Correct Option 1</option>
<option value="2">Correct Option 2</option>
<option value="3">Correct Option 3</option>
<option value="4">Correct Option 4</option>
</select>

<button class="btn btn-primary mt-3">Add Question</button>
</form>
