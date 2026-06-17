<?php
session_start();
require_once '../db_config.php';
require_once 'functions.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (!empty($email) && !empty($password)) {
        $user = obtenerUsuarioPorEmail($pdo, $email);
        
        if ($user && $user['activo'] == 1 && verificarPassword($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_email'] = $user['email'];
            $_SESSION['admin_nombre'] = $user['nombre'];
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_rol'] = $user['rol'];
            $_SESSION['last_activity'] = time();
            
            $update = $pdo->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?");
            $update->execute([$user['id']]);
            
            header('Location: index.php');
            exit;
        } else {
            $error = '❌ Correo o contraseña incorrectos, o usuario inactivo.';
        }
    } else {
        $error = '❌ Por favor, complete todos los campos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Voces del Sur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: #00093e;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container { max-width: 400px; width: 100%; }
        .login-card {
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
        }
        .login-card h1 { color: #4a5568; margin-bottom: 10px; }
        .login-card p { color: #718096; margin-bottom: 30px; }
        .input-group { margin-bottom: 20px; text-align: left; }
        .input-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; }
        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
        }
        .input-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 14px;
            font-size: 16px;
            border-radius: 12px;
            cursor: pointer;
            width: 100%;
            font-weight: 600;
        }
        .btn-login:hover { transform: translateY(-2px); }
        .error-message {
            background: #fed7d7;
            border-left: 4px solid #e53e3e;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: left;
            color: #742a2a;
        }
        .footer-note { margin-top: 20px; font-size: 0.75em; color: #718096; }
        .logos-header { display: flex; justify-content: center; gap: 15px; margin-bottom: 20px; }
        .logos-header img { height: 50px; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logos-header">
                <img src="../img/LOGOUCCAMPUSITAPÚA.png" alt="UC">
                <img src="../img/logodio.png" alt="Diócesis">
            </div>
            <h1><i class="fas fa-chart-line"></i> Panel de administración</h1>
            <p>Voces del Sur</p>
            
            <?php if ($error): ?>
                <div class="error-message"><i class="fas fa-exclamation-circle"></i> <?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="input-group">
                    <label><i class="fas fa-envelope"></i> Correo electrónico</label>
                    <input type="email" name="email" placeholder="admin@vocesdelsur.com" required autofocus>
                </div>
                <div class="input-group">
                    <label><i class="fas fa-lock"></i> Contraseña</label>
                    <input type="password" name="password" placeholder="Ingresa tu contraseña" required>
                </div>
                <button type="submit" class="btn-login"><i class="fas fa-sign-in-alt"></i> Ingresar</button>
            </form>
            <div class="footer-note">
                <small>Voces del Sur</small>
            </div>
        </div>
    </div>
</body>
</html>