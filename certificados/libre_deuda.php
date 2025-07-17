	<?php 
	//session_start();
	$matricula = $_SESSION['matricula_socio'];
	/*$consulta= "select s.nombre,SUM(f.TOTAL) as total FROM factura f inner join socios s on f.idsocio=s.idsocio
	where matricula=".$_SESSION['matricula_socio']."
	group by s.nombre" ;*/
	
	/*$consulta= "select count(f.idfactura) as total FROM factura f inner join socios s on f.idsocio=s.idsocio
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
	} */
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Documento sin t&iacute;tulo</title>
	</head>
	
	<body>
		<p></p>
		<?php 
		if ($deuda!=0){?>
			Sr. Socio usted tiene una deuda, por lo tanto no puede imprimir el certificado libre de deuda. 
		<?php }else{
			$sql = "select DATE_FORMAT(fecha_alta,'%d-%m-%Y') as fecha,n_documento as dni,nombre from socios where matricula=".$matricula;
			//print_r($sql);
			$resultado = mysqli_query($conn, $sql);
			while ($fila = mysqli_fetch_array($resultado)){
			$fecha_alta = $fila['fecha'];
			$dni = $fila['dni'];
			$socio = utf8_encode($fila['nombre']);
		}
		 $arrayMeses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
	   'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	 
	   $arrayDias = array( 'Domingo', 'Lunes', 'Martes',
		   'Miercoles', 'Jueves', 'Viernes', 'Sabado');
		 
		$fecha_actual = $arrayDias[date('w')]." ".date('d')." de ".$arrayMeses[date('m')-1]." de ".date('Y');
			//$fecha = date("d-m-Y");
		?>
		<div class="span8 no_imprimir">
								<a onclick="javascript:print()" class="btn btn-success btn-large "  >Imprimir Certificado</a></div><p><p/>
	<div class="span11" >
								<div class="impresion scrollspy-example" data-spy="scroll" >
									<table  border="0" >
	  <tr>
		<td  ><form id="form1" name="form1" method="post" action="">
		  <input name="imageField" type="image" id="imageField" src="imagenes/cabecera.png" width="100" align="left" />
		</form></td>
		<td  >&nbsp;</td>
	  </tr>
	  <tr>
		<td colspan="2"><div align="justify">
		  <div align="center">
			<h4 align="center">Decreto Ley N&ordm; 1-72</h4></div>
		  <pre align="center">  Av Fco. de Haro N&ordm; 2745 1&ordm; piso - Tel o376-4435310 - email info@arquitectosmisiones.org.ar</pre>
		  <p align="center" style="font-size:24px">Certificado Libre de Deuda</p><br/>
		  <p>Certificamos que el profesional arquitecto: <?php echo $socio;?>, DNI: <?php echo $dni; ?> se encuentra matriculado en este Colegio de la Provincia de Misiones, bajo el N&ordm; <?php echo $matricula;?> en esta instituci&oacute;n colegiado/a.
			<br />
			Se encuentra matriculado, y cumplimenta con los requerimientos establecidos por la ley N&ordm; 1-72&nbsp;<br />
			<br />
			La presente certificacion es valida por 30 d&iacute;as y se expide en Posadas,&nbsp;Capital de la Provincia de Misiones,a pedido del interesado&nbsp;y presentada ante Quien Corresponda&nbsp;<br />
			Posadas, <?php echo $fecha_actual;?></p>
		  <p>&nbsp;</p>
		  <p>&nbsp;</p>
		 <!--[if IE]>
				<?php 
				$fecha_a = date('Y')."".(date('m')-1)."".date('d');
				$codeContents = $_SESSION['matricula_socio'].$fecha_a; 
				include_once ('qrcode/phpqrcode.php');
				$text = QRcode::text($codeContents); 
					?>
			   <![endif]-->
				<!--[if !IE]><!-->
				<?php echo '<img  src="qr.php" />';
			
			 //<![endif]-->?>
				<!-- <![endif]-->
		  <p align="center" style="font-size:9px" class="pie_pagina">Emitido por Sistema Autogesti&oacute;n</p>
		</div></td>
		</tr>
	</table>
	
	  </div>
								</div>
		<?php } ?>
	</body>
	</html>