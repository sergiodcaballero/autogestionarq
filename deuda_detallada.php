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
						?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="iso-8859-1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>Colegio de Arquitectos de la Provincia de Misiones</title>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css" />
	<link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet" />

	<link rel="stylesheet" href="mis_estilos/mis_estilos.css" />
	<script type="text/javascript" src="bootstrap/js/jquery.md5.min.js"></script>
	<script type="text/javascript" src="ScriptLibrary/jquery-latest.pack.js"></script>
	<script src="bootstrap/js/jquery.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		function conf_salida() {
			if (confirm("Esta seguro de salir del Sistema? ")) {
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
							<ul class="nav nav-list bs-docs-sidenav">
								<li><a href="principal.php"><i class="icon-home"></i><i class="icon-chevron-right"></i> Inicio</a></li>
								<li><a onClick="conf_salida();" href="#"><i class="icon-remove "></i> Salir</a></li>
								<li class="nav-head"><a style=" color:#999;"><strong>ESTADO DE DEUDA</strong></a> </li>
								<li><a href="deuda_total.php" class="orden"><i class="icon-chevron-right"></i>Total</a></li>
								<li class="active"><a href="deuda_detallada.php"><i class="icon-chevron-right"></i>Detallada</a></li>
								<li class="nav-head"><a style=" color:#999;"><strong>CERTIFICADOS</strong></a> </li>
								<li><a href="certificado_libre_deuda.php" class="orden"><i class="icon-chevron-right"></i>Matr&iacute;cula / Libre Deuda</a></li>
								<li><a href="calculo_tasas.php" class="orden">
										<i class="icon-chevron-right"></i>C&aacute;lculo de Tasas
									</a></li>
								<li class="nav-head"><a style=" color:#999;"><strong>MIS DATOS</strong></a> </li>
								<li><a href="actualizar_datos.php" class="orden"><i class="icon-chevron-right"></i>Actualizar Datos</a></li>
								<li><a href="modificar_pass.php"><i class="icon-chevron-right"></i>Modificar Contrase&ntilde;a</a></li>
							</ul>
						</div>
						<div class="span8">
							<center>
								<h3>Mis Datos</h3>
							</center>
							<?php
							$consulta = "select nombre,n_documento,direccion,email from socios where matricula=" . $_SESSION['matricula_socio'];
							$resultado = mysqli_query($conn, $consulta);
							$Cantidad_Filas = mysqli_num_rows($resultado);
							if ($Cantidad_Filas == 0) {
							?><script language="JavaScript" type="text/javascript">
									var pagina = "cerrar_sesion.php"
									//location.href=pagina							
								</script> <?php
												} else {
													while ($fila = mysqli_fetch_array($resultado)) {
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
								<center>
									<h3> Estado de Deuda Detallado </h3>
								</center>
								<p></p>

								<?php
													$sql = "SELECT ano,mes,sum(total) as total FROM `factura` 
 where estado='' and idsocio in( select idsocio from socios where matricula=" . $_SESSION['matricula_socio'] . ") 	group by ano,mes,total order by ano DESC,mes DESC";
													// print_r($sql);
													$resultado = mysqli_query($conn, $sql);
													$Cantidad_Filas = mysqli_num_rows($resultado);
													if ($Cantidad_Filas == 0) {
														echo "No tiene deuda!";
													} else { ?><center>
										<div class="scrollspy-example" data-spy="scroll" style="max-height:300px;">
											<table class="table table-striped table-condensed table-bordered table-responsive">
												<thead>
													<tr>
														<th>A&Ntilde;O</th>
														<th>MES</th>
														<th>TOTAL</th>
													</tr>
												</thead>

												<?php while ($fila = mysqli_fetch_array($resultado)) { ?>
													<tr>
														<td><?php echo $fila['ano']; ?></td>
														<td><?php echo $fila['mes']; ?></td>
														<td><?php echo $fila['total']; ?></td>
													</tr>
												<?php } ?>

											</table>
									</center>
									<?php
														$sql = "SELECT s.nombre as socio,sum(f.total) as total from factura f inner join socios s on s.idsocio=f.idsocio where f.estado='' and s.matricula=" . $_SESSION['matricula_socio'] . " 	group by s.nombre";
														$resultado = mysqli_query($conn, $sql);
														$Cantidad_Filas = mysqli_num_rows($resultado);

														while ($fila = mysqli_fetch_array($resultado)) {
															echo "<div align='right'>Total de la Deuda $<strong>" . $fila['total'] . "</strong></div>";
														}

									?>
									<p> <strong>A los montos consignados al momento del pago se calcularan las actualizaciones por mora.</strong></p>
						</div><?php
													}
												}
									?>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
</body>

</html>