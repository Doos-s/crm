<?php
include_once "funciones.php";

if (!isset($_GET["busqueda"]) || empty($_GET["busqueda"])) {
    $clientes = obtenerClientes();
} else {
    $clientes = buscarClientes($_GET["busqueda"]);
}
?>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background-color: #fafaf9;
    display: flex;
    min-height: 100vh;
}

/* Sidebar */
.sidebar {
    width: 260px;
    background: linear-gradient(180deg, #7c2d12 0%, #991b1b 100%);
    color: white;
    padding: 30px 0;
    position: fixed;
    height: 100vh;
    overflow-y: auto;
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
    width: 45px;
    height: 45px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);
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

.sidebar-logo span {
    color: #fef3c7;
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

/* Main Content */
.main-content {
    margin-left: 260px;
    flex: 1;
    padding: 30px;
}

/* Header */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.header-left h1 {
    font-size: 32px;
    color: #7c2d12;
    margin-bottom: 5px;
}

.header-left p {
    color: #78716c;
    font-size: 14px;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 15px;
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

.user-profile {
    display: flex;
    align-items: center;
    gap: 10px;
    background-color: white;
    padding: 8px 15px;
    border-radius: 25px;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s;
}

.user-profile:hover {
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
}

.user-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fef3c7;
    font-weight: 600;
}

/* Stats Card */
.stats-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    gap: 20px;
    border-left: 4px solid #dc2626;
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    background: rgba(220, 38, 38, 0.1);
    color: #dc2626;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
}

.stats-info h3 {
    font-size: 32px;
    color: #7c2d12;
    margin-bottom: 5px;
}

.stats-info p {
    color: #78716c;
    font-size: 14px;
}

/* Action Bar */
.action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.btn {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: #fef3c7;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    border: none;
    cursor: pointer;
    font-size: 14px;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
}

.btn i {
    font-size: 16px;
}

/* Table Container */
.table-container {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    overflow-x: auto;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.table-header h2 {
    font-size: 20px;
    color: #7c2d12;
}

/* DataTable Customization */
#tablaClientes {
    width: 100% !important;
    border-collapse: collapse;
}

#tablaClientes thead th {
    background: linear-gradient(135deg, #7c2d12 0%, #991b1b 100%);
    color: #fef3c7;
    padding: 15px;
    text-align: left;
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
}

#tablaClientes thead th:first-child {
    border-radius: 8px 0 0 0;
}

#tablaClientes thead th:last-child {
    border-radius: 0 8px 0 0;
}

#tablaClientes tbody tr {
    border-bottom: 1px solid #e7e5e4;
    transition: all 0.3s;
}

#tablaClientes tbody tr:hover {
    background-color: #fef3c7;
    transform: scale(1.01);
}

#tablaClientes tbody td {
    padding: 15px;
    color: #57534e;
    font-size: 14px;
}

#tablaClientes tbody td:first-child {
    font-weight: 600;
    color: #7c2d12;
}

/* Action Buttons in Table */
.btn-small {
    padding: 6px 12px;
    font-size: 12px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s;
    font-weight: 500;
    margin-right: 5px;
}

.btn-edit {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: #7c2d12;
    box-shadow: 0 2px 6px rgba(251, 191, 36, 0.3);
}

.btn-edit:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
}

.btn-delete {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: #fef3c7;
    box-shadow: 0 2px 6px rgba(220, 38, 38, 0.3);
}

.btn-delete:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
}

/* DataTables Custom Styling */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    margin-bottom: 20px;
}

.dataTables_wrapper .dataTables_length select {
    padding: 8px 12px;
    border: 2px solid #e7e5e4;
    border-radius: 8px;
    margin: 0 5px;
    font-size: 14px;
}

.dataTables_wrapper .dataTables_filter input {
    padding: 8px 12px;
    border: 2px solid #e7e5e4;
    border-radius: 8px;
    margin-left: 5px;
    font-size: 14px;
}

