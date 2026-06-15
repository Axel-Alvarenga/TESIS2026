<?php
// index.php - Página de bienvenida (landing)
// Redirige a encuesta.php para comenzar
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Centro de Gestión del Conocimiento - Bienvenida</title>
    <link rel="stylesheet" href="css/estilo.css">
    <style>
        /* Estilos adicionales para centrar el cuadro */
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
        }
        
        /* Logos en esquinas */
        .header-principal {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .logo-izquierda {
            flex: 0 0 auto;
        }
        
        .logo-derecha {
            flex: 0 0 auto;
        }
        
        .logo-img {
            max-width: 100%;
            height: auto;
            max-height: 70px;
            width: auto;
            object-fit: contain;
        }
        
        /* Asegurar que el welcome-card esté centrado */
        .welcome-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: fadeIn 0.5s ease-in;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome-card">
            <!-- HEADER CON LOGOS EN ESQUINAS -->
            <div class="header-principal">
                <div class="logo-izquierda">
                    <img src="img/LOGOUCCAMPUSITAPÚA.png" alt="Universidad Católica Campus Itapúa" class="logo-img">
                </div>
                <div class="logo-derecha">
                    <img src="img/logodio.png" alt="Diócesis de Encarnación" class="logo-img">
                </div>
            </div>

            <!-- TÍTULO PRINCIPAL -->
            <div class="titulo-principal">
                <h2>Centro de Gestión del Conocimiento</h2>
                <p>Laboratorio de escucha genuina</p>
            </div>

            <!-- MENSAJE DE BIENVENIDA SIMPLE -->
            <div class="message">
                <p><strong>Bienvenido al Centro de Gestión del Conocimiento</strong></p>
                <p>Un espacio de escucha impulsado por la Diócesis de Encarnación y la Universidad Católica.</p>
            </div>

            <!-- BOTÓN PARA COMENZAR -->
            <div style="text-align: center; margin-top: 30px;">
                <a href="encuesta.php" class="btn-primary" style="text-decoration: none; display: inline-block; width: auto; padding: 15px 40px;">
                    Voces del Sur →
                </a>
                <p style="margin-top: 20px; font-size: 0.75em; color: #718096;">
                    Al continuar, deberás aceptar el consentimiento informado
                </p>
            </div>

            <!-- FOOTER -->
            <div class="footer-note">
                <small>Diócesis de la Santísima Encarnación y la Universidad Católica "Nuestra Señora de la Asunción" Campus Itapúa · Encarnación, Paraguay · 2026</small>
            </div>
        </div>
    </div>
</body>
</html>