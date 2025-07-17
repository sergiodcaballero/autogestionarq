<?php
session_start();
if (isset($_SESSION['matricula_socio'])) {
    require_once 'connections/honorarios.php';
} else {
    ?><script language="JavaScript" type="text/javascript">
            var pagina="index.php"
            location.href=pagina
        </script> <?php
}
$_SESSION['SISTEMA_URL'] = SISTEMA_URL;
?>
<!DOCTYPE html>
<html lang="en">
  <head><meta charset="windows-1252">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Colegio de Arquitectos de la Provincia de Misiones</title>
    <link rel="stylesheet"  href="bootstrap/css/bootstrap.css" />
    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet" />

    <link rel="stylesheet" href="mis_estilos/mis_estilos.css" />
    <link href="mis_estilos/estilos_impresion.css" rel="stylesheet" type="text/css" media="print"/>
    <script type="text/javascript" src="bootstrap/js/jquery.md5.min.js"></script>
    <script type="text/javascript" src="ScriptLibrary/jquery-latest.pack.js"></script>
    <script src="bootstrap/js/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        function conf_salida(){
            if (confirm("¿Esta seguro de salir del Sistema? ")){
                var pagina="cerrar_sesion.php"

                                location.href=pagina
            }
        }
    </script>
    <style>
        .scrollspy-example {
          overflow: auto;
          position: relative;
        }
    </style>
</head>

<body>
     <div class="container">
        <div class="contenido">
            <div class="row-fluid">
                <div class=" span10 " style="margin-top:-2%;" >
                     <img src="imagenes/cabecera.png" class="img-rounded no_imprimir" style=" ">
                </div>
                <div class="span12">
                    <div class="row">

                        <div class="span4">
              <?php include('menu.php') ?>
            </div>
                        <div class="span8">
                            <br/>
                            <?php

$consulta = "select count(f.idfactura) as total FROM factura f inner join socios s on f.idsocio=s.idsocio
                            where matricula=" . $_SESSION['matricula_socio'] . " and f.estado='' ";
//print_r($consulta);
$resultado      = mysqli_query($conn, $consulta);
$Cantidad_Filas = mysqli_num_rows($resultado);
if ($Cantidad_Filas == 0) {
    //print_r('hola');
    $deuda = 0;
} else {
    while ($fila = mysqli_fetch_array($resultado)) {
        $deuda = $fila['total'];
        //$socio = $fila['nombre'];
    }
}
if ($deuda == 0) {
    include_once 'certificados/matricula.php';
    //}else if ($deuda>0 and $deuda<=1 ){ se modicia por cuarentena. El 04/04/2020 -- vER 12 es cambiar por 1
} else if ($deuda > 0 and $deuda <= 1) {
    include_once 'certificados/matricula.php';
} else if ($deuda > 1) {
    include_once 'certificados/con_deuda.php';
}
?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
</body>
</html>