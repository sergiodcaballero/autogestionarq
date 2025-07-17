<?php

$idsocio = $_SESSION['socio']['IDSOCIO'];

$pago_creado = buscar_pago_creado($idsocio);
if (!empty($pago_creado)) {
    $id_pago = $pago_creado['id'];
}
$id_pago = crear_nuevo_pago($idsocio);
if (!empty($_POST['facturas'])) {
    foreach ($_POST['facturas'] as $fact) {
        $factura_encontrada = buscar_factura_pendiente($fact);
        if (empty($factura_encontrada)) {
            crear_factura_pendiente($fact, $id_pago);
            $factura_encontrada = buscar_factura_pendiente($fact);
        } else {
            actualizar_factura_pendiente($fact, $id_pago);
        }
    }
} else {
    header("Location: principal.php");
}

$facturas_pendientes = buscar_facturas_pendientes($id_pago);
$facturas_para_pagar = array();
$monto_total = 0;
foreach ($facturas_pendientes as $factura_encontrada) {
    $factura_encontrada['TOTALFORMATEADO'] = str_replace(".", "", $factura_encontrada['TOTAL']);
    $monto_total += $factura_encontrada['TOTALFORMATEADO'];
    $facturas_para_pagar[] = $factura_encontrada;
}

require_once __DIR__ . '/../clases/onclick.php';

$onClick = new OnClick();

$extra_data = array(
    'informacion' => $_SESSION['socio']['MATRICULA'] . "-" . $_SESSION['socio']['NOMBRE']
);

$data = $onClick->encriptarPost($monto_total, $id_pago, $extra_data);

$transaccionId = PREFIJO_PAGOS . "-" . $id_pago;

?>
<style>
    .input-det {
        border: 0px;
    }
</style>
<div class="cuotas_pendientes row">
    <div class="facturas-para-pagar col-12">
        <label for="">Nro de pago: <?php echo $transaccionId ?></label>
        <form id="formPago" method="post" action="<?php echo URL_API ?>">
            <input type="hidden" name="CallbackSuccess" value="<?php echo $data['callbackEncriptada'] ?>" />
            <input type="hidden" name="CallbackPending" value="<?php echo $data['pendingEncriptada'] ?>" />
            <input type="hidden" name="CallbackCancel" value="<?php echo $data['cancelEncriptada'] ?>" />
            <input type="hidden" name="Comercio" value="<?php echo GUID ?>" />
            <input type="hidden" name="SucursalComercio" value="<?php echo $data['sucursalEncriptada'] ?>" />
            <input type="hidden" name="Informacion" value="<?php echo $data['informacion'] ?>" />
            <input type="hidden" name="Hash" value="<?php echo $data['hashEncriptado'] ?>" />
            <input type="hidden" name="TransaccionComercioId" value="<?php echo $transaccionId; ?>" />
            <table class="table table-sm">
                <caption>Tabla que muestra las facturas que serán procesadas para el pago</caption>
                <thead>
                    <tr>
                        <th scope="row">Nro.</th>
                        <th scope="row">Detalle</th>
                        <th scope="row">Mes</th>
                        <th scope="row">Año</th>
                        <th scope="row">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    $monto_total_texto = 0;
                    foreach ($facturas_para_pagar as $factura) {
                        $producto = '<input type="text" class="input-det" name="Producto[' . $i . ']" value="Cobro Período ' . $factura['MES'] . '-' . $factura['ANO'] . '" readonly/>';
                        $monto = '<input type="hidden" class="input-det" name="MontoProducto[' . $i . ']" value="' . $factura['TOTALFORMATEADO'] . '" readonly />';
                        $monto_total_texto += floatval($factura['TOTAL']);
                        $i++;
                        $fila = "<tr>
                        <td>{$factura['IDFACTURA']}</td>
                        <td>{$producto} {$monto}</td>
                        <td>{$factura['MES']}</td>
                        <td>{$factura['ANO']}</td>
                        <td>$ {$factura['TOTAL']}</td>
                        </tr>";
                        echo $fila;
                    }
                    ?>
                    <tr class="font-weight-bold">
                        <td colspan="2" class="text-right">Cantidad:</td>
                        <td><?php echo $i ?></td>
                        <td class="text-right">Monto Total:</td>
                        <td>$ <?php echo $monto_total_texto ?></td>
                    </tr>
                </tbody>
            </table>
            <input type="hidden" name="Monto" value="<?php echo $data['montoEncriptado'] ?>" />
        </form>
        <button type="button" onclick="procesarPago()">Procesar Pago</button>
        <button type="button" onclick="volver()">Volver</button>
    </div>
</div>
<script>
    const procesarPago = () => {
        if (confirm("Realizar el pago?")) {
            formPago.submit();
        }
    }

    const volver = () => {
        if (confirm("Desea salir del pago?")) {
            window.location.href = 'principal.php'
        }
    }
</script>