// navegacion-pasos.js - Control de navegación entre bloques
const pages = document.querySelectorAll('.step-page');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const stepCounter = document.getElementById('stepCounter');
const stepProgressFill = document.getElementById('stepProgressFill');
const formulario = document.getElementById('encuestaForm');

let currentStep = 1;
const totalSteps = 12;

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
    
    // Validar campos obligatorios
    requiredFields.forEach(field => {
        // Saltar campos de texto "Otro" (se validan aparte)
        if (field.classList.contains('otro-input')) return;
        
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
    
    // Validación de permisos para menores
    const permisoDiv = document.getElementById('permisoMenores');
    const permisoCheckbox = document.getElementById('permisoPadres');
    if (permisoDiv && permisoDiv.style.display === 'block' && permisoCheckbox && !permisoCheckbox.checked) {
        isValid = false;
        mensajes.push('Debes marcar la casilla de autorización parental (eres menor de 18 años).');
    }
    
    // ========== VALIDACIÓN DE "OTRO" ==========
    // Buscar en el paso actual si hay algún campo "Otro" seleccionado
    const radiosOtro = currentPage.querySelectorAll('.radio-otro');
    let otroSeleccionado = false;
    let otroInputVacio = false;
    
    radiosOtro.forEach(radio => {
        if (radio.checked) {
            otroSeleccionado = true;
            // Buscar el campo de texto asociado
            const targetId = radio.dataset.target;
            if (targetId) {
                const textContainer = document.getElementById(targetId);
                if (textContainer) {
                    const input = textContainer.querySelector('.otro-input');
                    if (input && input.value.trim() === '') {
                        otroInputVacio = true;
                        input.classList.add('error');
                    } else if (input) {
                        input.classList.remove('error');
                    }
                }
            }
        }
    });
    
    if (otroSeleccionado && otroInputVacio) {
        isValid = false;
        mensajes.push('Debes especificar tu respuesta en "Otro".');
    }
    
    // ========== MOSTRAR ERRORES ==========
    if (!isValid) {
        if (mensajes.length === 0) {
            mensajes.push('Por favor, completa todos los campos obligatorios (marcados con *) antes de continuar.');
        }
        // Mostrar notificación moderna
        if (typeof mostrarNotificacion !== 'undefined') {
            mostrarNotificacion(mensajes.join('<br>'), 'error');
        } else {
            alert(mensajes.join('\n'));
        }
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