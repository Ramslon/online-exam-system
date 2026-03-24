<?php
include '../config/db.php';
session_start();

$questions = $conn->query("SELECT * FROM questions");
?>

<form method="POST" action="submit_test.php" id="examForm">
<div id="timer"></div>

<?php while($q = $questions->fetch_assoc()) { ?>
<p><?php echo $q['question']; ?></p>
<?php 
$options = $conn->query("SELECT * FROM options WHERE question_id=".$q['id']);
while($o = $options->fetch_assoc()) {
?>
<input type="radio" name="answers[<?php echo $q['id']; ?>]" value="<?php echo $o['id']; ?>">
<?php echo $o['option_text']; ?><br>
<?php } } ?>

<button type="submit">Submit</button>
</form>

<script>
let timeLeft = 60;
setInterval(() => {
    timeLeft--;
    document.getElementById("timer").innerText = timeLeft;
    if (timeLeft <= 0) {
        document.getElementById("examForm").submit();
    }
}, 1000);
</script>