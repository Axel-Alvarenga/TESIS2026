/**
 * captcha.js - Control de reCAPTCHA v3 para la página de bienvenida
 */

document.addEventListener('DOMContentLoaded', function() {
    const btnAcceder = document.getElementById('btnAcceder');
    const mensajeDiv = document.getElementById('mensajeCaptcha');
    const modoTestBanner = document.getElementById('modoTestBanner');
    
    // La clave de sitio se pasa como variable global desde PHP
    const SITE_KEY = window.RECAPTCHA_SITE_KEY || '';

    // Verificar si la URL tiene ?test=1
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('test') === '1') {
        if (modoTestBanner) modoTestBanner.classList.add('visible');
    }

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

    if (btnAcceder) {
        btnAcceder.addEventListener('click', async function(e) {
            e.preventDefault();
            
            this.classList.add('cargando');
            this.disabled = true;
            if (mensajeDiv) {
                mensajeDiv.className = 'mensaje-recaptcha cargando-msg';
                mensajeDiv.textContent = '🔍 Verificando...';
            }

            const cargado = await verificarRecaptcha();
            if (!cargado) {
                if (mensajeDiv) {
                    mensajeDiv.className = 'mensaje-recaptcha error';
                    mensajeDiv.textContent = '❌ Error al cargar reCAPTCHA. Por favor, recarga la página.';
                }
                this.classList.remove('cargando');
                this.disabled = false;
                return;
            }

            try {
                grecaptcha.ready(function() {
                    grecaptcha.execute(SITE_KEY, {action: 'acceso'}).then(function(token) {
                        if (!token) {
                            if (mensajeDiv) {
                                mensajeDiv.className = 'mensaje-recaptcha error';
                                mensajeDiv.textContent = '❌ No se pudo obtener el token de verificación.';
                            }
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
                                if (mensajeDiv) {
                                    mensajeDiv.className = 'mensaje-recaptcha exito';
                                    mensajeDiv.textContent = '✅ Verificación exitosa. Redirigiendo...';
                                }
                                setTimeout(function() {
                                    window.location.href = 'encuesta.php';
                                }, 800);
                            } else {
                                if (mensajeDiv) {
                                    mensajeDiv.className = 'mensaje-recaptcha error';
                                    mensajeDiv.textContent = '❌ ' + data.mensaje;
                                }
                                btnAcceder.classList.remove('cargando');
                                btnAcceder.disabled = false;
                            }
                        })
                        .catch(function() {
                            if (mensajeDiv) {
                                mensajeDiv.className = 'mensaje-recaptcha error';
                                mensajeDiv.textContent = '❌ Error de conexión. Por favor, intenta nuevamente.';
                            }
                            btnAcceder.classList.remove('cargando');
                            btnAcceder.disabled = false;
                        });
                    });
                });
            } catch (error) {
                if (mensajeDiv) {
                    mensajeDiv.className = 'mensaje-recaptcha error';
                    mensajeDiv.textContent = '❌ Error: ' + error.message;
                }
                btnAcceder.classList.remove('cargando');
                btnAcceder.disabled = false;
            }
        });
    }
});