<?php 
session_start();
if (isset($_SESSION['matricula_socio'])){
	require_once('connections/honorarios.php');
}else{
		?><script language="JavaScript" type="text/javascript">
			var pagina="index.php"								
			location.href=pagina							
		</script> <?php
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Colegio de Arquitectos de la Provincia de Misiones</title> 
<link rel="stylesheet"  href="bootstrap/css/bootstrap.css" />
<link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet" />
    
    <link rel="stylesheet" href="mis_estilos/mis_estilos.css" />
   <script type="text/javascript" src="bootstrap/js/jquery.md5.min.js"></script>
    <script type="text/javascript" src="ScriptLibrary/jquery-latest.pack.js"></script>
    <script src="bootstrap/js/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
    	function conf_salida(){
			if (confirm("¿Esta seguro de salir del Sistema? ")){
				var pagina="cerrar_sesion.php"
								
								location.href=pagina
			}
		}
    </script>
</head>

<body>
	 <div class="container">
     	<div class="contenido">
        	<div class="row-fluid">
            	<div class=" span10" style="margin-top:-2%;">
                  	 <img src="imagenes/cabecera.png" class="img-rounded" >
                </div>
            	<div class="span12">
                	<div class="row">
                    	
                    	<div class="span4" >
                        	<ul class="nav nav-list bs-docs-sidenav">
                            	<li class="active"><a href="principal.php"><i class="icon-home"></i><i class="icon-chevron-right"></i> Inicio</a></li>
                          		<li><a onClick="conf_salida();" href="#"><i class="icon-remove "></i> Salir</a></li>
                                <li class="nav-head"><a style=" color:#999;"><strong>ESTADO DE DEUDA</strong></a> </li>
                                <li><a href="deuda_total.php" class="orden"><i class="icon-chevron-right"></i>Total</a></li>
                                <li><a href="deuda_detallada.php"><i class="icon-chevron-right"></i>Detallada</a></li>
                                <li class="nav-head"><a style=" color:#999;"><strong>CERTIFICADOS</strong></a> </li>
                                <li><a href="certificado_libre_deuda.php" class="orden"><i class="icon-chevron-right"></i>Matr&iacute;cula / Libre Deuda</a></li>
                                <li><a href="calculo_tasas.php" class="orden">
                                    <i class="icon-chevron-right"></i>C&aacute;lculo de Tasas
                                </a></li>
                                <li class="nav-head"><a style=" color:#999;"><strong>SIMULADOR DE CALCULO DE HONORARIOS</strong></a> </li>
                                <li><a href="http://www.arquitectosmisiones.org.ar/calculo/calculo.php" class="orden"><i class="icon-chevron-right"></i>Simulador de C&aacute;lculo de Honorarios</a></li>
                                <li class="nav-head"><a style=" color:#999;"><strong>MIS DATOS</strong></a> </li>
                                <li><a href="http://www.arquitectosmisiones.org.ar/autogestion/home.php?p=formulario&id=2" class="orden"><i class="icon-chevron-right"></i>Cargar T&iacute;tulo</a></li>
                                <li><a href="actualizar_datos.php" class="orden"><i class="icon-chevron-right"></i>Actualizar Datos</a></li>
                                <li><a href="modificar_pass.php"><i class="icon-chevron-right"></i>Modificar Contrase&ntilde;a</a></li>
                            </ul>
                        </div>
                        <div class="span8">
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
							<?php }}
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
</body>
</html>