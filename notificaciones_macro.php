<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

require_once __DIR__ . "/includes/conexion.php";
require_once __DIR__ . "/includes/funciones.php";
/*
echo "URL DE NOTIFICACIONES";
echo "<br>";
echo print_r($_POST);
echo "<br>";
 */
$data = json_encode($_POST);
$json = file_get_contents('php://input');

$dataJson = json_decode($json);

if (!empty($data)) {
    $mysqli->query("INSERT INTO notificaciones (data, data_json) VALUES ('" . $data . "', '" . json_encode($dataJson) . "') ");
}
$notificacion = (array) $dataJson;
//obtener tipo de pago
$tipo_de_pago = '';
$medio_pago = intval($notificacion['MedioPago']);
if ($medio_pago == 8) {
    $tipo_de_pago = 'credito';
} elseif ($medio_pago == 9) {
    $tipo_de_pago = 'debito';
} elseif ($medio_pago == 10) {
    $tipo_de_pago = 'debin';
} elseif ($medio_pago == 11) {
    $tipo_de_pago = 'credito_cuotas';
} elseif ($medio_pago == 0) {
    $tipo_de_pago = 'efectivo';
}

//estado de pago
$estado_pago = intval($notificacion['EstadoId']);

//Registrar Pago solo si fue finalizado
if ($estado_pago === 3) {
    //obtengo nro de transaccion, si existe doy como pagado todas las facturas
    $nro_pago = obtener_nro_pago_de_transaccion($notificacion['TransaccionComercioId']);
    registrar_pago_finalizado($nro_pago);
}

echo json_encode($dataJson);
