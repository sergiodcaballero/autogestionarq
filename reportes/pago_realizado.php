<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . "/../includes/conexion.php";
require_once __DIR__ . "/../includes/funciones.php";
require_once __DIR__ . "/../lib/report-pdf/pdf.php";

class pagoRealizado extends FPDF
{
    public $titulo = "Pago realizado";
    public $nombre = '';
    public $matricula = '';
    public $documento = '';
    public $correo = '';
    public $cuit = '';
    public $nroPago = '';

    public $detalle = [];
    public $total = 0;

    function Header()
    {
        // Logo
        $this->Image('../imagenes/cabecera.png', 10, 8, 70);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        $this->Ln();
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

    private function datosComprobante()
    {
        $altura = $this->GetY();
        $this->setLinea("Nro. Pago: ", $this->nroPago);
    }

    private function datosDelUsuario()
    {
        $altura = $this->GetY();
        $this->setLinea("Nombre: ", $this->nombre);
        $this->setLinea("Matrícula: ", $this->matricula);
        $this->setLinea("Documento: ", $this->documento);
        $this->setLinea("E-Mail: ", $this->correo);
        $this->setLinea("CUIT: ", $this->cuit);
    }

    private function detalleComprobante()
    {
        $altura = $this->GetY();
        $this->setLinea("Detalle", "", 0, 0, 3);
        foreach ($this->detalle as $det) {
            $this->setLinea("", $det, 1, 0, 1);
        }
    }

    private function totalComprobante()
    {
        $altura = $this->GetY();
        $this->setLinea("Total", "$ " . $this->total, 20, 0, 3);
    }

    private function setLinea($label, $data, $x1 = 25, $x2 = 30, $ln = 0)
    {
        $fontSize = 10;
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', 'B', $fontSize);
        $this->Cell($x1, 4, utf8_decode($label), 0, 0, 'L');
        $this->SetFont('Arial', '', $fontSize);
        $this->Cell($x2, 4, utf8_decode($data), 0, 1, 'L');
        $this->Ln($ln);
    }

    private function setDivisor()
    {
        $this->Ln(3);
        // Colores de los bordes, fondo y texto
        $this->SetTextColor(220, 50, 50);
        // Ancho del borde (1 mm)
        $this->SetLineWidth(1);
        // Título
        $this->Cell(0, 0.5, "", 0, 1, 'C', true);
        $this->Ln(3);
    }

    public function verReporte()
    {
        $this->AliasNbPages();
        $this->AddPage();
        $this->datosComprobante();
        $this->setDivisor();
        $this->datosDelUsuario();
        $this->setDivisor();
        $this->detalleComprobante();
        $this->setDivisor();
        $this->totalComprobante();
        $this->Output();
    }
}

$idpago = 28;
if (!empty($_GET['pago'])) {
    $idpago = $_GET['pago'];
}

$pago = new pagoRealizado();
$datos_pago = buscar_pago_finalizado($idpago);
$socio = buscar_socio($datos_pago[0]['IDSOCIO']);
$detalle = array();
$total = 0;
foreach ($datos_pago as $det) {
    $detalle[] = "Id. Factura: " . $det['IDFACTURA'] . "  |  Monto: $ " . $det['TOTAL'] . "  |  Mes: " . $det['MES'] . "  |  Año: " . $det['ANO'];
    $total += $det['TOTAL'];
}
$pago->detalle = $detalle;
$pago->total = $total;
$pago->nroPago = $idpago;
$pago->nombre = utf8_encode($socio['NOMBRE']);
$pago->matricula = $socio['MATRICULA'];
$pago->documento = $socio['N_DOCUMENTO'];
$pago->correo = $socio['EMAIL'];
$pago->cuit = $socio['CUIT'];
$pago->verReporte();
