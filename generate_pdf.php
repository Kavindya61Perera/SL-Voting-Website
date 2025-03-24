<?php
require('C:\wamp64\www\SL Voting System\vendor\setasign\fpdf\fpdf.php');

// Database connection
$servername = "localhost";
$dbname = "slvoting";
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data
$sql = "SELECT * FROM candidates ORDER BY votes DESC";
$result = $conn->query($sql);

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(190, 10, 'Voting Results', 1, 1, 'C');

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 10, 'ID', 1);
$pdf->Cell(80, 10, 'Name', 1);
$pdf->Cell(60, 10, 'Party', 1);
$pdf->Cell(20, 10, 'Votes', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(20, 10, $row['ID'], 1);
    $pdf->Cell(80, 10, $row['Name'], 1);
    $pdf->Cell(60, 10, $row['Party'], 1);
    $pdf->Cell(20, 10, $row['votes'], 1);
    $pdf->Ln();
}

$pdf->Output('D', 'Voting_Results.pdf'); // 'D' forces download
$conn->close();
?>
