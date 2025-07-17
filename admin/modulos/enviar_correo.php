<?php
echo "Pruebas";

$correos = array();
$correo[0] = 'johnbabi.jb@gmail.com';
$correo[1] = 'Babi Shoon';
$correos[] = $correo;
$correo[0] = 'chinourquijo@gmail.com';
$correo[1] = 'Chinono';
//$correos[] = $correo;

$color_primario = "#1565c0";
$color_primario_oscuro =  "#003c8f";
$color_primario_claro =  "#5e92f3";

$titulo =  "Alta de usuario";
$titulo_contenido = 'Querido Usuario';
$contenido = '';

$nombre_firma = "Sistema de Autogestón";
$posicion_firma = "Trabajamos para mejorar el servicio";

$empresa_logo_chico =  get_url() . '/imagenes/logo.jpg';
$empresa_datos_1 =  'Colegio de Arquitectos';
$empresa_datos_2 =  "Argentina, Misiones, Posadas.";

$texto_final_aclaratorio = 'Cualquier consulta, no dudes en comunicarte con nosotros';

$asunto = 'Correo de Prueba';

$mensaje = get_modelo_notificacion($titulo, $titulo_contenido, $contenido, $nombre_firma, $posicion_firma, $texto_final_aclaratorio, $color_primario, $color_primario_claro, $color_primario_oscuro, $empresa_logo_chico, $empresa_datos_1, $empresa_datos_2);;
echo $mensaje;
//enviar_correo($correos, $asunto, $mensaje);
