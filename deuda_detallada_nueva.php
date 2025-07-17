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
							<?php include('menu.php') ?>
						</div>
						<div class="span8">
							<center>
								<h3>Mis Datos</h3>
							</center>
							<?php
							$consulta = "select nombre,n_documento,direccion,email 
							from socios 
							where matricula=" . $_SESSION['matricula_socio'];
							$resultado = mysqli_query($conn, $consulta);
							$Cantidad_Filas = mysqli_num_rows($resultado);
							if ($Cantidad_Filas == 0) {
							?><script language="JavaScript" type="text/javascript">
									var pagina = "cerrar_sesion.php"
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
								<h3> Estado de Deuda Detallado </h3>
								<p></p>
								<?php
													$sql = "SELECT idfactura as id, ano, mes, sum(total) as total
													FROM `factura`
													where estado=''
														and idsocio in( select idsocio from socios where matricula=" . $_SESSION['matricula_socio'] . ")
													GROUP BY ano,mes,total
													order by ano DESC,mes DESC";
													// print_r($sql);
													$resultado = mysqli_query($conn, $sql);
													$Cantidad_Filas = mysqli_num_rows($resultado);
													if ($Cantidad_Filas == 0) {
														echo "No tiene deuda!";
													} else { ?>
									<div class="scrollspy-example" data-spy="scroll" style="max-height:300px;">
										<form id="formPagar" method="post" action="home.php?p=pagos_pendientes">
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
														<td><input type="checkbox" name="facturas[]" value="<?php echo $fila['id'] ?>" /></td>
													</tr>
												<?php } ?>

											</table>
											<td><button type="button" onclick="pagar()">Generar Pago</button></td>
										</form>
										<?php
														$sql = "SELECT s.nombre as socio,sum(f.total) as total
														from factura f
														inner join socios s on s.idsocio=f.idsocio
														where f.estado='' and s.matricula=" . $_SESSION['matricula_socio'] . " 	group by s.nombre";
														$resultado = mysqli_query($conn, $sql);
														$Cantidad_Filas = mysqli_num_rows($resultado);

														while ($fila = mysqli_fetch_array($resultado)) {
															echo "<div align='right'>Total de la Deuda $<strong>" . $fila['total'] . "</strong></div>";
														}

										?>
										<p>
											<strong>A los montos consignados al momento del pago se calcularan las actualizaciones por mora.</strong>
										</p>
									</div><?php
													}
												} ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		'use strict'
		const pagar = () => {
			let facturas = document.querySelectorAll("[name='facturas[]']:checked")
			if (facturas.length > 0) {
				if (confirm("¿Esta seguro que desea pagar las facturas seleccionadas?")) {
					formPagar.submit();
				}
			} else {
				alert("Debe seleccionar al menos una factura para Generar el Pago.")
			}
		}
	</script>
</body>

</html>