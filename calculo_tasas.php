<?php
session_start();
if (isset($_SESSION['matricula_socio'])) {
  require_once('connections/honorarios.php');
} else {
?><script language="JavaScript" type="text/javascript">
    var pagina = "index.php"
    location.href = pagina
  </script> <?php
          }
          $consulta = "select count(f.idfactura) as total FROM factura f inner join socios s on f.idsocio=s.idsocio
  where matricula=" . $_SESSION['matricula_socio'] . " and f.estado='' ";
          //print_r($consulta);
          $resultado = mysqli_query($conn, $consulta);
          $Cantidad_Filas = mysqli_num_rows($resultado);
          if ($Cantidad_Filas == 0) {
            //print_r('hola');
            $deuda = 0;
          } else {
            while ($fila = mysqli_fetch_array($resultado)) {
              $deuda = $fila['total'];
              //$socio = $fila['nombre'];
            }
          }
          if ($deuda == 0) {
            $puede = true;
            //    
            //}else if ($deuda>0 and $deuda<=1 ){ se cambia el 1 por el 12, por cuarentena... 04/04/2020 
            //
          } else if ($deuda > 0 and $deuda <= 12) {
            $puede = true;
          } else if ($deuda > 12) {
            $puede = false;
          }
          if ($puede == false) {
            ?><script language="JavaScript" type="text/javascript">
    var pagina = "principal.php"
    alert('Usted registra una deuda impaga que debe regularizar para realizar el calculo de tasas')
    location.href = pagina
  </script> <?php
          }
            ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="gb18030">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Colegio de Arquitectos de la Provincia de Misiones</title>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.css" />
  <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet" />

  <link rel="stylesheet" href="mis_estilos/mis_estilos.css" />
  <link href="mis_estilos/estilos_impresion.css" rel="stylesheet" type="text/css" media="print" />
  <script type="text/javascript" src="bootstrap/js/jquery.md5.min.js"></script>
  <script type="text/javascript" src="ScriptLibrary/jquery-latest.pack.js"></script>
  <script src="bootstrap/js/jquery.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript">
    function conf_salida() {
      if (confirm("¿Esta seguro de salir del Sistema? ")) {
        var pagina = "cerrar_sesion.php"

        location.href = pagina
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
          <img src="imagenes/cabecera.png" class="img-rounded">
        </div>
        <div class="span12">
          <div class="row">
            <div class="span4">
              <?php include('menu.php') ?>
            </div>
            <div class="span8">
              <center>
                <h3>C&aacute;lculo de Tasas</h3>
              </center>
              <?php
              $consulta = "select nombre,n_documento,direccion,email, matricula from socios where matricula=" . $_SESSION['matricula_socio'];
              $resultado = mysqli_query($conn, $consulta);
              $Cantidad_Filas = mysqli_num_rows($resultado);
              if ($Cantidad_Filas == 0) {
              ?><script language="JavaScript" type="text/javascript">
                  var pagina = "cerrar_sesion.php"
                  //location.href=pagina							
                </script> <?php
                        } else {
                          while ($fila = mysqli_fetch_array($resultado)) {
                            $nombre = utf8_encode($fila['nombre']);
                            $matricula = $fila['matricula'];
                            $email = $fila['email'];
                          ?>
                  <table class="table table-striped table-condensed table-bordered table-responsive">
                    <tbody>
                      <tr>
                        <td>Apellido y Nombre</td>
                        <td><?php echo utf8_encode($fila['nombre']); ?></td>
                      </tr>
                      <tr>
                        <td>N&ordm; Documento</td>
                        <td><?php echo $fila['n_documento']; ?></td>
                      </tr>
                      <tr>
                        <td>Direcci&oacute;n</td>
                        <td><?php echo utf8_encode($fila['direccion']); ?></td>
                      </tr>
                      <tr>
                        <td>Mail</td>
                        <td><?php echo $fila['email']; ?></td>
                      </tr>
                    </tbody>
                  </table>
                <?php } ?>
                <form id="form_calculo_de_tasas" class="form-horizontal modif" action="resultado_calculo_de_tasas.php" method="post" onsubmit="return finalizar_formulario()" enctype="multipart/form-data">
                  <input type="hidden" name="nombre" value="<?php echo $nombre ?>">
                  <input type="hidden" name="matricula" value="<?php echo $matricula ?>">
                  <input type="hidden" name="email" value="<?php echo $email ?>">
                  <input type="hidden" id="calculos_de_tasas" name="calculos_de_tasas">
                  <div class="control-group">
                    <label class="control-label" for="cuitcomitente">CUIT Comitente</label>
                    <div class="controls">
                      <input type="text" id="cuitcomitente" placeholder="CUIT Comitente" name="cuitcomitente" class="cuitcomitente" required>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="comitente">Comitente</label>
                    <div class="controls">
                      <input type="text" id="comitente" placeholder="Comitente" name="comitente" class="comitente" required>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="direccion">Direcci&oacute;n</label>
                    <div class="controls">
                      <input type="text" id="direccion" placeholder="Dirección" name="direccion" class="direccion" required>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="localidad">Localidad</label>
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
                          $consulta = "select * from tipos_de_obra";
                          $resultado = mysqli_query($conn, $consulta);
                          $grupo = "";
                          $resultados = array();
                          while ($fila = mysqli_fetch_array($resultado)) {
                            $resultados[] = $fila;
                            if ($grupo != $fila['grupo']) {
                              if ($grupo != "") {
                                echo "</optgroup>";
                              }
                              $grupo = $fila['grupo'];
                              echo "<optgroup label='" . $fila['grupo'] . "'>";
                            }
                            echo "<option value='" . $fila['id'] . "_" . $fila['coef_p'] . "_" . $fila['coef_do'] . "_" . $fila['coef_e'] . "'>" . utf8_encode($fila['nombre']) . "</option>";
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
                        <input type="number" step="0.01" value="0" id="m2_proyecto" placeholder="Metros cuadrados a construir" name="m2_proyecto" class="m2_proyecto">
                      </div>
                    </div>
                    <div id="div_direccion" class="control-group">
                      <label class="control-label" for="inputPassword">Direcci&oacute;n T&eacute;cnica</label>
                      <div class="controls">
                        <input type="number" step="0.01" value="0" id="m2_direccion" placeholder="Metros cuadrados a m2_direccion" name="m2_direccion" class="m2_direccion">
                      </div>
                    </div>
                    <div id="div_relevamiento" class="control-group">
                      <label class="control-label" for="inputPassword">Relevamiento</label>
                      <div class="controls">
                        <input type="number" step="0.01" value="0" id="m2_relevamiento" placeholder="Metros cuadrados a construir" name="m2_relevamiento" class="m2_relevamiento">
                      </div>
                    </div>
                  </div>
                  <div class="control-group">
                    <div class="controls"> <input type="hidden" name="grabar" value="si" />
                      <input type="button" class="btn btn-default guardar" value="Agregar a la lista de calculos" name='agregar' onclick="agregar_calculo(tipo_de_obra.value, m2_proyecto.value, m2_direccion.value, m2_relevamiento.value, $('#tipo_de_obra option:selected').html())" />



                    </div>
                  </div>
                  <fieldset>
                    <legend>Lista de Calculos</legend>
                    <div id="div_calculos_de_tasas"></div>
                  </fieldset>
                  <input type="submit" class="btn btn-info guardar" style="float: right;" value="Calcular Todas las Tasas y Finalizar" onclick="verificar_calculos()" name='guardar' />
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
    var cantidad = 0;
    var calculos = Array();

    $(document).ready(function() {
      ocultar_inputs();
      $('#form_calculo_de_tasas').on('submit', function(e) {
        var datos = JSON.stringify(calculos);
        if (calculos.length == 0) {
          //alert('calculos = 0');
          alert('Presione "Agregar a la lista de Calculos" para poder continuar.');
          return false;
        } else {
          //alert('calculos > 0');
          $('#calculos_de_tasas').val(datos);
          return true;
        }
      });
    });

    function finalizar_formulario() {
      let retorno = false;
      if (validaCuit($('#cuitcomitente').val())) {
        retorno = true;
      }
      return retorno;
    }

    function verificar_calculos() {
      if (calculos.length > 0) {
        $('#m2_proyecto').attr('required', false);
        $('#m2_direccion').attr('required', false);
        $('#m2_relevamiento').attr('required', false);
      } else {
        if (tipo_de_obra.value != '') {
          ver_inputs(tipo_de_obra.value);
        }
      }
    }

    function ocultar_inputs() {
      $('#m2_proyecto').attr('required', false);
      $('#m2_direccion').attr('required', false);
      $('#m2_relevamiento').attr('required', false);
      $('#div_proyecto').hide();
      $('#div_direccion').hide();
      $('#div_relevamiento').hide();
    }

    function ver_inputs(dato) {
      ocultar_inputs();
      var datos = dato.split('_');
      if (datos[1] != 0) {
        $('#div_proyecto').show();
        $('#m2_proyecto').attr('required', true);
      }
      if (datos[2] != 0) {
        $('#div_direccion').show();
        $('#m2_direccion').attr('required', true);
      }
      if (datos[3] != 0) {
        $('#div_relevamiento').show();
        $('#m2_relevamiento').attr('required', true);
      }
    }

    function eliminar_fila(id) {
      document.getElementById(id.id).remove();
      var arraytemp = new Array();
      var idtemp = id.id.split("_");
      for (var i = 0; i < calculos.length; i++) {
        var temp = calculos[i];
        if (temp[0] != idtemp[1]) {
          arraytemp.push(temp);
        }
      }
      calculos = arraytemp;
    }

    function agregar_calculo(tipo, m2p, m2do, m2r, texto) {
      //alert(tipo);
      var datos = tipo.split('_');
      if (datos[3] == 0) {
        if (tipo == '' || m2p == '' || m2do == '') {
          alert('No deje Campos vacios.');
          return false;
        }
      } else {
        if (tipo == '' || m2p == '' || m2do == '' || m2r == '') {
          alert('No deje Campos vacios.');
          return false;
        }
      }

      if (document.getElementById('div_calculos_de_tasas').innerHTML == '') {
        document.getElementById('div_calculos_de_tasas').innerHTML = "<table class='table table-condensed'><thead><th>Tipo de Obra</th><th>M2 Proyecto</th><th>M2 Dir. T&eacute;cnica</th><th>M2 Relevamiento</th><th>Opciones</th></thead><tbody id='div_cuerpo_tabla'></tbody></table>";
      }
      cantidad++;
      var fila = new Array(cantidad, tipo, m2p, m2do, m2r);
      calculos.push(fila);
      document.getElementById('div_cuerpo_tabla').innerHTML = document.getElementById('div_cuerpo_tabla').innerHTML + "<tr id='fila_" + (cantidad) + "'><td>" + texto + "</td><td>" + m2p + "</td><td>" + m2do + "</td><td>" + m2r + "</td><td><input type='button' value='Quitar' onclick='eliminar_fila(fila_" + cantidad + ")' /></td></tr>";
      $('#m2_proyecto').val('');
      $('#m2_direccion').val('');
      $('#m2_relevamiento').val('');
    }

    function validaCuit(sCUIT) {
      var aMult = '5432765432';
      var aMult = aMult.split('');

      if (sCUIT && sCUIT.length == 11) {
        aCUIT = sCUIT.split('');
        var iResult = 0;
        for (i = 0; i <= 9; i++) {
          iResult += aCUIT[i] * aMult[i];
        }
        iResult = (iResult % 11);
        iResult = 11 - iResult;

        if (iResult == 11) iResult = 0;
        if (iResult == 10) iResult = 9;

        if (iResult == aCUIT[10]) {
          return true;
        }
      }
      alert('Cuit Inválido');
      return false;
    }
  </script>
</body>

</html>