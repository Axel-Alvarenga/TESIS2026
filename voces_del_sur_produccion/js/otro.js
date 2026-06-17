/**
 * otro.js - Control de la opción "Otro"
 * - Cuando se selecciona "OTRO", el campo de comentario se vuelve OBLIGATORIO
 * - Cuando se selecciona otra opción, el campo de comentario es OPCIONAL
 */

document.addEventListener('DOMContentLoaded', function() {

    // ==================== CONFIGURACIÓN DE TEXTOS ====================
    const TEXTO_OTRO_OBLIGATORIO = '⚠️ Este campo es OBLIGATORIO porque seleccionaste "Otro".';
    const TEXTO_OTRO_OPCIONAL = 'Este campo es opcional y nos ayuda a entender mejor tus respuestas.';
    const PLACEHOLDER_OTRO_OBLIGATORIO = 'Obligatorio: explica tu respuesta aquí...';
    const PLACEHOLDER_OTRO_OPCIONAL = 'Opcional: comparte aquí cualquier comentario, opinión o experiencia relacionada con tu respuesta...';

    // ==================== FUNCIÓN PARA MOSTRAR NOTIFICACIÓN MODERNA ====================
    function mostrarNotificacion(mensaje, tipo = 'error') {
        const notificacionesAnteriores = document.querySelectorAll('.notificacion-flotante');
        notificacionesAnteriores.forEach(n => n.remove());
        
        const notificacion = document.createElement('div');
        notificacion.className = 'notificacion-flotante';
        
        let icono = '⚠️';
        let border = '#e53e3e';
        
        if (tipo === 'exito') {
            icono = '✅';
            border = '#38a169';
        } else if (tipo === 'info') {
            icono = 'ℹ️';
            border = '#3182ce';
        }
        
        notificacion.innerHTML = `
            <div class="notificacion-contenido">
                <div class="notificacion-icono">${icono}</div>
                <div class="notificacion-mensaje">${mensaje}</div>
                <button class="notificacion-cerrar" onclick="this.parentElement.parentElement.remove()">✕</button>
            </div>
        `;
        
        notificacion.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            width: 90%;
            max-width: 500px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            border-left: 5px solid ${border};
            animation: notificacionEntrada 0.3s ease;
            padding: 16px 20px;
        `;
        
        const contenido = notificacion.querySelector('.notificacion-contenido');
        contenido.style.cssText = `
            display: flex;
            align-items: center;
            gap: 14px;
        `;
        
        const iconoEl = notificacion.querySelector('.notificacion-icono');
        iconoEl.style.cssText = `
            font-size: 1.8em;
            line-height: 1;
            flex-shrink: 0;
        `;
        
        const mensajeEl = notificacion.querySelector('.notificacion-mensaje');
        mensajeEl.style.cssText = `
            flex: 1;
            font-size: 0.95em;
            color: #2d3748;
            font-weight: 500;
            line-height: 1.4;
        `;
        
        const cerrarBtn = notificacion.querySelector('.notificacion-cerrar');
        cerrarBtn.style.cssText = `
            background: none;
            border: none;
            font-size: 1.2em;
            color: #a0aec0;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 8px;
            transition: all 0.2s;
        `;
        cerrarBtn.onmouseover = () => { cerrarBtn.style.background = '#f7fafc'; };
        cerrarBtn.onmouseout = () => { cerrarBtn.style.background = 'none'; };
        
        document.body.appendChild(notificacion);
        
        setTimeout(() => {
            if (notificacion.parentElement) {
                notificacion.style.animation = 'notificacionSalida 0.3s ease forwards';
                setTimeout(() => notificacion.remove(), 300);
            }
        }, 5000);
    }

    // ==================== ACTUALIZAR ESTADO DE TODOS LOS CAMPOS ====================
    function actualizarCamposOtro() {
        const radios = document.querySelectorAll('.radio-opcion');
        radios.forEach(radio => {
            const comentarioId = radio.dataset.comentario;
            if (!comentarioId) return;
            
            const comentarioContainer = document.getElementById(comentarioId);
            if (!comentarioContainer) return;
            
            const textarea = comentarioContainer.querySelector('textarea');
            const requiredMark = comentarioContainer.querySelector('.required-mark');
            const ayuda = comentarioContainer.querySelector('small');
            
            if (!textarea) return;
            
            const esOtro = radio.value === 'OTRO' && radio.checked;
            
            if (esOtro) {
                textarea.required = true;
                textarea.placeholder = PLACEHOLDER_OTRO_OBLIGATORIO;
                if (requiredMark) requiredMark.style.display = 'inline';
                if (ayuda) {
                    ayuda.textContent = TEXTO_OTRO_OBLIGATORIO;
                    ayuda.style.color = '#dc2626';
                }
                if (textarea.value.trim() === '') {
                    textarea.classList.add('error');
                } else {
                    textarea.classList.remove('error');
                }
            } else {
                textarea.required = false;
                textarea.placeholder = PLACEHOLDER_OTRO_OPCIONAL;
                if (requiredMark) requiredMark.style.display = 'none';
                if (ayuda) {
                    ayuda.textContent = TEXTO_OTRO_OPCIONAL;
                    ayuda.style.color = '#94a3b8';
                }
                textarea.classList.remove('error');
            }
        });

        const checkboxes = document.querySelectorAll('.checkbox-otro');
        checkboxes.forEach(checkbox => {
            const comentarioId = checkbox.dataset.comentario;
            if (!comentarioId) return;
            
            const comentarioContainer = document.getElementById(comentarioId);
            if (!comentarioContainer) return;
            
            const textarea = comentarioContainer.querySelector('textarea');
            const requiredMark = comentarioContainer.querySelector('.required-mark');
            const ayuda = comentarioContainer.querySelector('small');
            
            if (!textarea) return;
            
            if (checkbox.checked) {
                textarea.required = true;
                textarea.placeholder = PLACEHOLDER_OTRO_OBLIGATORIO;
                if (requiredMark) requiredMark.style.display = 'inline';
                if (ayuda) {
                    ayuda.textContent = TEXTO_OTRO_OBLIGATORIO;
                    ayuda.style.color = '#dc2626';
                }
                if (textarea.value.trim() === '') {
                    textarea.classList.add('error');
                } else {
                    textarea.classList.remove('error');
                }
            } else {
                textarea.required = false;
                textarea.placeholder = PLACEHOLDER_OTRO_OPCIONAL;
                if (requiredMark) requiredMark.style.display = 'none';
                if (ayuda) {
                    ayuda.textContent = TEXTO_OTRO_OPCIONAL;
                    ayuda.style.color = '#94a3b8';
                }
                textarea.classList.remove('error');
            }
        });
    }

    // ==================== VALIDACIÓN ANTES DE ENVIAR ====================
    function validarOtros() {
        const radiosOtro = document.querySelectorAll('.radio-otro');
        const checkboxOtro = document.querySelectorAll('.checkbox-otro');
        let isValid = true;
        let mensajes = [];
        
        radiosOtro.forEach(radio => {
            if (radio.checked) {
                const comentarioId = radio.dataset.comentario;
                if (!comentarioId) return;
                
                const comentarioContainer = document.getElementById(comentarioId);
                if (!comentarioContainer) return;
                
                const textarea = comentarioContainer.querySelector('textarea');
                if (!textarea) return;
                
                if (textarea.value.trim() === '') {
                    isValid = false;
                    textarea.classList.add('error');
                    mensajes.push('Debes completar el campo "Otro" porque seleccionaste esa opción.');
                } else {
                    textarea.classList.remove('error');
                }
            }
        });
        
        checkboxOtro.forEach(checkbox => {
            if (checkbox.checked) {
                const comentarioId = checkbox.dataset.comentario;
                if (!comentarioId) return;
                
                const comentarioContainer = document.getElementById(comentarioId);
                if (!comentarioContainer) return;
                
                const textarea = comentarioContainer.querySelector('textarea');
                if (!textarea) return;
                
                if (textarea.value.trim() === '') {
                    isValid = false;
                    textarea.classList.add('error');
                    mensajes.push('Debes completar el campo "Otro" porque seleccionaste esa opción.');
                } else {
                    textarea.classList.remove('error');
                }
            }
        });
        
        if (!isValid && mensajes.length > 0) {
            mostrarNotificacion(mensajes.join('<br>'), 'error');
        }
        
        return isValid;
    }

    // ==================== EVENTOS ====================

    const surveyForm = document.querySelector('.survey-form');
    if (surveyForm) {
        surveyForm.addEventListener('click', function(e) {
            if (e.target.type === 'radio' || e.target.type === 'checkbox') {
                setTimeout(actualizarCamposOtro, 20);
            }
        });
        
        surveyForm.addEventListener('change', function(e) {
            if (e.target.type === 'radio' || e.target.type === 'checkbox') {
                setTimeout(actualizarCamposOtro, 20);
            }
        });
    }

    document.addEventListener('input', function(e) {
        if (e.target.tagName === 'TEXTAREA') {
            const container = e.target.closest('.comentario-original');
            if (container) {
                const requiredMark = container.querySelector('.required-mark');
                if (requiredMark && requiredMark.style.display === 'inline') {
                    if (e.target.value.trim() !== '') {
                        e.target.classList.remove('error');
                    }
                }
            }
        }
    });

    const form = document.getElementById('encuestaForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            actualizarCamposOtro();
            if (!validarOtros()) {
                e.preventDefault();
            }
        });
    }

    setTimeout(actualizarCamposOtro, 150);

    if (surveyForm) {
        const observer = new MutationObserver(function() {
            setTimeout(actualizarCamposOtro, 50);
        });
        observer.observe(surveyForm, {
            childList: true,
            subtree: true
        });
    }

});