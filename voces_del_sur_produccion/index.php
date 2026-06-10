<?php
// index.php - Página de bienvenida (landing)
// Redirige a encuesta.php para comenzar
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Voces del Sur - Bienvenida</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <div class="container">
        <div class="welcome-card">
            <!-- HEADER CON LOGOS -->
            <div class="header-principal">
                <div class="logo-izquierda">
                    <img src="img/LOGOUCCAMPUSITAPÚA.png" alt="Universidad Católica Campus Itapúa" class="logo-img">
                </div>
                <div class="logo-central">
                    <img src="img/bie-cat.jpeg" alt="BIE CAT" class="logo-img">
                </div>
                <div class="logo-derecha">
                    <img src="img/logodio.png" alt="Diócesis de Encarnación" class="logo-img">
                </div>
            </div>

            <!-- TÍTULO PRINCIPAL -->
            <div class="titulo-principal">
                <h2>Voces del Sur</h2>
                <p>Laboratorio de escucha genuina</p>
            </div>

            <!-- MENSAJE DE BIENVENIDA -->
            <div class="message">
                <p><strong>¡Bienvenido/a a Voces del Sur!</strong></p>
                <p>Este es un espacio de escucha impulsado por la Diócesis de Encarnación y la Universidad Católica, donde queremos conocer tu opinión sincera sobre los temas que realmente te importan.</p>
                
                <div class="info-box">
                    ⏱ 5 a 7 minutos · 100% anónimo · Sin apellidos ni cédula · Caduca el 31/12/2026
                </div>
                
                <div class="warning-message" style="background: #e8f0fe; border-left-color: #667eea;">
                    <span class="warning-icon">🎯</span>
                    <div class="warning-text">
                        <strong>¿Por qué participar?</strong> Tus respuestas nos ayudarán a entender mejor la realidad de los jóvenes de Itapúa y a diseñar propuestas que realmente respondan a sus necesidades.
                    </div>
                </div>
            </div>

            <!-- BOTÓN PARA COMENZAR (ahora apunta a encuesta.php) -->
            <div style="text-align: center; margin-top: 30px;">
                <a href="encuesta.php" class="btn-primary" style="text-decoration: none; display: inline-block; width: auto; padding: 15px 40px;">
                    Comenzar encuesta →
                </a>
                <p style="margin-top: 20px; font-size: 0.75em; color: #718096;">
                    Al continuar, deberás aceptar el consentimiento informado
                </p>
            </div>

            <!-- FOOTER -->
            <div class="footer-note">
                <small>Proyecto Voces del Sur · Diócesis de la Santísima Encarnación · Universidad Católica Nuestra Señora de la Asunción · Pastoral de Juventud · Encarnación, Paraguay · 2026</small>
            </div>
        </div>
    </div>
</body>
</html>