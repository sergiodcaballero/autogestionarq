<?php 
session_start();
//print_r($_POST);
	if (isset($_SESSION['matricula_socio'])){
	//	$datos = explode("__", $_SESSION['id_orden']);		
		//if ($datos[0]==$_SESSION['N_Afiliado']){
			//$fecha_actual = date('Y')."".(date('m')-1)."".date('d');
			// $arrayDias[date('w')]." ".date('d')." de ".$arrayMeses[date('m')-1]." de ".date('Y');
			$fecha_actual = date('Ymd');
			$contenido = $_SESSION['SISTEMA_URL']."/verificador.php?c=".$_SESSION['matricula_socio']."-".$fecha_actual; 
			$codeContents = $contenido;
			include_once ('qrcode/phpqrcode.php');
			QRcode::png($codeContents);
			$raw = join("<br/>", $text); 
            $raw = strtr($raw, array( 
                '0' => '<span style="color:white">&#9608;&#9608;</span>', 
                '1' => '&#9608;&#9608;' 
            )); echo '<tt style="font-size:3px">'.$raw.'</tt>'; 
		//}
	//	unset($_SESSION['id_orden']);
		
	}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
</body>
</html>