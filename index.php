<?php
// index.php - Página de bienvenida con reCAPTCHA v3
session_start();
require_once 'config.php';

// ==================== MODO PRUEBA (desactivar reCAPTCHA) ====================
if (isset($_GET['test']) && $_GET['test'] === '1') {
    $_SESSION['acceso_verificado'] = true;
    $_SESSION['acceso_verificado_timestamp'] = time();
    header('Location: encuesta.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Centro de Gestión del Conocimiento - Bienvenida</title>
    <link rel="stylesheet" href="css/estilo.css">
    <!-- reCAPTCHA v3 -->
    <script src="https://www.google.com/recaptcha/api.js?render=<?= RECAPTCHA_SITE_KEY ?>" async defer></script>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container {
            margin: 0 auto;
            width: 100%;
            max-width: 800px;
        }
        .header-principal {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }
        .logo-izquierda, .logo-derecha {
            flex: 0 0 auto;
        }
        .logo-img {
            max-width: 100%;
            height: auto;
            max-height: 70px;
            width: auto;
            object-fit: contain;
        }
        .welcome-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: fadeIn 0.5s ease-in;
        }
        .btn-recaptcha {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .btn-recaptcha:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        .btn-recaptcha .spinner {
            display: none;
            width: 22px;
            height: 22px;
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 0.8s ease infinite;
            margin: 0 auto;
        }
        .btn-recaptcha.cargando .spinner {
            display: block;
        }
        .btn-recaptcha.cargando .btn-texto {
            display: none;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .mensaje-recaptcha {
            margin-top: 15px;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.9em;
            display: none;
            text-align: left;
        }
        .mensaje-recaptcha.error {
            display: block;
            background: #fed7d7;
            color: #742a2a;
            border-left: 4px solid #e53e3e;
        }
        .mensaje-recaptcha.exito {
            display: block;
            background: #c6f6d5;
            color: #22543d;
            border-left: 4px solid #48bb78;
        }
        .mensaje-recaptcha.cargando-msg {
            display: block;
            background: #ebf8ff;
            color: #2b6cb0;
            border-left: 4px solid #3182ce;
        }
        .badge-test {
            display: inline-block;
            background: #f6e05e;
            color: #744210;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 8px;
        }
        .modo-test-banner {
            margin-top: 15px;
            background: #fefcbf;
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 0.85em;
            color: #744210;
            border: 1px solid #ecc94b;
            display: none;
        }
        .modo-test-banner.visible {
            display: block;
        }
        @media (max-width: 768px) {
            .welcome-card { padding: 25px 20px; }
            .logo-img { max-height: 45px; }
            .btn-recaptcha { padding: 12px 25px; font-size: 16px; width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome-card">
            <div class="header-principal">
                <div class="logo-izquierda">
                    <img src="img/LOGOUCCAMPUSITAPÚA.png" alt="Universidad Católica Campus Itapúa" class="logo-img">
                </div>
                <div class="logo-derecha">
                    <img src="img/logodio.png" alt="Diócesis de Encarnación" class="logo-img">
                </div>
            </div>

            <div class="titulo-principal">
                <h2>Centro de Gestión del Conocimiento</h2>
                <p>Laboratorio de escucha genuina</p>
            </div>

            <div class="message">
                <p><strong>Bienvenido al Centro de Gestión del Conocimiento</strong></p>
                <p>Un espacio de escucha impulsado por la Diócesis de Encarnación y la Universidad Católica.</p>
            </div>

            <!-- BANNER DE MODO PRUEBA -->
            <div id="modoTestBanner" class="modo-test-banner">
                🔧 <strong>MODO PRUEBA ACTIVO</strong> — reCAPTCHA desactivado<br>
                <small>Si ves esto, significa que ya estás en modo prueba. Haz clic en "Voces del Sur →" para continuar.</small>
            </div>

            <div style="text-align: center; margin-top: 30px;">
                <button id="btnAcceder" class="btn-primary btn-recaptcha" style="text-decoration: none; display: inline-block; width: auto; padding: 15px 40px; border: none; cursor: pointer;">
                    <span class="btn-texto">Voces del Sur →</span>
                    <span class="spinner"></span>
                </button>
                
                <div id="mensajeCaptcha" class="mensaje-recaptcha"></div>
                
                <p style="margin-top: 20px; font-size: 0.75em; color: #718096;">
                    Al continuar, deberás aceptar el consentimiento informado
                </p>

                <p style="margin-top: 15px; font-size: 0.7em; color: #a0aec0;">
                    <a href="?test=1" style="color: #a0aec0; text-decoration: underline;">⚡ Modo prueba (saltar reCAPTCHA)</a>
                </p>
            </div>

            <div class="footer-note">
                <small>Diócesis de la Santísima Encarnación y la Universidad Católica "Nuestra Señora de la Asunción" Campus Itapúa · Encarnación, Paraguay · 2026</small>
            </div>
        </div>
    </div>

    <!-- ==================== PASAR LA CLAVE DE SITIO A JAVASCRIPT ==================== -->
    <script>
        window.RECAPTCHA_SITE_KEY = '<?= RECAPTCHA_SITE_KEY ?>';
    </script>

    <!-- ==================== SCRIPTS ==================== -->
    <script src="js/captcha.js"></script>
</body>
</html>