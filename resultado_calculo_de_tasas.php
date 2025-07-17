<?php
session_start();
if (isset($_SESSION['matricula_socio'])) {
    require_once('connections/honorarios.php');
    if (isset($_POST)) {
        if (isset($_POST['comitente']) && isset($_POST['direccion']) && isset($_POST['localidad']) && isset($_POST['tipo_de_obra'])) {

            $tempar = explode("_", $_POST['tipo_de_obra']);


            $_SESSION['email'] = $_POST['email'];

            $tipo_de_obra = $tempar[0];
            if (isset($_POST['calculos_de_tasas']) && $_POST['calculos_de_tasas'] != '') {
                $calculos_de_tasas = $_POST['calculos_de_tasas'];
            } else {
                $m2_proyecto = $_POST['m2_proyecto'];
                $m2_direccion = $_POST['m2_direccion'];
                $m2_relevamiento = $_POST['m2_relevamiento'];
            }
            //$archivo = $_POST['plano'];

            $_SESSION['cuitcomitente'] = $_POST['cuitcomitente'];
            $_SESSION['comitente'] = $_POST['comitente'];
            $_SESSION['direccion'] = $_POST['direccion'];
            $_SESSION['localidad'] = $_POST['localidad'];
            $_SESSION['matricula'] = $_POST['matricula'];
            $_SESSION['nombre'] = $_POST['nombre'];
            $_SESSION['tipo_de_obra'] = $tipo_de_obra;

            $nombre = $_SESSION['nombre'];
            $cuitcomitente = $_SESSION['cuitcomitente'];
            $comitente = $_SESSION['comitente'];
            $direccion = $_SESSION['direccion'];
            $localidad = $_SESSION['localidad'];
            $matricula = $_SESSION['matricula'];

            if (isset($calculos_de_tasas)) {
                $_SESSION['calculos_de_tasas'] = $_POST['calculos_de_tasas'];
                $datos_calculos = json_decode($_POST['calculos_de_tasas']);
            } else {
                $_SESSION['m2_proyecto'] = $_POST['m2_proyecto'];
                $_SESSION['m2_direccion'] = $_POST['m2_direccion'];
                $_SESSION['m2_relevamiento'] = $_POST['m2_relevamiento'];
            }

            $c = "insert into tasas (cuit, comitente, direccion, localidad, matricula) values ('$cuitcomitente', '$comitente', '$direccion', '$localidad', '$matricula') ";
            $r = mysqli_query($conn, $c);
            $id_tasa = mysqli_insert_id($conn); 
            //$_SESSION['plano'] = $archivo;



            //$filas_proyectos[] = array();
            //$tipo_de_obra = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($tipo_de_obra) : mysql_escape_string($tipo_de_obra);
            if (isset($calculos_de_tasas)) {
                //var_dump($datos_calculos[0]);
                $total_tasa_cabecera = 0;
                for ($j = 0; $j < count($datos_calculos); $j++) {
                    $coeficientes = explode("_", $datos_calculos[$j][1]);
                    $m2_proyecto = $datos_calculos[$j][2];
                    $m2_direccion = $datos_calculos[$j][3];
                    $m2_relevamiento = $datos_calculos[$j][4];
                    //var_dump($coeficientes);
                    $sql = "SELECT * FROM tipos_de_obra WHERE id = '" . $coeficientes[0] . "'";
                    $resultado = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($resultado) > 0) {
                        $fila = mysqli_fetch_array($resultado);
                        //cambiar el valor del metro cuadrado 
                        //Ultima modificación 11-2019 
                        //
                        //$x_metro = 23.49; se actualizo el 22/02/2021 
                        //$x_metro = 32.49; se actualizo el 01/12/2021
                        //$x_metro = 85; se actualizo el 01/12/2022 ojo cambiar tambien en el else 
                        //$x_metro = 160;  se actualizo el 05/09/2023 ojo cambiar tambien en el else 
                        //$x_metro = 240;  se actualizo el 10/01/2024 ojo cambiar tambien en el else 
                        //$x_metro = 400; se actualizo el 30/07/2024 ojo cambiar tambien en el else 
                        //$x_metro = 690; se actualizo el 15/01/2025 ojo cambiar tambien en el else 
                        //$x_metro = 750; se actualizo el 27/05/2025 ojo cambiar tambien en el else 
                        $x_metro = 1100;
                        //
                        //
                        //
                        $valor_x_metro = $x_metro * $fila['coef'];
                        $valor_x_metro_p = $valor_x_metro * $fila['coef_p'];
                        $valor_x_metro_do = $valor_x_metro * $fila['coef_do'];
                        $valor_x_metro_e = $valor_x_metro * $fila['coef_e'];
                        $monto_p = $m2_proyecto * $valor_x_metro_p;
                        $monto_do = $m2_direccion * $valor_x_metro_do;
                        $monto_r = $m2_relevamiento * $valor_x_metro_e;
                        $total = $monto_p + $monto_do + $monto_r;
                        $total_tasa_cabecera += $total;
                        $_SESSION['monto_p'] = $monto_p;
                        $_SESSION['monto_do'] = $monto_do;
                        $_SESSION['monto_r'] = $monto_r;
                        $_SESSION['total'] = $total;
                        $_SESSION['valor_metro_p'] = $valor_x_metro_p;
                        $_SESSION['valor_metro_do'] = $valor_x_metro_do;
                        $_SESSION['valor_metro_e'] = $valor_x_metro_e;

                        //inserto el detalle de la tasa
                        $c = "insert into tasas_detalle (id_tasa, proyecto, direccion_tecnica, relevamiento, id_tipo_obra, total) values ('$id_tasa', '$m2_proyecto', '$m2_direccion', '$m2_relevamiento', '{$coeficientes[0]}', '$total' ) ";
                        $r = mysqli_query($conn, $c);
                        $filas_proyectos[] = array($fila['nombre'], $m2_proyecto, $m2_direccion, $m2_relevamiento, round($total, 2));
                    }
                    $c= "update tasas set total = '$total_tasa_cabecera' where id = '$id_tasa' ";
                    mysqli_query($conn, $c);
                }
            } else {
                $sql = "SELECT * FROM tipos_de_obra WHERE id = '" . $tipo_de_obra . "'";
                $resultado = mysqli_query($conn, $sql);
                if (mysqli_num_rows($resultado) > 0) {
                    $fila = mysqli_fetch_array($resultado);
                    //$x_metro = 160; modificacion 05/09/2023
                    // CAMBIAR AQUI EL  VALOR DE LOS METROS 
                    $x_metro = 1100;
                    $valor_x_metro = $x_metro * $fila['coef'];
                    $valor_x_metro_p = $valor_x_metro * $fila['coef_p'];
                    $valor_x_metro_do = $valor_x_metro * $fila['coef_do'];
                    $valor_x_metro_e = $valor_x_metro * $fila['coef_e'];
                    $monto_p = $m2_proyecto * $valor_x_metro_p;
                    $monto_do = $m2_direccion * $valor_x_metro_do;
                    $monto_r = $m2_relevamiento * $valor_x_metro_e;
                    $total = $monto_p + $monto_do + $monto_r;
                    $_SESSION['monto_p'] = $monto_p;
                    $_SESSION['monto_do'] = $monto_do;
                    $_SESSION['monto_r'] = $monto_r;
                    $_SESSION['total'] = $total;
                    $_SESSION['valor_metro_p'] = $valor_x_metro_p;
                    $_SESSION['valor_metro_do'] = $valor_x_metro_do;
                    $_SESSION['valor_metro_e'] = $valor_x_metro_e;
                    
                    //inserto el detalle de la tasa
                    $c = "insert into tasas_detalle (id_tasa, proyecto, direccion_tecnica, relevamiento, id_tipo_obra, total) values ('$id_tasa', '$m2_proyecto', '$m2_direccion', '$m2_relevamiento', '{$coeficientes[0]}', '$total' ) ";
                    $r = mysqli_query($conn, $c);

                    $filas_proyectos[] = array($fila['nombre'], $m2_proyecto, $m2_direccion, $m2_relevamiento, round($total, 2));
                }
            }

?>
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

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
                <style type="text/css">
                    .td-datos {
                        text-align: center !important;
                    }
                </style>
            </head>

            <body>
                <div class="container">
                    <div class="contenido">
                        <div>
                            <div>
                                <center><strong>
                                        <h1>C&aacute;lculo de Tasas</h1>
                                    </strong></center>
                            </div>
                            <table class="table">
                                <tr>
                                    <td colspan="2" style="text-align: left"><strong>Mis datos</strong></td>
                                </tr>
                                <tr>
                                    <td>CUIT</td>
                                    <td><?php echo @$cuitcomitente ?></td>
                                </tr>
                                <tr>
                                    <td>Nombre</td>
                                    <td><?php echo @$nombre ?></td>
                                </tr>
                                <tr>
                                    <td>Matr&iacute;cula</td>
                                    <td><?php echo @$matricula ?></td>
                                </tr>
                            </table>
                            <table class="table">
                                <tr>
                                    <td colspan="2" style="text-align: left"><strong>Datos de Obra</strong></td>
                                </tr>
                                <tr>
                                    <td>Comitente</td>
                                    <td><?php echo @($comitente) ?></td>
                                </tr>
                                <tr>
                                    <td>Direcci&oacute;n</td>
                                    <td><?php echo @($direccion) ?></td>
                                </tr>
                                <tr>
                                    <td>Localidad</td>
                                    <td><?php echo @($localidad) ?></td>
                                </tr>
                            </table>
                            <table class="table">

                                <thead>
                                    <tr>
                                        <td colspan="2" style="text-align: left"><strong>Montos a cobrar</strong></td>
                                    </tr>
                                    <th>
                                        <tr>
                                            <td>Proyecto</td>
                                            <td class="td-datos">M2 Proyecto</td>
                                            <td class="td-datos">M2 Direccion</td>
                                            <td class="td-datos">M2 Relevamiento</td>
                                            <td style="text-align: right">Total</td>
                                        </tr>
                                    </th>
                                </thead>
                                <tbody>
                                    <?php
                                    $totalgral = 0;
                                    @$_SESSION['filas_proyectos'] = json_encode($filas_proyectos);
                                    for ($i = 0; $i < count(@$filas_proyectos); $i++) {
                                        $totalgral += $filas_proyectos[$i][4];
                                    ?>
                                        <tr>
                                            <td><?php echo $filas_proyectos[$i][0]; ?></td>
                                            <td class="td-datos"><?php echo $filas_proyectos[$i][1]; ?></td>
                                            <td class="td-datos"><?php echo $filas_proyectos[$i][2]; ?></td>
                                            <td class="td-datos"><?php echo $filas_proyectos[$i][3]; ?></td>
                                            <td style="text-align: right"><?php echo "<strong>$</strong>" . $filas_proyectos[$i][4]; ?></td>
                                        </tr>
                                    <?php

                                    }
                                    ?>
                                </tbody>
                                <!--
                    <tr>
                        <td>Proyecto</td>
                        <td style="text-align: right"><?php echo $m2_proyecto ?></td>
                        <td style="text-align: right"><?php echo "$ " . $valor_x_metro_p ?></td>
                        <td style="text-align: right"><?php echo "$ " . $monto_p ?></td>
                    </tr>
                    <tr>
                        <td>Direcci&oacute;n T&eacute;cnica</td>
                        <td style="text-align: right"><?php echo $m2_direccion ?></td>
                        <td style="text-align: right"><?php echo "$ " . $valor_x_metro_do ?></td>
                        <td style="text-align: right"><?php echo "$ " . $monto_do ?></td>
                    </tr>
                    <?php if ($m2_relevamiento != 0) { ?>
                    <tr>
                        <td>Relevamiento</td>
                        <td style="text-align: right"><?php echo $m2_relevamiento ?></td>
                        <td style="text-align: right"><?php echo "$ " . $valor_x_metro_e ?></td>
                        <td style="text-align: right"><?php echo "$ " . $monto_r ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                    -->
                                <tr>
                                    <td></td>
                                    <td colspan="3" style="text-align: right">Total: </td>
                                    <td style="text-align: right"><?php echo "<strong>$</strong>" . $totalgral ?></td>
                                </tr>

                            </table>
                            <div id="botones" style="width: 100%;">
                                <form action="ver_resultado.php" method="post" enctype="multipart/form-data">
                                    <div class="control-group" style="width: 100%; border: 1px solid;">
                                        <div style="padding: 10px;">
                                            <label class="control-label">Cargar Planta/s en archivo .pdf</label>
                                            <span>Puede subir multiples archivos</span>
                                            <div class="controls">
                                                <input type="file" id="plano" name="plano[]" class="plano" accept="image/png, .jpeg, .jpg, image/gif, .pdf" multiple="true" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="text-align: left; width: 49%; float: left;" class="col-md-6">
                                        <button type="button" class="btn btn-default btn-lg">
                                            <a href="principal.php">
                                                <img width="18px" src="imagenes/volver.png" class="glyphicon" aria-hidden="true"> Volver</a>
                                        </button>
                                    </div>
                                    <div style="text-align: right; width: 49%; float: left;" class="col-md-6">
                                        <input type="hidden" name="send">
                                        <button type="button" class="btn btn-default btn-lg">
                                            <a target="_blank" href="ver_resultado.php">
                                                <img width="18px" src="imagenes/pdf.png" class="glyphicon" aria-hidden="true"> Ver PDF</a>
                                        </button>
                                        <button type="submit" class="btn btn-default btn-lg">
                                            <img width="18px" src="imagenes/enviar.png" class="glyphicon" aria-hidden="true"> Enviar
                                        </button>
                                    </div>
                                </form>
                                <div style="clear: both;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
            <script type="text/javascript">

            </script>

            </html>
        <?php } else {
        ?>
            <script type="text/javascript">
                window.location.href = 'calculo_tasas.php';
            </script>
        <?php
        }
    } else {
        ?>
        <script type="text/javascript">
            window.location.href = 'calculo_tasas.php';
        </script>
    <?php
    }
} else {
    ?>
    <script type="text/javascript">
        window.location.href = 'calculo_tasas.php';
    </script>
<?php
}
?>