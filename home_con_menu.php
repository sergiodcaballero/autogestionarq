<?php
require "includes/conexion.php";
require "includes/funciones.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autogestión</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css" />
    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet" />
    <link rel="stylesheet" href="mis_estilos/mis_estilos.css" />
    <link rel="stylesheet" href="css/home.css" />

    <script type="text/javascript" src="bootstrap/js/jquery.md5.min.js"></script>
    <script type="text/javascript" src="ScriptLibrary/jquery-latest.pack.js"></script>
    <script src="bootstrap/js/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        function conf_salida() {
            if (confirm("�Esta seguro de salir del Sistema? ")) {
                var pagina = "cerrar_sesion.php"

                location.href = pagina
            }
        }
    </script>
</head>

<body>
    <div class="container">
        <div class="contenido">
            <div class="row-fluid">
                <div class=" span10" style="margin-top:-2%;">
                    <img src="imagenes/cabecera.png" class="img-rounded">
                </div>

                <div class="span12">
                    <div class="row">
                        <div class="span4">
                            <?php include "menu.php" ?>
                        </div>
                        <div class="span8">
                            <?php if (isset($_GET['p']) && $_GET['p'] != '') {
                                $pagina = addslashes($_GET['p']);
                                include "modulos/" . $pagina . ".php";
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>