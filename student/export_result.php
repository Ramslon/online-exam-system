<?php
require('../fpdf/fpdf.php');
include '../config/db.php';

session_start();
$student_id = $_SESSION['user_id'];

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Student Results Report',0,1);

$res = $conn->query("SELECT * FROM results WHERE student_id=$student_id");

while($row=$res->fetch_assoc()){
    $test=$conn->query("SELECT title FROM tests WHERE id={$row['test_id']}")->fetch_assoc();
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,10,"Test: ".$test['title']." | Score: ".$row['score']."%",0,1);
}

$pdf->Output();
?>