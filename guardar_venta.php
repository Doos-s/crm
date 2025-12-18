<?php
if (!isset($_POST["id_cliente"]) || !isset($_POST["monto"]) || !isset($_POST["fecha"])) {
    exit("Faltan datos para registrar la venta.");
}

include_once "funciones.php";

$id_cliente = $_POST["id_cliente"]; 
$monto = $_POST["monto"];
$fecha = $_POST["fecha"];

$resultado = agregarVenta($id_cliente, $monto, $fecha);

if ($resultado) {
    header("Location: dashboard_cliente.php?id=" . $id_cliente);
} else {
    echo "Error al guardar la venta.";
}
?>