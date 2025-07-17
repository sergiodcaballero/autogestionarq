<?php 
	session_start();
	$matricula = $_SESSION['matricula_socio'];
	require_once('connections/honorarios.php'); 
	//mysql_select_db($database_honorarios, $honorarios);
	$consulta_sql = "SELECT s.direccion,s.n_documento,s.email,s.nombre FROM  socios s  where s.matricula=$matricula";
	$res = mysqli_query($conn, $consulta_sql);
	$Cantidad_Filas = mysqli_num_rows($res);
	if ($Cantidad_Filas < 1){
		$resultado['respuesta'] = 'NO';
	}else{
		while ($fila = mysqli_fetch_array($res)){
		$dni = $fila['n_documento'];
		$socio = $fila['nombre'];
		
	}
	
	require_once("clases/mail.php");
	$smtp = 'mail.arquitectosmisiones.org.ar';
	$puerto = 9025;
	$usuario = 'autogestion@arquitectosmisiones.org.ar';
	$clave= 'autogestionZ';
	$asunto = 'Pedido de Actualizacion de datos del Sistema Autogestión';
	$from =array('info@arquitectosmisiones.org.ar' => 'Sistema de Autogestion del Colegio de Arquitectos de la Prov. de Mnes.');
	$to = array( 'info@arquitectosmisiones.org.ar');
	$body = '<p>El Socio '.$socio.', DNI '.$dni.' y matricula '.$matricula.', solicita la actualización de sus datos. Lo solicitado por el mismo es lo siguiente: <br></p><p>'.$_POST['mail'].'</p>';
	$correo = new mail($puerto,$smtp,$from,$usuario,$clave,$asunto,$body,$to);
	
		// Create the Transport
	$resultado = $correo->enviar_mail();
	}
	echo json_encode($resultado);
?>