<?php
require_once "config/db.php";
include_once "funciones.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $direccion = $_POST['direccion'];
    $departamento = $_POST['departamento'];
    
    actualizarCliente($id, $nombre, $edad, $telefono, $correo, $direccion, $departamento);
    
    header("Location: clientes.php");
    exit();
}
?>