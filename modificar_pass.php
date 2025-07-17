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
if (isset($_POST['grabar']) and $_POST['grabar']=='si'){
	$_POST['grabar'] = 'no';
	
}else{
	
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
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
		$(document).ready(function(e){
			$(".cancelar").click(function(evento){
				$(".pass").val('');
				$(".passnueva").val('');
				$(".passnueva2").val('');
				
			});
			$(".guardar").click(function(evento){
				
				var pass = $(".pass").val();
				var passnueva = $(".passnueva").val();
				var passnueva2 =  $(".passnueva2").val();
				if (pass=='' & passnueva=='' & passnueva2==''){				
					alert('Complete los datos');
					return false;
				}
				if (pass==''){				
					alert('Ingrese la Contraseña actual');
					return false;
				}
				
				if (passnueva==''){
					alert('Ingrese la nueva Contraseña');
					$(".passnueva2").val('');
					return false;
				}
				if (passnueva2==''){
					alert('Repita la nueva Contraseña');
					$(".passnueva").val('');
					return false;
				}
				if (passnueva != passnueva2){
					alert('La nueva Contraseña debe coincidir con la repeticion de la misma');
					$(".passnueva").val('');
					$(".passnueva2").val('');
					return false;

				}
				$.ajax({
					url: 'verificar_claves.php',
					data: {
						pass: pass,
						pass_nueva: passnueva,
						pass_nueva2: passnueva2
					},
					type: 'POST',
					dataType: 'json',
					success: function(datos){
						if (datos.respuesta=='SI'){
						alert('Se ha modificado exitosamente la contraseña');
							$(".modif").submit();


 
						}else {
							//$('#myModal').modal('hide')
							alert($datos.respuesta);
							$(".pass").val('');
						}
						//alert(JSON.stringify(datos, null, 4));
					   
					}
				});
			});
		});
		
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
                    	<div class="span4">
                        	<ul class="nav nav-list bs-docs-sidenav">
                            	<li ><a href="principal.php"><i class="icon-home"></i><i class="icon-chevron-right"></i> Inicio</a></li>
                          		<li><a onClick="conf_salida();" href="#"><i class="icon-remove "></i> Salir</a></li>
                                <li class="nav-head"><a style=" color:#999;"><strong>ESTADO DE DEUDA</strong></a> </li>
                                <li ><a href="deuda_total.php" class="orden"><i class="icon-chevron-right"></i>Total</a></li>
                                <li><a href="deuda_detallada.php"><i class="icon-chevron-right"></i>Detallada</a></li>
                                <li class="nav-head"><a style=" color:#999;"><strong>CERTIFICADOS</strong></a> </li>
                                <li><a href="certificado_libre_deuda.php" class="orden"><i class="icon-chevron-right"></i>Matr&iacute;cula / Libre Deuda</a></li>
                                <li><a href="calculo_tasas.php" class="orden">
							        <i class="icon-chevron-right"></i>C&aacute;lculo de Tasas
							    </a></li>
                                <li class="nav-head"><a style=" color:#999;"><strong>MIS DATOS</strong></a> </li>
                                <li><a href="actualizar_datos.php" class="orden"><i class="icon-chevron-right"></i>Actualizar Datos</a></li>
                                <li  class="active"><a href="modificar_pass.php"><i class="icon-chevron-right"></i>Modificar Contrase&ntilde;a</a></li>
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
							<?php } ?>
								<center><h3> Modificar Contrase&ntilde;a </h3></center>               
                                <p></p>
                                <form class="form-horizontal modif" action="" method="post">
                                	<div class="control-group">
                                        <label class="control-label" for="inputPassword">Contrase&ntilde;a Actual</label>
                                        <div class="controls">
                                          <input type="password" id="inputPassword" placeholder="Contraseña Actual" name="pass" class="pass">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="inputPassword1">Nueva Contrase&ntilde;a</label>
                                        <div class="controls">
                                          <input type="password" id="inputPassword1" placeholder="Nueva Contraseña" name="passnueva" class="passnueva">
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="inputPassword2">Repetir Contrase&ntilde;a</label>
                                        <div class="controls">
                                          <input type="password" id="inputPassword2" placeholder="Repetir Contraseña" name="passnueva2" class="passnueva2">
                                        </div>
                                      </div>
                                        <div class="control-group">
                                            <div class="controls"> <input type="hidden" name="grabar" value="si" />
                                              <input type="button" class="btn btn-info guardar" value="Guardar" name='guardar'/>
                                             
                                              <input type="button" class="btn cancelar" value="Cancelar"  name="cancelar"/>
                                            </div>
                                          </div>
                                </form>
							<?php }
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
</body>
</html>