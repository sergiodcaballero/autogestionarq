<?php 
session_start();
$matricula = $_SESSION['matricula_socio'];
require_once('connections/honorarios.php'); 
	//mysql_select_db($database_honorarios, $honorarios);
	$consulta_sql = "SELECT * FROM  usuario  where 	IDUSUARIO=".$matricula." and CONTRASENA='".$_POST['pass']."'";
	$res = mysqli_query($conn, $consulta_sql);
	$Cantidad_Filas = mysqli_num_rows($res);
	if ($Cantidad_Filas < 1){
		$resultado['respuesta'] = 'La contraseña actual ingresada es incorrecta';
		
	}else{
		$sql = "update usuario set  CONTRASENA = '".$_POST['pass_nueva']."'  where IDUSUARIO =".$matricula;
		$res = mysqli_query($conn, $sql);
		$resultado['respuesta'] = 'SI';
	} 

echo json_encode($resultado);
?>