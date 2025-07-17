<?php
require_once "connections/conexion.php";
require_once "connections/funciones.php";
function get_localidades()
{
    global $mysqli;
    $retorno = array();
    $r = $mysqli->query("SELECT * FROM localidad");
    while ($f = $r->fetch_assoc()) {
        $retorno[] = $f;
    }
    return $retorno;
}

function subir_imagen($archivo, $nombre, $carpeta = 'tmp')
{
    $retorno = array();
    $retorno['codigo'] = 0;
    $retorno['mensaje'] = 'No paso nada.';
    if ($archivo['error'] == 0) {
        $fc_id = str_replace("campo", "", $key);
        $ext = '';
        if ($archivo['type'] == "application/pdf") {
            $ext = "pdf";
        } else if ($archivo['type'] == "image/jpeg") {
            $ext = "jpg";
        } else if ($archivo['type'] == "image/png") {
            $ext = "png";
        }
        $nombre_archivo = $nombre . time() . "." . $ext;
        if (move_uploaded_file($archivo['tmp_name'], $carpeta . '/' . $nombre_archivo)) {
            $retorno['codigo'] = 1;
            $retorno['mensaje'] = 'Subio el archivo correctamente.';
        } else {
            $retorno['codigo'] = -1;
            $retorno['mensaje'] = 'No subio el archivo.';
        }
    }
    return $retorno;
}
