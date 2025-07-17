<?php

// session_name('arquitectos');
include_once __DIR__ . '/../config/config.php';
@session_start();

$mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);

// ¡Oh, no! Existe un error 'connect_errno', fallando así el intento de conexión

if ($mysqli->connect_errno) {

    // La conexión falló. ¿Que vamos a hacer? 

    // Se podría contactar con uno mismo (¿email?), registrar el error, mostrar una bonita página, etc.

    // No se debe revelar información delicada



    // Probemos esto:

    echo "Lo sentimos, este sitio web está experimentando problemas.";



    // Algo que no se debería de hacer en un sitio público, aunque este ejemplo lo mostrará

    // de todas formas, es imprimir información relacionada con errores de MySQL -- se podría registrar

    echo "Error: Fallo al conectarse a MySQL debido a: \n";

    echo "Errno: " . $mysqli->connect_errno . "\n";

    echo "Error: " . $mysqli->connect_error . "\n";



    // Podría ser conveniente mostrar algo interesante, aunque nosotros simplemente saldremos

    exit;
}

$mysqli->set_charset('utf8');



function escapar($v)

{

    global $mysqli;

    return $mysqli->real_escape_string($v);
}



function get_usuario($cod)

{

    global $mysqli;

    $retorno = array();

    $cod = escapar($cod);

    $r = $mysqli->query("SELECT u.*, u.idusuario as usu_id FROM usuario u WHERE u.IDUSUARIO = '$cod' ");

    if ($r->num_rows > 0) {

        $retorno = $r->fetch_assoc();
    }

    return $retorno;
}
