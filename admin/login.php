<?php
require '../includes/conexion.php';
require '../includes/funciones.php';
if (isset($_GET['login']) && $_GET['login'] == 'ok') {
    $usuario = escapar($_POST['usuario']);
    $clave = escapar($_POST['clave']);
    $r = $mysqli->query("SELECT * FROM usuarios WHERE usuario = '$usuario' and clave = '$clave' ");
    if ($r->num_rows > 0) {
        $f = $r->fetch_assoc();
        $_SESSION['usuario'] = $f;
        header('Location: index.php');
    } else {
        echo "<script> alert('Usuario y/o Clave incorrectos'); window.location.href = 'login.php' </script>";
    }
    echo var_dump($_SESSION);
}

if (isset($_GET['logout']) && $_GET['logout'] == 'ok') {
    session_destroy();
    session_unset();
    unset($_SESSION);
    header('Location: index.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración</title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <form class="login" action="login.php?login=ok" method="post">
        <div class="fila">
            <label class="titulo-login">
                Bienvenido al sistema
            </label>
        </div>
        <div class="fila">
            <label for="usuario">Usuario</label>
            <input id="usuario" type="text" class="f-item" placeholder="Ingrese el usuario" name="usuario" />
        </div>
        <div class="fila">
            <label for="clave">Clave</label>
            <input id="clave" type="password" class="f-item" placeholder="Ingrese su Clave" name="clave" />
        </div>
        <div class="fila botones-login">
            <button type="submit" class="boton">Ingresar</button>
        </div>
    </form>
</body>

</html>