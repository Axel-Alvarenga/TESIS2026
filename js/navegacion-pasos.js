// js/navegacion-pasos.js - Navegación por pasos
let pasoActual = 1;
const totalPasos = 12;

document.addEventListener('DOMContentLoaded', function() {
    mostrarPaso(pasoActual);
    actualizarBotones();
    
    // Event listeners para los botones
    document.getElementById('nextBtn').addEventListener('click', function() {
        if (validarPaso(pasoActual)) {
            if (pasoActual < totalPasos) {
                pasoActual++;
                mostrarPaso(pasoActual);
                actualizarBotones();
            }
        }
    });
    
    document.getElementById('prevBtn').addEventListener('click', function() {
        if (pasoActual > 1) {
            pasoActual--;
            mostrarPaso(pasoActual);
            actualizarBotones();
        }
    });
});

function mostrarPaso(paso) {
    // Ocultar todos los pasos
    document.querySelectorAll('.step-page').forEach(el => {
        el.classList.remove('active');
        el.style.display = 'none';
    });
    
    // Mostrar el paso actual
    const pasoElement = document.querySelector(`.step-page[data-step="${paso}"]`);
    if (pasoElement) {
        pasoElement.style.display = 'block';
        pasoElement.classList.add('active');
    }
    
    // Actualizar indicador de progreso
    const progressFill = document.getElementById('stepProgressFill');
    const stepCounter = document.getElementById('stepCounter');
    
    if (progressFill) {
        progressFill.style.width = ((paso / totalPasos) * 100) + '%';
    }
    if (stepCounter) {
        stepCounter.textContent = `Bloque ${paso} de ${totalPasos}`;
    }
}

function actualizarBotones() {
    const prevBtn = document.getElementById('prevBtn');
    if (pasoActual > 1) {
        prevBtn.style.visibility = 'visible';
    } else {
        prevBtn.style.visibility = 'hidden';
    }
    
    const nextBtn = document.getElementById('nextBtn');
    if (pasoActual === totalPasos) {
        nextBtn.textContent = 'Enviar Encuesta';
        nextBtn.type = 'submit';
    } else {
        nextBtn.textContent = 'Siguiente →';
        nextBtn.type = 'button';
    }
}

