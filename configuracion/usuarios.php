<?php


session_start();

require_once '../config/db.php';

$mensaje = '';
$tipo_mensaje = '';

// Procesar eliminación de usuario
if (isset($_GET['eliminar'])) {
    $id_usuario = intval($_GET['eliminar']);
    
    $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    
    if ($stmt->execute()) {
        $mensaje = "Usuario eliminado exitosamente";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al eliminar el usuario";
        $tipo_mensaje = "error";
    }
    $stmt->close();
}

// Obtener todos los usuarios
$query = "SELECT id_usuario, nombre_usuario, correo, rol, fecha_registro FROM usuarios ORDER BY fecha_registro DESC";
$resultado = $conexion->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            padding: 30px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-title h1 {
            font-size: 28px;
            font-weight: 600;
        }

        .header-title i {
            font-size: 32px;
        }

        .header-buttons {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn-nuevo, .btn-volver {
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-nuevo {
            background: white;
            color: #dc2626;
        }

        .btn-nuevo:hover {
            background: #f9f9f9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .btn-volver {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
        }

        .btn-volver:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .content {
            padding: 40px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #dc2626;
        }

        .stat-card h3 {
            color: #666;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .stat-card p {
            color: #333;
            font-size: 28px;
            font-weight: 700;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 10px;
            border: 1px solid #e0e0e0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8f9fa;
        }

        thead th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e0e0e0;
        }

        tbody tr {
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.2s ease;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        tbody td {
            padding: 15px;
            color: #555;
            font-size: 14px;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .badge.administrador {
            background: #fef3c7;
            color: #92400e;
        }

        .badge.gerente {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge.agente {
            background: #d1fae5;
            color: #065f46;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
        }

        .btn-edit {
            background: #3b82f6;
            color: white;
        }

        .btn-edit:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        .btn-delete {
            background: #ef4444;
            color: white;
        }

        .btn-delete:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }

        .no-users {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .no-users i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .no-users p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        /* Modal de confirmación */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            max-width: 400px;
            width: 90%;
            text-align: center;
        }

        .modal-content i {
            font-size: 48px;
            color: #ef4444;
            margin-bottom: 20px;
        }

        .modal-content h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .modal-content p {
            color: #666;
            margin-bottom: 25px;
        }

        .modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .modal-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-confirm {
            background: #ef4444;
            color: white;
        }

        .btn-confirm:hover {
            background: #dc2626;
        }

        .btn-cancel {
            background: #e5e7eb;
            color: #666;
        }

        .btn-cancel:hover {
            background: #d1d5db;
        }

        @media (max-width: 768px) {
            .header {
                padding: 20px;
            }

            .header-title h1 {
                font-size: 22px;
            }

            .content {
                padding: 20px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .table-container {
                border: none;
            }

            table {
                font-size: 12px;
            }

            thead th, tbody td {
                padding: 10px 8px;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-title">
                <i class="fas fa-users"></i>
                <h1>Gestión de Usuarios</h1>
            </div>
            <div class="header-buttons">
                <a href="../dashboard.php" class="btn-volver">
                    <i class="fas fa-arrow-left"></i>
                    Volver
                </a>
                <a href="../configuracion/nuevo_usuario.php" class="btn-nuevo">
                    <i class="fas fa-user-plus"></i>
                    Nuevo Usuario
                </a>
            </div>
        </div>

        <div class="content">
            <?php if (!empty($mensaje)): ?>
                <div class="alert <?php echo $tipo_mensaje; ?>">
                    <i class="fas <?php echo $tipo_mensaje === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <div class="stats">
                <div class="stat-card">
                    <h3>Total de Usuarios</h3>
                    <p><?php echo $resultado->num_rows; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Administradores</h3>
                    <p><?php 
                        $admin_query = $conexion->query("SELECT COUNT(*) as total FROM usuarios WHERE rol = 'administrador'");
                        echo $admin_query->fetch_assoc()['total'];
                    ?></p>
                </div>
                <div class="stat-card">
                    <h3>Gerentes</h3>
                    <p><?php 
                        $gerente_query = $conexion->query("SELECT COUNT(*) as total FROM usuarios WHERE rol = 'gerente'");
                        echo $gerente_query->fetch_assoc()['total'];
                    ?></p>
                </div>
                <div class="stat-card">
                    <h3>Agentes</h3>
                    <p><?php 
                        $agente_query = $conexion->query("SELECT COUNT(*) as total FROM usuarios WHERE rol = 'agente'");
                        echo $agente_query->fetch_assoc()['total'];
                    ?></p>
                </div>
            </div>

            <?php if ($resultado->num_rows > 0): ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Fecha Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($usuario = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $usuario['id_usuario']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></strong>
                                    </td>
                                    <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $usuario['rol']; ?>">
                                            <?php echo htmlspecialchars($usuario['rol']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="editar_usuario.php?id=<?php echo $usuario['id_usuario']; ?>" class="btn-action btn-edit">
                                                <i class="fas fa-edit"></i>
                                                Editar
                                            </a>
                                            <button class="btn-action btn-delete" onclick="confirmarEliminacion(<?php echo $usuario['id_usuario']; ?>, '<?php echo htmlspecialchars($usuario['nombre_usuario']); ?>')">
                                                <i class="fas fa-trash"></i>
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-users">
                    <i class="fas fa-users-slash"></i>
                    <p>No hay usuarios registrados</p>
                    <a href="crear_usuario.php" class="btn-nuevo">
                        <i class="fas fa-user-plus"></i>
                        Crear Primer Usuario
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal" id="modalConfirmacion">
        <div class="modal-content">
            <i class="fas fa-exclamation-triangle"></i>
            <h3>¿Confirmar eliminación?</h3>
            <p>¿Está seguro que desea eliminar al usuario <strong id="nombreUsuario"></strong>?</p>
            <div class="modal-buttons">
                <button class="btn-confirm" id="btnConfirmar">Eliminar</button>
                <button class="btn-cancel" onclick="cerrarModal()">Cancelar</button>
            </div>
        </div>
    </div>

    <script>
        let idUsuarioEliminar = null;

        function confirmarEliminacion(id, nombre) {
            idUsuarioEliminar = id;
            document.getElementById('nombreUsuario').textContent = nombre;
            document.getElementById('modalConfirmacion').classList.add('active');
        }

        function cerrarModal() {
            document.getElementById('modalConfirmacion').classList.remove('active');
            idUsuarioEliminar = null;
        }

        document.getElementById('btnConfirmar').addEventListener('click', function() {
            if (idUsuarioEliminar) {
                window.location.href = 'usuarios.php?eliminar=' + idUsuarioEliminar;
            }
        });

        // Cerrar modal al hacer clic fuera
        document.getElementById('modalConfirmacion').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModal();
            }
        });

        // Auto-ocultar mensajes de éxito después de 3 segundos
        const alertSuccess = document.querySelector('.alert.success');
        if (alertSuccess) {
            setTimeout(() => {
                alertSuccess.style.opacity = '0';
                alertSuccess.style.transition = 'opacity 0.5s';
                setTimeout(() => {
                    alertSuccess.style.display = 'none';
                }, 500);
            }, 3000);
        }
    </script>
</body>
</html>