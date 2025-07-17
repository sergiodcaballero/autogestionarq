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
    <script type="text/javascript" src="bootstrap/js/bootbox.js"></script>
    <script type="text/javascript">
    	function conf_salida(){
			if (confirm("¿Esta seguro de salir del Sistema? ")){
				var pagina="cerrar_sesion.php"
								
								location.href=pagina
			}
		}
		$(document).ready(function(e){
			$(".cancelar").click(function(evento){
				$(".body_principal").val('');				
			});
			$(".enviar_mail").click(function(evento){
				var mail = $(".body_principal").val();
				if (mail!=''){
					var box=bootbox.dialog({
							message: '</br> Enviando mail..</br></br>'+
							'<div class="progress progress-striped active">'+ 
										'<div class="bar" style="width: 100%;"> ' +
										'</div></br>',
							closeButton: false,	
					});
					box.modal('show');
					$.ajax({
					url: 'pedido_actualizacion.php',
					data: {
						mail: mail
					},
					type: 'POST',
					dataType: 'json',
					success: function(datos){
						
						$('#myModal').modal('hide')
						box.modal('hide');
						if (datos.respuesta=='NO'){
							alert('El correo ingresado no existe');
						}else if(datos.respuesta=='SI'){
							$(".body_principal").val('');
							alert('El correo ha sido enviado exitosamente!');
							
						}else{
							//$('#myModal').modal('hide')
							alert('No se ha podido enviar el correo. Intente más tarde');
						}
						//alert(JSON.stringify(datos, null, 4));
					   
					}
				});
				}else{
					alert('Debe completar los datos!');
				}
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
                                <li><a href="deuda_total.php" class="orden"><i class="icon-chevron-right"></i>Total</a></li>
                                <li><a href="deuda_detallada.php"><i class="icon-chevron-right"></i>Detallada</a></li>
                                <li class="nav-head"><a style=" color:#999;"><strong>CERTIFICADOS</strong></a> </li>
                                <li><a href="certificado_libre_deuda.php" class="orden"><i class="icon-chevron-right"></i>Matr&iacute;cula / Libre Deuda</a></li>
                                <li><a href="calculo_tasas.php" class="orden">
							        <i class="icon-chevron-right"></i>C&aacute;lculo de Tasas
							    </a></li>
                                <li class="nav-head"><a style=" color:#999;"><strong>MIS DATOS</strong></a> </li>
                                <li class="active"><a href="actualizar_datos.php" class="orden"><i class="icon-chevron-right"></i>Actualizar Datos</a></li>
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
                                            <td><?php echo $fila['nombre'];?></td>
                                        </tr>
                                        <tr>
                                        	<td>N&ordm; Documento</td>
                                            <td><?php echo $fila['n_documento'];?></td>
                                        </tr>
                                        <tr>
                                        	<td>Direcci&oacute;n</td>
                                            <td><?php echo $fila['direccion'];?></td>
                                        </tr>
                                        <tr>
                                        	<td>Mail</td>
                                            <td><?php echo $fila['email'];?></td>
                                        </tr>
                                    </tbody>
                                  </table>
							<?php } ?>
								<center><h3> Actualizaci&oacute;n de Datos </h3></center>
                                <p></p>
                                <form class="form-horizontal" method="post" action="actualizar_datos.php">
                                	<textarea class="span12 body_principal"  rows="8"  placeholder="Ingrese los datos que solicita actualizarlos..." name="body_principal"></textarea>
                                    <br/>
                                    <p></p>
                                   <!--   <button class="btn  btn-info" type="submit">Enviar Pedido de Actualización</button> -->								
                                     <input type="button" name="enviar_mail" class="btn  btn-info enviar_mail" value="Enviar pedido de actualización"/>
       <!--   <a class="btn btn-large btn-primary" href="principal.php" >Ingresar</a>--> 
        <a class="btn cancelar" href="" >Cancelar</a> 
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