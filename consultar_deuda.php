<?php
require_once('connections/honorarios.php');
if(isset($_POST['matricula'])){
	$matricula = addslashes($_POST['matricula']);

	$consulta= "select f.* from factura f inner join socios s on s.IDSOCIO = f.IDSOCIO where MATRICULA='$matricula' order by f.periodo asc";				
	$resultado = mysqli_query($conn, $consulta);
	if(mysqli_num_rows($resultado) > 0){
		$tabla = "<table class='table table-condensed'><thead><th>A&ntilde;o</th><th>Mes</th><th>Monto</th></thead><tbody>";
		$total = 0;
		
		while ($fila = mysqli_fetch_array($resultado)){
			$tabla.= "<tr><td>".$fila['ANO']."</td><td>".$fila['MES']."</td><td>$ ".$fila['TOTAL']."</td></tr>";
			$total += $fila['TOTAL'];
		}
		$tabla.=  "<tr><td colspan='2' style='text-align: right;'><strong>Total:</strong></td><td>$ $total</td></tr>";
		$tabla.=  "</tbody></table>";
		echo $tabla;	
	}else{
		echo "NO POSEE DEUDAS REGISTRADAS.";
	}

}
?>