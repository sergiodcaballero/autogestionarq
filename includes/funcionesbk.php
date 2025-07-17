<?php
function calcular_deuda($matricula = '', $fecha = ''){
	global $conn;
	if($matricula==''){
		$matricula=$_SESSION['matricula_socio'];
	}
	if($fecha==''){
		$fecha=date('Y-m-d');
	}
	$matricula=mysqli_real_escape_string($conn, $matricula);
	$fecha=mysqli_real_escape_string($conn, $fecha);
	/*
	$c="select * from factura;";
	$r = mysqli_query($conn, $c);
	$i=0;
	echo "<table>";
	while ($f=mysqli_fetch_assoc($r)) {
		//echo var_dump($f)."<br>";
		$head=$f;
		if($i==0){
			$i++;
			echo "<tr>";
			foreach ($head as $key => $value) {
				echo "<td>";
				echo $key;
				echo "</td>";
			}
			echo "</tr>";
		}

		echo "<tr>";
		foreach ($f as $key => $value) {
			echo "<td  style='border: 1px solid;'>";
			echo $value;
			echo "</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
	*/


	$consulta= "select count(f.idfactura) as total 
				FROM factura f 
				inner join socios s on f.idsocio=s.idsocio
				where matricula= '".$matricula."' and f.estado='' and fecha <= '$fecha'" ;
	$resultado = mysqli_query($conn, $consulta);
	$Cantidad_Filas = mysqli_num_rows($resultado);
	if ($Cantidad_Filas==0){
		$deuda =0;
	} else{
		while ($fila = mysqli_fetch_array($resultado)){
			$deuda = $fila['total'];
		}
	}
	return $deuda;
}