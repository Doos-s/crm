<?php
// Iniciar sesión y verificar autenticación
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar si es administrador
if ($_SESSION['rol'] !== 'administrador') {
    header("Location: dashboard.php");
    exit();
}

// Incluir conexión a la base de datos
require_once 'config/db.php.php';

$mensaje = '';
$tipo_mensaje = '';

// Procesar formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = trim($_POST['nombre_usuario']);
    $correo = trim($_POST['correo']);
    $password = $_POST['password'];
    $rol = $_POST['rol'];
    
    // Validaciones del lado del servidor
    $errores = [];
    
    if (strlen($nombre_usuario) < 3) {
        $errores[] = "El nombre de usuario debe tener al menos 3 caracteres";
    }
    
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo electrónico no es válido";
    }
    
    if (strlen($password) < 6) {
        $errores[] = "La contraseña debe tener al menos 6 caracteres";
    }
    
    if (empty($rol)) {
        $errores[] = "Debe seleccionar un rol";
    }
    
    // Verificar si el nombre de usuario ya existe
    if (empty($errores)) {
        $stmt = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE nombre_usuario = ?");
        $stmt->bind_param("s", $nombre_usuario);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errores[] = "El nombre de usuario ya está en uso";
        }
        $stmt->close();
    }
    
    // Verificar si el correo ya existe
    if (empty($errores)) {
        $stmt = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errores[] = "El correo electrónico ya está registrado";
        }
        $stmt->close();
    }
    
    // Si no hay errores, insertar el usuario
    if (empty($errores)) {
        // Encriptar la contraseña
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Preparar la consulta
        $stmt = $conexion->prepare("INSERT INTO usuarios (nombre_usuario, correo, password, rol, fecha_registro) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $nombre_usuario, $correo, $password_hash, $rol);
        
        if ($stmt->execute()) {
            $mensaje = "Usuario creado exitosamente";
            $tipo_mensaje = "success";
            
            // Limpiar variables después de éxito
            $nombre_usuario = '';
            $correo = '';
            $password = '';
            $rol = '';
        } else {
            $mensaje = "Error al crear el usuario: " . $stmt->error;
            $tipo_mensaje = "error";
        }
        
        $stmt->close();
    } else {
        // Mostrar errores
        $mensaje = implode("<br>", $errores);
        $tipo_mensaje = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Usuario</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 500px;
            width: 100%;
        }

        .form-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .form-header h2 {
            color: #333;
            font-size: 28px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .form-header p {
            color: #666;
            font-size: 14px;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            display: block;
        }

        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            display: block;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group label i {
            margin-right: 5px;
            color: #dc2626;
        }

        .input-wrapper {
            position: relative;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
            outline: none;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .form-group select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 35px;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            transition: color 0.3s;
        }

        .password-toggle:hover {
            color: #dc2626;
        }

        .info-text {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
            display: flex;
            align-items: center;
        }

        .info-text i {
            margin-right: 5px;
            font-size: 10px;
        }

        .btn-group {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #f0f0f0;
        }

        .btn {
            padding: 12px 28px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: #dc2626;
            color: white;
        }

        .btn-primary:hover {
            background: #b91c1c;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        .btn-secondary {
            background: white;
            color: #666;
            border: 1px solid #ddd;
        }

        .btn-secondary:hover {
            background: #f9f9f9;
            border-color: #bbb;
        }

        @media (max-width: 768px) {
            .container {
                padding: 25px;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-header">
            <h2><i class="fas fa-user-plus"></i> Crear Nuevo Usuario</h2>
            <p>Complete los datos del usuario</p>
        </div>

        <?php if (!empty($mensaje)): ?>
            <div class="alert <?php echo $tipo_mensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            
            <!-- Nombre de Usuario -->
            <div class="form-group">
                <label for="nombre_usuario">
                    <i class="fas fa-user"></i>
                    Nombre de Usuario
                </label>
                <input 
                    type="text" 
                    id="nombre_usuario" 
                    name="nombre_usuario" 
                    placeholder="Ingrese el nombre de usuario"
                    required
                    maxlength="50"
                    value="<?php echo isset($nombre_usuario) ? htmlspecialchars($nombre_usuario) : ''; ?>"
                >
                <div class="info-text">
                    <i class="fas fa-info-circle"></i>
                    Mínimo 3 caracteres
                </div>
            </div>

            <!-- Correo Electrónico -->
            <div class="form-group">
                <label for="correo">
                    <i class="fas fa-envelope"></i>
                    Correo Electrónico
                </label>
                <input 
                    type="email" 
                    id="correo" 
                    name="correo" 
                    placeholder="usuario@ejemplo.com"
                    required
                    value="<?php echo isset($correo) ? htmlspecialchars($correo) : ''; ?>"
                >
            </div>

            <!-- Contraseña -->
            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i>
                    Contraseña
                </label>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Ingrese una contraseña segura"
                        required
                        minlength="6"
                    >
                    <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                </div>
                <div class="info-text">
                    <i class="fas fa-info-circle"></i>
                    Mínimo 6 caracteres
                </div>
            </div>

            <!-- Rol -->
            <div class="form-group">
                <label for="rol">
                    <i class="fas fa-user-tag"></i>
                    Rol del Usuario
                </label>
                <select id="rol" name="rol" required>
                    <option value="">Seleccione un rol</option>
                    <option value="administrador" <?php echo (isset($rol) && $rol === 'administrador') ? 'selected' : ''; ?>>Administrador</option>
                    <option value="gerente" <?php echo (isset($rol) && $rol === 'gerente') ? 'selected' : ''; ?>>Gerente</option>
                    <option value="agente" <?php echo (isset($rol) && $rol === 'agente') ? 'selected' : ''; ?>>Agente de ventas</option>
                </select>
            </div>

            <!-- Botones -->
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Crear Usuario
                </button>
                <a href="gestionar_usuarios.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <script>
        // Toggle para mostrar/ocultar contraseña
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }

        // Auto-ocultar mensajes de éxito después de 3 segundos
        const alertSuccess = document.querySelector('.alert.success');
        if (alertSuccess) {
            setTimeout(() => {
                alertSuccess.style.display = 'none';
            }, 3000);
        }
    </script>
</body>
</html>