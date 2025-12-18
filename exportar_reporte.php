<?php
$fechaInicio = $_POST["inicio"];
$fechaFin = $_POST["fin"];
$id_cliente = $_POST["id_cliente"];
$formato = $_POST["formato"];

$bd = new PDO('mysql:host=localhost;dbname=crm', 'root', '');
$sql = "SELECT v.fecha, v.monto, c.nombre 
        FROM ventas v 
        INNER JOIN clientes c ON v.id_cliente = c.id 
        WHERE v.fecha BETWEEN '$fechaInicio' AND '$fechaFin'";

if ($id_cliente != "") {
    $sql .= " AND v.id_cliente = '$id_cliente'";
}

$sentencia = $bd->prepare($sql);
$sentencia->execute();
$ventas = $sentencia->fetchAll(PDO::FETCH_OBJ);


$total = 0;
foreach($ventas as $v) $total += $v->monto;


$nombreArchivo = "Reporte_Ventas_" . date("Y-m-d");

if ($formato == "excel") {
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=$nombreArchivo.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    
    echo "<table border='1'>";
    echo "<tr style='background-color: #7c2d12; color: white;'><th>Fecha</th><th>Cliente</th><th>Monto</th></tr>";
    foreach ($ventas as $venta) {
        echo "<tr>";
        echo "<td>" . $venta->fecha . "</td>";
        echo "<td>" . utf8_decode($venta->nombre) . "</td>"; 
        echo "<td>" . number_format($venta->monto, 2) . "</td>";
        echo "</tr>";
    }
    echo "<tr><td colspan='2' align='right'><strong>TOTAL</strong></td><td><strong>" . number_format($total, 2) . "</strong></td></tr>";
    echo "</table>";

} elseif ($formato == "word") {
    header("Content-Type: application/vnd.ms-word");
    header("Content-Disposition: attachment; filename=$nombreArchivo.doc");
    
    echo "<h1>Reporte de Ventas</h1>";
    echo "<p><strong>Desde:</strong> $fechaInicio <strong>Hasta:</strong> $fechaFin</p>";
    echo "<table border='1' cellpadding='10' cellspacing='0' style='width:100%; border-collapse: collapse;'>";
    echo "<tr style='background-color: #ddd;'><th>Fecha</th><th>Cliente</th><th>Monto</th></tr>";
    foreach ($ventas as $venta) {
        echo "<tr>";
        echo "<td>" . $venta->fecha . "</td>";
        echo "<td>" . $venta->nombre . "</td>";
        echo "<td>$" . number_format($venta->monto, 2) . "</td>";
        echo "</tr>";
    }
    echo "<tr><td colspan='2' align='right'><strong>TOTAL ACUMULADO</strong></td><td><strong>$" . number_format($total, 2) . "</strong></td></tr>";
    echo "</table>";

} elseif ($formato == "txt") {
    header("Content-Type: text/plain");
    header("Content-Disposition: attachment; filename=$nombreArchivo.txt");

    echo "----------------------------------------\r\n";
    echo "          REPORTE DE VENTAS            \r\n";
    echo "----------------------------------------\r\n";
    echo "Rango: $fechaInicio al $fechaFin\r\n\r\n";
    echo str_pad("FECHA", 15) . str_pad("CLIENTE", 20) . "MONTO\r\n";
    echo "----------------------------------------\r\n";
    
    foreach ($ventas as $venta) {
        echo str_pad($venta->fecha, 15);
        echo str_pad(substr($venta->nombre, 0, 18), 20); // Recortar nombre si es muy largo
        echo "$" . number_format($venta->monto, 2) . "\r\n";
    }
    
    echo "----------------------------------------\r\n";
    echo str_pad("TOTAL ACUMULADO:", 35) . "$" . number_format($total, 2);
}
?>