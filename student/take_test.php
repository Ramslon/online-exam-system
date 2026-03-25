<?php include '../includes/header.php'; include '../config/db.php'; ?>

<form method="POST" action="submit_test.php" id="examForm">
<div class="alert alert-info">Time Left: <span id="timer"></span></div>

<?php $q = $conn->query("SELECT * FROM questions"); while($row=$q->fetch_assoc()) { ?>
<div class="card p-3 mt-3">
<p><?php echo $row['question']; ?></p>
<?php $opt=$conn->query("SELECT * FROM options WHERE question_id=".$row['id']); while($o=$opt->fetch_assoc()) { ?>
<div class="form-check">
<input class="form-check-input" type="radio" name="answers[<?php echo $row['id']; ?>]" value="<?php echo $o['id']; ?>">
<label><?php echo $o['option_text']; ?></label>
</div>
<?php } ?>
</div>
<?php } ?>

<button class="btn btn-success mt-3">Submit</button>
</form>

<script>
let t=120;
setInterval(()=>{t--;document.getElementById('timer').innerText=t;if(t<=0)document.getElementById('examForm').submit();},1000);
</script>

<?php include '../includes/footer.php'; ?>