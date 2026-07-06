/**
 * otro.js - Control de la opción "Otro"
 */

document.addEventListener('DOMContentLoaded', function() {

    // ==================== CONFIGURACIÓN ====================
    const TEXTO_OTRO_OBLIGATORIO = '⚠️ Este campo es OBLIGATORIO porque seleccionaste "Otro".';
    const TEXTO_OTRO_OPCIONAL = 'Este campo es opcional y nos ayuda a entender mejor tus respuestas.';
    const PLACEHOLDER_OTRO_OBLIGATORIO = 'Obligatorio: explica tu respuesta aquí...';
    const PLACEHOLDER_OTRO_OPCIONAL = 'Opcional: comparte aquí cualquier comentario, opinión o experiencia relacionada con tu respuesta...';

    // ==================== FUNCIÓN PRINCIPAL ====================

    function actualizarCamposOtro() {
        // === RADIOS ===
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

        // === CHECKBOXES (P9) ===
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

    // ==================== INICIALIZACIÓN ====================

    // Inicializar al cargar
    setTimeout(actualizarCamposOtro, 150);

    // Observador para cambios de paso
    if (surveyForm) {
        const observer = new MutationObserver(function() {
            setTimeout(actualizarCamposOtro, 50);
        });
        observer.observe(surveyForm, {
            childList: true,
            subtree: true
        });
    }

    // Exponer función para que navegacion-pasos.js pueda usarla si es necesario
    window.actualizarCamposOtro = actualizarCamposOtro;

});