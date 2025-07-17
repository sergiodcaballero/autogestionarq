<?php 
session_start();
if (isset($_SESSION['matricula_socio'])){
	require_once('connections/honorarios.php');
    require('/fpdf/fpdf.php');
        if(isset($_SESSION['comitente']) && isset($_SESSION['direccion']) && isset($_SESSION['localidad']) && isset($_SESSION['tipo_de_obra'])){
               

            $comitente = $_SESSION['comitente'];
            $direccion = $_SESSION['direccion'];
            $localidad = $_SESSION['localidad'];
            $matricula = $_SESSION['matricula'];
            $nombre = $_SESSION['nombre'];
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

            $sql = "SELECT * FROM tipos_de_obra WHERE id = $tipo_de_obra";
            //print_r($sql);
            $resultado = mysqli_query($conn, $sql);
            $fila = mysqli_fetch_array($resultado);

            

            class PDF extends FPDF{
            //Cabecera de página
               function Header()
               {
                //Logo
                $this->Image("imagenes/logo.jpg" , 10 ,8, 52 , 30 , "JPG");
                //Arial bold 15
                $this->SetFont('Arial','B',15);
                //Movernos a la derecha
                $this->Cell(80);
                //Título
                $this->Cell(35,10,utf8_decode('Sistema de Autogestión'),0,2,'C');
                $this->Cell(35,10,utf8_decode('Cálculo de Tasas'),0,2,'C');
                //Salto de línea
                $this->Ln(20);
                  
               }
               
               //Pie de página
               function Footer()
               {
                //Posición: a 1,5 cm del final
                $this->SetY(-15);
                //Arial italic 8
                $this->SetFont('Arial','I',8);
                //Número de página
                $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
               }   

            }   


            $pdf=new PDF();

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
            

            $pdf->SetFont('Arial','B',12);
            $pdf->cell(22,$alto,"Mis Datos ",'B0',2);
            $pdf->SetFont('Arial','',12);
            $pdf->cell(40,$alto,"Matricula: ",0,0);
            $pdf->cell(80,$alto, $matricula,0,0);
            $pdf->Ln();
            $pdf->cell(40,$alto,"Apellido y Nombre: ",0,0);
            $pdf->cell(80,$alto, $nombre,0,0);

            $pdf->Ln();
            $pdf->Ln();
            $pdf->SetFont('Arial','B',12);
            $pdf->cell(36,$alto,"Datos Comitente",'B0',2);
            $pdf->SetFont('Arial','',12);
            //$pdf->SetY(70);
            $pdf->Cell(40,$alto,"Comitente ",0);
            $pdf->Cell(80,$alto,$comitente,0);
            $pdf->Ln();
            $pdf->Cell(40,$alto,utf8_decode("Dirección"),0);
            $pdf->Cell(80,$alto,$direccion,0);
            $pdf->Ln();
            $pdf->Cell(40,$alto,"Localidad ",0);
            $pdf->Cell(80,$alto,$localidad,0);
            $pdf->Ln();
            $pdf->Ln();
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(120,$alto,"Montos a cobrar ",0,0,'C');
            $pdf->Ln();
            $pdf->Ln();
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(40,$alto,"Tipo ",'B1');
            $pdf->Cell(40,$alto,"Metros Cuadrados",'B1');
            $pdf->Cell(40,$alto,"Precio por metro",'B1');
            $pdf->Cell(40,$alto,"Monto",'B1',0,'C');
            $pdf->Ln();
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
                $pdf->Cell(40,$alto,utf8_decode("Relevamiento"),'R');
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
            if(isset($_POST['send'])){
                $archivo = $_FILES['plano'];
                // Undefined | Multiple Files | $_FILES Corruption Attack
                // If this request falls under any of them, treat it invalid.
                if (!isset($archivo['error']) || 
                    is_array($archivo['error'])){
                    throw new RuntimeException('Invalid parameters.');
                }

                $exttemp = explode(".",$_FILES['plano']['name']);
                $ext = $exttemp[1];

                // Check $_FILES['upfile']['error'] value.
                switch ($archivo['error']) {
                    case UPLOAD_ERR_OK:
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        throw new RuntimeException('No file sent.');
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        throw new RuntimeException('Exceeded filesize limit.');
                    default:
                        throw new RuntimeException('Unknown errors.');
                }

                // You should also check filesize here. 
                if ($archivo['size'] > 1000000) {
                    throw new RuntimeException('Exceeded filesize limit.');
                }

                //echo $archivo['tmp_name'];

                if(file_exists('uploads/plano_'.$matricula.".".$ext)){
                    unlink('uploads/plano_'.$matricula.".".$ext);
                }

                
                if (!move_uploaded_file(
                    $archivo['tmp_name'],
                    sprintf('uploads/%s.%s',
                        "plano_".$matricula,
                        $ext)
                )) {
                    throw new RuntimeException('Failed to move uploaded file.');
                }
                require_once("clases/mail.php");
                
                $smtp = 'mail.arquitectosmisiones.org.ar';
                $puerto = 9025;
                $usuario = 'autogestion@arquitectosmisiones.org.ar';
                $clave= 'autogestionZ';
                $asunto = $matricula.' - '.$nombre.' - '.$direccion;
                $from =array('autogestion@arquitectosmisiones.org.ar' => 'Sistema de Autogestion del Colegio de Arquitectos de la Prov. de Mnes.');
                $to = array( 'tasas@arquitectosmisiones.org.ar', $_SESSION['email']);
                $body = '<p>Datos de la obra en los archivos adjuntos</p>';
                $correo = new mail($puerto,$smtp,$from,$usuario,$clave,$asunto,$body,$to);
                
                
                $archivopdf = "reporte.pdf";
                $pdf->Output($archivopdf,'F');
                /*
                $smtp = 'smtp.gmail.com';
                $puerto = 587;
                $usuario = 'correogmail@gmail.com';
                $clave= 'contraseña';
                $asunto = 'Calculo de Tasas';
                $from =array('correogmail@gmail.com' => 'Sistema de Autogestion del Colegio de Arquitectos de la Prov. de Mnes.');
                $to = array( 'correogmail@gmail.com');
                $body = '<p>El Socio , DNI  y matricula , solicita la actualización de sus datos. Lo solicitado por el mismo es lo siguiente: <br></p><p></p>';
                $correo = new mail($puerto,$smtp,$from,$usuario,$clave,$asunto,$body,$to);
                */
                $correo->agregar_archivo($archivopdf);
                $correo->agregar_archivo('uploads/'.$matricula.".".$ext);

                    // Create the Transport
                $resultado = $correo->enviar_mail_con_archivos();
            }
            $pdf->Output();
        }    
    }else{
        ?>
            <script language="JavaScript" type="text/javascript">
                var pagina="index.php"                              
                location.href=pagina                            
            </script> 
        <?php    
    }
?>