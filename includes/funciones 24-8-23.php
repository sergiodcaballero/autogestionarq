<?php
function calcular_deuda($matricula = '', $fecha = '')
{
	global $conn;
	if ($matricula == '') {
		$matricula = $_SESSION['matricula_socio'];
	}
	if ($fecha == '') {
		$fecha = date('Y-m-d');
	}
	$matricula = mysqli_real_escape_string($conn, $matricula);
	$fecha = mysqli_real_escape_string($conn, $fecha);
	/*
	$c="select * from factura;";
	$r = mysqli_query($conn, $c);
	$i=0;
	echo "<table>";
	while ($f=mysqli_fetch_assoc($r)) {
		//echo var_dump($f)."<br>";
		$head=$f;
		if($i==0){
			$i++;
			echo "<tr>";
			foreach ($head as $key => $value) {
				echo "<td>";
				echo $key;
				echo "</td>";
			}
			echo "</tr>";
		}

		echo "<tr>";
		foreach ($f as $key => $value) {
			echo "<td  style='border: 1px solid;'>";
			echo $value;
			echo "</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
	*/


	$consulta = "select count(f.idfactura) as total 
				FROM factura f 
				inner join socios s on f.idsocio=s.idsocio
				where matricula= '" . $matricula . "' and f.estado='' and fecha <= '$fecha'";
	$resultado = mysqli_query($conn, $consulta);
	$Cantidad_Filas = mysqli_num_rows($resultado);
	if ($Cantidad_Filas == 0) {
		$deuda = 0;
	} else {
		while ($fila = mysqli_fetch_array($resultado)) {
			$deuda = $fila['total'];
		}
	}
	return $deuda;
}


function resizeImagen($ruta, $nombre, $alto, $ancho, $nombreN, $extension)
{
	$rutaImagenOriginal = $ruta . $nombre;

	if (exif_imagetype($rutaImagenOriginal) == 2) {
		$extension = 'jpg';
	} elseif (exif_imagetype($rutaImagenOriginal) == 3) {
		$extension = 'png';
	} elseif (exif_imagetype($rutaImagenOriginal) == 1) {
		$extension = 'gif';
	}
	if ($extension == 'GIF' || $extension == 'gif') {
		$img_original = imagecreatefromgif($rutaImagenOriginal);
	}
	if ($extension == 'jpg' || $extension == 'JPG') {
		$img_original = imagecreatefromjpeg($rutaImagenOriginal);
	}
	if ($extension == 'png' || $extension == 'PNG') {
		$img_original = imagecreatefrompng($rutaImagenOriginal);
	}
	//$max_ancho = $ancho;
	//$max_alto = $alto;
	$max_ancho = 1024;
	$max_alto = 1024;
	list($ancho, $alto) = getimagesize($rutaImagenOriginal);
	$x_ratio = $max_ancho / $ancho;
	$y_ratio = $max_alto / $alto;
	if (($ancho <= $max_ancho) && ($alto <= $max_alto)) { //Si ancho 
		$ancho_final = $ancho;
		$alto_final = $alto;
	} elseif (($x_ratio * $alto) < $max_alto) {
		$alto_final = ceil($x_ratio * $alto);
		$ancho_final = $max_ancho;
	} else {
		$ancho_final = ceil($y_ratio * $ancho);
		$alto_final = $max_alto;
	}
	$tmp = imagecreatetruecolor($ancho_final, $alto_final);
	imagecopyresampled($tmp, $img_original, 0, 0, 0, 0, $ancho_final, $alto_final, $ancho, $alto);
	imagedestroy($img_original);
	$calidad = 70;
	imagejpeg($tmp, $ruta . $nombreN, $calidad);
}

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;

