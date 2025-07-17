<?php
require('connections/honorarios.php');
include('includes/funciones.php');
if(isset($_GET) && $_GET['c'] != ''){
	$dato=explode("-", $_GET['c']);
	$matricula=mysqli_real_escape_string($conn, $dato[0]);
	$fecha_actual=date('Y-m-d');
	$fecha_certificado=date('Y-m-d',strtotime(mysqli_real_escape_string($conn, $dato[1])));
	$fecha_validez = date('Y-m-d',strtotime($fecha_certificado.' +1 month '));

	$r=mysqli_query($conn,"SELECT NOMBRE, N_DOCUMENTO FROM socios WHERE matricula='$matricula'");
	if(mysqli_num_rows($r)>0){
		$f=mysqli_fetch_assoc($r);
		$nombre = $f['NOMBRE'];
		$documento=$f['N_DOCUMENTO'];
		//$deuda = true;
		//$mensaje = 'NO ES VÁLIDO AL DÍA DE LA FECHA';
		//$class_css = 'invalido';
		//if(calcular_deuda($matricula)==0 && $fecha_actual <= $fecha_validez){
			$deuda=false;
			$mensaje = 'ES VÁLIDO AL DÍA DE LA FECHA';
			$class_css='valido';
		//}		
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<title>Verificador de Prácticas</title>
	<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet"> 
	<style type="text/css">
		body{
			background: #CCC;
    		font-family: 'Roboto', sans-serif;
		}
		.info{
			max-width: 800px;
			margin: 1rem;
			background: white;
			-webkit-box-shadow: -10px 10px 40px -6px rgba(74,74,74,0.35);
			-moz-box-shadow: -10px 10px 40px -6px rgba(74,74,74,0.35);
			box-shadow: -10px 10px 40px -6px rgba(74,74,74,0.35);
		}
		.titulo, .contenido{
			padding: 1rem;
		}
		.titulo, .contenido{
			text-align: center;
		}
		.contenido{
			text-align: justify-all;
		}
		.mensaje{
			padding: .5rem 1rem .5rem 1rem ;
			margin-top: 2rem;
		}
		.valido{
			background: green;
			color: white;
		}

		.invalido{
			background: red;
			color: white;
		}
	</style>
</head>
<body>
	<div class="info">
		<div class="titulo">
			<h1>Verificador de Certificados</h1>
		</div>
		<div class="contenido">
			El Presente certificado emitido el <strong><?php echo date('d/m/Y',strtotime($fecha_certificado)); ?></strong>.<br>
			Del Matriculado <strong><?php echo $nombre ?></strong> con <strong>DNI <?php echo number_format($documento,0,",",".") ?></strong><br>
			<div class="mensaje <?php echo $class_css; ?>"><?php echo $mensaje; ?></div>
		</div>
	</div>
</body>
</html>