<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
global $conn;
$hostname_honorarios = 'arquitectosmisiones.org.ar';
//$username_honorarios = 'root';
//$password_honorarios = ''; // NOTA: Reemplace password por el password de su cuenta de hosting
// $username_honorarios = 'root';
// $password_honorarios = 'root';
$username_honorarios  = 'arquite1_autog';
$password_honorarios = 'autogestionZ';
$database_honorarios = 'arquite1_autog';
//  $honorarios = mysqli_connect($hostname_honorarios, $username_honorarios, $password_honorarios,$database_honorarios) or die ('Ocurrió un error al conectarse al servidor mysql');
$conn = mysqli_connect($hostname_honorarios, $username_honorarios, $password_honorarios, $database_honorarios) or die('Ocurrió un error al conectarse al servidor mysql');
//mysql_select_db($database_honorarios);

//se definen esta variable para usar en el proyecto por si se cambia de servidor o dns.
define("WEB_URL", 'http://www.arquitectosmisiones.org.ar');
define("SISTEMA_URL", 'http://www.arquitectosmisiones.org.ar/autogestion');