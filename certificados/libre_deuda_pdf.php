<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
if (isset($_SESSION['matricula_socio'])) {
    require_once '../connections/honorarios.php';
    require_once '../lib/fpdf/fpdf.php';
    require_once '../lib/qrcode/qrcode.class.php';
} else {
    ?><script language="JavaScript" type="text/javascript">
			var pagina="index.php"
			location.href=pagina
	</script> <?php
}

//$matricula = "'" . $_SESSION['matricula_socio'] . "'";
$matricula = $_SESSION['matricula_socio'];
$tipo = substr($_SESSION['matricula_socio'], 0, 1);

if ($tipo == 'M') {

    $tipo = "Dr/a.";

} else if ($tipo == 'A') {

    $tipo = "Aux.";

} else {

    $tipo = "Tec.";

}

$sql = "select DATE_FORMAT(fecha_alta,'%d-%m-%Y') as fecha,n_documento as dni,nombre from socios where matricula=" . $matricula;

//print_r($sql);
$honorarios = $conn;
$resultado  = mysqli_query($honorarios, $sql);

while ($fila = mysqli_fetch_array($resultado)) {

    $fecha_alta = $fila['fecha'];

    $dni = $fila['dni'];

    $socio = $fila['nombre'];

    $arrayMeses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

    $arrayDias = array('Domingo', 'Lunes', 'Martes',

        'Miercoles', 'Jueves', 'Viernes', 'Sabado');

    $fecha_actual = $arrayDias[date('w')] . " " . date('d') . " de " . $arrayMeses[date('m') - 1] . " de " . date('Y');

    //$fecha = date("d-m-Y");

    $consulta = "select count(f.idfactura) as total FROM factura f inner join socios s on f.idsocio=s.idsocio
	where matricula= '" . $_SESSION['matricula_socio'] . "' and f.estado='' ";

    $resultado      = mysqli_query($honorarios, $consulta);
    $Cantidad_Filas = mysqli_num_rows($resultado);

    if ($Cantidad_Filas == 0) {
        $deuda = 0;

    } else {

        while ($fila = mysqli_fetch_array($resultado)) {

            $deuda = $fila['total'];

            //$socio = $fila['nombre'];

        }

    }
    $titulo      = "";
    $cabeceraalt = "Decreto Ley N° 1-72";
    $cabecera    = "Av Fco. de Haro N° 2745 1° piso - Tel 0376-4435310 - email info@arquitectosmisiones.org.ar";
    if ($deuda == 0) {
        //$titulo = "Certificado Libre de Deuda";  se saco por COVID
        $titulo = "Certificado de Matrícula Habilitada";
        $cuerpo = "Certificamos que el profesional arquitecto: ".utf8_encode($socio).", DNI: $dni se encuentra matriculado en este Colegio de la Provincia de Misiones, bajo el N°; $matricula en esta institución colegiado/a.

Se encuentra matriculado, y cumplimenta con los requerimientos establecidos por la ley N°; 1-72.

La presente certificación es válida por 30 días y se expide en Posadas, Capital de la Provincia de Misiones, a pedido del interesado y presentada ante Quien Corresponda.
            
Posadas, $fecha_actual";
    } else if ($deuda > 0 and $deuda <= 12) {
        $titulo = "Certificado  de Matrícula Habilitada";
        $cuerpo = "Certificamos que el profesional arquitecto: ".utf8_encode($socio).", DNI: $dni se encuentra matriculado en este Colegio de la Provincia de Misiones, bajo el N°; $matricula  en esta institución colegiado/a.

Se encuentra matriculado, y cumplimenta con los requerimientos establecidos por la ley N°; 1-72.

La presente certificación es válida por 30 días y se expide en Posadas, Capital de la Provincia de Misiones, a pedido del interesado y presentada ante Quien Corresponda.
            
Posadas, $fecha_actual";
    } else if ($deuda > 1) {
        //echo "<script> window.close(); </script>";
        exit();
    }
    class PDF extends FPDF
    {
        // Cabecera de página
        public function Header()
        {
            global $titulo, $cabecera, $cabeceraalt;
            // Logo
            $this->Image('../imagenes/cabecera.png', 10, 8, 33);
            // Arial bold 15
            // Movernos a la derecha
            $this->SetFont('Arial', '', 12);
            $this->SetY(30);
            $this->Cell(0, 15, utf8_decode($cabeceraalt), 0, 1, 'C');
            $this->setFillColor(222, 222, 222);
            $this->SetY(45);
            $this->Cell(0, 15, utf8_decode($cabecera), 1, 1, 'C', true);
            $this->SetFont('Arial', 'B', 15);
            $this->Cell(80);
            // Título
            $this->Cell(30, 10, utf8_decode($titulo), 0, 0, 'C');
            // Salto de línea
            $this->Ln(20);
        }

        // Pie de página
        public function Footer()
        {
            // Posición: a 1,5 cm del final
            $this->SetY(-25);
            // Arial italic 8
            $this->SetFont('Arial', 'I', 8);
            // Número de página
            $this->Cell(0, 10, utf8_decode('Emitido por el Sistema de Autogestión'), 0, 1, 'C');
            $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
        }
    }

    // Creación del objeto de la clase heredada
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Times', '', 12);

    $pdf->MultiCell(0, 7, utf8_decode($cuerpo));
    //$pdf->Image('../imagenes/firma.JPG',150,170,33);

    $qrcode = new QRcode($_SESSION['SISTEMA_URL']."/verificador.php?c=".$matricula.'-'.date('Ymd'), 'H'); // error level : L, M, Q, H
    $qrcode->displayFPDF($pdf, 20, 160, 33);
    $pdf->Output();
    exit();
}