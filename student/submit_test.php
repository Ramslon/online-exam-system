<?php include '../includes/header.php'; include '../config/db.php'; 
session_start();

if (isset($_SESSION['submitted'])) {
    echo "Already submitted!";
    exit();
}
?>
$_SESSION['submitted'] = true;
<?php
$answers=$_POST['answers'];
$score=0;
foreach($answers as $qid=>$oid){
$res=$conn->query("SELECT is_correct FROM options WHERE id=$oid");
$r=$res->fetch_assoc();
if($r['is_correct']) $score++;
}
$student_id = $_SESSION['user_id'];
$test_id = 1; // later make dynamic

$conn->query("INSERT INTO results (student_id,test_id,score) 
VALUES ('$student_id','$test_id','$score')");
?>

<div class="card p-4 shadow text-center">
<h2>Your Score: <?php echo $score; ?></h2>
</div>

<?php include '../includes/footer.php'; ?>