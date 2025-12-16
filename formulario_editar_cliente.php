<?php
include_once "funciones.php";
$cliente = obtenerClientePorId($_GET["id"]);
$departamentos = obtenerDepartamentos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente - <?php echo $cliente->nombre; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
            padding: 40px;
            background: linear-gradient(135deg, #fafaf9 0%, #f5f5f4 100%);
        }

        h1 {
            font-size: 28px;
            color: #7c2d12;
            font-weight: 700;
            margin-bottom: 25px;
            text-align: center;
        }

        .formulario {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            padding: 30px 40px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group-full {
            grid-column: 1 / -1;
        }

        label {
            font-weight: 600;
            color: #444;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        label i {
            color: #dc2626;
            font-size: 14px;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #7c2d12;
            outline: none;
            box-shadow: 0 0 4px rgba(124, 45, 18, 0.4);
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .acciones {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        button {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        button:hover {
            background: linear-gradient(135deg, #b91c1c 0%, #7c2d12 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        .btn-volver {
            background: #6c757d;
            color: #fff;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }

        .btn-volver:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }

        @media (max-width: 768px) {
            .sidebar { width: 80px; }
            .sidebar-logo h2, .sidebar-menu a span { display: none; }
            .main-content { margin-left: 80px; padding: 20px; }
            .formulario { padding: 20px; }
            .form-row { grid-template-columns: 1fr; }
            .acciones { flex-direction: column-reverse; gap: 10px; }
            .acciones button, .acciones a { width: 100%; justify-content: center; }
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
            <h2>CRM</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fas fa-home"></i> <span>Inicio</span></a></li>
            <li><a href="clientes.php" class="active"><i class="fas fa-users"></i> <span>Clientes</span></a></li>
            <li><a href="ventas.php"><i class="fas fa-shopping-cart"></i> <span>Ventas</span></a></li>
        </ul>
    </aside>

    <!-- MAIN -->
    <main class="main-content">
        <h1><i class="fas fa-user-edit"></i> Editar Cliente</h1>
        <form class="formulario" action="actualizar_cliente.php" method="post">
            <input type="hidden" name="id" value="<?php echo $cliente->id; ?>">

            <div class="form-row">
                <div class="form-group">
                    <label for="nombre">
                        <i class="fas fa-user"></i>
                        Nombre Completo
                    </label>
                    <input required type="text" name="nombre" id="nombre" 
                           value="<?php echo htmlspecialchars($cliente->nombre); ?>" 
                           placeholder="Ingrese el nombre completo">
                </div>

                <div class="form-group">
                    <label for="edad">
                        <i class="fas fa-birthday-cake"></i>
                        Edad
                    </label>
                    <input required type="number" name="edad" id="edad" 
                           value="<?php echo htmlspecialchars($cliente->edad); ?>" 
                           placeholder="Edad del cliente" min="1" max="120">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="telefono">
                        <i class="fas fa-phone"></i>
                        Teléfono
                    </label>
                   <input required type="tel" name="telefono" id="telefono" 
       value="<?php echo htmlspecialchars($cliente->telefono ?? ''); ?>" 
       placeholder="Ej: 987654321">

                </div>

                <div class="form-group">
                    <label for="correo">
                        <i class="fas fa-envelope"></i>
                        Correo Electrónico
                    </label>
                    <input required type="email" name="correo" id="correo" 
       value="<?php echo htmlspecialchars($cliente->correo ?? ''); ?>" 
       placeholder="correo@ejemplo.com">

                </div>
            </div>

            <div class="form-row">
                <div class="form-group form-group-full">
                    <label for="direccion">
                        <i class="fas fa-map-marker-alt"></i>
                        Dirección
                    </label>
                   <textarea name="direccion" id="direccion" 
          placeholder="Ingrese la dirección completa"><?php 
    echo htmlspecialchars($cliente->direccion ?? ''); 
?></textarea>

                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="departamento">
                        <i class="fas fa-map"></i>
                        Departamento
                    </label>
                    <select required name="departamento" id="departamento">
                        <option value="">Seleccione un departamento</option>
                        <?php foreach ($departamentos as $departamento): ?>
                            <option value="<?php echo $departamento; ?>" 
                                    <?php if($cliente->departamento == $departamento) echo "selected"; ?>>
                                <?php echo $departamento; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha_registro">
                        <i class="fas fa-calendar"></i>
                        Fecha de Registro
                    </label>
                    <input type="date" name="fecha_registro" id="fecha_registro" 
                           value="<?php echo htmlspecialchars($cliente->fecha_registro); ?>" readonly>
                </div>
            </div>

            <div class="acciones">
                <a href="clientes.php" class="btn-volver">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <button type="submit">
                    <i class="fas fa-save"></i> Actualizar Cliente
                </button>
            </div>
        </form>
    </main>
</body>
</html>