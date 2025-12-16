<?php
session_start();
include_once "funciones.php";
$clientes = obtenerClientes();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Venta</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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

        /* Submenú */
        .submenu-item {
            position: relative;
        }

        .submenu-toggle .arrow {
            float: right;
            transition: transform 0.3s ease;
            font-size: 0.8em;
            margin-left: 10px;
        }

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

        .submenu-item.active .submenu {
            display: block;
        }

        .submenu-item.active .arrow {
            transform: rotate(180deg);
        }

        .logout-btn {
            position: absolute;
            bottom: 20px;
            left: 15px;
            right: 15px;
            background-color: rgba(0, 0, 0, 0.2);
            padding: 12px 15px;
            border-radius: 8px;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logout-btn:hover {
            background-color: rgba(0, 0, 0, 0.4);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
            background: linear-gradient(135deg, #fafaf9 0%, #f5f5f4 100%);
        }

        /* ===== HEADER ===== */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-header h1 {
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

        /* ===== FORM CARD ===== */
        .form-card {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            max-width: 600px;
            margin: 0 auto;
            border-top: 4px solid #dc2626;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #7c2d12;
            font-weight: 600;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e7e5e4;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
            background-color: #fafaf9;
        }

        .form-control:focus {
            outline: none;
            border-color: #dc2626;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .btn {
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-success {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        @media (max-width: 768px) {
            .sidebar { width: 80px; }
            .sidebar-logo h2, .sidebar-menu a span { display: none; }
            .main-content { margin-left: 80px; }
            .form-card { padding: 25px; }
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
            <h2>Ventas</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="clientes.php"><i class="fas fa-users"></i> <span>Clientes</span></a></li>
            <li><a href="ventas.php" class="active"><i class="fas fa-shopping-cart"></i> <span>Ventas</span></a></li>
            <li><a href="reportes.php"><i class="fas fa-chart-bar"></i> <span>Reportes</span></a></li>
            <li class="submenu-item">
                <a href="#" class="submenu-toggle"><i class="fas fa-cog"></i> <span>Configuración</span><i class="fas fa-chevron-down arrow"></i></a>
                <ul class="submenu">
                    <li><a href="configuracion/usuarios.php"><i class="fas fa-users"></i> Gestionar Usuarios</a></li>
                    <li><a href="configuracion/nuevo_usuarios.php"><i class="fas fa-user-plus"></i> Nuevo Usuario</a></li>                                   
                </ul>
            </li>
            <a href="login/logout.php" class="logout-btn" onclick="return confirm('¿Estás seguro de que deseas cerrar sesión?');">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar Sesión</span>
            </a>
        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-shopping-cart"></i> Registrar Venta</h1>
            <div class="header-actions">
                <div class="header-icon"><i class="fas fa-bell"></i></div>
                <div class="header-icon"><i class="fas fa-user"></i></div>
            </div>
        </div>

        <div class="form-card">
            <form action="guardar_venta.php" method="post">
                <div class="form-group">
                    <label for="id_cliente"><i class="fas fa-user"></i> Cliente</label>
                    <select required name="id_cliente" id="id_cliente" class="form-control">
                        <option value="">Seleccione un cliente</option>
                        <?php foreach ($clientes as $cliente) { ?>
                            <option value="<?php echo $cliente->id ?>"><?php echo $cliente->nombre ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="monto"><i class="fas fa-dollar-sign"></i> Monto</label>
                    <input required type="number" step="0.01" class="form-control" placeholder="Ingrese el monto" name="monto" id="monto">
                </div>

                <div class="form-group">
                    <label for="fecha"><i class="fas fa-calendar"></i> Fecha</label>
                    <input required type="date" value="<?php echo date("Y-m-d") ?>" class="form-control" name="fecha" id="fecha">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar Venta
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const submenuToggle = document.querySelector('.submenu-toggle');
            const submenuItem = document.querySelector('.submenu-item');
            
            if(submenuToggle) {
                submenuToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    submenuItem.classList.toggle('active');
                });
            }
        });
    </script>
</body>
</html>