<?php include '../config/db.php'; session_start(); ?>

<?php $questions = $conn->query("SELECT * FROM questions"); ?>

<form method="POST" action="submit_test.php" id="examForm" class="container">
<div class="alert alert-info">Time Left: <span id="timer"></span></div>

<?php while($q = $questions->fetch_assoc()) { ?>
<div class="card p-3 mt-2">
<p><?php echo $q['question']; ?></p>
<?php 
$options = $conn->query("SELECT * FROM options WHERE question_id=".$q['id']);
while($o = $options->fetch_assoc()) {
?>
<div class="form-check">
<input class="form-check-input" type="radio" name="answers[<?php echo $q['id']; ?>]" value="<?php echo $o['id']; ?>">
<label><?php echo $o['option_text']; ?></label>
</div>
<?php } ?>
</div>
<?php } ?>

<button class="btn btn-success mt-3">Submit</button>
</form>

<script>
let timeLeft = 120;
setInterval(() => {
    timeLeft--;
    document.getElementById("timer").innerText = timeLeft;
    if (timeLeft <= 0) {
        document.getElementById("examForm").submit();
    }
}, 1000);
</script>