<?php 
//session_start();
$matricula = $_SESSION['matricula_socio'];
/*$consulta= "select s.nombre,SUM(f.TOTAL) as total FROM factura f inner join socios s on f.idsocio=s.idsocio
where matricula=".$_SESSION['matricula_socio']."
group by s.nombre" ;*/
/*
$consulta= "select count(f.idfactura) as total FROM factura f inner join socios s on f.idsocio=s.idsocio
where matricula=".$_SESSION['matricula_socio']." and f.estado='' " ;

//print_r($consulta);							
$resultado = mysql_query($consulta);
$Cantidad_Filas = mysql_num_rows($resultado);
if ($Cantidad_Filas==0){
	//print_r('hola');
	$deuda =0;
} else{
	while ($fila = mysql_fetch_array($resultado)){
		$deuda = $fila['total'];
		$socio = $fila['nombre'];
	}
}*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<center><h3>Mis Datos</h3></center>
	 <?php
							$consulta= "select nombre,n_documento,direccion,email from socios where matricula=".$_SESSION['matricula_socio'];							
							$resultado = mysqli_query($conn, $consulta);
							$Cantidad_Filas = mysqli_num_rows($resultado);
							if ($Cantidad_Filas==0){
								?><script language="JavaScript" type="text/javascript">
									var pagina="cerrar_sesion.php"								
									//location.href=pagina							
								</script> <?php
							}else{
								while ($fila = mysqli_fetch_array($resultado)){
								?>
								  <table class="table table-striped table-condensed table-bordered table-responsive">
                                  	<tbody>
                                    	<tr>
                                        	<td>Apellido y Nombre</td>
                                            <td><?php echo utf8_encode($fila['nombre']);?></td>
                                        </tr>
                                        <tr>
                                        	<td>N&ordm; Documento</td>
                                            <td><?php echo $fila['n_documento'];?></td>
                                        </tr>
                                        <tr>
                                        	<td>Direcci&oacute;n</td>
                                            <td><?php echo utf8_encode($fila['direccion']);?></td>
                                        </tr>
                                        <tr>
                                        	<td>Mail</td>
                                            <td><?php echo $fila['email'];?></td>
                                        </tr>
                                    </tbody>
                                  </table>
							<?php } } ?> 
	<p></p>
    <div style="font-size:18px">
    <center>
   	<strong>Estimado colegiado, a la fecha registra una deuda impaga que debe regularizarse a fin de obtener el certificado solicitado.</strong>
    </center>
    </div>
</body>
</html>