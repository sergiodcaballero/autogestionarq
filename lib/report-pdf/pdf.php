<?php
require "fpdf.php";

class PDF extends FPDF
{
    private $titulo = 'Título';
    // Cabecera de página
    function Header()
    {
        // Logo
        $this->Image('../imagenes/cabecera.png', 10, 8, 70);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Movernos a la derecha
        $this->Ln(20);
        
        // Título
        $this->Cell(0, 10, utf8_decode($this->titulo), 0, 0, 'C');
        // Salto de línea
        $this->Ln(20);
    }

    // Pie de página
    function Footer()
    {
        //fecha del reporte
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Reporte emitido el ' . date('d/m/Y H:i:s')), 0, 0, 'L');
        //a 1.5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
    }

    public function setTitulo($t){
        $this->titulo = $t;
    }   
}
