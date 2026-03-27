<?php
require('../fpdf/fpdf.php');
include '../config/db.php';

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);

$pdf->Cell(190,10,'Exam Results Report',0,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(40,10,'Name',1);
$pdf->Cell(50,10,'Email',1);
$pdf->Cell(30,10,'Test',1);
$pdf->Cell(20,10,'Score',1);
$pdf->Cell(50,10,'Date',1);
$pdf->Ln();

$res = $conn->query("
    SELECT r.*, u.name, u.email, t.title 
    FROM results r
    JOIN users u ON r.student_id = u.id
    JOIN tests t ON r.test_id = t.id
");

$pdf->SetFont('Arial','',10);

while($row = $res->fetch_assoc()){
    $pdf->Cell(40,10,$row['name'],1);
    $pdf->Cell(50,10,$row['email'],1);
    $pdf->Cell(30,10,$row['title'],1);
    $pdf->Cell(20,10,$row['score'],1);
    $pdf->Cell(50,10,$row['submitted_at'],1);
    $pdf->Ln();
}

$pdf->Output();
?>