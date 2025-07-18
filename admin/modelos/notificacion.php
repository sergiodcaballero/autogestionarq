<?php
$color_primario = isset($_POST['color_primario']) ? $_POST['color_primario'] : "#aaddaa";
$color_primario_oscuro = isset($_POST['color_primario_oscuro']) ? $_POST['color_primario_oscuro'] : "#aa55aa";
$color_primario_claro = isset($_POST['color_primario_claro']) ? $_POST['color_primario_claro'] : "#aaffaa";

$titulo = isset($_POST['titulo']) ? $_POST['titulo'] : "Título del correo";
$titulo_contenido = isset($_POST['titulo_contenido']) ? $_POST['titulo_contenido'] : '';
$contenido = isset($_POST['contenido']) ? $_POST['contenido'] : 'contenido';

$nombre_firma = isset($_POST['nombre_firma']) ? $_POST['nombre_firma'] : "Persona que firma el correo";
$posicion_firma = isset($_POST['posicion_firma']) ? $_POST['posicion_firma'] : "Posición en la empresa";

$empresa_logo_chico = isset($_POST['empresa_logo_chico']) ? $_POST['empresa_logo_chico'] : '../../imagenes/logo.jpg';
$empresa_datos_1 = isset($_POST['empresa_datos_1']) ? $_POST['empresa_datos_1'] : 'Dirección de la empresa';
$empresa_datos_2 = isset($_POST['empresa_datos_2']) ? $_POST['empresa_datos_2'] : "Argentina, Misiones, Posadas.";

$texto_final_aclaratorio = isset($_POST['texto_final_aclaratorio']) ? $_POST['texto_final_aclaratorio'] : 'Cualquier consulta no dudes en comunicarte con nosotros';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" style="width:100%;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0">

<head>
    <meta http-equiv="Content-Security-Policy" content="script-src 'none'; connect-src 'none'; object-src 'none'; form-action 'none';">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="telephone=no" name="format-detection">
    <title>Correo</title>
    <!--[if (mso 16)]>
    <style type="text/css">
    a {text-decoration: none;}
    </style>
    <![endif]-->
    <!--[if gte mso 9]><style>sup { font-size: 100% !important; }</style><![endif]-->
    <!--[if !mso]><!-- -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i" rel="stylesheet">
    <!--<![endif]-->
    <!--[if gte mso 9]>
<xml>
    <o:OfficeDocumentSettings>
    <o:AllowPNG></o:AllowPNG>
    <o:PixelsPerInch>96</o:PixelsPerInch>
    </o:OfficeDocumentSettings>
