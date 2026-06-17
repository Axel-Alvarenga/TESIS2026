// navegacion-pasos.js - Control de navegación entre bloques
const pages = document.querySelectorAll('.step-page');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const stepCounter = document.getElementById('stepCounter');
const stepProgressFill = document.getElementById('stepProgressFill');
const formulario = document.getElementById('encuestaForm');

let currentStep = 1;
const totalSteps = 12;

// ==================== SISTEMA DE NOTIFICACIONES MODERNO ====================
function mostrarNotificacion(mensaje, tipo = 'error') {
    // Eliminar notificaciones anteriores
    const notificacionesAnteriores = document.querySelectorAll('.notificacion-flotante');
    notificacionesAnteriores.forEach(n => n.remove());
    
    // Crear notificación
    const notificacion = document.createElement('div');
    notificacion.className = 'notificacion-flotante';
    
    // Configurar según tipo
    let icono = '⚠️';
    let color = '#e53e3e';
    let bg = '#fff5f5';
    let border = '#e53e3e';
    
    if (tipo === 'exito') {
        icono = '✅';
        color = '#38a169';
        bg = '#f0fff4';
        border = '#38a169';
    } else if (tipo === 'info') {
        icono = 'ℹ️';
        color = '#3182ce';
        bg = '#ebf8ff';
        border = '#3182ce';
    }
    
    notificacion.innerHTML = `
        <div class="notificacion-contenido">
            <div class="notificacion-icono">${icono}</div>
            <div class="notificacion-mensaje">${mensaje}</div>
            <button class="notificacion-cerrar" onclick="this.parentElement.parentElement.remove()">✕</button>
        </div>
    `;
    
    // Estilos en línea para la notificación
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
    
    // Estilos para el contenido
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
    
    // Auto-cerrar después de 5 segundos
    setTimeout(() => {
        if (notificacion.parentElement) {
            notificacion.style.animation = 'notificacionSalida 0.3s ease forwards';
            setTimeout(() => notificacion.remove(), 300);
        }
    }, 5000);
}

// Agregar estilos CSS dinámicamente si no existen
if (!document.getElementById('notificacion-estilos')) {
    const style = document.createElement('style');
    style.id = 'notificacion-estilos';
    style.textContent = `
        @keyframes notificacionEntrada {
            from { opacity: 0; transform: translateX(-50%) translateY(-20px); }
            to { opacity: 1; transform: translateX(-50%) translateY(0); }
        }
        @keyframes notificacionSalida {
            from { opacity: 1; transform: translateX(-50%) translateY(0); }
            to { opacity: 0; transform: translateX(-50%) translateY(-20px); }
        }
    `;
    document.head.appendChild(style);
}

function updateStepVisibility() {
    pages.forEach((page, index) => {
        if (index + 1 === currentStep) {
            page.classList.add('active');
        } else {
            page.classList.remove('active');
        }
    });
    
    stepCounter.textContent = `Bloque ${currentStep} de ${totalSteps}`;
    const progress = (currentStep / totalSteps) * 100;
    stepProgressFill.style.width = `${progress}%`;
    
    if (currentStep === 1) {
        prevBtn.style.visibility = 'hidden';
    } else {
        prevBtn.style.visibility = 'visible';
    }
    
    if (currentStep === totalSteps) {
        nextBtn.textContent = 'Enviar respuestas ✓';
    } else {
        nextBtn.textContent = 'Siguiente →';
    }
}

function validateCurrentStep() {
    const currentPage = document.querySelector(`.step-page[data-step="${currentStep}"]`);
    const requiredFields = currentPage.querySelectorAll('[required]');
    let isValid = true;
    let mensajes = [];
    
    // Validación especial para parroquia en el paso 1
    if (currentStep === 1 && typeof selectedParroquia !== 'undefined' && selectedParroquia === '') {
        isValid = false;
        mensajes.push('Por favor, selecciona una parroquia.');
        const parroquiaError = document.getElementById('parroquiaError');
        const selectorInput = document.getElementById('selectorInput');
        if (parroquiaError) parroquiaError.style.display = 'block';
        if (selectorInput) selectorInput.classList.add('error');
    } else if (currentStep === 1) {
        const parroquiaError = document.getElementById('parroquiaError');
        const selectorInput = document.getElementById('selectorInput');
        if (parroquiaError) parroquiaError.style.display = 'none';
        if (selectorInput) selectorInput.classList.remove('error');
    }
    
    requiredFields.forEach(field => {
        if (field.id === 'parroquiaInput') return;
        if (field.type === 'radio') {
            const radioGroup = document.querySelectorAll(`input[name="${field.name}"]`);
            const isChecked = Array.from(radioGroup).some(r => r.checked);
            if (!isChecked) {
                isValid = false;
                field.classList.add('error');
                // Obtener el label de la pregunta
                const question = field.closest('.question');
                const label = question ? question.querySelector('label') : null;
                const text = label ? label.innerText.replace(/\*/g, '').trim() : field.name;
                if (!mensajes.some(m => m.includes(text.substring(0, 30)))) {
                    mensajes.push(`Completa la pregunta: "${text.substring(0, 50)}..."`);
                }
            } else {
                field.classList.remove('error');
            }
        } else if (field.type === 'checkbox' && field.required && !field.checked) {
            isValid = false;
            field.classList.add('error');
        } else if ((field.value === '' || field.value === null) && field.type !== 'checkbox') {
            isValid = false;
            field.classList.add('error');
        } else {
            field.classList.remove('error');
        }
    });
    
    const permisoDiv = document.getElementById('permisoMenores');
    const permisoCheckbox = document.getElementById('permisoPadres');
    if (permisoDiv && permisoDiv.style.display === 'block' && permisoCheckbox && !permisoCheckbox.checked) {
        isValid = false;
        mensajes.push('Debes marcar la casilla de autorización parental (eres menor de 18 años).');
    }
    
    if (!isValid) {
        if (mensajes.length === 0) {
            mensajes.push('Por favor, completa todos los campos obligatorios (marcados con *) antes de continuar.');
        }
        // Mostrar notificación moderna
        mostrarNotificacion(mensajes.join('<br>'), 'error');
    }
    
    return isValid;
}

function nextStep() {
    if (validateCurrentStep()) {
        if (currentStep < totalSteps) {
            currentStep++;
            updateStepVisibility();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            formulario.submit();
        }
    }
}

function prevStep() {
    if (currentStep > 1) {
        currentStep--;
        updateStepVisibility();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

nextBtn.addEventListener('click', nextStep);
prevBtn.addEventListener('click', prevStep);

// Inicializar
updateStepVisibility();