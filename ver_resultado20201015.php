<?php
session_start();
set_time_limit(300);
//header('Content-type: application/pdf');
include_once('pdf.php');
//require_once('connections/honorarios.php');

//error_reporting(E_ALL);

if (isset($_SESSION['matricula_socio'])) {
    //echo $_SESSION['matricula_socio'];
    //echo 'asd';
    if (isset($_SESSION['comitente']) && isset($_SESSION['direccion']) && isset($_SESSION['localidad']) && isset($_SESSION['tipo_de_obra'])) {
        $comitente = $_SESSION['comitente'];
        $direccion = $_SESSION['direccion'];
        $localidad = $_SESSION['localidad'];
        $matricula = $_SESSION['matricula'];
        $nombre = $_SESSION['nombre'];
        /*
            $tipo_de_obra = $_SESSION['tipo_de_obra'];
            $m2_proyecto = $_SESSION['m2_proyecto'];
            $m2_direccion = $_SESSION['m2_direccion'];
            $m2_relevamiento = $_SESSION['m2_relevamiento'];
            //$archivo = $_SESSION['plano'];
            $monto_p = round($_SESSION['monto_p'],2);
            $monto_do = round($_SESSION['monto_do'],2);
            $monto_r = round($_SESSION['monto_r'],2);
            $total = round($_SESSION['total'],2);
            $valor_metro_p = round($_SESSION['valor_metro_p'],2);
            $valor_metro_do = round($_SESSION['valor_metro_do'],2);
            $valor_metro_e = round($_SESSION['valor_metro_e'],2);
            $tipo_de_obra = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($conn, $tipo_de_obra) : mysqli_escape_string($conn, $tipo_de_obra);  
            */

        //$pdf->Output();  

        //$sql = "SELECT * FROM tipos_de_obra WHERE id = $tipo_de_obra";
        //print_r($sql);
        //$resultado = mysqli_query($conn, $sql);
        //$fila = mysqli_fetch_array($resultado);
        //echo $fila['id'];

        $pdf = new PDF();

        $pdf->AliasNbPages();
        $pdf->AddPage();

        $pdf->SetFont('Arial', '', 8);
        $alto = 6;

        $hoy = date('d/m/Y H:i:s');

        $pdf->SetY(1);
        $pdf->SetX(10);
        $pdf->cell(20, $alto, $hoy, 0, 0);

        $pdf->SetFont('Arial', '', 14);
        $pdf->SetY(45);


        $pdf->SetFont('Arial', 'B', 12);
        $pdf->cell(22, $alto, "Mis Datos ", 'B0', 2);
        $pdf->SetFont('Arial', '', 12);
        $pdf->cell(40, $alto, "Matricula: ", 0, 0);
        $pdf->cell(80, $alto, $matricula, 0, 0);
        $pdf->Ln();
        $pdf->cell(40, $alto, "Apellido y Nombre: ", 0, 0);
        $pdf->cell(80, $alto, utf8_decode($nombre), 0, 0);
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->cell(36, $alto, "Datos Comitente", 'B0', 2);
        $pdf->SetFont('Arial', '', 12);
        //$pdf->SetY(70);
        $pdf->Cell(40, $alto, "Comitente ", 0);
        $pdf->Cell(80, $alto, utf8_decode($comitente), 0);
        $pdf->Ln();
        $pdf->Cell(40, $alto, utf8_decode("Dirección"), 0);
        $pdf->Cell(80, $alto, utf8_decode($direccion), 0);
        $pdf->Ln();
        $pdf->Cell(40, $alto, "Localidad ", 0);
        $pdf->Cell(80, $alto, utf8_decode($localidad), 0);
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(120, $alto, "Montos a cobrar ", 0, 0, 'C');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(70, $alto, "Tipo de Obra", 'B1');
        $pdf->Cell(30, $alto, "M2 Proyecto", 'B1');
        $pdf->Cell(30, $alto, utf8_decode("m2 Dirección"), 'B1');
        $pdf->CellFitSpace(30, $alto, "M2 Relevamiento", 'B1', 0, 'C');
        $pdf->Cell(30, $alto, "Total", 'B1', 0, 'C');
        $pdf->Ln();

        $datos = json_decode($_SESSION['filas_proyectos']);
        //var_dump($datos);
        $totalgral = 0;

        for ($i = 0; $i < count($datos); $i++) {
            $totalgral += $datos[$i][4];
            $valor = $pdf->GetY();
            $pdf->MultiCell(70, 6, utf8_decode($datos[$i][0]), 'RB');
            $valor2 = $pdf->GetY();
            $alto = $valor2 - $valor;
            $pdf->SetY($valor);
            $pdf->SetX(80);
            $pdf->Cell(30, $alto, $datos[$i][1], 'RB', 0, "R");
            $pdf->Cell(30, $alto, $datos[$i][2], 'RB', 0, "R");
            $pdf->Cell(30, $alto, $datos[$i][3], 'RB', 0, "R");
            $pdf->Cell(30, $alto, "$ " . $datos[$i][4], 'B', 0, "R");
            $pdf->Ln();
        }
        /*
            for($i = 0; $i < 5; $i++){
                $totalgral += $datos[0][4];
                $valor = $pdf->GetY();
                $pdf->MultiCell(70,6,utf8_decode($valor.$datos[0][0]),'R');
                $valor2 = $pdf->GetY();
                $alto = $valor2 - $valor;
                $pdf->SetY($valor);
                $pdf->SetX(80);
                $pdf->Cell(30,$alto,$valor2."//".$datos[0][1],'R',0,"R");
                $pdf->Cell(30,$alto,$datos[0][2],'R',0,"R");
                $pdf->Cell(30,$alto,$datos[0][3],'R',0,"R");
                $pdf->Cell(30,$alto,"$ ".$datos[0][4],0,0,"R");
                //$pdf->SetY($valor2);
                $pdf->Ln();                 
            }
            */
        $alto = 6;
        //$pdf->CellFit(40,$alto,utf8_decode($datos[$i][0]),'R');
        //$pdf->Cell(40,$alto,$datos[$i][1],'R',0,"R");
        //$pdf->Cell(40,$alto,$datos[$i][2],'R',0,"R");
        $pdf->Cell(160, $alto, "Total: ", 'RT', 0, "R");
        $pdf->Cell(30, $alto, "$ " . $totalgral, 'T', 0, "R");
        $pdf->Ln();

        /*
            $pdf->Cell(40,$alto,"Proyecto ",'R');
            $pdf->Cell(40,$alto,$m2_proyecto,'R',0,"R");
            $pdf->Cell(40,$alto,"$ ".$valor_metro_p,'R',0,"R");
            $pdf->Cell(40,$alto,"$ ".$monto_p,0,0,"R");
            $pdf->Ln();
            $pdf->Cell(40,$alto,utf8_decode("Dirección Técnica"),'R');
            $pdf->Cell(40,$alto,$m2_direccion,'R',0,"R");
            $pdf->Cell(40,$alto,"$ ".$valor_metro_do,'R',0,"R");
            $pdf->Cell(40,$alto,"$ ".$monto_do,0,0,"R");
            if($m2_relevamiento!=''){
                $pdf->Ln();
                $pdf->Cell(40,$alto,("Relevamiento"),'R');
                $pdf->Cell(40,$alto,$m2_relevamiento,'R',0,"R");
                $pdf->Cell(40,$alto,"$ ".$valor_metro_e,'R',0,"R");
                $pdf->Cell(40,$alto,"$ ".$monto_r,0,0,"R");
            }
            $pdf->Ln();
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(80,$alto,'',0);
            $pdf->Cell(40,$alto,"Total: ",0,0,"R");
            $pdf->Cell(40,$alto,"$ ".$total,0,0,"R");
            $pdf->Ln();
  */
        if (isset($_POST['send'])) {
            $img = $_FILES['plano'];

            function reArrayFiles($file)
            {
                $file_ary = array();
                $file_count = count($file['name']);
                $file_key = array_keys($file);

                for ($i = 0; $i < $file_count; $i++) {
                    foreach ($file_key as $val) {
                        $file_ary[$i][$val] = $file[$val][$i];
                    }
                }
                return $file_ary;
            }

            $img_desc = reArrayFiles($img);
            //print_r($img_desc);

            $cont = 0;
            foreach ($img_desc as $val) {
                //$newname = date('YmdHis',time()).mt_rand().'.jpg';
                //move_uploaded_file($val['tmp_name'],'./uploads/'.$newname);
                $bandera = true;
                $mensaje_error = '';
                //$archivo = $_FILES['plano'];
                $archivo = $val;
                //var_dump($archivo);
                // Undefined | Multiple Files | $_FILES Corruption Attack
                // If this request falls under any of them, treat it invalid.
                if (
                    !isset($archivo['error']) ||
                    is_array($archivo['error'])
                ) {
                    $mensaje_error = 'Parametros invalidos.';
                    $bandera = false;
                }

                $exttemp = explode(".", $archivo['name']);
                $ext = $exttemp[1];

                // Check $_FILES['upfile']['error'] value.
                switch ($archivo['error']) {
                    case UPLOAD_ERR_OK:
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $mensaje_error = 'Error al subir archivo.';
                        $bandera = false;
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $mensaje_error = 'Limite de tamaño de archivo excedido (Max. 10 MB).';
                        $bandera = false;
                    default:
                        $mensaje_error = 'Error desconocido .';
                        $bandera = false;
                }

                // You should also check filesize here. 
                if ($archivo['size'] > 10000000) {
                    $mensaje_error = 'Limite de tamaño de archivo excedido (Max. 10 MB).';
                    $bandera = false;
                }

                //echo $archivo['tmp_name'];

                $cont++;
                if (file_exists('uploads/plano_' . $matricula . "_" . $cont . "." . $ext)) {
                    unlink('uploads/plano_' . $matricula . "_" . $cont . "." . $ext);
                }

                if (!move_uploaded_file(
                    $archivo['tmp_name'],
                    sprintf(
                        'uploads/%s.%s',
                        "plano_" . $matricula . "_" . $cont,
                        $ext
                    )
                )) {
                    $mensaje_error = 'Error al mover el archivo subido.';
                    $bandera = false;
                }
            }

            require_once("clases/mail.php");

            $smtp = 'mail.arquitectosmisiones.org.ar';
            //$puerto = 9025;
            $puerto = 587;
            $usuario = 'autogestion@arquitectosmisiones.org.ar';
            $clave = 'autogestionZ';
            $asunto = $matricula . ' - ' . $nombre . ' - ' . $direccion;
            $from = array('autogestion@arquitectosmisiones.org.ar' => 'Sistema de Autogestion del Colegio de Arquitectos de la Prov. de Mnes.');
            $to = array('tasas@arquitectosmisiones.org.ar', $_SESSION['email']);
            //$_SESSION['email'] = 'johnbabi.jb@gmail.com';
            //$to = array($_SESSION['email']);
            $body = '<p>Datos de la obra en los archivos adjuntos</p>';
            $correo = new mail($puerto, $smtp, $from, $usuario, $clave, $asunto, $body, $to);


            $archivopdf = "reporte_" . $matricula . ".pdf";
            $pdf->Output($archivopdf, 'F');

            /* este codigo es para enviar con el smtp de gmail
                $smtp = 'smtp.gmail.com';
                $puerto = 587;
                $usuario = 'mail@gmail.com';
                $clave= 'pass';
                $asunto = 'Calculo de Tasas';
                $from =array('mail@gmail.com' => 'Sistema de Autogestion del Colegio de Arquitectos de la Prov. de Mnes.');
                $to = array( 'mail@gmail.com');
                $body = '<p>El Socio , DNI  y matricula , solicita la actualización de sus datos. Lo solicitado por el mismo es lo siguiente: <br></p><p></p>';
                $correo = new mail($puerto,$smtp,$from,$usuario,$clave,$asunto,$body,$to);
                */

            $correo->agregar_archivo($archivopdf);
            for ($i = 1; $i <= $cont; $i++) {
                $correo->agregar_archivo('uploads/plano_' . $matricula . "_" . $i . "." . $ext);
            }

            // Create the Transport
            if ($bandera) {
                $resultado = $correo->enviar_mail_con_archivos();
                if ($resultado['respuesta'] == 'SI') {
                    echo "<h3 style='background-color: green; color: white;'>Su solicitud ha sido procesada por el Colegio de Arquitectos de la Provincia de Misiones. Se envió una copia a su E-Mail." . $_SESSION['email'] . "</h3>";
?>
                    <a style="background-color: gray; border-radius: 5px; padding: 7px; color: white;" value="VOLVER ATRÁS" href="calculo_tasas.php"> Calcular tasas </a>
                <?php
                } else {
                    echo "<h3 style='background-color: red; color: white;'>Error con el hosting de envios de E-Mail.</h3>";
                ?>
                    <input style="background-color: gray; border-radius: 5px; padding: 7px; color: white;" type="button" value="VOLVER ATRÁS" name="Back2" onclick="history.back()" />
                <?php
                }
            } else {
                echo "<h3 style='background-color: red; color: white;'>" . $mensaje_error . "</h3>";
                ?>
                <input style="background-color: gray; border-radius: 5px; padding: 7px; color: white;" type="button" value="VOLVER ATRÁS" name="Back2" onclick="history.back()" />
    <?php
            }
            //echo "<script>alert('Enviado correctamente.')</script>";
        } else {
            $pdf->Output();
        }
    }
} else {
    ?>
    <script language="JavaScript" type="text/javascript">
        var pagina = "index.php"
        location.href = pagina
    </script>
<?php
}
?>