</xml>
<![endif]-->
    <!--<link rel="shortcut icon" type="image/png" href="https://stripo.email/assets/img/favicon.png">-->
    <style type="text/css">
        #outlook a {
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }

        .es-button {
            mso-style-priority: 100 !important;
            text-decoration: none !important;
        }

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        .es-desk-hidden {
            display: none;
            float: left;
            overflow: hidden;
            width: 0;
            max-height: 0;
            line-height: 0;
            mso-hide: all;
        }

        @media only screen and (max-width:600px) {

            p,
            ul li,
            ol li,
            a {
                font-size: 16px !important;
                line-height: 150% !important
            }

            h1 {
                font-size: 32px !important;
                text-align: left;
                line-height: 120% !important
            }

            h2 {
                font-size: 26px !important;
                text-align: left;
                line-height: 120% !important
            }

            h3 {
                font-size: 20px !important;
                text-align: left;
                line-height: 120% !important
            }

            h1 a {
                font-size: 36px !important;
                text-align: left
            }

            h2 a {
                font-size: 30px !important;
                text-align: left
            }

            h3 a {
                font-size: 18px !important;
                text-align: left
            }

            .es-menu td a {
                font-size: 16px !important
            }

            .es-header-body p,
            .es-header-body ul li,
            .es-header-body ol li,
            .es-header-body a {
                font-size: 16px !important
            }

            .es-footer-body p,
            .es-footer-body ul li,
            .es-footer-body ol li,
            .es-footer-body a {
                font-size: 16px !important
            }

            .es-infoblock p,
            .es-infoblock ul li,
            .es-infoblock ol li,
            .es-infoblock a {
                font-size: 12px !important
            }

            *[class="gmail-fix"] {
                display: none !important
            }

            .es-m-txt-c,
            .es-m-txt-c h1,
            .es-m-txt-c h2,
            .es-m-txt-c h3 {
                text-align: center !important
            }

            .es-m-txt-r,
            .es-m-txt-r h1,
            .es-m-txt-r h2,
            .es-m-txt-r h3 {
                text-align: right !important
            }

            .es-m-txt-l,
            .es-m-txt-l h1,
            .es-m-txt-l h2,
            .es-m-txt-l h3 {
                text-align: left !important
            }

            .es-m-txt-r img,
            .es-m-txt-c img,
            .es-m-txt-l img {
                display: inline !important
            }

            .es-button-border {
                display: inline-block !important
            }

            a.es-button {
                font-size: 16px !important;
                display: inline-block !important;
                border-width: 15px 30px 15px 30px !important
            }

            .es-btn-fw {
                border-width: 10px 0px !important;
                text-align: center !important
            }

            .es-adaptive table,
            .es-btn-fw,
            .es-btn-fw-brdr,
            .es-left,
            .es-right {
                width: 100% !important
            }

            .es-content table,
            .es-header table,
            .es-footer table,
            .es-content,
            .es-footer,
            .es-header {
                width: 100% !important;
                max-width: 600px !important
            }

            .es-adapt-td {
                display: block !important;
                width: 100% !important
            }

            .adapt-img {
                width: 100% !important;
                height: auto !important
            }

            .es-m-p0 {
                padding: 0px !important
            }

            .es-m-p0r {
                padding-right: 0px !important
            }

            .es-m-p0l {
                padding-left: 0px !important
            }

            .es-m-p0t {
                padding-top: 0px !important
            }

            .es-m-p0b {
                padding-bottom: 0 !important
            }

            .es-m-p20b {
                padding-bottom: 20px !important
            }

            .es-mobile-hidden,
            .es-hidden {
                display: none !important
            }

            tr.es-desk-hidden,
            td.es-desk-hidden,
            table.es-desk-hidden {
                width: auto !important;
                overflow: visible !important;
                float: none !important;
                max-height: inherit !important;
                line-height: inherit !important
            }

            tr.es-desk-hidden {
                display: table-row !important
            }

            table.es-desk-hidden {
                display: table !important
            }

            td.es-desk-menu-hidden {
                display: table-cell !important
            }

            table.es-table-not-adapt,
            .esd-block-html table {
                width: auto !important
            }

            table.es-social {
                display: inline-block !important
            }

            table.es-social td {
                display: inline-block !important
            }
        }
    </style>
    <meta property="og:title" content="Correo" />
    <meta property="og:type" content="article" />
</head>

