<?php
session_start();
require_once "../config/db.php";

if (isset($_POST['login'])) {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE correo=?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        if (password_verify($password, $usuario['password'])) {
            // Guardar datos de sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];
            header("Location: ../dashboard.php"); // Redirige al dashboard
            exit();
        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "Usuario no encontrado";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DyV Electronic</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d0a0a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow: hidden;
        }

        /* Animated Background */
        .bg-decoration {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
            overflow: hidden;
        }

        .circle {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.15), rgba(185, 28, 28, 0.1));
            animation: float 20s infinite ease-in-out;
        }

        .circle1 {
            width: 300px;
            height: 300px;
            top: -100px;
            left: -100px;
        }

        .circle2 {
            width: 400px;
            height: 400px;
            bottom: -150px;
            right: -150px;
            animation-delay: -10s;
        }

        .circle3 {
            width: 200px;
            height: 200px;
            top: 50%;
            left: 10%;
            animation-delay: -5s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) scale(1);
            }
            50% {
                transform: translateY(-50px) scale(1.1);
            }
        }

        .container {
            display: flex;
            max-width: 1200px;
            width: 100%;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(220, 38, 38, 0.4);
            position: relative;
            z-index: 1;
        }

        /* Left Side - Showcase */
        .showcase {
            flex: 1;
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            padding: 50px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 650px;
        }

        .showcase-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .showcase-title {
            color: #fef3c7;
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 2px;
        }

        .showcase-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .logo-circle {
            width: 300px;
            height: 300px;
            animation: pulse 2s ease-in-out infinite;
        }

        .logo-main-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                
            }
            50% {
                transform: scale(1.05);
                
            }
        }

        .showcase-footer {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .creator-avatar {
            width: 50px;
            height: 50px;
            
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            
            
            overflow: hidden;
            padding: 5px;
        }

        .creator-avatar img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }

        .creator-info h4 {
            color: #fef3c7;
            font-size: 16px;
            margin-bottom: 3px;
        }

        .creator-info p {
            color: rgba(254, 243, 199, 0.7);
            font-size: 13px;
        }

        .showcase-nav-arrows {
            display: flex;
            gap: 10px;
            margin-left: auto;
        }

        .arrow-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(251, 191, 36, 0.2);
            border: 1px solid rgba(251, 191, 36, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fef3c7;
            cursor: pointer;
            transition: all 0.3s;
        }

        .arrow-btn:hover {
            background: rgba(251, 191, 36, 0.3);
            border-color: #fbbf24;
            transform: scale(1.1);
        }

        /* Right Side - Login Form */
        .login-section {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            font-size: 36px;
            font-weight: 700;
            color: #7c2d12;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }


        .logo-gold {
            color: #f59e0b;
        }

        .welcome-text h1 {
            font-size: 36px;
            color: #7c2d12;
            margin-bottom: 10px;
        }

        .welcome-text p {
            color: #78716c;
            font-size: 15px;
        }

        /* Error Message */
        .error-message {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #dc2626;
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            border-left: 4px solid #dc2626;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-form {
            margin-top: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #57534e;
            font-size: 14px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e7e5e4;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s;
            background: #fafaf9;
        }

        .form-control:focus {
            outline: none;
            border-color: #dc2626;
            background: white;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
        }

        .forgot-password {
            text-align: right;
            margin-top: 10px;
        }

        .forgot-password a {
            color: #dc2626;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: #fef3c7;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
            margin-top: 25px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .signup-text {
            text-align: center;
            margin-top: 25px;
            color: #78716c;
            font-size: 14px;
        }

        .signup-text a {
            color: #dc2626;
            text-decoration: none;
            font-weight: 600;
        }

        .signup-text a:hover {
            text-decoration: underline;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #fafaf9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #78716c;
            cursor: pointer;
            transition: all 0.3s;
        }

        .social-icon:hover {
            background: #dc2626;
            color: #fef3c7;
            transform: translateY(-3px);
        }

        @media (max-width: 968px) {
            .container {
                flex-direction: column;
            }

            .showcase {
                min-height: 400px;
            }

            .logo-container {
                max-width: 250px;
                height: 250px;
            }

            .logo-circle {
                width: 200px;
                height: 200px;
                padding: 20px;
            }

            .login-section {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="bg-decoration">
        <div class="circle circle1"></div>
        <div class="circle circle2"></div>
        <div class="circle circle3"></div>
    </div>

    <div class="container">
        <!-- Left Side - Showcase -->
        <div class="showcase">
            <div class="showcase-header">
                <div class="showcase-title"> CRM EMPRESARIAL</div>
            </div>

            <div class="showcase-content">
                <div class="logo-container">
                    <div class="logo-circle">
                        <img src="../img/logocrm.png" alt="Logo D&V Electronic" class="logo-main-image">
                    </div>
                </div>
            </div>

            <div class="showcase-footer">
                <div class="creator-avatar">
                    <img src="../img/logocrm.png" alt="D&V">
                </div>
                <div class="creator-info">
                    <h4>DyV Electronic</h4>
                    <p>Sistema de Gestión Empresarial</p>
                </div>
               
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-section">
            <div class="login-header">
                <div class="logo">
                    <span class="logo-red">D&V</span> 
                    <span class="logo-gold">Electronic</span>
                </div>
            </div>

            <div class="welcome-text">
                <h1>Hola, Bienvenido</h1>
                <p>Ingresa a tu cuenta del sistema CRM</p>
            </div>

            <?php if(isset($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $error; ?></span>
            </div>
            <?php endif; ?>

            <form class="login-form" method="POST">
                <div class="form-group">
                    <label for="correo">Correo Electrónico</label>
                    <input type="email" id="correo" name="correo" class="form-control" placeholder="correo@gmail.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>


                <button type="submit" name="login" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </button>


                <div class="social-icons">
                    
                    <div class="social-icon">
                     <a href="https://www.facebook.com/ventas.dyv.electronick/?locale=es_LA" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-facebook-f"></i>
                     </a>
                </div> 
                <div class="social-icon">
                    <a href="https://www.dyvelectronick.net/" target="_blank" rel="noopener noreferrer">
                      <i class="fas fa-globe"></i>
                     </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>