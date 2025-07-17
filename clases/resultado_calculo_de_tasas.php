<?php
session_start();
if (isset($_SESSION['matricula_socio'])){
    require_once('connections/honorarios.php');
    if(isset($_POST)){
        if(isset($_POST['comitente']) && isset($_POST['direccion']) && isset($_POST['localidad']) && isset($_POST['tipo_de_obra'])){

            $tempar = explode("_",$_POST['tipo_de_obra']);

            $comitente = $_POST['comitente'];
            $direccion = $_POST['direccion'];
            $localidad = $_POST['localidad'];
            $matricula = $_POST['matricula'];
            $_SESSION['email'] = $_POST['email'];
            $nombre = $_POST['nombre'];
            $tipo_de_obra = $tempar[0];
            $m2_proyecto = $_POST['m2_proyecto'];
            $m2_direccion = $_POST['m2_direccion'];
            $m2_relevamiento = $_POST['m2_relevamiento'];
            //$archivo = $_POST['plano'];

            $_SESSION['comitente'] = $_POST['comitente'];
            $_SESSION['direccion'] = $_POST['direccion'];
            $_SESSION['localidad'] = $_POST['localidad'];
            $_SESSION['matricula'] = $_POST['matricula'];
            $_SESSION['nombre'] = $_POST['nombre'];
            $_SESSION['tipo_de_obra'] = $tipo_de_obra;
            $_SESSION['m2_proyecto'] = $_POST['m2_proyecto'];
            $_SESSION['m2_direccion'] = $_POST['m2_direccion'];
            $_SESSION['m2_relevamiento'] = $_POST['m2_relevamiento'];
            //$_SESSION['plano'] = $archivo;

            //$tipo_de_obra = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($tipo_de_obra) : mysql_escape_string($tipo_de_obra);

            $sql = "SELECT * FROM tipos_de_obra WHERE id = '".$tipo_de_obra."'";
            $resultado = mysqli_query($conn, $sql);
            if(mysqli_num_rows($resultado) > 0){
                $fila = mysqli_fetch_array($resultado);
                $x_metro = 12.40;
                $valor_x_metro = $x_metro * $fila['coef'];
                $valor_x_metro_p = $valor_x_metro * $fila['coef_p'];
                $valor_x_metro_do = $valor_x_metro * $fila['coef_do'];
                $valor_x_metro_e = $valor_x_metro * $fila['coef_e'];
                $monto_p = $m2_proyecto * $valor_x_metro_p;
                $monto_do = $m2_proyecto * $valor_x_metro_do;
                $monto_r = $m2_proyecto * $valor_x_metro_e;
                $total = $monto_p + $monto_do + $monto_r;
                $_SESSION['monto_p'] = $monto_p;
                $_SESSION['monto_do'] = $monto_do;
                $_SESSION['monto_r'] = $monto_r;
                $_SESSION['total'] = $total;
                $_SESSION['valor_metro_p'] = $valor_x_metro_p;
                $_SESSION['valor_metro_do'] = $valor_x_metro_do;
                $_SESSION['valor_metro_e'] = $valor_x_metro_e;
            }else{

            }

        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
<title>Colegio de Arquitectos de la Provincia de Misiones</title> 
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="bootstrap/css/bootstrap-responsive.css" />
    
    <link rel="stylesheet" href="mis_estilos/mis_estilos.css" />
    <script type="text/javascript" src="bootstrap/js/jquery.js"></script>
    <script type="text/javascript" src="ScriptLibrary/jquery-latest.pack.js"></script>
    <script src="bootstrap/js/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
</head>

<body>
     <div class="container">
        <div class="contenido">
            <div>
                <div><center><strong><h1>C&aacute;lculo de Tasas</h1></strong></center></div>
                <table class="table">
                    <tr>
                        <td colspan="2" style="text-align: left"><strong>Mis datos</strong></td>
                    </tr>
                    <tr>
                        <td>Nombre</td>
                        <td><?php echo $nombre ?></td>
                    </tr>
                    <tr>
                        <td>Matr&iacute;cula</td>
                        <td><?php echo $matricula ?></td>
                    </tr>
                </table>
                <table class="table">
                    <tr>
                        <td colspan="2" style="text-align: left"><strong>Datos de Obra</strong></td>
                    </tr>
                    <tr>
                        <td>Comitente</td>
                        <td><?php echo $comitente ?></td>
                    </tr>
                    <tr>
                        <td>Direcci&oacute;n</td>
                        <td><?php echo $direccion ?></td>
                    </tr>
                    <tr>
                        <td>Localidad</td>
                        <td><?php echo $localidad ?></td>
                    </tr>
                    <tr>
                        <td>Tipo de Obra</td>
                        <td><?php echo $fila['nombre'] ?></td>
                    </tr>
                </table>
                <table class="table">
                    <tr>
                        <td colspan="2" style="text-align: left"><strong>Montos a cobrar</strong></td>
                    </tr>
                    <th>
                        <tr>
                            <td>Proyecto</td>
                            <td style="text-align: right">Metros</td>
                            <td style="text-align: right">Valor por Metro</td>
                            <td style="text-align: right">Monto</td>  
                        </tr>
                    </th>
                    <tr>
                        <td>Proyecto</td>
                        <td style="text-align: right"><?php echo $m2_proyecto ?></td>
                        <td style="text-align: right"><?php echo "$ ".$valor_x_metro_p ?></td>
                        <td style="text-align: right"><?php echo "$ ".$monto_p ?></td>
                    </tr>
                    <tr>
                        <td>Direcci&oacute;n T&eacute;cnica</td>
                        <td style="text-align: right"><?php echo $m2_direccion ?></td>
                        <td style="text-align: right"><?php echo "$ ".$valor_x_metro_do ?></td>
                        <td style="text-align: right"><?php echo "$ ".$monto_do ?></td>
                    </tr>
                    <?php if($m2_relevamiento!=0){ ?>
                    <tr>
                        <td>Relevamiento</td>
                        <td style="text-align: right"><?php echo $m2_relevamiento ?></td>
                        <td style="text-align: right"><?php echo "$ ".$valor_x_metro_e ?></td>
                        <td style="text-align: right"><?php echo "$ ".$monto_r ?></td>
                    </tr>
                    <?php 
                            }
                    ?>
                    <tr>
                        <td></td>
                        <td colspan="2" style="text-align: right">Total: </td>
                        <td style="text-align: right"><?php echo "$ ".$total ?></td>
                    </tr>
                </table>
                <div id="botones" style="width: 100%;">
                    <form action="ver_resultado.php" method="post" enctype="multipart/form-data">
                        <div class="control-group" style="width: 100%">
                            <label class="control-label">Cargar Plano</label>
                            <div class="controls">
                              <input type="file" id="plano" name="plano" class="plano" required>
                            </div>
                        </div>
                        <div style="text-align: left; width: 49%; float: left;" class="col-md-6">
                              <input type="button" class="btn btn-default btn-lg" value="Volver" onclick="history.back()" />
                        </div>
                        <div style="text-align: right; width: 49%; float: left;" class="col-md-6">
                            <input type="hidden" name="send">
                            <button type="button" class="btn btn-default btn-lg">
                                <a  target="_blank" href="ver_resultado.php">
                              <img width = "18px" src="imagenes/pdf.png" class="glyphicon" aria-hidden="true"> Ver PDF</a>
                            </button>
                            <button type="submit" class="btn btn-default btn-lg">
                              <span class="glyphicon glyphicon-send" aria-hidden="true"></span> Enviar
                            </button>    
                        </div>
                    </form>
                    <div style="clear: both;"></div>
                </div>
            </div>
        </div>
     </div>
</body>
</html>