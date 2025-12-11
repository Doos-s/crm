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
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            padding: 30px 40px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        label {
            font-weight: 600;
            color: #444;
            margin-bottom: 5px;
            display: block;
        }

        input, select {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
            margin-bottom: 18px;
            transition: all 0.3s ease;
        }

        input:focus, select:focus {
            border-color: #7c2d12;
            outline: none;
            box-shadow: 0 0 4px rgba(124, 45, 18, 0.4);
        }

        .acciones {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 25px;
        }

        button {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        button:hover {
            background: linear-gradient(135deg, #b91c1c 0%, #7c2d12 100%);
            transform: scale(1.03);
        }

        .btn-volver {
            background-color: #6c757d;
            color: #fff;
            padding: 10px 25px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-volver:hover {
            background-color: #5a6268;
            transform: scale(1.03);
        }

        @media (max-width: 768px) {
            .sidebar { width: 80px; }
            .sidebar-logo h2, .sidebar-menu a span { display: none; }
            .main-content { margin-left: 80px; padding: 20px; }
            .formulario { padding: 20px; }
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
        <h1>Editar Cliente</h1>
        <form class="formulario" action="actualizar_cliente.php" method="post">
            <input type="hidden" name="id" value="<?php echo $cliente->id; ?>">

            <label for="nombre">Nombre</label>
            <input required type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($cliente->nombre); ?>">

            <label for="edad">Edad</label>
            <input required type="number" name="edad" id="edad" value="<?php echo htmlspecialchars($cliente->edad); ?>">

            <label for="departamento">Departamento</label>
            <select name="departamento" id="departamento">
                <?php foreach ($departamentos as $departamento): ?>
                    <option value="<?php echo $departamento; ?>" <?php if($cliente->departamento == $departamento) echo "selected"; ?>>
                        <?php echo $departamento; ?>
                    </option
                <?php endforeach; ?>
            </select>

            <div class="acciones">
                <a href="clientes.php" class="btn-volver">← Volver</a>
                <button type="submit">Actualizar Cliente</button>
            </div>
        </form>
    </main>
</body>
</html>
