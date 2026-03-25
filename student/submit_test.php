<?php include '../includes/header.php'; include '../config/db.php'; ?>

<?php
$answers=$_POST['answers'];
$score=0;
foreach($answers as $qid=>$oid){
$res=$conn->query("SELECT is_correct FROM options WHERE id=$oid");
$r=$res->fetch_assoc();
if($r['is_correct']) $score++;
}
?>

<div class="card p-4 shadow text-center">
<h2>Your Score: <?php echo $score; ?></h2>
</div>

<?php include '../includes/footer.php'; ?>