function generar_pdf_tramite($id_tramite)
{
	global $mysqli;
	$retorno = array();
	$retorno['estado'] = false;
	$retorno['datos'] = "";
	require "lib/fpdf/fpdf.php";
	require "lib/fpdi/autoload.php";

	class PDF extends Fpdi
	{
		private $titulo = 'Titulo';
		// Cabecera de página
		function Header()
		{
			// Logo
			//$this->Image('logo_pb.png', 10, 8, 33);
			// Arial bold 15
			$this->SetFont('Arial', 'B', 15);
			// Movernos a la derecha
			$this->Cell(80);
			// Título
			$this->Cell(30, 10, utf8_decode($this->titulo), 0, 0, 'C');
			// Salto de línea
			$this->Ln(20);
		}

		// Pie de página
		function Footer()
		{
			// Posición: a 1,5 cm del final
			$this->SetY(-15);
			// Arial italic 8
			$this->SetFont('Arial', 'I', 8);
			// Número de página
			$this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
		}

		function setTitulo($tit)
		{
			$this->titulo = $tit;
		}
	}

	if (isset($id_tramite)) {
		$id = escapar($id_tramite);
		try {
			$pdf = new PDF();

			$r = $mysqli->query("SELECT t.*, f.nombre as nombre_formulario FROM tramites t inner join formularios f on t.for_id = f.id WHERE t.id = '$id' ");
			if ($r->num_rows == 0) {
				throw new Exception('Tramite erroneo.');
			}
			$tramite = $r->fetch_assoc();

			$pdf->setTitulo('' . $tramite['nombre_formulario'] . ' # ' . $tramite['id']);
			$pdf->AliasNbPages();
			$pdf->AddPage();
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->Cell(0, 7, utf8_decode('Trámite nro.: # ' . $tramite['id']), 0, 1);
			$pdf->Cell(0, 7, utf8_decode('Trámite: ' . $tramite['nombre_formulario']), 0, 1);
			$pdf->Cell(0, 7, utf8_decode('Fecha: ' . date('d/m/Y H:i:s', strtotime($tramite['fecha_realizado']))), 0, 1);
			if ($tramite['usu_id'] != 0) {
				$r = $mysqli->query("SELECT * FROM usuario WHERE IDUSUARIO = '{$tramite['usu_id']}'");
				$usuario = $r->fetch_assoc();
				$pdf->Cell(0, 7, utf8_decode('Realizado por: ' . $usuario['IDUSUARIO'] . " - " . $usuario['DESCRIPCION']), 0, 1);
			}


			$r = $mysqli->query("SELECT td.*, fc.nombre as nombre_campo FROM tramites_detalle td inner join formularios_campos fc on fc.id = td.fc_id where td.tra_id = '$id' ");
			$detalles = $r->fetch_all(MYSQLI_ASSOC);
			$pdf->Cell(0, 7, utf8_decode('Datos Cargados'), 0, 1);
			$c1 = 0;
			$c2 = 0;
			$alto_fila = 5;
			$cambiar_color = true;
			$i = 0;
			foreach ($detalles as $det) {
				if ($cambiar_color) {
					$cambiar_color = !$cambiar_color;
					$pdf->SetFillColor(222);
				} else {
					$pdf->SetFillColor(200);
					$cambiar_color = !$cambiar_color;
				}
				//$directorio = "../archivos_md5";
				$directorio = dirname(dirname(__FILE__)) . "/archivos_md5";
				if ($det['tipo_dato'] == 'file') {
					$i++;
					$archivo = explode(".", $det['valor']);
					$ext = $archivo[1];
					$archivoshaext = $archivo[0] . "." . $archivo[1];
					if ($ext == 'pdf') {
						//$directorio=getcwd().'/tmp';
						//echo 'gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dBATCH -sOutputFile=' . $directorio . '/res_' . $archivoshaext . ' ' . $directorio . '/' . $archivoshaext;
						exec('gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dBATCH -sOutputFile=' . $directorio . '/res_' . $archivoshaext . ' ' . $directorio . '/' . $archivoshaext);
						//unlink($directorio . '/' . $archivoshaext);
						$archivo = $directorio . '/res_' . $archivoshaext;
					} else {
						//$minFoto = 'min_'.$archivosha;
						$resFoto = 'res_' . $archivoshaext;
						//resizeImagen($directorio.'/', $archivosha, 65, 65,$minFoto,$extension);
						resizeImagen($directorio . '/', $archivoshaext, 90, 90, $resFoto, $ext);
						$archivo = $directorio . '/' . $resFoto;
						//unlink($directorio.'/'.$archivosha);
					}
					$pdf->setTitulo('');
					if ($ext == 'pdf') {
						$pageCount = $pdf->setSourceFile($archivo);
						for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
							$pdf->AddPage();
							// import a page
							$templateId = $pdf->importPage($pageNo);
							// use the imported page and adjust the page size
							$pdf->useTemplate($templateId, ['adjustPageSize' => true]);

							$pdf->setXY(10, 1);
							$pdf->SetFont('Arial', 'B', 15);
							$pdf->Cell(0, 7, utf8_decode('Archivo '  . "Adjunto N°: " . ($i)), 0, 1);

							$pdf->SetFont('Helvetica');
							$pdf->SetXY(5, 5);
							//$pdf->Write(8, 'A complete document imported with FPDI');
						}
					} else {
						$pdf->AddPage();
						$pdf->setXY(10, 10);
						$pdf->SetFont('Arial', 'B', 15);
						$pdf->Cell(0, 7, utf8_decode('Archivo '  . "Adjunto N°: " . ($i)), 0, 1);
						$pdf->Image($archivo, 10, 20, 180);
						$pdf->ln();
					}
				} else {
					$pdf->SetFillColor(245);
					$pdf->SetFont('Arial', 'B', 10);
					$pdf->Cell($c1, $alto_fila, utf8_decode("" . $det['nombre_campo']), 1, 1, 'L', true);
					$pdf->SetFillColor(255);
					$pdf->SetFont('Arial', '', 10);
					$pdf->Cell($c2, $alto_fila, utf8_decode($det['valor']), 1, 0, 'L', true);
					$pdf->Ln(1);
					//echo $det['nombre_campo'] . ': ' . $det['valor'];
				}
				$pdf->Ln();
			}
			$pdf->Output('F', $directorio . '/tramite-' . $id_tramite . '.pdf', true);
			$retorno['estado'] = true;
			$retorno['datos'] = 'tramite-' . $id_tramite . '.pdf';
		} catch (Exception $e) {
			$retorno['estado'] = true;
			$retorno['datos'] = $e->getMessage();;
		}
	}
	return json_encode($retorno);
}
