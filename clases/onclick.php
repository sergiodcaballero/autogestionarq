<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use PlusPagos\SHA256Encript;
use PlusPagos\AESEncrypter;

class OnClick
{
    //funciones nuevas

    private function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $_SERVER['REMOTE_ADDR'];
    }

    function encriptarPost($monto, $idpago = 0, $extra_data = array())
    {
        $hash = new SHA256Encript();
        $ipAddress = $this->getRealIpAddr();
        $secretKey = SECRET_KEY;
        $comercio = GUID;
        $sucursal = "";
        $amount = $monto;

        $hashEncriptado = $hash->Generate($ipAddress, $secretKey, $comercio, $sucursal, $amount);

        $aes = new AESEncrypter();

        $callbackSuccess = SISTEMA_URL . "/home.php?p=pago_finalizado&fin=ok&pago=" . $idpago;
        $callbackPending = SISTEMA_URL . "/home.php?p=pago_finalizado&pendiente=ok&pago=" . $idpago;
        $callbackCancel = SISTEMA_URL . "/home.php?p=pago_finalizado&cancel=ok&pago=" . $idpago;
        $callbackEncriptada = $aes->EncryptString($callbackSuccess, $secretKey);
        $pendingEncriptada = $aes->EncryptString($callbackPending, $secretKey);
        $cancelEncriptada = $aes->EncryptString($callbackCancel, $secretKey);
        $montoEncriptado = $aes->EncryptString($amount, $secretKey);
        $sucursalEncriptada = $aes->EncryptString($sucursal, $secretKey);
        $informacion = !empty($extra_data['informacion']) ? $aes->EncryptString($extra_data['informacion'], $secretKey) : '';
        $url = URL_API;

        return [
            'callbackEncriptada' => $callbackEncriptada,
            'pendingEncriptada' => $pendingEncriptada,
            'cancelEncriptada' => $cancelEncriptada,
            'montoEncriptado' => $montoEncriptado,
            'sucursalEncriptada' => $sucursalEncriptada,
            'informacion' => $informacion,
            'url' => $url,
            'hashEncriptado' => $hashEncriptado
        ];
    }
}
