<?php
require('../libs/fpdf.php');

$name = $_POST['name'];
$course = $_POST['course'];
$date = date("d M, Y");

// Create PDF
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();

// ✅ Set Background Template
// Replace this with your actual PNG/JPG path
$pdf->Image('../assets/cert-pro.png', 0, 0, 297, 210);

// ✅ Transparent Background Text Styling
$pdf->SetTextColor(0, 0, 0); // black text

// Name
$pdf->SetFont('Arial', 'B', 28);
$pdf->SetXY(0, 100); // Adjust Y position depending on template
$pdf->Cell(297, 10, $name, 0, 1, 'C');

// Course Name
$pdf->SetFont('Arial', '', 20);
$pdf->SetXY(0, 120);
$pdf->Cell(297, 10, "for successfully completing the course:", 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 24);
$pdf->Cell(297, 12, $course, 0, 1, 'C');

// Date
$pdf->SetFont('Arial', '', 16);
$pdf->SetXY(30, 170); // Left bottom side
$pdf->Cell(0, 10, "Date: " . $date, 0, 1);

// Signature (Optional, if your template has signature space)
$pdf->Image('../assets/signature.png', 210, 145, 40); // Adjust if needed

$pdf->SetFont('Arial', '', 12);
$pdf->SetXY(210, 185);
$pdf->Cell(40, 5, 'Authorized Signature', 0, 0, 'C');

// ✅ Output file as download
$pdf->Output("D", $name . "_Certificate.pdf");
?>