.dataTables_wrapper .dataTables_filter input:focus {
    outline: none;
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 6px 12px;
    margin: 0 3px;
    border-radius: 6px;
    border: none;
    background: white;
    color: #57534e;
    transition: all 0.3s;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #dc2626;
    color: white;
    border: none;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: #fef3c7;
    border: none;
}

.dataTables_wrapper .dataTables_info {
    color: #78716c;
    font-size: 14px;
    padding-top: 15px;
}

@media (max-width: 768px) {
    .sidebar {
        width: 80px;
    }
    
    .sidebar-logo h2,
    .sidebar-menu a span {
        display: none;
    }
    
    .main-content {
        margin-left: 80px;
    }

    .header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .stats-card {
        flex-direction: column;
        text-align: center;
    }
}
</style>
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">
            <img src="./img/logo.png" alt="Logo" onerror="this.style.display='none'">
        </div>
        <h2>Clientes</h2>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="dashboard.php">
                <i class="fas fa-home"></i> 
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="clientes.php" class="active">
                <i class="fas fa-users"></i> 
                <span>Clientes</span>
            </a>
        </li>
        <li>
            <a href="ventas.php">
                <i class="fas fa-shopping-cart"></i> 
                <span>Ventas</span>
            </a>
        </li>
        <li>
            <a href="reportes.php">
                <i class="fas fa-chart-bar"></i> 
                <span>Reportes</span>
            </a>
        </li>
        <li>
            <a href="configuracion.php">
                <i class="fas fa-cog"></i> 
                <span>Configuración</span>
            </a>
        </li>
    </ul>
</aside>

<div class="main-content">
    <div class="header">
        <div class="header-left">
            <h1><i class="fas fa-users"></i> Clientes</h1>
            <p>Gestión general de clientes registrados en el sistema</p>
        </div>
        <div class="header-right">
            <div class="user-profile">
                <div class="user-avatar">M</div>
                <span>Administrador</span>
            </div>
        </div>
    </div>

    <div class="action-bar">
        <a href="formulario_agregar_cliente.php" class="btn">
            <i class="fas fa-user-plus"></i> Agregar Cliente
        </a>
        <form action="clientes.php" method="get" class="d-flex" style="gap:10px;">
            <input type="text" name="busqueda" 
                value="<?php echo isset($_GET["busqueda"]) ? htmlspecialchars($_GET["busqueda"]) : ""; ?>" 
                placeholder="Buscar cliente por nombre..." 
                class="form-control" style="border:2px solid #e7e5e4; border-radius:8px; padding:8px 12px;">
            <button type="submit" class="btn">
                <i class="fas fa-search"></i> Buscar
            </button>
        </form>
    </div>

    <div class="table-container">
        <div class="table-header">
            <h2><i class="fas fa-list"></i> Lista de Clientes</h2>
        </div>

        <table id="tablaClientes" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Departamento</th>
                    <th>Edad</th>
                    <th>Fecha de Registro</th>
                    <th>Dashboard</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente) { ?>
                    <tr>
                        <td><?php echo $cliente->id; ?></td>
                        <td><?php echo htmlspecialchars($cliente->nombre); ?></td>
                        <td><?php echo htmlspecialchars($cliente->departamento); ?></td>
                        <td><?php echo htmlspecialchars($cliente->edad); ?></td>
                        <td><?php echo htmlspecialchars($cliente->fecha_registro); ?></td>
                        <td>
                            <a class="btn-small btn-edit" href="dashboard_cliente.php?id=<?php echo $cliente->id; ?>">
                                <i class="fas fa-chart-line"></i> Ver
                            </a>
                        </td>
                        <td>
                            <a href="formulario_editar_cliente.php?id=<?php echo $cliente->id; ?>" class="btn-small btn-edit">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="eliminar_cliente.php?id=<?php echo $cliente->id; ?>" 
                               class="btn-small btn-delete" 
                               onclick="return confirm('¿Estás seguro de eliminar este cliente?');">
                                <i class="fas fa-trash"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once "pie.php"; ?>

<!-- Inicialización de DataTables -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#tablaClientes').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        pageLength: 10,
        responsive: true
    });
});
</script>
