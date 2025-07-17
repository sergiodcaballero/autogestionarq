<?php 
//print_r($_GET);
require_once('connections/honorarios.php'); 
//mysql_select_db($database_honorarios, $honorarios);
$consulta_sql = "SELECT u.contrasena,s.nombre FROM usuario u inner join socios s on s.matricula=u.idusuario and s.email='".$_POST['mail']."'";

$res = mysqli_query($conn, $consulta_sql);
$Cantidad_Filas = mysqli_num_rows($res);
//$Cant = mysql_num_rows($resultado);
//$Cantidad_Filas = 1;
if ($Cantidad_Filas < 1){
	$resultado['respuesta'] = 'NO';
}else{
	while ($fila = mysqli_fetch_array($res)){
		$pass = $fila['contrasena'];
		$socio = $fila['nombre'];
		
	}
	require_once("clases/mail.php");
	$smtp = 'mail.arquitectosmisiones.org.ar';
	$puerto = 587;
	$usuario = 'autogestion@arquitectosmisiones.org.ar';
	$clave= 'autogestionZ';
	$asunto = 'Recuperar password';
	$from =array('autogestion@arquitectosmisiones.org.ar' => 'Colegio de Arquitectos de la Prov. de Mnes.');
	$to = array( $_POST['mail'] => $socio);
	$body = '<p>Sr. Socio/a:<br>Por la presente le informamos que ha solicitado la recuperación de clave en el sistema de Autogestión del Colegio de Arquitectos de la Provincia de Misiones.<br>Su clave de acceso es: '.$pass.'</p><p>Trabajamos para mejorar los servicios.<br>
			Saludos<br>
			Colegio de Arquitectos de la Provincia de Misiones</p>';
	$correo = new mail($puerto,$smtp,$from,$usuario,$clave,$asunto,$body,$to);
	
		// Create the Transport
	$resultado = $correo->enviar_mail();
		
		
	//$resultado['enviado'] = $_POST;$resultado['enviado'] = $pass;
 //  $resultado['respuesta'] = 'Gracias por sus datos '.$_POST['mail'];
}
echo json_encode($resultado);
?>