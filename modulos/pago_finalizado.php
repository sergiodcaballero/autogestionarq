<?php

if (!empty($_GET['pago'])) {
    if (!empty($_GET['fin']) && $_GET['fin'] == 'ok') {
        //Pago finalizado
        registrar_pago_finalizado($_GET['pago']);
        alerta("El pago a finalizado correctamente.");
        redir("principal.php");
    }
    if (!empty($_GET['cancel']) && $_GET['cancel'] == 'ok') {
        //Se cancelo el pago
        alerta("El sistema pago ha sido cancelado.");
        redir("principal.php");
    }
    if (!empty($_GET['pendiente']) && $_GET['pendiente'] == 'ok') {
        //Pago pendiente
        registrar_pago_pendiente($_GET['pago']);
        alerta("El sistema no soporta pagos con ese medio aún.");
        redir("principal.php");
    }
}
