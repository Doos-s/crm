<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Cliente</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .breadcrumb {
            color: #999;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .breadcrumb a {
            color: #d32f2f;
            text-decoration: none;
        }

        .section-header {
            background-color: #f8f9fa;
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-header-icon {
            width: 40px;
            height: 40px;
            background-color: #d32f2f;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .section-header-text h2 {
            color: #333;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .section-header-text p {
            color: #666;
            font-size: 13px;
        }

        .alert-info {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px 15px;
            margin-bottom: 25px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-info::before {
            content: "⚠";
            color: #ffc107;
            font-size: 20px;
        }

        .alert-info p {
            color: #856404;
            font-size: 13px;
            margin: 0;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-group label::after {
            content: " *";
            color: #d32f2f;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: white;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #d32f2f;
            box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.1);
        }

        .form-group input::placeholder {
            color: #999;
        }

        .form-group small {
            display: block;
            color: #666;
            font-size: 12px;
            margin-top: 5px;
        }

        .icon-input {
            position: relative;
        }

        .icon-input::before {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 16px;
        }

        .icon-input input {
            padding-left: 40px;
        }

        .acciones {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .btn-volver {
            padding: 12px 30px;
            background-color: #f5f5f5;
            color: #333;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-volver:hover {
            background-color: #e0e0e0;
            border-color: #ccc;
        }

        button[type="submit"] {
            padding: 12px 35px;
            background-color: #d32f2f;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        button[type="submit"]:hover {
            background-color: #b71c1c;
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.3);
        }

        button[type="submit"]::before {
            content: "";
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .acciones {
                flex-direction: column;
            }

            .btn-volver,
            button[type="submit"] {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

    <div class="container">
    

        <h1>Agregar Nuevo Cliente</h1>
        <p class="subtitle">Complete el formulario para registrar un nuevo cliente</p>

        <div class="section-header">
            <div class="section-header-icon">👤</div>
            <div class="section-header-text">
                <h2>Información del Cliente</h2>
                <p>Los campos marcados con * son obligatorios para el registro</p>
            </div>
        </div>

        <div class="alert-info">
            <p><strong>Importante:</strong> Asegúrate de ingresar correctamente la información del cliente. Los datos de nombre y apellido son obligatorios para el registro.</p>
        </div>

        <form action="guardar_cliente.php" method="post">
            <div class="form-row">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input required type="text" name="nombre" id="nombre" placeholder="Ej: Juan">
                    <small>Ingresa el nombre del cliente</small>
                </div>

                <div class="form-group">
                    <label for="edad">Edad</label>
                    <input required type="number" name="edad" id="edad" placeholder="Ej: 25">
                    <small>Ingresa la edad del cliente</small>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input required type="text" name="telefono" id="telefono" placeholder="Ej: +51 999 999 999">
                    <small>Formato: +51 999 999 999</small>
                </div>

                <div class="form-group">
                    <label for="correo">Correo Electrónico</label>
                    <input required type="email" name="correo" id="correo" placeholder="Ej: cliente@ejemplo.com">
                    <small>Correo válido para contacto</small>
                </div>
            </div>

            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input required type="text" name="direccion" id="direccion" placeholder="Ej: Av. Principal 123, Lima, Perú">
                <small>Dirección completa del cliente</small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="departamento">Departamento</label>
                    <select name="departamento" id="departamento">
                        <option value="">Seleccione un departamento</option>
                        <option value="Lima">Lima</option>
                        <option value="Arequipa">Arequipa</option>
                        <option value="Cusco">Cusco</option>
                        <option value="Trujillo">Trujillo</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha_registro">Fecha de Registro</label>
                    <input required type="date" name="fecha_registro" id="fecha_registro">
                </div>
            </div>

            <div class="acciones">
                <a href="clientes.php" class="btn-volver">✕ Cancelar</a>
                <button type="submit">Guardar Cliente</button>
            </div>
        </form>
    </div>

</body>
</html>