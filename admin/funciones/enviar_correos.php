<?php
require '../../includes/conexion.php';
require '../includes/funciones.php';
if (isset($_GET['f']) && $_GET['f'] != '') {
    $funcion = escapar($_GET['f']);
    if (function_exists($funcion)) {
        call_user_func($funcion);
    }
}

function aceptar_usuario()
{
    $retorno = 0;
    global $mysqli;
    //saneo las variables
    $id = escapar($_POST['id']);
    $correo_usuario = escapar($_POST['correo']);
    $mensaje = escapar($_POST['mensaje']);

    $correos = array(); //Envio copia a ...
    $correo[0] = 'johnbabi.jb@gmail.com';
    $correo[1] = 'Babi Shoon';
    $correos[] = $correo;
    if ($correo_usuario != '') {
        $correo[0] = $correo_usuario;
        $correo[1] = 'Usuario';
        $correos[] = $correo;
    }

    $color_primario = "#1565c0";
    $color_primario_oscuro =  "#003c8f";
    $color_primario_claro =  "#5e92f3";

    $titulo =  "Alta de usuario Aceptada";
    $titulo_contenido = 'Querido Usuario';
    $contenido = $mensaje;

    $nombre_firma = "Sistema de Autogestón";
    $posicion_firma = "Trabajamos para mejorar el servicio";

    $empresa_logo_chico =  get_url() . '/imagenes/logo.jpg';
    $empresa_datos_1 =  'Colegio de Arquitectos';
    $empresa_datos_2 =  "Argentina, Misiones, Posadas.";

    $texto_final_aclaratorio = 'Cualquier consulta, no dudes en comunicarte con nosotros';

    $asunto = 'Alta de usuario Aceptada';

    $mensaje = get_modelo_notificacion($titulo, $titulo_contenido, $contenido, $nombre_firma, $posicion_firma, $texto_final_aclaratorio, $color_primario, $color_primario_claro, $color_primario_oscuro, $empresa_logo_chico, $empresa_datos_1, $empresa_datos_2);;

    if (enviar_correo($correos, $asunto, $mensaje)) {
        if ($mysqli->query("UPDATE tramites SET estado = 1 WHERE id = '$id' ")) {
            $retorno = 1;
        }
    }
    echo json_encode($retorno);
}


function cancelar_usuario()
{
    $retorno = 0;
    global $mysqli;
    //saneo las variables
    $id = escapar($_POST['id']);
    $correo_usuario = escapar($_POST['correo']);
    $mensaje = escapar($_POST['mensaje']);

    $correos = array(); //Envio copia a ...
    $correo[0] = 'johnbabi.jb@gmail.com';
    $correo[1] = 'Babi Shoon';
    $correos[] = $correo;
    if ($correo_usuario != '') {
        $correo[0] = $correo_usuario;
        $correo[1] = 'Usuario';
        $correos[] = $correo;
    }

    $color_primario = "#1565c0";
    $color_primario_oscuro =  "#003c8f";
    $color_primario_claro =  "#5e92f3";

    $titulo =  "Alta de usuario Cancelada";
    $titulo_contenido = 'Querido Usuario';
    $contenido = $mensaje;

    $nombre_firma = "Sistema de Autogestón";
    $posicion_firma = "Trabajamos para mejorar el servicio";

    $empresa_logo_chico =  get_url() . '/imagenes/logo.jpg';
    $empresa_datos_1 =  'Colegio de Arquitectos';
    $empresa_datos_2 =  "Argentina, Misiones, Posadas.";

    $texto_final_aclaratorio = 'Cualquier consulta, no dudes en comunicarte con nosotros';

    $asunto = 'Alta de usuario Cancelada';

    $mensaje = get_modelo_notificacion($titulo, $titulo_contenido, $contenido, $nombre_firma, $posicion_firma, $texto_final_aclaratorio, $color_primario, $color_primario_claro, $color_primario_oscuro, $empresa_logo_chico, $empresa_datos_1, $empresa_datos_2);;

    if (enviar_correo($correos, $asunto, $mensaje)) {
        if ($mysqli->query("UPDATE tramites SET estado = -1 WHERE id = '$id' ")) {
            $retorno = 1;
        }
    }
    echo json_encode($retorno);
}
