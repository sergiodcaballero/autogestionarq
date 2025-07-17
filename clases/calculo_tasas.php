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
  $consulta= "select count(f.idfactura) as total FROM factura f inner join socios s on f.idsocio=s.idsocio
  where matricula=".$_SESSION['matricula_socio']." and f.estado='' " ;
  //print_r($consulta);
  $resultado = mysqli_query($conn, $consulta);
  $Cantidad_Filas = mysqli_num_rows($resultado);
  if ($Cantidad_Filas==0){
    //print_r('hola');
    $deuda =0;
  } else{
    while ($fila = mysqli_fetch_array($resultado)){
      $deuda = $fila['total'];
      //$socio = $fila['nombre'];
    }
  }
  if ($deuda==0){
    $puede = true;
  }else if ($deuda>0 and $deuda<=1 ){
    $puede = true;
  }else if($deuda>1){
    $puede = false;
  }
if($puede == false){
    ?><script language="JavaScript" type="text/javascript">
      var pagina="principal.php"
      alert('Usted registra una deuda impaga que debe regularizar para realizar el calculo de tasas')   
      location.href=pagina
    </script> <?php
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
    <link href="mis_estilos/estilos_impresion.css" rel="stylesheet" type="text/css" media="print"/>
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
    <style>
      .scrollspy-example {  
      overflow: auto;
      position: relative;
    }
    </style>
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
                                <li class="active"><a href="calculo_tasas.php" class="orden">
							        <i class="icon-chevron-right"></i>C&aacute;lculo de Tasas
							    </a></li>
                                <li class="nav-head"><a style=" color:#999;"><strong>MIS DATOS</strong></a> </li>
                                <li><a href="actualizar_datos.php" class="orden"><i class="icon-chevron-right"></i>Actualizar Datos</a></li>
                                <li><a href="modificar_pass.php"><i class="icon-chevron-right"></i>Modificar Contrase&ntilde;a</a></li>
                            </ul>
                        </div>
                        <div class="span8">
                        	<center><h3>C&aacute;lculo de Tasas</h3></center>
                            <?php
							$consulta= "select nombre,n_documento,direccion,email, matricula from socios where matricula=".$_SESSION['matricula_socio'];							
							$resultado = mysqli_query($conn, $consulta);
							$Cantidad_Filas = mysqli_num_rows($resultado);
							if ($Cantidad_Filas==0){
								?><script language="JavaScript" type="text/javascript">
									var pagina="cerrar_sesion.php"								
									//location.href=pagina							
								</script> <?php
							}else{
								while ($fila = mysqli_fetch_array($resultado)){
									$nombre = $fila['nombre'];
									$matricula = $fila['matricula'];
                  $email = $fila['email'];
								?>
								  <table class="table table-striped table-condensed table-bordered table-responsive">
                                  	<tbody>
                                    	<tr>
                                        	<td>Apellido y Nombre</td>
                                            <td><?php echo ($fila['nombre']);?></td>
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
                                <form class="form-horizontal modif" action="resultado_calculo_de_tasas.php" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="nombre" value="<?php echo $nombre ?>">
                                <input type="hidden" name="matricula" value="<?php echo $matricula ?>">
                                <input type="hidden" name="email" value="<?php echo $email ?>">
                                	<div class="control-group">
                                        <label class="control-label" for="inputPassword">Comitente</label>
                                        <div class="controls">
                                          <input type="text" id="comitente" placeholder="Comitente" name="comitente" class="comitente" required>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="inputPassword1">Direcci&oacute;n</label>
                                        <div class="controls">
                                          <input type="text" id="direccion" placeholder="Dirección" name="direccion" class="direccion" required>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="inputPassword2">Localidad</label>
                                        <div class="controls">
                                          <input type="text" id="localidad" placeholder="Localidad " name="localidad" class="localidad" required>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <label class="control-label" for="inputPassword">Tipo de Obra</label>
                                        <div class="controls">
                                        	<select name="tipo_de_obra" id="tipo_de_obra" onchange="ver_inputs(this.value)" required>
                                        		<option value="">
                                        			Seleccione tipo de obra..
                                        		</option>
    		<?php
    			$consulta= "select * from tipos_de_obra";							
				$resultado = mysqli_query($conn, $consulta);
				$grupo = "";
        $resultados = array();
				while ($fila = mysqli_fetch_array($resultado)){
          $resultados[] = $fila;
					if($grupo != $fila['grupo']){
						if($grupo != ""){
							echo "</optgroup>";
						}
						$grupo = $fila['grupo'];
						echo "<optgroup label='".$fila['grupo']."'>";
					}
					echo "<option value='".$fila['id']."_".$fila['coef_p']."_".$fila['coef_do']."_".$fila['coef_e']."'>".$fila['nombre']."</option>";
				}
					echo "</optgroup>"
    		?>
                                        	</select>
                                        </div>
                                      </div>                                  	
                                      <div id="m2_inputs">	
                                        <div id="div_proyecto" class="control-group">
	                                        <label class="control-label" for="inputPassword">Proyecto</label>
	                                        <div class="controls">
	                                          <input type="number" step="0.01" id="m2_proyecto" placeholder="Metros cuadrados a construir" name="m2_proyecto" class="m2_proyecto">
	                                        </div>
	                                      </div>
	                                      <div id="div_direccion" class="control-group">
	                                        <label class="control-label" for="inputPassword">Direcci&oacute;n T&eacute;cnica</label>
	                                        <div class="controls">
	                                          <input type="number" step="0.01" id="m2_direccion" placeholder="Metros cuadrados a m2_direccion" name="m2_direccion" class="m2_direccion">
	                                        </div>
	                                      </div>
	                                      <div id="div_relevamiento" class="control-group">
	                                        <label class="control-label" for="inputPassword">Relevamiento</label>
	                                        <div class="controls">
	                                          <input type="number" step="0.01" id="m2_relevamiento" placeholder="Metros cuadrados a construir" name="m2_relevamiento" class="m2_relevamiento">
	                                        </div>
	                                      </div>
                                      </div>
                                        <div class="control-group">
                                          <div class="controls"> <input type="hidden" name="grabar" value="si" />
                                            <input type="submit" class="btn btn-info guardar" value="Calcular Tasas" name='guardar'/>
                                           
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
     <script type="text/javascript">
        $(document).ready(function(){
          ocultar_inputs();
        });

        function ocultar_inputs(){
          $('#m2_proyecto').attr('required', 'false');
          $('#m2_direccion').attr('required', 'false');
          $('#m2_relevamiento').attr('required', 'false');
          $('#div_proyecto').hide();
          $('#div_relevamiento').hide();
          $('#div_direccion').hide();
          
        }

        function ver_inputs(dato){
          ocultar_inputs();
          var datos = dato.split('_');
          if(datos[1] != 0){
            $('#div_proyecto').show();
            $('#m2_proyecto').attr('required', 'true');
          }
          if(datos[2] != 0){
            $('#div_direccion').show();
            $('#m2_direccion').attr('required', 'true');
          }
          if(datos[3] != 0){
            $('#div_relevamiento').show();
            $('#m2_relevamiento').attr('required', 'true');
          }
        }
     </script>
</body>
</html>