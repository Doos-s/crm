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
            background: write;
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

        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
            padding: 15px;
            background: #dc2626;
            border-radius: 8px;
            color: white;
        }

        .section-header i {
            font-size: 24px;
        }

        .section-header .section-info h3 {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }

        .section-header .section-info p {
            font-size: 13px;
            margin: 0;
            opacity: 0.9;
        }

        .alert-info {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 25px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .alert-info i {
            color: #ffc107;
            font-size: 20px;
            margin-top: 2px;
        }

        .alert-info-content strong {
            color: #856404;
            display: block;
            margin-bottom: 3px;
        }

        .alert-info-content p {
            color: #856404;
            margin: 0;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group label .required {
            color: #dc2626;
            margin-left: 3px;
        }

        .form-group .help-text {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
            font-weight: normal;
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
            color: #667eea;
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

        @media (max-width: 768px) {
            .container {
                padding: 25px;
            }

            .form-row {
                grid-template-columns: 1fr;
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
            <i class="fas fa-user-plus"></i>
            <h2>Crear Nuevo Usuario</h2>
            <p>Complete los datos del usuario</p>
        </div>

        <div id="alertMessage" class="alert"></div>

        <form id="formUsuario" method="POST" action="procesar_usuario.php">
            
            <!-- ID Usuario (oculto, se genera automáticamente) -->
            <input type="hidden" name="id_usuario" id="id_usuario">

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
                    <option value="administrador">Administrador</option>
                    <option value="editor">Editor</option>
                    <option value="usuario">Usuario</option>
                    <option value="invitado">Invitado</option>
                </select>
            </div>

            <!-- Fecha de Registro (oculto, se genera automáticamente) -->
            <input type="hidden" name="fecha_registro" id="fecha_registro">

            <!-- Botones -->
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Crear Usuario
                </button>
                <button type="button" class="btn btn-secondary" onclick="limpiarFormulario()">
                    <i class="fas fa-times"></i>
                    Cancelar
                </button>
            </div>
        </form>
    </div>

    <script>
        // Establecer fecha de registro automática al cargar
        document.addEventListener('DOMContentLoaded', function() {
            const fechaActual = new Date().toISOString().slice(0, 19).replace('T', ' ');
            document.getElementById('fecha_registro').value = fechaActual;
        });

        // Toggle para mostrar/ocultar contraseña
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Validación del formulario
        document.getElementById('formUsuario').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const nombreUsuario = document.getElementById('nombre_usuario').value.trim();
            const correo = document.getElementById('correo').value.trim();
            const password = document.getElementById('password').value;
            const rol = document.getElementById('rol').value;
            
            // Validaciones
            if (nombreUsuario.length < 3) {
                mostrarAlerta('El nombre de usuario debe tener al menos 3 caracteres', 'error');
                return;
            }
            
            if (password.length < 6) {
                mostrarAlerta('La contraseña debe tener al menos 6 caracteres', 'error');
                return;
            }
            
            if (!rol) {
                mostrarAlerta('Debe seleccionar un rol', 'error');
                return;
            }
            
            // Si todo está correcto, enviar el formulario
            mostrarAlerta('Usuario creado exitosamente', 'success');
            
            // Descomentar la siguiente línea para enviar realmente el formulario
            // this.submit();
            
            // Limpiar después de 2 segundos (solo para demo)
            setTimeout(() => {
                limpiarFormulario();
            }, 2000);
        });

        function mostrarAlerta(mensaje, tipo) {
            const alerta = document.getElementById('alertMessage');
            alerta.textContent = mensaje;
            alerta.className = `alert ${tipo}`;
            alerta.style.display = 'block';
            
            setTimeout(() => {
                alerta.style.display = 'none';
            }, 3000);
        }

        function limpiarFormulario() {
            document.getElementById('formUsuario').reset();
            const fechaActual = new Date().toISOString().slice(0, 19).replace('T', ' ');
            document.getElementById('fecha_registro').value = fechaActual;
            document.getElementById('alertMessage').style.display = 'none';
        }
    </script>
</body>
</html>