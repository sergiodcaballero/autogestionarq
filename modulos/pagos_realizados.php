<?php

$idsocio = $_SESSION['socio']['IDSOCIO'];

$pagos = buscar_pagos_finalizados($idsocio);
$pagos_realizados = array();
foreach ($pagos as $pago) {
    $datos_pago = buscar_pago_finalizado($pago['nropago']);
    $total = 0;
    foreach ($datos_pago as $det) {
        $total += $det['TOTAL'];
        $pago['fecha_pago'] = $det['FECHA_PAGO'] . " " . $det['HORA_PAGO'];
    }
    $pago['detalles'] = $datos_pago;
    $pago['monto_total'] = $total;
    $pagos_realizados[] = $pago;
}

function mostrar_detalle($pago)
{
    $tabla = "<table class='table table-sm table-hovered table-dark'>";
    $tabla .= "<thead class='thead-dark'><tr>
        <th>Nro. Factura</th>
        <th>Monto</th>
        <th>Período</th>
    </tr></thead>";
    $tabla .= "<tbody>";
    foreach ($pago['detalles'] as $det) {
        $det['TOTAL_FORMATEADO'] = formatear_numero($det['TOTAL']);
        $tabla .= "<tr>";
        $tabla .= "<td>{$det['IDFACTURA']}</td>";
        $tabla .= "<td>$ {$det['TOTAL_FORMATEADO']}</td>";
        $tabla .= "<td>{$det['MES']}-{$det['ANO']}</td>";
        $tabla .= "</tr>";
    }
    $tabla .= "</tbody>";
    $tabla .= "</table>";
    return $tabla;
}

function mostrar_pagos($pagos)
{
    $tabla = "<table id='tabla-pagos' class='table table-sm table-hovered'>";
    $tabla .= "<caption>Muestra los pagos realizados por autogestión</caption>";
    $tabla .= "<thead class='thead-dark'><tr>
        <th>Nro. de Pago</th>
        <th>Monto</th>
        <th>Fecha</th>
        <th>Detalles</th>
    </tr></thead>";
    $tabla .= "<tbody>";
    foreach ($pagos as $pago) {
        $pago['fecha_pago_formateado'] = formatear_fecha($pago['fecha_pago'], 'd/m/Y H:i');
        $pago['monto_total_formateado'] = formatear_numero($pago['monto_total']);
        $tabla .= "<tr>";
        $tabla .= "<td>{$pago['nropago']}</td>";
        $tabla .= "<td>$ {$pago['monto_total_formateado']}</td>";
        $tabla .= "<td>{$pago['fecha_pago_formateado']}</td>";
        $tabla .= "<td>";
        $tabla .= "<button class='btn btn-sm btn-info' onclick='ver_detalle({$pago['nropago']})'>Ver detalle</button>";
        $tabla .= "<button class='btn btn-sm btn-primary ml-2' onclick='ver_comprobante({$pago['nropago']})'>Ver Comprobante</button>";
        $tabla .= "</td>";
        $tabla .= "</tr>";
        $tabla .= "<tr id='detalle_{$pago['nropago']}' ><td>Detalle</td><td colspan='3'>";
        $tabla .= mostrar_detalle($pago);
        $tabla .= "</td></tr>";
    }
    $tabla .= "</tbody>";
    $tabla .= "</table>";
    return $tabla;
}
?>
<div class="pagos_realizados">
    <a href="principal.php" class="btn btn-secondary btn-sm">Volver</a>
    <br>
    <br>
    <h3 for="">Pagos realizados</h3>
    <?php
    echo mostrar_pagos($pagos_realizados);
    ?>
    <script>
        const ver_detalle = (nropago) => {
            $(`#detalle_${nropago}`).toggle();
        }

        const ver_comprobante = (nropago) => {
            window.open(`reportes/pago_realizado.php?pago=${nropago}`)
        }

        $(`[id*="detalle_"`).hide();
    </script>
</div>
