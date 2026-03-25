<?php include '../includes/header.php'; include '../config/db.php'; ?>

<h3>Add Question</h3>
<form method="POST" class="card p-3 shadow">
<input name="test_id" class="form-control" placeholder="Test ID" required>
<textarea name="question" class="form-control mt-2" placeholder="Question"></textarea>

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

<button class="btn btn-primary mt-3">Save Question</button>
</form>

<?php include '../includes/footer.php'; ?>