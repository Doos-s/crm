<?php

date_default_timezone_set("America/Lima");

function obtenerDepartamentos()
{
    return [
        "Lima",
        "Arequipa",
        "Cusco",
        "La Libertad",
        "Piura",
        "Lambayeque",
        "Junín",
        "Puno",
        "Ancash",
        "Ica",
    ];
}
function obtenerBD()
{
    $password = "";
    $user = "root";
    $dbName = "crm";
    $database = new PDO('mysql:host=localhost;dbname=' . $dbName, $user, $password);
    $database->query("set names utf8;");
    $database->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $database->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    return $database;
}

function agregarCliente($nombre, $edad, $departamento)
{
    $bd = obtenerBD();
    $fechaRegistro = date("Y-m-d");
    $sentencia = $bd->prepare("INSERT INTO clientes(nombre, edad, departamento, fecha_registro) VALUES (?, ?, ? ,?)");
    return $sentencia->execute([$nombre, $edad, $departamento, $fechaRegistro]);
}

function obtenerClientes() {
    $bd = obtenerBD();
    $sentencia = $bd->query("
        SELECT id, nombre, edad, telefono, correo, direccion, departamento, fecha_registro 
        FROM clientes 
        ORDER BY nombre ASC
    ");
    return $sentencia->fetchAll(PDO::FETCH_OBJ);
}

function buscarClientes($busqueda) {
    $bd = obtenerBD();
    $busqueda = trim($busqueda);
    
    $busqueda_inicial = $busqueda . "%";
    
    $sentencia = $bd->prepare("
        SELECT id, nombre, edad, telefono, correo, direccion, departamento, fecha_registro 
        FROM clientes 
        WHERE nombre LIKE ?
        ORDER BY nombre ASC
    ");
    
    $sentencia->execute([$busqueda_inicial]);
    
    return $sentencia->fetchAll(PDO::FETCH_OBJ);
}

function eliminarCliente($id)
{
    $bd = obtenerBD();
    $sentencia = $bd->prepare("DELETE FROM clientes WHERE id = ?");
    return $sentencia->execute([$id]);
}

function obtenerClientePorId($id)
{
    $bd = obtenerBD();
    $sentencia = $bd->prepare("SELECT id, nombre, edad, departamento, fecha_registro FROM clientes WHERE id = ?");
    $sentencia->execute([$id]);
    return $sentencia->fetchObject();
}
function actualizarCliente($id, $nombre, $edad, $telefono, $correo, $direccion, $departamento) {
    $bd = obtenerBD();
    $sentencia = $bd->prepare("
        UPDATE clientes 
        SET nombre = ?, edad = ?, telefono = ?, correo = ?, direccion = ?, departamento = ? 
        WHERE id = ?
    ");
    return $sentencia->execute([$nombre, $edad, $telefono, $correo, $direccion, $departamento, $id]);
}

function agregarVenta($idCliente, $monto, $fecha)
{
    $bd = obtenerBD();
    $sentencia = $bd->prepare("INSERT INTO ventas_clientes(id_cliente, monto, fecha) VALUES (?, ?, ?)");
    return $sentencia->execute([$idCliente, $monto, $fecha]);
}
function registrarVenta($idCliente, $monto, $fecha) {
    return agregarVenta($idCliente, $monto, $fecha);
}
function eliminarVenta($id)
{
    $bd = obtenerBD();
    $sentencia = $bd->prepare("DELETE FROM ventas_clientes WHERE id = ?");
    return $sentencia->execute([$id]);
}

function obtenerVentas() {
    $bd = obtenerBD(); 
    $sentencia = $bd->query("SELECT ventas_clientes.id, ventas_clientes.fecha, ventas_clientes.monto, clientes.nombre as nombre_cliente 
                             FROM ventas_clientes 
                             INNER JOIN clientes ON ventas_clientes.id_cliente = clientes.id 
                             ORDER BY ventas_clientes.fecha DESC");
    return $sentencia->fetchAll();
}
function obtenerVentasPorCliente($id_cliente) {
    $bd = obtenerBD();
    $sentencia = $bd->prepare("SELECT ventas_clientes.id, ventas_clientes.fecha, ventas_clientes.monto, clientes.nombre as nombre_cliente 
                               FROM ventas_clientes 
                               INNER JOIN clientes ON ventas_clientes.id_cliente = clientes.id 
                               WHERE ventas_clientes.id_cliente = ? 
                               ORDER BY ventas_clientes.fecha DESC");
    $sentencia->execute([$id_cliente]);
    return $sentencia->fetchAll(); 
}

function obtenerVentaPorId($id) {
    $bd = obtenerBD();
    $sentencia = $bd->prepare("SELECT ventas_clientes.id, ventas_clientes.fecha, ventas_clientes.monto, clientes.nombre as nombre_cliente 
                               FROM ventas_clientes 
                               INNER JOIN clientes ON ventas_clientes.id_cliente = clientes.id 
                               WHERE ventas_clientes.id = ?");
    $sentencia->execute([$id]);
    return $sentencia->fetchObject();
}

function obtenerVentasPorRango($fechaInicio, $fechaFin) {
    $bd = obtenerBD();
    $sentencia = $bd->prepare("SELECT ventas_clientes.fecha, ventas_clientes.monto, clientes.nombre 
                               FROM ventas_clientes 
                               INNER JOIN clientes ON ventas_clientes.id_cliente = clientes.id 
                               WHERE ventas_clientes.fecha >= ? AND ventas_clientes.fecha <= ? 
                               ORDER BY ventas_clientes.fecha DESC");
    $sentencia->execute([$fechaInicio, $fechaFin]);
    return $sentencia->fetchAll();
}

function totalAcumuladoVentasPorCliente($idCliente)
{
    $bd = obtenerBD();
    $sentencia = $bd->prepare("SELECT COALESCE(SUM(monto), 0) AS total FROM ventas_clientes WHERE id_cliente = ?");
    $sentencia->execute([$idCliente]);
    return $sentencia->fetchObject()->total;
}

function totalAcumuladoVentasPorClienteEnUltimoMes($idCliente)
{
    $inicio = date("Y-m-01");
    $bd = obtenerBD();
    $sentencia = $bd->prepare("SELECT COALESCE(SUM(monto), 0) AS total FROM ventas_clientes WHERE id_cliente = ? AND fecha >= ?");
    $sentencia->execute([$idCliente, $inicio]);
    return $sentencia->fetchObject()->total;
}
function totalAcumuladoVentasPorClienteEnUltimoAnio($idCliente)
{
    $inicio = date("Y-01-01");
    $bd = obtenerBD();
    $sentencia = $bd->prepare("SELECT COALESCE(SUM(monto), 0) AS total FROM ventas_clientes WHERE id_cliente = ? AND fecha >= ?");
    $sentencia->execute([$idCliente, $inicio]);
    return $sentencia->fetchObject()->total;
}
function totalAcumuladoVentasPorClienteAntesDeUltimoAnio($idCliente)
{
    $inicio = date("Y-01-01");
    $bd = obtenerBD();
    $sentencia = $bd->prepare("SELECT COALESCE(SUM(monto), 0) AS total FROM ventas_clientes WHERE id_cliente = ? AND fecha < ?");
    $sentencia->execute([$idCliente, $inicio]);
    return $sentencia->fetchObject()->total;
}

function obtenerNumeroTotalClientes()
{
    $bd = obtenerBD();
    $sentencia = $bd->query("SELECT COUNT(*) AS conteo FROM clientes");
    return $sentencia->fetchObject()->conteo;
}
function obtenerNumeroTotalClientesUltimos30Dias()
{
    $hace30Dias = date("Y-m-d", strtotime("-30 day"));
    $bd = obtenerBD();
    $sentencia = $bd->prepare("SELECT COUNT(*) AS conteo FROM clientes WHERE fecha_registro >= ?");
    $sentencia->execute([$hace30Dias]);
    return $sentencia->fetchObject()->conteo;
}

function obtenerNumeroTotalClientesUltimoAnio()
{
    $inicio = date("Y-01-01");
    $bd = obtenerBD();
    $sentencia = $bd->prepare("SELECT COUNT(*) AS conteo FROM clientes WHERE fecha_registro >= ?");
    $sentencia->execute([$inicio]);
    return $sentencia->fetchObject()->conteo;
}

function obtenerNumeroTotalClientesAniosAnteriores()
{
    $inicio = date("Y-01-01");
    $bd = obtenerBD();
    $sentencia = $bd->prepare("SELECT COUNT(*) AS conteo FROM clientes WHERE fecha_registro < ?");
    $sentencia->execute([$inicio]);
    return $sentencia->fetchObject()->conteo;
}

function obtenerTotalDeVentas()
{
    $bd = obtenerBD();
    $sentencia = $bd->query("SELECT COALESCE(SUM(monto), 0) AS total FROM ventas_clientes");
    return $sentencia->fetchObject()->total;
}

function obtenerClientesPorDepartamento()
{
    $bd = obtenerBD();
    $sentencia = $bd->query("SELECT departamento, COUNT(*) AS conteo FROM clientes GROUP BY departamento");
    return $sentencia->fetchAll();
}

function obtenerConteoClientesPorRangoDeEdad($inicio, $fin)
{
    $bd = obtenerBD();
    $sentencia = $bd->prepare("select count(*) AS conteo from clientes WHERE edad >= ? AND edad <= ?;");
    $sentencia->execute([$inicio, $fin]);
    return $sentencia->fetchObject()->conteo;
}

function obtenerVentasAnioActualOrganizadasPorMes()
{
    $bd = obtenerBD();
    $anio = date("Y");
    $sentencia = $bd->prepare("select MONTH(fecha) AS mes, COUNT(*) AS total from ventas_clientes WHERE YEAR(fecha) = ? GROUP BY MONTH(fecha);");
    $sentencia->execute([$anio]);
    return $sentencia->fetchAll();
}

function obtenerReporteClientesEdades()
{
    $rangos = [
        [1, 10],
        [11, 20],
        [20, 40],
        [40, 80],
    ];
    $resultados = [];
    foreach ($rangos as $rango) {
        $inicio = $rango[0];
        $fin = $rango[1];
        $conteo = obtenerConteoClientesPorRangoDeEdad($inicio, $fin);
        $dato = new stdClass;
        $dato->etiqueta = $inicio . " - " . $fin;
        $dato->valor = $conteo;
        array_push($resultados, $dato);
    }
    return $resultados;
}

