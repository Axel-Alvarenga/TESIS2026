<?php
// error_rate_limit.php - Página de error para rate limit
$tiempo_espera = isset($_GET['espera']) ? intval($_GET['espera']) : 60;
if ($tiempo_espera < 0) $tiempo_espera = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Espera un momento - Voces del Sur</title>
    <link rel="stylesheet" href="css/estilo.css">
    <style>
        .error-container {
            max-width: 800px;
            margin: 0 auto;
            animation: fadeIn 0.5s ease-in;
        }
        
        .error-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .error-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
        }
        
        .error-icon {
            font-size: 4em;
            margin-bottom: 20px;
            display: inline-block;
        }
        
        .error-title {
            color: #c53030;
            font-size: 1.8em;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .error-message {
            color: #4a5568;
            font-size: 1.1em;
            margin-bottom: 25px;
            line-height: 1.5;
        }
        
        .error-timer {
            background: #f7fafc;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .timer-number {
            font-size: 3em;
            font-weight: 700;
            color: #e53e3e;
            font-family: monospace;
        }
        
        .timer-label {
            font-size: 0.85em;
            color: #718096;
            margin-top: 5px;
        }
        
        .progress-bar-container {
            width: 100%;
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            margin: 20px 0;
            overflow: hidden;
        }
        
        .progress-bar {
            height: 100%;
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
            width: 0%;
            transition: width 1s linear;
            border-radius: 4px;
        }
        
        .btn-retry {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        
        .btn-retry:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(229, 62, 62, 0.3);
        }
        
        .btn-retry:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .suggestion-text {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 0.85em;
            color: #718096;
        }
        
        .suggestion-text a {
            color: #667eea;
            text-decoration: none;
        }
        
        .suggestion-text a:hover {
            text-decoration: underline;
        }
        
        .header-error {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .logo-error {
            flex: 1;
            display: flex;
            align-items: center;
        }
        
        .logo-left { justify-content: flex-start; }
        .logo-center { justify-content: center; }
        .logo-right { justify-content: flex-end; }
        
        .logo-img-error {
            max-width: 100%;
            height: auto;
            max-height: 60px;
            width: auto;
            object-fit: contain;
        }
        
        @media (max-width: 768px) {
            .error-card { padding: 25px 20px; }
            .error-title { font-size: 1.5em; }
            .error-message { font-size: 0.95em; }
            .timer-number { font-size: 2.5em; }
            .logo-img-error { max-height: 40px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-container">
            <div class="error-card">
                <div class="header-error">
                    <div class="logo-error logo-left">
                        <img src="img/LOGOUCCAMPUSITAPÚA.png" alt="Universidad Católica" class="logo-img-error">
                    </div>
                    <div class="logo-error logo-center">
                        <img src="img/bie-cat.jpeg" alt="BIE CAT" class="logo-img-error">
                    </div>
                    <div class="logo-error logo-right">
                        <img src="img/logodio.png" alt="Diócesis de Encarnación" class="logo-img-error">
                    </div>
                </div>
                
                <div class="error-icon">⏳</div>
                <h1 class="error-title">¡Espera un momento!</h1>
                <p class="error-message">
                    Solo puedes enviar <strong>una respuesta por minuto</strong>.<br>
                    Por favor, espera y vuelve a intentar.
                </p>
                
                <div class="error-timer">
                    <div id="contador" class="timer-number"><?= $tiempo_espera ?></div>
                    <div class="timer-label">segundos restantes</div>
                    <div class="progress-bar-container">
                        <div id="progressBar" class="progress-bar"></div>
                    </div>
                </div>
                
                <button id="btnReintentar" class="btn-retry" disabled>
                    ⏳ Esperando...
                </button>
                
                <div class="suggestion-text">
                    <p>💡 <strong>¿Ya pasó un minuto?</strong> Si el tiempo ya expiró,</p>
                    <p>puedes <a href="index.php">volver al inicio</a> para intentar de nuevo.</p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        let tiempoRestante = <?= $tiempo_espera ?>;
        const contadorElement = document.getElementById('contador');
        const progressBar = document.getElementById('progressBar');
        const btnReintentar = document.getElementById('btnReintentar');
        const totalTiempo = tiempoRestante > 0 ? tiempoRestante : 60;
        
        function actualizarContador() {
            if (tiempoRestante <= 0) {
                contadorElement.textContent = '0';
                btnReintentar.disabled = false;
                btnReintentar.innerHTML = '🔄 Reintentar ahora';
                btnReintentar.onclick = function() {
                    window.location.href = 'index.php';
                };
                return;
            }
            
            contadorElement.textContent = tiempoRestante;
            const porcentaje = (tiempoRestante / totalTiempo) * 100;
            progressBar.style.width = (100 - porcentaje) + '%';
            
            tiempoRestante--;
            setTimeout(actualizarContador, 1000);
        }
        
        actualizarContador();
    </script>
</body>
</html>