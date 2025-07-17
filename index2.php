
<!DOCTYPE html>
<head lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title>Auto Gesti&oacute;n  - P&aacute;gina principal</title>
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css" />
<link href="mis_estilos/mis_estilos.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-responsive.css" />
<script type="text/javascript" src="ScriptLibrary/jquery-latest.pack.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
<style></style>
</head>

<body>
	<div class="container">
            <div class="contenido">
            
          <div class="row-fluid">
            <div class="span12">
                <div class="encabezado">
                    <div class="row-fluid">
                        <div class="span4">
                        <img src="images/Logo.gif" class="img-rounded" style="margin-left:1%" /> 
                        </div>
                        <div class="span8">
                            <div class="titulo">
                                <h3>AUTOGESTI&Oacute;N S.M.A.U.Na.M.</h3>
                            </div>
                        </div>
                    </div>
                      </div>
            </div>
            <div class="span12">
                <div class="contenido_base">
                <form name='datos' action='verificadatos.php' method='post' class="form-horizontal" >
                  <div class="control-group">
                    <label class="control-label" for="N_Afiliado" >Usuario:</label>
                    
                      <input type="text" name="N_Afiliado" id="N_Afiliado" maxlength="10"/>
                  
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="password" >Contrase&ntilde;a:&nbsp;</label>
                    <div class="controls">
                      <input type="password" name="password" id="password" maxlength="10"/>
                    </div>
                  </div>
                  <div class="control-group">
                  <center>
                    <button type="submit" class="btn btn-success" onclick="MM_validateForm('N_Afiliado','','RisNum','Nro_Doc','','RisNum');return document.MM_returnValue">Ingresar</button>
                    <a class="btn" href="http://www.smaunam.com.ar" style="margin-left:2%;">Volver</a> 
                    <a href="#myModal" role="button" class="btn btn-primary" data-toggle="modal"  style="margin-left:2%;">Recuperar Contraseña</a> 
                  </center>
                  </div>
                </form>
                <div class="alert alert-info" style="margin-right:20%;margin-top:2%; margin-left:-10%;padding-top:1%">
                    <strong>*Señor Afiliado:</strong><br/>
                    - Para poder ingresar al sistema de auto Gestión deberá enviar una solicitud <strong>Alta Usuario.</strong><br/>
                    - Para descargar el manual de Ayuda, seleccione 
                    <a target="_blank" href="http://www.smaunam.com.ar/wp-content/uploads/2015/04/man_sis_auto.pdf" class="btn ">Descargar</a>
                   
                </div>
                </div>
            </div>
            <div class="span12">
                            
            </div>
          </div>
        </div>
        </div>
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
             <button class="btn btn-success reenvio_clave confirmar" >Aceptar</button>
             <button class="btn"  data-dismiss="modal" aria-hidden="true">Cancelar</button>
          </div>
        </div>

</body>
</html>