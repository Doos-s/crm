<?php
if (!isset($_GET["id"]) || !isset($_GET["id_cliente"])) {
    exit("No hay ID de venta o cliente");
}

include_once "funciones.php";

$id_venta = $_GET["id"];
$id_cliente = $_GET["id_cliente"];

eliminarVenta($id_venta);

header("Location: dashboard_cliente.php?id=" . $id_cliente);
?>