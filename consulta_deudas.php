<?php 
session_start();
//if (isset($_SESSION['matricula_socio'])){
	require_once('connections/honorarios.php');
//}else{
//} 
/*          
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
  */
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
                        <div class="span12">
                        	<center><h3>Consulta de Saldos</h3></center><input class="btn btn-default" type="button" onclick="consultar_again();" name="consultar_de_nuevo" value="Consultar de Nuevo">
                          <div id="datos_usuario"></div>
                          <table class="table table-striped table-bordered table-responsive" id="tabla">
                              <thead>
                                <th>Matr&iacute;cula <input type="text" name="matricula" onkeyup="filtra2(this.value,'tabla',0)"></th>
                                <th>Apellido y nombre <input type="text" name="apellido" onkeyup="filtra2(this.value,'tabla',1)"></th>
                                <th>Nro. Documento</th>
                                <th>Opciones</th>
                              </thead>
                              <tbody id="table_body_deuda">
                            <?php
							$consulta= "select nombre,n_documento,direccion,email, matricula from socios";				
							$resultado = mysqli_query($conn, $consulta);
							$Cantidad_Filas = mysqli_num_rows($resultado);
							if ($Cantidad_Filas==0){
								?><script language="JavaScript" type="text/javascript">
									var pagina="cerrar_sesion.php"								
									//location.href=pagina							
								</script> <?php
							}else{
								while ($fila = mysqli_fetch_array($resultado)){
									$nombre = utf8_encode($fila['nombre']);
									$matricula = $fila['matricula'];
                  $email = $fila['email'];
								?>
              	   <tr name="fila[]" id="fila_<?php echo $fila['matricula']?>">
                      <td><?php echo utf8_encode($fila['matricula']);?></td>
                      <td><?php echo utf8_encode($fila['nombre']);?></td>
                      <td><?php echo $fila['n_documento'];?></td>
                      <td><input class="btn btn-default" type="button" name="ver_deuda" id="<?php echo $fila['matricula']; ?>" onclick="ver_deuda(this.id)" value="Ver Deuda"></td>
                  </tr>
                                    
							<?php } ?> 
              </tbody>
                                  </table>
							<?php }
							?>
                        <div id="resultado"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
     <script type="text/javascript">
        $(document).ready(function(){
          
        });

        function consultar_again(){
          /*
          var elementos = document.getElementsByName("fila[]");
          for (var i = 0; i < elementos.length; i+=1) {
            elementos[i].style.display = 'inline';
          }
          */
          document.getElementById("tabla").style.display = 'inline';
          $("#resultado").html('');
          $("#datos_usuario").html(''); 
        }

        function ver_deuda(id){
          /*
          var elementos = document.getElementsByName("fila[]");
          for (var i = 0; i < elementos.length; i+=1) {
            elementos[i].style.display = 'none';
          }
          */
          document.getElementById("tabla").style.display = 'none';
          var mostrar = $("#fila_"+id).html();
          //alert(mostrar);
          $('#datos_usuario').html('<table class="table table-condensed"><thead><th>Matr&iacute;cula</th><th>Apellido y Nombre</th><th>Nro. Documento</th></thead><tr>'+mostrar+'</tr></table>');
          //mostrar.style.display = 'block';
          

          var texto = $('#fila_'+id).html();
          //$('#table_body_deuda').html(texto);
          var parametros = {
                "matricula" : id
          };
          $.ajax({
                  data:  parametros,
                  url:   'consultar_deuda.php',
                  type:  'post',
                  beforeSend: function () {
                          $("#resultado").html("Procesando, espere por favor...");
                  },
                  success:  function (response) {
                          $("#resultado").html(response);
                  }
          });
        }

        function filtra2(txt,tabla,c) {
            colum=c;
            t = document.getElementById(tabla);
            filas = t.getElementsByTagName('tr');
            for (i=1; ele=filas[i]; i++) {
            texto = ele.getElementsByTagName('td')[colum].innerHTML.toUpperCase();
            num=2;
          
            if (num==0) posi = (texto.indexOf(txt.toUpperCase()) == 0);
            else if (num==1) posi = (texto.lastIndexOf(txt.toUpperCase()) == texto.length-txt.length);
            else posi = (texto.indexOf(txt.toUpperCase()) != -1);
            ele.style.display = (posi) ? '' : 'none';
            }  
        }
     </script>
</body>
</html>