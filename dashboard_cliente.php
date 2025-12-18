<?php
include_once "funciones.php";

if (!isset($_GET["id"])) {
    header("Location: clientes.php");
    exit;
}

$cliente = obtenerClientePorId($_GET["id"]);

$totalVentas = totalAcumuladoVentasPorCliente($cliente->id);
$totalVentasUltimoMes = totalAcumuladoVentasPorClienteEnUltimoMes($cliente->id);
$totalVentasUltimoAnio = totalAcumuladoVentasPorClienteEnUltimoAnio($cliente->id);
$totalVentasEnOtroPeriodo = totalAcumuladoVentasPorClienteAntesDeUltimoAnio($cliente->id);

$historialVentas = obtenerVentasPorCliente($cliente->id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de <?php echo $cliente->nombre ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #fafaf9; display: flex; min-height: 100vh; margin: 0; }
        
        .sidebar { width: 260px; background: linear-gradient(180deg, #7c2d12 0%, #991b1b 100%); color: white; padding: 30px 0; position: fixed; height: 100vh; box-shadow: 4px 0 15px rgba(220, 38, 38, 0.15); z-index: 10; }
        .sidebar-logo { padding: 0 25px 30px; border-bottom: 1px solid rgba(251, 191, 36, 0.2); margin-bottom: 20px; display: flex; align-items: center; gap: 12px; }
        .sidebar-logo-icon { width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; }
        .sidebar-logo-icon img { width: 100%; height: 100%; object-fit: contain; border-radius: 50%; }
        .sidebar-logo h2 { font-size: 22px; font-weight: 700; color: #fbbf24; margin: 0; }
        .sidebar-menu { list-style: none; padding: 0 15px; }
        .sidebar-menu a { display: flex; align-items: center; padding: 12px 15px; color: rgba(254,243,199,0.7); text-decoration: none; border-radius: 8px; transition: all 0.3s; margin-bottom: 5px;}
        .sidebar-menu a:hover, .sidebar-menu a.active { background-color: rgba(251, 191, 36, 0.15); color: #fef3c7; }
        .sidebar-menu i { width: 25px; text-align: center; margin-right: 10px; }

        .main-content { margin-left: 260px; flex: 1; padding: 30px; background: linear-gradient(135deg, #fafaf9 0%, #f5f5f4 100%); }
        
        .header-box { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header-box h1 { font-size: 28px; color: #7c2d12; font-weight: 700; margin: 0; }
        
        .btn-back { background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: #fff; padding: 10px 20px; border-radius: 8px; text-decoration: none; transition: all 0.3s; font-weight: 600; font-size: 14px; border: none; }
        .btn-back:hover { background: linear-gradient(135deg, #b91c1c 0%, #7c2d12 100%); box-shadow: 0 4px 10px rgba(220,38,38,0.3); color: #fff; }

        .stat-card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s; position: relative; overflow: hidden; height: 100%; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; }
        .stat-card.type-1::before { background: linear-gradient(180deg, #dc2626 0%, #b91c1c 100%); }
        .stat-card.type-2::before { background: linear-gradient(180deg, #fbbf24 0%, #f59e0b 100%); }
        .stat-card.type-3::before { background: linear-gradient(180deg, #7c2d12 0%, #991b1b 100%); }
        
        .stat-icon { width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; font-size: 24px; }
        .stat-card.type-1 .stat-icon { background: rgba(220, 38, 38, 0.1); color: #dc2626; }
        .stat-card.type-2 .stat-icon { background: rgba(251, 191, 36, 0.1); color: #f59e0b; }
        .stat-card.type-3 .stat-icon { background: rgba(124, 45, 18, 0.1); color: #7c2d12; }
        
        .stat-number { font-size: 28px; font-weight: 700; color: #7c2d12; }
        .stat-label { color: #78716c; font-size: 14px; }

        .content-box { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 30px; height: 100%; }
        .content-box h3 { font-size: 18px; font-weight: 700; color: #7c2d12; margin-bottom: 20px; }

        .search-container { position: relative; max-width: 300px; }
        .search-container input { padding-left: 35px; border-radius: 8px; border: 1px solid #e7e5e4; background-color: #fafaf9; width: 100%; padding-top: 8px; padding-bottom: 8px; }
        .search-container input:focus { border-color: #fbbf24; outline: none; box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.1); }
        .search-container i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a8a29e; font-size: 14px; }

        .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .custom-table th { background-color: #7c2d12; color: #fef3c7; padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
        .custom-table th:first-child { border-top-left-radius: 8px; }
        .custom-table th:last-child { border-top-right-radius: 8px; }
        .custom-table td { padding: 15px; border-bottom: 1px solid #e7e5e4; color: #57534e; vertical-align: middle; }
        .custom-table tr:hover { background-color: #fffbeb; }
        
        .btn-action { padding: 6px 12px; border-radius: 6px; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: all 0.2s; border: none; cursor: pointer; }
        .btn-del { background-color: #fee2e2; color: #991b1b; }
        .btn-del:hover { background-color: #ef4444; color: white; }

        @media (max-width: 768px) {
            .sidebar { width: 80px; }
            .sidebar-logo h2, .sidebar-menu a span { display: none; }
            .main-content { margin-left: 80px; }
            .header-box { flex-direction: column; align-items: flex-start; gap: 15px; }
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <img src="./img/logocrm.png" alt="Logo" onerror="this.style.display='none'">
            </div>
            <h2>Dashboard</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fas fa-home"></i> <span>Inicio</span></a></li>
            <li><a href="clientes.php" class="active"><i class="fas fa-users"></i> <span>Clientes</span></a></li>
            <li><a href="ventas.php"><i class="fas fa-shopping-cart"></i> <span>Ventas</span></a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="header-box">
            <h1><i class="fas fa-id-card-alt"></i> Dashboard de <?php echo $cliente->nombre ?></h1>
            <a href="clientes.php" class="btn-back"><i class="fas fa-arrow-left"></i> Volver</a>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card type-1">
                    <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                    <div class="stat-number">$<?php echo number_format($totalVentas, 2) ?></div>
                    <div class="stat-label">Total de ventas</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card type-2">
                    <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
                    <div class="stat-number">$<?php echo number_format($totalVentasUltimoMes, 2) ?></div>
                    <div class="stat-label">Último mes</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card type-3">
                    <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
                    <div class="stat-number">$<?php echo number_format($totalVentasUltimoAnio, 2) ?></div>
                    <div class="stat-label">Último año</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="content-box text-center">
                    <h3>Distribución de ventas</h3>
                    <div style="position: relative; height: 300px; display:flex; justify-content:center;">
                        <canvas id="grafica"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 mb-4">
                <div class="content-box">
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                        <h3><i class="fas fa-list-ul"></i> Historial de Ventas</h3>
                        <div class="search-container">
                            <i class="fas fa-search"></i>
                            <input type="text" id="buscador" placeholder="Buscar fecha, monto o ID...">
                        </div>
                    </div>

                    <?php if(count($historialVentas) > 0): ?>
                    <div class="table-responsive">
                        <table class="custom-table" id="tablaVentas">
                            <thead>
                                <tr>
                                    <th># ID</th>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($historialVentas as $venta): ?>
                                <tr>
                                    <td><?php echo $venta->id; ?></td>
                                    <td><?php echo date("d/m/Y", strtotime($venta->fecha)); ?></td>
                                    <td class="fw-bold text-danger">$<?php echo number_format($venta->monto, 2); ?></td>
                                    <td>
                                        <a href="eliminar_venta.php?id=<?php echo $venta->id; ?>&id_cliente=<?php echo $cliente->id ?>" class="btn-action btn-del" onclick="return confirm('¿Seguro que deseas eliminar esta venta?');">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div id="sinResultados" class="text-center py-4 text-muted" style="display: none;">
                            <i class="fas fa-search fa-2x mb-2 opacity-50"></i>
                            <p>No se encontraron coincidencias.</p>
                        </div>
                    </div>
                    <?php else: ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                            <p>No se encontraron ventas registradas para este cliente.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </main>

    <script>
        const ctx = document.querySelector("#grafica");
        const etiquetas = ["En otro período", "Último mes", "Último año"];
        const datos = {
            data: [
                parseFloat("<?php echo $totalVentasEnOtroPeriodo ?>"),
                parseFloat("<?php echo $totalVentasUltimoMes ?>"),
                parseFloat("<?php echo $totalVentasUltimoAnio ?>")
            ],
            backgroundColor: [
                'rgba(220,38,38,0.8)',
                'rgba(251,191,36,0.8)',
                'rgba(124,45,18,0.8)'
            ],
            borderColor: '#fff',
            borderWidth: 2
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: etiquetas,
                datasets: [datos]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                }
            }
        });

        const buscador = document.getElementById('buscador');
        
        if(buscador) {
            buscador.addEventListener('keyup', function() {
                const filtro = this.value.toLowerCase();
                const filas = document.querySelectorAll('#tablaVentas tbody tr');
                let hayResultados = false;

                filas.forEach(fila => {
                    const textoFila = fila.textContent.toLowerCase();
                    if(textoFila.includes(filtro)) {
                        fila.style.display = '';
                        hayResultados = true;
                    } else {
                        fila.style.display = 'none';
                    }
                });

                const mensajeSinResultados = document.getElementById('sinResultados');
                if (mensajeSinResultados) {
                    mensajeSinResultados.style.display = hayResultados ? 'none' : 'block';
                }
            });
        }
    </script>
</body>
</html>