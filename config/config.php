<?php

define('MYSQL_USER', 'arquite1_autog');
define('MYSQL_PASS', 'autogestionZ');
define('MYSQL_HOST', 'arquitectosmisiones.org.ar');
define('MYSQL_PORT', 3306);
define('MYSQL_DATABASE', 'arquite1_autog');

define('DEBUG', false);

define('PREFIJO_PAGOS', 'onclick');
define("HABILITAR_PAGOS_MACRO", true);

/* sandbox
define('URL_API', 'https://sandboxpp.asjservicios.com.ar');
define('GUID', '6addadd8-ee33-4d1e-a6fd-cc5f27c354b6');
define('FRASE', 'jOEgrRntkknd745ZMrb4LFXOqGcU1OYJSx1T06c1OMg=');
define('SECRET_KEY', 'COLEGIODEARQUITECTOSDELAPROVINCIADEMISIONES_54666a23-980c-4303-b418-80e54cd30276');
*/

/* production */
define('URL_API', 'https://botonpp.macroclickpago.com.ar');
define('GUID', '5bed89e3-f848-425a-b770-36b310a5aaac');
define('FRASE', 'o+VT5bVSK3BoVi2AygqnS7zeHNAVo4Ixx33m8qp8NMw=');
define('SECRET_KEY', 'COLEGIODEARQUITECTOSDELAPROVINCIADEMISIONES_3fe0ca7e-6fe2-4539-949d-2bc6c8f5f041');

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
