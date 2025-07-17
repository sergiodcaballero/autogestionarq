<?php
$url_admin = dirname(__DIR__);

function ffecha($fecha, $formato = 'd/m/Y')
{
    return date($formato, strtotime($fecha));
}

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function enviar_correo($correos = array(), $asunto = 'Asunto del correo', $mensaje = 'Cuerpo del correo', $archivos = array(), $enviado_por = array('autogestion@arquitectosmisiones.org.ar', 'Sistema de Autogestión'), $responder_a = array())
{
    global $url_admin;
    require $url_admin . '/lib/PHPMailer/PHPMailer.php';
    require $url_admin . '/lib/PHPMailer/SMTP.php';
    require $url_admin . '/lib/PHPMailer/Exception.php';

    //Create a new PHPMailer instance
    $mail = new PHPMailer;

    //Tell PHPMailer to use SMTP
    $mail->isSMTP();

    //Enable SMTP debugging
    // SMTP::DEBUG_OFF = off (for production use)
    // SMTP::DEBUG_CLIENT = client messages
    // SMTP::DEBUG_SERVER = client and server messages
    $mail->SMTPDebug = SMTP::DEBUG_OFF;

    //Set the hostname of the mail server
    $mail->Host = 'mail.arquitectosmisiones.org.ar';
    // use
    // $mail->Host = gethostbyname('smtp.gmail.com');
    // if your network does not support SMTP over IPv6

    //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    $mail->Port = 587;

    //Set the encryption mechanism to use - STARTTLS or SMTPS
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;

    //Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = 'autogestion@arquitectosmisiones.org.ar';

    //Password to use for SMTP authentication
    $mail->Password = 'autogestionZ';

    //Set charset config
    $mail->CharSet = 'UTF-8';

    //Set who the message is to be sent from
    $mail->setFrom($enviado_por[0], $enviado_por[1]);

    //Set who the message is to be reply
    if (count($responder_a) > 0) {
        $mail->addReplyTo($responder_a[0], $responder_a[1]);
    }

    //Set an alternative reply-to address
    //$mail->addReplyTo('replyto@example.com', 'First Last');

    foreach ($correos as $correo) {
        //Set who the message is to be sent to
        // 0 mail 1 nombre
        $mail->addAddress($correo[0], $correo[1]);
    }

    //Set the subject line
    $mail->Subject = $asunto;

    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
    $mail->msgHTML($mensaje);

    //Replace the plain text body with one created manually
    $mail->AltBody = 'This is a plain-text message body';
    foreach ($archivos as $archivo) {
        //Attach an image file
        $mail->addAttachment($archivo);
    }

    //send the message, check for errors
    if (!$mail->send()) {
        //echo 'Mailer Error: ' . $mail->ErrorInfo;
        return false;
    } else {
        //echo 'Message sent!';
        return true;
        //Section 2: IMAP
        //Uncomment these to save your message in the 'Sent Mail' folder.
        #if (save_mail($mail)) {
        #    echo "Message saved!";
        #}
    }
}

function get_url_admin()
{
    return "http://" . $_SERVER['SERVER_NAME'] . '/' . basename(dirname(__DIR__, 2)) . '/' . basename(dirname(__DIR__, 1));
}

function get_url()
{
    return "http://" . $_SERVER['SERVER_NAME'] . '/' . basename(dirname(__DIR__, 2));
}

function get_modelo_notificacion($titulo = '', $titulo_contenido = '', $contenido = '', $nombre_firma = '', $posicion_firma = '', $texto_final_aclaratorio = '', $color_primario = '', $color_primario_claro = '', $color_primario_oscuro = '', $empresa_logo_chico, $empresa_datos_1, $empresa_datos_2)
{
    $datos_post = http_build_query(
        array(
            'color_primario' => $color_primario,
            'color_primario_claro' => $color_primario_claro,
            'color_primario_oscuro' => $color_primario_oscuro,
            'titulo' => $titulo,
            'titulo_contenido' => $titulo_contenido,
            'contenido' => $contenido,
            'nombre_firma' => $nombre_firma,
            'posicion_firma' => $posicion_firma,
            'empresa_logo_chico' => $empresa_logo_chico,
            'empresa_datos_1' => $empresa_datos_1,
            'empresa_datos_2' => $empresa_datos_2,
            'texto_final_aclaratorio' => $texto_final_aclaratorio,
        )
    );

    $opciones = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $datos_post
    ));

    $contexto = stream_context_create($opciones);
    $ruta = get_url_admin() . '/modelos/notificacion.php';
    $resultado = file_get_contents($ruta, false, $contexto);
    return $resultado;
}
