<?php
include_once('fpdf/fpdf.php');
//require('connections/honorarios.php');

//error_reporting(E_ALL);

//if (isset($_SESSION['matricula_socio'])){
	$pdf = new FPDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();

	$pdf->SetFont('Arial','',8);
	$alto = 6;

	$hoy = date('d/m/Y H:i:s');

	$pdf->SetY(1);
	$pdf->SetX(10);
	$pdf->cell(20,$alto,$hoy,0,0);

	$pdf->SetFont('Arial','',14);
	$pdf->SetY(45);
//}


$pdf->Output();

?>