<body style="width:100%;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0">
    <div class="es-wrapper-color" style="background-color:#EEEEEE">
        <!--[if gte mso 9]>
			<v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t">
				<v:fill type="tile" color="#eeeeee"></v:fill>
			</v:background>
		<![endif]-->
        <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top">
            <tbody>
                <tr style="border-collapse:collapse">
                    <td valign="top" style="padding:0;Margin:0">
                        <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%">
                            <tbody>
                                <tr style="border-collapse:collapse">
                                    <td align="center" style="padding:0;Margin:0">
                                        <table class="es-content-body" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;width:600px" cellspacing="0" cellpadding="0" align="center">
                                            <tbody>
                                                <tr style="border-collapse:collapse">
                                                    <td align="left" style="Margin:0;padding-left:10px;padding-right:10px;padding-top:15px;padding-bottom:15px">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%">
                            <tbody>
                                <tr style="border-collapse:collapse"></tr>
                                <tr style="border-collapse:collapse">
                                    <td align="center" style="padding:0;Margin:0">
                                        <table class="es-header-body" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:<?php echo $color_primario_oscuro ?>;width:600px" cellspacing="0" cellpadding="0" bgcolor="<?php echo $color_primario_oscuro ?>" align="center">
                                            <tbody>
                                                <tr style="border-collapse:collapse">
                                                    <td align="left" style="Margin:0;padding-top:35px;padding-left:35px;padding-right:35px;padding-bottom:40px">
                                                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                            <tbody>
                                                                <tr style="border-collapse:collapse">
                                                                    <td valign="top" align="center" style="padding:0;Margin:0;width:530px">
                                                                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                                            <tbody>
                                                                                <tr style="border-collapse:collapse">
                                                                                    <td class="es-m-txt-c" align="center" style="padding:0;Margin:0">
                                                                                        <h1 style="Margin:0;line-height:36px;mso-line-height-rule:exactly;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;font-size:36px;font-style:normal;font-weight:bold;color:#FFFFFF"><?php echo $titulo ?></h1>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%">
                            <tbody>
                                <tr style="border-collapse:collapse">
                                    <td align="center" style="padding:0;Margin:0">
                                        <table class="es-content-body" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px">
                                            <tbody>
                                                <tr style="border-collapse:collapse">
                                                    <td align="left" style="Margin:0;padding-bottom:25px;padding-top:35px;padding-left:35px;padding-right:35px">
                                                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                            <tbody>
                                                                <tr style="border-collapse:collapse">
                                                                    <td valign="top" align="center" style="padding:0;Margin:0;width:530px">
                                                                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                                            <tbody>
                                                                                <tr style="border-collapse:collapse">
                                                                                    <td align="left" style="padding:0;Margin:0;padding-bottom:5px;padding-top:20px">
                                                                                        <h3 style="Margin:0;line-height:22px;mso-line-height-rule:exactly;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;font-size:18px;font-style:normal;font-weight:bold;color:#333333"><?php echo $titulo_contenido ?><br></h3>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr style="border-collapse:collapse">
                                                                                    <td align="left" style="padding:0;Margin:0;padding-bottom:10px;padding-top:15px">
                                                                                        <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:16px;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;line-height:24px;color:#777777"><?php echo $contenido ?></p>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr style="border-collapse:collapse">
                                                                                    <td align="left" style="padding:0;Margin:0;padding-top:40px">
                                                                                        <h3 style="Margin:0;line-height:22px;mso-line-height-rule:exactly;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;font-size:18px;font-style:normal;font-weight:bold;color:#333333"><?php echo $nombre_firma ?></h3>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr style="border-collapse:collapse">
                                                                                    <td align="left" style="padding:0;Margin:0">
                                                                                        <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:16px;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;line-height:24px;color:#777777"><?php echo $posicion_firma ?></p>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%">
                            <tbody>
                                <tr style="border-collapse:collapse">
                                    <td align="center" style="padding:0;Margin:0">
                                        <table class="es-content-body" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px">
                                            <tbody>
                                                <tr style="border-collapse:collapse">
                                                    <td align="left" style="padding:0;Margin:0;padding-top:15px;padding-left:35px;padding-right:35px">
                                                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                            <tbody>
                                                                <tr style="border-collapse:collapse">
                                                                    <td valign="top" align="center" style="padding:0;Margin:0;width:530px">
                                                                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                                            <tbody>
                                                                                <tr style="border-collapse:collapse">
                                                                                    <td align="center" style="padding:0;Margin:0;font-size:0"><img src="#" alt style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic" width="46"></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;">
                            <tbody>
                                <tr style="border-collapse:collapse">
                                    <td align="center" style="padding:0;Margin:0">
                                        <table class="es-content-body" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color: <?php echo $color_primario ?>;width:600px;border-bottom:10px solid <?php echo $color_primario_oscuro ?>; min-height: 60px;" cellspacing="0" cellpadding="0" bgcolor="#1b9ba3" align="center">
                                            <tbody>
                                                <tr style="border-collapse:collapse">
                                                    <td align="left" style="padding:0;Margin:0">
                                                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                            <tbody>
                                                                <tr style="border-collapse:collapse">
                                                                    <td valign="top" align="center" style="padding:0;Margin:0;width:600px">
                                                                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                                            <tbody>
                                                                                <tr style="border-collapse:collapse">
                                                                                    <td style="padding:0;Margin:0">
                                                                                        <table class="es-menu" width="40%" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                                                            <tbody>
                                                                                                <tr class="links-images-top" style="border-collapse:collapse;">
                                                                                                    <!-- LOGOS DE REDES
                                                                                                    <td style="Margin:0;padding-left:5px;padding-right:5px;padding-top:35px;padding-bottom:30px;border:0" width="25.00%" bgcolor="transparent" align="center"><a target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;font-size:20px;text-decoration:none;display:block;color:#FFFFFF" href=""><img src="https://tlr.stripocdn.email/content/guids/CABINET_3ef3c4a0538c293f4c84f503cd8af2cc/images/60961522067175378.png" alt title height="27" align="absmiddle" style="display:inline-block !important;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;padding-bottom:5px"><br></a></td>
                                                                                                    <td style="Margin:0;padding-left:5px;padding-right:5px;padding-top:35px;padding-bottom:30px;border:0" width="25.00%" bgcolor="transparent" align="center"><a target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;font-size:20px;text-decoration:none;display:block;color:#FFFFFF" href=""><img src="https://tlr.stripocdn.email/content/guids/CABINET_3ef3c4a0538c293f4c84f503cd8af2cc/images/72681522067183042.png" alt title height="27" align="absmiddle" style="display:inline-block !important;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;padding-bottom:5px"><br></a></td>
                                                                                                    <td style="Margin:0;padding-left:5px;padding-right:5px;padding-top:35px;padding-bottom:30px;border:0" width="25.00%" bgcolor="transparent" align="center"><a target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;font-size:20px;text-decoration:none;display:block;color:#FFFFFF" href=""><img src="https://tlr.stripocdn.email/content/guids/CABINET_3ef3c4a0538c293f4c84f503cd8af2cc/images/76121522068412489.jpg" alt title height="27" align="absmiddle" style="display:inline-block !important;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;padding-bottom:5px"><br></a></td>
                                                                                                    <td style="Margin:0;padding-left:5px;padding-right:5px;padding-top:35px;padding-bottom:30px;border:0" width="25.00%" bgcolor="transparent" align="center"><a target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;font-size:20px;text-decoration:none;display:block;color:#FFFFFF" href=""><img src="https://tlr.stripocdn.email/content/guids/CABINET_3ef3c4a0538c293f4c84f503cd8af2cc/images/12411522072775563.jpg" alt title height="27" align="absmiddle" style="display:inline-block !important;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;padding-bottom:5px"><br></a></td>
                                                                                                    -->
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" class="es-footer" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top">
                            <tbody>
                                <tr style="border-collapse:collapse">
                                    <td align="center" style="padding:0;Margin:0">
                                        <table class="es-footer-body" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px">
                                            <tbody>
                                                <tr style="border-collapse:collapse">
                                                    <td align="left" style="Margin:0;padding-top:35px;padding-left:35px;padding-right:35px;padding-bottom:40px">
                                                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                            <tbody>
                                                                <tr style="border-collapse:collapse">
                                                                    <td valign="top" align="center" style="padding:0;Margin:0;width:530px">
                                                                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                                            <tbody>
                                                                                <tr style="border-collapse:collapse">
                                                                                    <td align="center" style="padding:0;Margin:0;padding-bottom:15px;font-size:0"><img src="<?php echo $empresa_logo_chico ?>" alt="Logo" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic" title="Logo Empresa" width="50px"></td>
                                                                                </tr>
                                                                                <tr style="border-collapse:collapse">
                                                                                    <td align="center" style="padding:0;Margin:0;padding-bottom:35px">
                                                                                        <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;line-height:21px;color:#333333"><strong><?php echo $empresa_datos_1 ?></strong></p>
                                                                                        <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;line-height:21px;color:#333333"><strong><?php echo $empresa_datos_2 ?></strong></p>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr style="border-collapse:collapse">
                                                                                    <td esdev-links-color="#777777" align="left" class="es-m-txt-c" style="padding:0;Margin:0;padding-bottom:5px">
                                                                                        <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;line-height:21px;color:#777777"><?php echo $texto_final_aclaratorio ?></u>.</p>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>