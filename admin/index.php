<?php
require '../includes/conexion.php';
require '../includes/funciones.php';
require 'includes/funciones.php';
if (!isset($_SESSION) || !isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>

    <link rel="stylesheet" href="lib/fancy/jquery.fancybox.min.css">

    <link rel="stylesheet" href="css/mi.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/menu.css">
    <!--
    <link rel="stylesheet" href="lib/bootstrap/css/bootstrap.min.css">
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
-->

    <script src="lib/jquery/jquery.js"></script>
    <script src="lib/fancy/jquery.fancybox.min.js"></script>
    <script src="js/index.js"></script>
</head>

<body>
    <div class="contenedor">
        <header>
            <div class="grid align-center">
                <div class="logo-header">
                    <img alt="Logo" title="Logo" src="../imagenes/logo2.jpg" />
                </div>
                <div class="titulo-header">
                    <label>Sistema de Gestión Administrativa</label>
                </div>
            </div>

        </header>

        <div class="menu">
            <label class="titulo-menu">Opciones</label>
            <ul>
                <li>
                    <a href="#">
                        <img alt="" title="" src="img/iconos/people-24.svg" />
                        <label>Altas de usuario</label>
                    </a>
                    <ul>
                        <li>
                            <a href="index.php?p=altas_usuarios&t=1">Usuarios Pendientes</a>
                        </li>
                        <li>
                            <a href="index.php?p=altas_usuarios_aceptados&t=1">Usuarios Aceptados</a>
                        </li>
                        <li>
                            <a href="index.php?p=altas_usuarios_cancelados&t=1">Usuarios Cancelados</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <img alt="" title="" src="img/iconos/people-24.svg" />
                        <label>Altas de Titulo</label>
                    </a>
                    <ul>
                        <li>
                            <a href="index.php?p=altas_usuarios&t=2">Titulo Pendientes</a>
                        </li>
                        <li>
                            <a href="index.php?p=altas_usuarios_aceptados&t=2">Tituloa Aceptados</a>
                        </li>
                        <li>
                            <a href="index.php?p=altas_usuarios_cancelados&t=2">Titulos Cancelados</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

        <section class="contenido">
            <?php
            if (isset($_GET['p']) && $_GET['p'] != '') {
                $pagina = escapar($_GET['p']);
                if (file_exists('modulos/' . $pagina . ".php")) {
                    include 'modulos/' . $pagina . ".php";
                }
            }
            ?>
        </section>

    </div>
    <footer>
        <label class="dev"></label>
        <label class="version">Software Versión 1.0</label>
    </footer>
</body>

</html>