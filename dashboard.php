<?php
session_start();
include_once "funciones.php";
$totalClientes = obtenerNumeroTotalClientes();
$totalClientesUltimos30Dias = obtenerNumeroTotalClientesUltimos30Dias();
$totalClientesUltimoAnio = obtenerNumeroTotalClientesUltimoAnio();
$totalClientesAniosAnteriores = obtenerNumeroTotalClientesAniosAnteriores();
$totalVentas = obtenerTotalDeVentas();
$clientesPorDepartamento = obtenerClientesPorDepartamento();
$clientesPorEdad = obtenerReporteClientesEdades();
$ventasAnioActual = obtenerVentasAnioActualOrganizadasPorMes();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard General</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #fafaf9;
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #7c2d12 0%, #991b1b 100%);
            color: white;
            padding: 30px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 4px 0 15px rgba(220, 38, 38, 0.15);
            z-index: 1000;
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px;
        }

        .sidebar-logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }

        .sidebar-logo h2 {
            font-size: 24px;
            font-weight: 700;
            color: #fbbf24;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0 15px;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: rgba(254, 243, 199, 0.7);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: rgba(251, 191, 36, 0.15);
            color: #fef3c7;
            border-left: 3px solid #fbbf24;
            padding-left: 12px;
        }

        .sidebar-menu a i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
            background: linear-gradient(135deg, #fafaf9 0%, #f5f5f4 100%);
        }

        /* ===== HEADER ===== */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .dashboard-header h1 {
            font-size: 32px;
            color: #7c2d12;
            font-weight: 700;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .header-icon {
            width: 40px;
            height: 40px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            color: #78716c;
        }

        .header-icon:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: #fef3c7;
        }

        /* ===== STATS CARDS ===== */
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
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
        }

        .stat-card:nth-child(1)::before { background: linear-gradient(180deg, #dc2626 0%, #b91c1c 100%); }
        .stat-card:nth-child(2)::before { background: linear-gradient(180deg, #fbbf24 0%, #f59e0b 100%); }
        .stat-card:nth-child(3)::before { background: linear-gradient(180deg, #7c2d12 0%, #991b1b 100%); }
        .stat-card:nth-child(4)::before { background: linear-gradient(180deg, #dc2626 0%, #7c2d12 100%); }

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
        .stat-card:nth-child(4) .stat-icon { background: rgba(153, 27, 27, 0.1); color: #991b1b; }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #7c2d12;
            margin-bottom: 5px;
        }

        .stat-label { color: #78716c; font-size: 14px; }

        /* ===== CHARTS ===== */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .chart-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s;
            min-height: 350px;
        }

        .chart-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.12);
        }

        .chart-card:nth-child(1) { border-top: 3px solid #dc2626; }
        .chart-card:nth-child(2) { border-top: 3px solid #fbbf24; }
        .chart-card:nth-child(3) { border-top: 3px solid #7c2d12; }
        .chart-card:nth-child(4) { border-top: 3px solid #991b1b; }

        .chart-card h3 {
            font-size: 18px;
            font-weight: 600;
            color: #7c2d12;
            margin-bottom: 20px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .sidebar { width: 80px; }
            .sidebar-logo h2, .sidebar-menu a span { display: none; }
            .main-content { margin-left: 80px; }
        }





/* Contenedor del submenú */
.submenu-item {
    position: relative;
}

/* Flecha indicadora */
.submenu-toggle .arrow {
    float: right;
    transition: transform 0.3s ease;
    font-size: 0.8em;
    margin-left: 10px;
}

/* Submenú oculto por defecto */
.submenu {
    display: none;
    list-style: none;
    padding-left: 20px;
    margin: 0;
    background-color: rgba(0, 0, 0, 0.1);
}

.submenu li {
    padding: 0;
}

.submenu li a {
    padding: 10px 15px;
    display: block;
    font-size: 0.9em;
}

.submenu li a:hover {
    background-color: rgba(0, 0, 0, 0.1);
}

/* Cuando está activo */
.submenu-item.active .submenu {
    display: block;
}

.submenu-item.active .arrow {
    transform: rotate(180deg);
}
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <img src="img/logocrm.png" alt="Logo" onerror="this.style.display='none'">
            </div>
            <h2>Dashboard</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="clientes.php"><i class="fas fa-users"></i> <span>Clientes</span></a></li>
            <li><a href="ventas.php"><i class="fas fa-shopping-cart"></i> <span>Ventas</span></a></li>
            <li><a href="reportes.php"><i class="fas fa-chart-bar"></i> <span>Reportes</span></a></li>
            <li class="submenu-item"> <a href="#" class="submenu-toggle"><i class="fas fa-cog"></i> <span>Configuración</span><i class="fas fa-chevron-down arrow"></i></a>
                <ul class="submenu">
                    <li><a href="usuarios.php"><i class="fas fa-users"></i> Gestionar Usuarios</a></li>
                    <li><a href="nuevo_usuarios.php"><i class="fas fa-user-plus"></i> Nuevo Usuario</a></li>
                    <li><a href="permisos.php"><i class="fas fa-user-shield"></i> Permisos</a></li>
                    
                 </ul>
            </li>
        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="dashboard-header">
            <h1>Dashboard General</h1>
            <div class="header-actions">
                <div class="header-icon"><i class="fas fa-bell"></i></div>
                <div class="header-icon"><i class="fas fa-user"></i></div>
            </div>
        </div>

        <!-- STATS -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-dollar-sign fa-2x"></i></div>
                <div class="stat-number">$<?php echo number_format($totalVentas ?? 0, 2) ?></div>
                <div class="stat-label">Total ventas</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users fa-2x"></i></div>
                <div class="stat-number"><?php echo $totalClientes ?? 0 ?></div>
                <div class="stat-label">Clientes registrados</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-user-plus fa-2x"></i></div>
                <div class="stat-number"><?php echo $totalClientesUltimos30Dias ?? 0 ?></div>
                <div class="stat-label">Clientes en los últimos 30 días</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-calendar-check fa-2x"></i></div>
                <div class="stat-number"><?php echo $totalClientesUltimoAnio ?? 0 ?></div>
                <div class="stat-label">Clientes en el último año</div>
            </div>
        </div>

        <!-- CHARTS -->
        <div class="charts-grid">
            <div class="chart-card">
                <h3>Clientes por departamento</h3>
                <canvas id="grafica"></canvas>
            </div>

            <div class="chart-card">
                <h3>Clientes por edad</h3>
                <canvas id="graficaEdad"></canvas>
            </div>

            <div class="chart-card">
                <h3>Ventas del año actual</h3>
                <canvas id="graficaVentas"></canvas>
            </div>

            <div class="chart-card">
                <h3>Clientes por año</h3>
                <canvas id="graficaClientes"></canvas>
            </div>
        </div>
    </main>

    <!-- SCRIPTS -->
    <script>
        const clientesPorDepartamento = <?php echo json_encode($clientesPorDepartamento ?? []) ?>;
        const clientesPorEdad = <?php echo json_encode($clientesPorEdad ?? []) ?>;
        const ventasPorMes = <?php echo json_encode($ventasAnioActual ?? [], JSON_NUMERIC_CHECK) ?>;

        const meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
        ventasPorMes.forEach(v => { if(v.mes) v.mes = meses[v.mes - 1]; });

        // Clientes por departamento
        const $grafica = document.querySelector("#grafica");
        if($grafica && clientesPorDepartamento.length > 0) {
            new Chart($grafica, {
                type: 'pie',
                data: {
                    labels: clientesPorDepartamento.map(d => d.departamento),
                    datasets: [{
                        data: clientesPorDepartamento.map(d => d.conteo),
                        backgroundColor: ['rgba(220,38,38,0.8)','rgba(251,191,36,0.8)','rgba(124,45,18,0.8)','rgba(153,27,27,0.8)'],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
            });
        }

        // Clientes por edad
        const $graficaEdad = document.querySelector("#graficaEdad");
        if($graficaEdad && clientesPorEdad.length > 0) {
            new Chart($graficaEdad, {
                type: 'pie',
                data: {
                    labels: clientesPorEdad.map(d => d.etiqueta),
                    datasets: [{
                        data: clientesPorEdad.map(d => d.valor),
                        backgroundColor: ['rgba(220,38,38,0.8)','rgba(251,191,36,0.8)','rgba(124,45,18,0.8)','rgba(153,27,27,0.8)'],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
            });
        }

        // Ventas por mes
        const $graficaVentas = document.querySelector("#graficaVentas");
        if($graficaVentas && ventasPorMes.length > 0) {
            new Chart($graficaVentas, {
                type: 'bar',
                data: {
                    labels: ventasPorMes.map(d => d.mes),
                    datasets: [{
                        label: "Ventas por mes",
                        data: ventasPorMes.map(d => d.total),
                        backgroundColor: 'rgba(220,38,38,0.8)',
                        borderColor: 'rgba(220,38,38,1)',
                        borderWidth: 2
                    }]
                },
                options: { responsive: true, scales: { y: { beginAtZero: true } } }
            });
        }

        // Clientes por año
        const $graficaClientes = document.querySelector("#graficaClientes");
        if($graficaClientes) {
            new Chart($graficaClientes, {
                type: 'pie',
                data: {
                    labels: ["Año actual", "Otros años"],
                    datasets: [{
                        data: [<?php echo $totalClientesUltimoAnio ?? 0 ?>, <?php echo $totalClientesAniosAnteriores ?? 0 ?>],
                        backgroundColor: ['rgba(220,38,38,0.8)','rgba(124,45,18,0.8)'],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
            });
        }
    </script>


<!-- Tu HTML aquí -->

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const submenuToggle = document.querySelector('.submenu-toggle');
        const submenuItem = document.querySelector('.submenu-item');
        
        submenuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            submenuItem.classList.toggle('active');
        });
    });
    </script>

</body>
</html>
</body>
</html>
