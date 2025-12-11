<?php
include_once "funciones.php";
$cliente = obtenerClientePorId($_GET["id"]);
$totalVentas = totalAcumuladoVentasPorCliente($cliente->id);
$totalVentasUltimoMes = totalAcumuladoVentasPorClienteEnUltimoMes($cliente->id);
$totalVentasUltimoAnio = totalAcumuladoVentasPorClienteEnUltimoAnio($cliente->id);
$totalVentasEnOtroPeriodo = totalAcumuladoVentasPorClienteAntesDeUltimoAnio($cliente->id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de <?php echo $cliente->nombre ?></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #fafaf9;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #7c2d12 0%, #991b1b 100%);
            color: white;
            padding: 30px 0;
            position: fixed;
            height: 100vh;
            box-shadow: 4px 0 15px rgba(220, 38, 38, 0.15);
        }

        .sidebar-logo {
            padding: 0 25px 30px;
            border-bottom: 1px solid rgba(251, 191, 36, 0.2);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-logo-icon {
            width: 60px;
            height: 60px;
            background: rgba(251, 191, 36, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }

        .sidebar-logo h2 {
            font-size: 22px;
            font-weight: 700;
            color: #fbbf24;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0 15px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: rgba(254,243,199,0.7);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .sidebar-menu a:hover {
            background-color: rgba(251, 191, 36, 0.15);
            color: #fef3c7;
        }

        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
            background: linear-gradient(135deg, #fafaf9 0%, #f5f5f4 100%);
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .dashboard-header h1 {
            font-size: 30px;
            color: #7c2d12;
            font-weight: 700;
        }

        .back-btn {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: #fff;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .back-btn:hover {
            background: linear-gradient(135deg, #b91c1c 0%, #7c2d12 100%);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 4px; height: 100%;
        }

        .stat-card:nth-child(1)::before { background: linear-gradient(180deg, #dc2626 0%, #b91c1c 100%); }
        .stat-card:nth-child(2)::before { background: linear-gradient(180deg, #fbbf24 0%, #f59e0b 100%); }
        .stat-card:nth-child(3)::before { background: linear-gradient(180deg, #7c2d12 0%, #991b1b 100%); }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .stat-card:nth-child(1) .stat-icon { background: rgba(220, 38, 38, 0.1); color: #dc2626; }
        .stat-card:nth-child(2) .stat-icon { background: rgba(251, 191, 36, 0.1); color: #f59e0b; }
        .stat-card:nth-child(3) .stat-icon { background: rgba(124, 45, 18, 0.1); color: #7c2d12; }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #7c2d12;
        }

        .stat-label {
            color: #78716c;
            font-size: 14px;
        }

        .chart-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .chart-card h3 {
            font-size: 18px;
            font-weight: 600;
            color: #7c2d12;
            margin-bottom: 15px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .sidebar { width: 80px; }
            .sidebar-logo h2, .sidebar-menu a span { display: none; }
            .main-content { margin-left: 80px; }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <img src="./img/logo.png" alt="Logo" onerror="this.style.display='none'">
            </div>
            <h2>Dashboard</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fas fa-home"></i> <span>Inicio</span></a></li>
            <li><a href="clientes.php" class="active"><i class="fas fa-users"></i> <span>Clientes</span></a></li>
            <li><a href="ventas.php"><i class="fas fa-shopping-cart"></i> <span>Ventas</span></a></li>
        </ul>
    </aside>

    <!-- MAIN -->
    <main class="main-content">
        <div class="dashboard-header">
            <h1>Dashboard de <?php echo $cliente->nombre ?></h1>
            <a href="clientes.php" class="back-btn"><i class="fas fa-arrow-left"></i> Volver</a>
        </div>

        <!-- STATS -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-dollar-sign fa-2x"></i></div>
                <div class="stat-number">$<?php echo number_format($totalVentas, 2) ?></div>
                <div class="stat-label">Total de ventas</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-calendar-day fa-2x"></i></div>
                <div class="stat-number">$<?php echo number_format($totalVentasUltimoMes, 2) ?></div>
                <div class="stat-label">Último mes</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-calendar-alt fa-2x"></i></div>
                <div class="stat-number">$<?php echo number_format($totalVentasUltimoAnio, 2) ?></div>
                <div class="stat-label">Último año</div>
            </div>
        </div>

        <!-- CHART -->
        <div class="chart-card" style="
    background:#fff;
    border-radius:12px;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
    padding:15px;
    margin:15px auto;
    text-align:center;
    width:45%;           /* antes 60%, más pequeño */
">
    <h3 style="
        color:#333;
        font-weight:600;
        margin-bottom:8px;
        font-size:1.1rem;
    ">
        Distribución de ventas
    </h3>
    <canvas id="grafica" style="
        max-width:260px;  /* antes 350px */
        max-height:260px; /* antes 350px */
        margin:0 auto;
    "></canvas>
</div>

    </main>

    <!-- SCRIPT -->
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
            type: 'pie',
            data: {
                labels: etiquetas,
                datasets: [datos]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
</body>
</html>
