<?php
$host = "localhost";
$usuario = "root";
$pass = "";
$bd = "crm";

$conexion = new mysqli($host, $usuario, $pass, $bd);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
$conexion->set_charset("utf8");
?>

