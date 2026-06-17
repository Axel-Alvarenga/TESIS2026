<?php
// index.php - Página de bienvenida con reCAPTCHA v3
session_start();
require_once 'config.php';

// Si el usuario ya pasó la verificación, redirigir directamente a encuesta.php
if (isset($_SESSION['acceso_verificado']) && $_SESSION['acceso_verificado'] === true) {
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
    <script src="https://www.google.com/recaptcha/api.js?render=<?= RECAPTCHA_SITE_KEY ?>" async defer></script>
    <!-- ... resto del head igual ... -->
</head>
<body>
    <!-- ... contenido igual ... -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnAcceder = document.getElementById('btnAcceder');
            const mensajeDiv = document.getElementById('mensajeCaptcha');
            const SITE_KEY = '<?= RECAPTCHA_SITE_KEY ?>';

            function verificarRecaptcha() {
                return new Promise((resolve) => {
                    if (typeof grecaptcha !== 'undefined' && grecaptcha.execute) {
                        resolve(true);
                    } else {
                        let intentos = 0;
                        const intervalo = setInterval(() => {
                            intentos++;
                            if (typeof grecaptcha !== 'undefined' && grecaptcha.execute) {
                                clearInterval(intervalo);
                                resolve(true);
                            } else if (intentos > 20) {
                                clearInterval(intervalo);
                                resolve(false);
                            }
                        }, 300);
                    }
                });
            }

            btnAcceder.addEventListener('click', async function(e) {
                e.preventDefault();
                
                this.classList.add('cargando');
                this.disabled = true;
                mensajeDiv.className = 'mensaje-recaptcha cargando-msg';
                mensajeDiv.textContent = '🔍 Verificando...';

                const cargado = await verificarRecaptcha();
                if (!cargado) {
                    mensajeDiv.className = 'mensaje-recaptcha error';
                    mensajeDiv.textContent = '❌ Error al cargar reCAPTCHA. Por favor, recarga la página.';
                    btnAcceder.classList.remove('cargando');
                    btnAcceder.disabled = false;
                    return;
                }

                try {
                    grecaptcha.ready(function() {
                        grecaptcha.execute(SITE_KEY, {action: 'acceso'}).then(function(token) {
                            if (!token) {
                                mensajeDiv.className = 'mensaje-recaptcha error';
                                mensajeDiv.textContent = '❌ No se pudo obtener el token de verificación.';
                                btnAcceder.classList.remove('cargando');
                                btnAcceder.disabled = false;
                                return;
                            }

                            fetch('verificar_captcha.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: 'recaptcha_response=' + encodeURIComponent(token)
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    mensajeDiv.className = 'mensaje-recaptcha exito';
                                    mensajeDiv.textContent = '✅ Verificación exitosa. Redirigiendo...';
                                    setTimeout(function() {
                                        window.location.href = 'encuesta.php';
                                    }, 800);
                                } else {
                                    mensajeDiv.className = 'mensaje-recaptcha error';
                                    mensajeDiv.textContent = '❌ ' + data.mensaje;
                                    btnAcceder.classList.remove('cargando');
                                    btnAcceder.disabled = false;
                                }
                            })
                            .catch(function(error) {
                                mensajeDiv.className = 'mensaje-recaptcha error';
                                mensajeDiv.textContent = '❌ Error de conexión. Por favor, intenta nuevamente.';
                                btnAcceder.classList.remove('cargando');
                                btnAcceder.disabled = false;
                            });
                        });
                    });
                } catch (error) {
                    mensajeDiv.className = 'mensaje-recaptcha error';
                    mensajeDiv.textContent = '❌ Error: ' + error.message;
                    btnAcceder.classList.remove('cargando');
                    btnAcceder.disabled = false;
                }
            });
        });
    </script>
</body>
</html>