function validarPaso(paso) {
    let valido = true;
    let mensajesError = [];
    
    // Limpiar errores anteriores
    document.querySelectorAll('.mensaje-error-texto').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.error').forEach(el => el.classList.remove('error'));
    
    // Eliminar contenedor de errores anterior
    const errorContainerAntiguo = document.getElementById('error-container');
    if (errorContainerAntiguo) errorContainerAntiguo.remove();
    
    switch(paso) {
        case 1: // Bloque 1: Datos demográficos
            const anio = document.getElementById('anioNacimiento');
            if (!anio || anio.value === '') {
                mensajesError.push('Debes seleccionar tu año de nacimiento');
                valido = false;
                if (anio) anio.classList.add('error');
            }
            
            const sexo = document.querySelector('input[name="sexo"]:checked');
            if (!sexo) {
                mensajesError.push('Debes seleccionar tu género');
                valido = false;
            }
            
            const parroquia = document.getElementById('parroquiaHidden');
            if (!parroquia || parroquia.value === '') {
                mensajesError.push('Debes seleccionar tu parroquia');
                valido = false;
                const selectorInput = document.getElementById('selectorInput');
                if (selectorInput) selectorInput.classList.add('error');
                const parroquiaError = document.getElementById('parroquiaError');
                if (parroquiaError) parroquiaError.style.display = 'block';
            }
            break;
            
        case 2: // Bloque 2: P3 - Pertenencia
            const p3 = document.querySelector('input[name="p3_pertenencia"]:checked');
            if (!p3) {
                mensajesError.push('Debes seleccionar una opción en P3');
                valido = false;
            } else if (p3.value === 'OTRO') {
                const textarea = document.getElementById('textarea_p3');
                if (!textarea || textarea.value.trim() === '') {
                    mensajesError.push('Debes especificar tu respuesta en "Otro" (P3)');
                    valido = false;
                    if (textarea) {
                        textarea.classList.add('error');
                        const errorMsg = document.querySelector('#comentario_p3 .mensaje-error-texto');
                        if (errorMsg) errorMsg.style.display = 'block';
                    }
                }
            }
            break;
            
        case 3: // Bloque 3: P4 - Atracción
            const p4 = document.querySelector('input[name="p4_atraccion"]:checked');
            if (!p4) {
                mensajesError.push('Debes seleccionar una opción en P4');
                valido = false;
            } else if (p4.value === 'OTRO') {
                const textarea = document.getElementById('textarea_p4');
                if (!textarea || textarea.value.trim() === '') {
                    mensajesError.push('Debes especificar tu respuesta en "Otro" (P4)');
                    valido = false;
                    if (textarea) {
                        textarea.classList.add('error');
                        const errorMsg = document.querySelector('#comentario_p4 .mensaje-error-texto');
                        if (errorMsg) errorMsg.style.display = 'block';
                    }
                }
            }
            break;
            
        case 4: // Bloque 4: P4b-1 - Situación
            const p4b1 = document.querySelector('input[name="p4b_situacion"]:checked');
            if (!p4b1) {
                mensajesError.push('Debes seleccionar tu situación principal');
                valido = false;
            } else if (p4b1.value === 'OTRO') {
                const textarea = document.getElementById('textarea_p4b1');
                if (!textarea || textarea.value.trim() === '') {
                    mensajesError.push('Debes especificar tu respuesta en "Otro" (P4b-1)');
                    valido = false;
                    if (textarea) {
                        textarea.classList.add('error');
                        const errorMsg = document.querySelector('#comentario_p4b1 .mensaje-error-texto');
                        if (errorMsg) errorMsg.style.display = 'block';
                    }
                }
            }
            break;
            
        case 5: // Bloque 5: P4b-2 - Área
            const p4b2 = document.querySelector('input[name="p4b_area"]:checked');
            if (!p4b2) {
                mensajesError.push('Debes seleccionar tu área de interés');
                valido = false;
            } else if (p4b2.value === 'OTRO') {
                const textarea = document.getElementById('textarea_p4b2');
                if (!textarea || textarea.value.trim() === '') {
                    mensajesError.push('Debes especificar tu respuesta en "Otro" (P4b-2)');
                    valido = false;
                    if (textarea) {
                        textarea.classList.add('error');
                        const errorMsg = document.querySelector('#comentario_p4b2 .mensaje-error-texto');
                        if (errorMsg) errorMsg.style.display = 'block';
                    }
                }
            }
            break;
            
        case 6: // Bloque 6: P4b-3 - Movilidad
            const p4b3 = document.querySelector('input[name="p4b_movilidad"]:checked');
            if (!p4b3) {
                mensajesError.push('Debes seleccionar tu movilidad territorial');
                valido = false;
            } else if (p4b3.value === 'OTRO') {
                const textarea = document.getElementById('textarea_p4b3');
                if (!textarea || textarea.value.trim() === '') {
                    mensajesError.push('Debes especificar tu respuesta en "Otro" (P4b-3)');
                    valido = false;
                    if (textarea) {
                        textarea.classList.add('error');
                        const errorMsg = document.querySelector('#comentario_p4b3 .mensaje-error-texto');
                        if (errorMsg) errorMsg.style.display = 'block';
                    }
                }
            }
            break;
            
        case 7: // Bloque 7: P5 - Espiritualidad
            const p5 = document.querySelector('input[name="p5_espiritualidad"]:checked');
            if (!p5) {
                mensajesError.push('Debes seleccionar una opción en P5');
                valido = false;
            } else if (p5.value === 'OTRO') {
                const textarea = document.getElementById('textarea_p5');
                if (!textarea || textarea.value.trim() === '') {
                    mensajesError.push('Debes especificar tu respuesta en "Otro" (P5)');
                    valido = false;
                    if (textarea) {
                        textarea.classList.add('error');
                        const errorMsg = document.querySelector('#comentario_p5 .mensaje-error-texto');
                        if (errorMsg) errorMsg.style.display = 'block';
                    }
                }
            }
            break;
            
        case 8: // Bloque 8: P6 - Familia
            const p6 = document.querySelector('input[name="p6_familia"]:checked');
            if (!p6) {
                mensajesError.push('Debes seleccionar una opción en P6');
                valido = false;
            } else if (p6.value === 'OTRO') {
                const textarea = document.getElementById('textarea_p6');
                if (!textarea || textarea.value.trim() === '') {
                    mensajesError.push('Debes especificar tu respuesta en "Otro" (P6)');
                    valido = false;
                    if (textarea) {
                        textarea.classList.add('error');
                        const errorMsg = document.querySelector('#comentario_p6 .mensaje-error-texto');
                        if (errorMsg) errorMsg.style.display = 'block';
                    }
                }
            }
            break;
            
        case 9: // Bloque 9: P7 - Proyecto de vida
            const p7 = document.querySelector('input[name="p7_proyecto"]:checked');
            if (!p7) {
                mensajesError.push('Debes seleccionar una opción en P7');
                valido = false;
            } else if (p7.value === 'OTRO') {
                const textarea = document.getElementById('textarea_p7');
                if (!textarea || textarea.value.trim() === '') {
                    mensajesError.push('Debes especificar tu respuesta en "Otro" (P7)');
                    valido = false;
                    if (textarea) {
                        textarea.classList.add('error');
                        const errorMsg = document.querySelector('#comentario_p7 .mensaje-error-texto');
                        if (errorMsg) errorMsg.style.display = 'block';
                    }
                }
            }
            break;
            
        case 10: // Bloque 10: P8 - Vocación
            const p8 = document.querySelector('input[name="p8_vocacion"]:checked');
            if (!p8) {
                mensajesError.push('Debes seleccionar una opción en P8');
                valido = false;
            } else if (p8.value === 'OTRO') {
                const textarea = document.getElementById('textarea_p8');
                if (!textarea || textarea.value.trim() === '') {
                    mensajesError.push('Debes especificar tu respuesta en "Otro" (P8)');
                    valido = false;
                    if (textarea) {
                        textarea.classList.add('error');
                        const errorMsg = document.querySelector('#comentario_p8 .mensaje-error-texto');
                        if (errorMsg) errorMsg.style.display = 'block';
                    }
                }
            }
            break;
            
        case 11: // Bloque 11: P9 - Crítica (checkbox)
            const p9Checkboxes = document.querySelectorAll('input[name="p9_critica[]"]:checked');
            if (p9Checkboxes.length === 0) {
                mensajesError.push('Debes seleccionar al menos una opción en P9');
                valido = false;
            } else if (p9Checkboxes.length > 2) {
                mensajesError.push('Solo puedes seleccionar hasta 2 opciones en P9');
                valido = false;
            } else {
                // Verificar si seleccionó "OTRO" y si el campo está lleno
                let otroSeleccionado = false;
                p9Checkboxes.forEach(cb => {
                    if (cb.value === 'OTRO') otroSeleccionado = true;
                });
                if (otroSeleccionado) {
                    const textarea = document.getElementById('textarea_p9');
                    if (!textarea || textarea.value.trim() === '') {
                        mensajesError.push('Debes especificar tu respuesta en "Otro" (P9)');
                        valido = false;
                        if (textarea) {
                            textarea.classList.add('error');
                            const errorMsg = document.querySelector('#comentario_p9 .mensaje-error-texto');
                            if (errorMsg) errorMsg.style.display = 'block';
                        }
                    }
                }
            }
            break;
            
        case 12: // Bloque 12: P10 - Esperanza
            const p10 = document.querySelector('input[name="p10_esperanza"]:checked');
            if (!p10) {
                mensajesError.push('Debes seleccionar tu nivel de esperanza');
                valido = false;
            }
            
            // Validar permiso de padres si es menor
            const permisoDiv = document.getElementById('permisoMenores');
            if (permisoDiv && permisoDiv.style.display !== 'none') {
                const permisoCheck = document.getElementById('permisoPadres');
                if (!permisoCheck || !permisoCheck.checked) {
                    mensajesError.push('Debes aceptar el permiso de tus padres o tutores');
                    valido = false;
                }
            }
            break;
    }
    
    if (mensajesError.length > 0) {
        mostrarMensajesError(mensajesError);
        return false;
    }
    
    return true;
}

function mostrarMensajesError(mensajes) {
    const pasoActivo = document.querySelector('.step-page.active');
    if (!pasoActivo) return;
    
    const errorContainer = document.createElement('div');
    errorContainer.id = 'error-container';
    errorContainer.style.cssText = `
        background: #fee2e2;
        color: #991b1b;
        padding: 15px 20px;
        border-radius: 10px;
        border-left: 4px solid #dc2626;
        margin-bottom: 20px;
        animation: fadeInStep 0.3s ease;
    `;
    
    let html = '<strong>❌ Por favor, corrige los siguientes errores:</strong><ul style="margin: 10px 0 0 20px;">';
    mensajes.forEach(msg => {
        html += `<li style="margin-bottom: 5px;">${msg}</li>`;
    });
    html += '</ul>';
    errorContainer.innerHTML = html;
    
    pasoActivo.insertBefore(errorContainer, pasoActivo.firstChild);
    errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
}