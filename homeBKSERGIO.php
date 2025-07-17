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

    <link rel="stylesheet" href="lib/bootstrap-4.3.1/css/bootstrap.css" />

    <link rel="stylesheet" href="mis_estilos/mis_estilos.css" />

    <link rel="stylesheet" href="css/home.css" />



    <script type="text/javascript" src="lib/jquery351.js"></script>

    <script src="lib/bootstrap-4.3.1/js/bootstrap.min.js"></script>

    <script type="text/javascript">

        function conf_salida() {

            if (confirm("Esta seguro de salir del Sistema? ")) {

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

                <div class="col-12">
                <?php if (isset($_GET['p']) && $_GET['p'] != '') {

                    $pagina = addslashes($_GET['p']);

                    include "modulos/" . $pagina . ".php";
                } 

                ?>


                </div>

            </div>

        </div>

    </div>

</body>



</html>