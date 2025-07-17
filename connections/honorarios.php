<?php

include_once __DIR__ . '/../config/config.php';

global $conn;
$hostname_honorarios = MYSQL_HOST;
$username_honorarios  = MYSQL_USER;
$password_honorarios = MYSQL_PASS;
$database_honorarios = MYSQL_DATABASE;

$conn = mysqli_connect($hostname_honorarios, $username_honorarios, $password_honorarios, $database_honorarios) or die('Ocurrió un error al conectarse al servidor mysql');
