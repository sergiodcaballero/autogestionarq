<?php
session_start();
if (isset($_SESSION['matricula_socio'])){
	header('Location:principal.php');
}
if (isset($_POST['logueo']) and $_POST['logueo']=='si'){
	$_POST['logueo']='no';
	if ($_POST['usuario']!='' and $_POST['pass']!=''){
		require_once('connections/honorarios.php'); 
		$consulta="select * from usuario where contrasena='".$_POST['pass']."' and idusuario='".$_POST['usuario']."'";
		//print_r($consulta);
		$resultado = mysqli_query($conn,$consulta);
		$Cantidad_Filas = mysqli_num_rows($resultado);
		if ($Cantidad_Filas==0){
			?>
			<script type="text/javascript">alert('Los datos ingresados son erroneos!');</script>
			<?php
		}else{
			while ($fila = mysqli_fetch_array($resultado)){
				$baja= $fila['BAJA'];
			}
			if ($baja=='true'){
				?>
				<script type="text/javascript">alert('Su Cuenta de Usuario esta dada de baja!');</script>
                <?php
			}else{
				$_SESSION['matricula_socio'] = $_POST['usuario'];?>
				<script language="JavaScript" type="text/javascript">
					var pagina="principal.php";						
					location.href=pagina;							
				</script> <?php
			}			
		}
	}else{
		?>
		<script type="text/javascript">alert('Ingrese el Usuario y Password!');</script>
        <?php
	}
	
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Colegio de Arquitectos de la Provincia de Misiones</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css" />
    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="mis_estilos/mis_estilos.css">
    <script src="bootstrap/js/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootbox.js"></script>
	<script type="text/JavaScript">

$(document).ready(function(e){

	$(".anular").click(function(evento){
		
		});
		
		$(".confirmar1").click(function(evento){
			var mail = $(".mail").val();
			if (mail==''){
				alert("Ingrese su Correo!");
			}else{
				var verificar_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
				if (verificar_email.test(mail)==false) {
						alert('El correo ingresado no es válido');
				}else{	
				
				var box=bootbox.dialog({
						message: '</br> Enviando mail..</br></br>'+
						'<div class="progress progress-striped active">'+ 
									'<div class="bar" style="width: 100%;"> ' +
									'</div></br>',
						closeButton: false,	
           });box.modal('show');
				$.ajax({
					url: 'recuperar_clave.php',
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
							
							alert('El correo ha sido enviado exitosamente!');
						}else{
							//$('#myModal').modal('hide')
							alert('No se ha podido enviar el correo. Intente más tarde');
						}
						//alert(JSON.stringify(datos, null, 4));
					   
					}
				});
					
				}
			}
		});
	
	});
	

</script>
    <!-- Le styles -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
       /* background-color: #f5f5f5;*/
		background: url(imagenes/fondo2.png) no-repeat fixed ; 
		-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
      }

      .form-signin {
        max-width: 320px;
        padding: 19px 39px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
				opacity:0.88;
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }
	
    </style>
   
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../../Third Party Source Code/bootstrap/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->

  </head>

  <body>

    <div class="container">

      <form class="form-signin" method="post">
        <p>
          <img src="imagenes/logo2.jpg" class="img-rounded">
        <br />
         </p>
        <div class="control-group" style="margin-top:9%;">
        <label for="usuario" class="control-label"><i class="  icon-user"></i>&nbsp;Usuario</label>
        <input type="text" name="usuario" id="usuario" class="input-block-level" placeholder="Usuario">
        </div>
         <div class="control-group" style="margin-top:-3%;">
          <label for="pass" class="control-label"><i class="  icon-lock"></i>&nbsp;Contraseña</label>
        <input type="password" class="input-block-level" placeholder="Contraseña" name="pass" id="pass">
        </div><input type="hidden" name="logueo" value="si">
       <button class="btn btn-large btn-primary" type="submit">Ingresar</button>
       <!--   <a class="btn btn-large btn-primary" href="principal.php" >Ingresar</a>--> 
        <a class="btn btn-large" href="http://www.arquitectosmisiones.org.ar/" >Salir</a> 
       <a class="btn btn-link" href="#myModal" role="button" data-toggle="modal">Recuperar Contraseña</a> 
       		<div class="alert alert-info" style="margin-left:-2%">
        	<p>- Se recomienda utilizar el <strong>Navegador Chrome y/o Firefox</strong> para las impresiones.<br/>
        	 
        	  </p>
           
        </div>
      </form>
		
    </div> <!-- /container -->
	<div id="myModal" class="modal hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:40%;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Recuperar Contraseña</h3>
      </div>
      <div class="modal-body">
        <p>Ingrese su Correo Electrónico</p>
        <div class="control-group">
              <div class="controls">
                  <input name="mail" id="mail" type="email" class="mail form-control"/>
                </div>
              </div>
      </div>
      <div class="modal-footer">
         <button class="btn btn-success reenvio_clave confirmar1" >Aceptar</button>
         <button class="btn"  data-dismiss="modal" aria-hidden="true">Cancelar</button>
      </div>
    </div>
  </body>
</html>
