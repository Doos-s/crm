<?php
require_once "config/db.php";

// Datos del usuario admin
$nombre = 'Admin';
$correo = 'admin@dve.com';
$passwordPlano = '12345';
$rol = 'administrador';

// Hashear la contraseña
$passwordHash = password_hash($passwordPlano, PASSWORD_DEFAULT);

// Verificar si ya existe un usuario con ese correo
$sqlCheck = "SELECT * FROM usuarios WHERE correo = '$correo'";
$resultado = $conexion->query($sqlCheck);

if ($resultado && $resultado->num_rows > 0) {
    echo " El usuario admin ya existe.";
} else {
    // Crear el usuario
    $sqlInsert = "INSERT INTO usuarios (nombre_usuario, correo, password, rol)
                  VALUES ('$nombre', '$correo', '$passwordHash', '$rol')";

    if ($conexion->query($sqlInsert)) {
        echo " Usuario admin creado correctamente.";
    } else {
        echo " Error al crear usuario: " . $conexion->error;
    }
}
?>
