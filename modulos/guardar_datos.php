<?php
//Si estoy logueado registra que usuario realizo el trámite.
$usu_id = 0;
if (isset($_SESSION['matricula_socio'])) {
    $usuario = get_usuario($_SESSION['matricula_socio']);
    $usu_id = $usuario['usu_id'];
}

$form_id = '';

// inicializo la variable de los correos que se cargarán
$correos = array();
$archivos = array(); //archivos que se enviaran por correo

if (isset($_POST['form_id']) && $_POST['form_id'] != '') {
    $form_id = escapar($_POST['form_id']);
    $r = $mysqli->query("SELECT * FROM formularios WHERE id = '$form_id' ");
    if ($r->num_rows > 0) {
        $formulario = $r->fetch_assoc();
        $formulario['nombre'] = $formulario['nombre']; //ya formateo por si trae tildes
    }
} else {
    echo "<script>alert('Error'); window.location.href = 'home.php'; </script>";
}
$c = "INSERT INTO tramites (for_id, usu_id, fecha_realizado) VALUES ('$form_id', '$usu_id', '" . date('Y-m-d H:i:s') . "')";
$r = $mysqli->query($c);
$tra_id = $mysqli->insert_id;
$errores = array();
foreach ($_POST as $key => $campos) {
    if ($key != 'form_id') {
        //si el dato ingresado es un correo, agrego para enviar una copia del correo
        if (filter_var($campos, FILTER_VALIDATE_EMAIL)) {
            $correo[0] = $campos;
            $correos[] = $correo;
        }
        $fc_id = str_replace("campo", "", $key);
        $c = "INSERT INTO tramites_detalle (tra_id, fc_id, tipo_dato, valor, md5) 
                VALUES ('$tra_id', '$fc_id', '', '$campos', '" . md5($campos) . "') ";
        if (!$mysqli->query($c)) {
            $errores[] = $mysqli->error;
        }
    }
}

$cont = 1;
foreach ($_FILES as $key => $archivo) {
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
        $nombre_archivo = 'arc-' . $usu_id . '-' . $cont++ . "-" . time() . "." . $ext;
        if (move_uploaded_file($archivo['tmp_name'], 'archivos_md5/' . $nombre_archivo)) {
            $c = "INSERT INTO tramites_detalle (tra_id, fc_id, tipo_dato, valor, md5) 
                    VALUES ('$tra_id', '$fc_id', 'file', '$nombre_archivo', '" . md5_file('archivos_md5/' . $nombre_archivo) . "') ";
            if (!$mysqli->query($c)) {
                $errores[] = $mysqli->error;
            }
        } else {
            $errores[] = "No subio el archivo " . $nombre_archivo;
        }
    }
}

if (count($errores) > 0) {
    foreach ($errores as $error) {
        echo $error;
    }
} else {
    //genero el pdf del tramite y obtengo el resultado con datos
    $res = generar_pdf_tramite($tra_id);
    $res = json_decode($res);
    //si creo correctamente actualizo los datos del tramite
    if ($res->estado === true) {
        $c = "UPDATE tramites SET archivo = '" . $res->datos . "', archivo_md5 = '" . md5_file('archivos_md5/' . $res->datos) . "' WHERE id = '$tra_id' ";
        if ($mysqli->query($c)) {
            //si actualizo los datos del tramite envio un correo para informar al usuario
            include_once 'admin/includes/funciones.php';

            //cargo una copia de correo para enviar
            $correo[0] = 'johnbabi.jb@gmail.com';
            $correo[1] = 'Babi John';
            $correos[] = $correo;

            //archivos a enviar
            $archivos[] = 'archivos_md5/' . $res->datos;

            //configuracion del mail
            //colores
            $color_primario = "#1565c0";
            $color_primario_oscuro =  "#003c8f";
            $color_primario_claro =  "#5e92f3";

            //titulo, titulo de contenido y el contenido en si
            $titulo =  "Trámite Nro. #" . $tra_id;
            $titulo_contenido = 'Querido Usuario';
            $contenido = 'Gracias por realizar el trámite <strong>' . $formulario['nombre'] . '</strong> del Sistema de Autogestión, a la brevedad se le informará el estado del trámite. <br><br>Saludos cordiales.';

            //quien firma el correo y un detalle de la misma
            $nombre_firma = "Sistema de Autogestión";
            $posicion_firma = "Trabajamos para mejorar el servicio";

            //obtengo el logo completo de la empresa para mostrar correctamente en el mail
            // agrego datos adicionales al pie de pagina del correo
            $empresa_logo_chico =  get_url() . '/imagenes/logo.jpg';
            $empresa_datos_1 =  'Colegio de Arquitectos';
            $empresa_datos_2 =  "Argentina, Misiones, Posadas.";

            //Este texto se agrega al final del correo.
            $texto_final_aclaratorio = 'Cualquier consulta, no dudes en comunicarte con nosotros';


            $asunto = 'Trámite nro. ' . $tra_id . ' - ' . $formulario['nombre'] . ' realízada';
            $mensaje = get_modelo_notificacion($titulo, $titulo_contenido, $contenido, $nombre_firma, $posicion_firma, $texto_final_aclaratorio, $color_primario, $color_primario_claro, $color_primario_oscuro, $empresa_logo_chico, $empresa_datos_1, $empresa_datos_2);

            //echo $mensaje;
            if (!enviar_correo($correos, $asunto, $mensaje, $archivos)) {
                echo "<script>alert('Su trámite se generó correctamente pero Hubo un inconveniente al enviar el correo de comprobante.'); window.location.href = 'home.php?p=tramite_finalizado&id=$tra_id'; </script>";
            } else {
                echo "<script>alert('Su trámite se generó correctamente se ha enviado un correo de comprobante a su casilla.'); window.location.href = 'home.php?p=tramite_finalizado&id=$tra_id'; </script>";
            }
        } else {
            echo "<script>alert('Error inesperado por favor intente nuevamente'); window.location.href = 'home.php?p=formulario&id=$form_id'; </script>";
        }
    }
}
