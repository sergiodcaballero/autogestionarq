<?php

define('MYSQL_USER', 'root');
define('MYSQL_PASS', 'secretkey');
define('MYSQL_HOST', 'mysql');
define('MYSQL_PORT', 3306);
define('MYSQL_DATABASE', 'arquite1_autog');

define('DEBUG', true);

define('PREFIJO_PAGOS', 'test1');
define("HABILITAR_PAGOS_MACRO", false);

//define('URL_API', 'https://sandboxpp.asjservicios.com.ar:8082/v1');
define('URL_API', 'https://sandboxpp.asjservicios.com.ar');

define('GUID', '6addadd8-ee33-4d1e-a6fd-cc5f27c354b6');
define('FRASE', 'jOEgrRntkknd745ZMrb4LFXOqGcU1OYJSx1T06c1OMg=');
define('SECRET_KEY', 'COLEGIODEARQUITECTOSDELAPROVINCIADEMISIONES_54666a23-980c-4303-b418-80e54cd30276');

$protocol = 'https://';
if (!isset($_SERVER['HTTPS']) || empty($_SERVER['HTTPS'])) {
  $protocol = 'http://';
}

$servidor = $_SERVER["SERVER_NAME"];

define("WEB_URL", $protocol . $servidor);
define('HTTP_URL', $protocol . $servidor);
define('SISTEMA_URL', $protocol . $servidor . "/autogestion");

if (DEBUG) {
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
}
