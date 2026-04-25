// ==================== FORMULARIO POR PASOS ====================
const pages = document.querySelectorAll('.step-page');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const stepCounter = document.getElementById('stepCounter');
const stepProgressFill = document.getElementById('stepProgressFill');
const formulario = document.getElementById('encuestaForm');

let currentStep = 1;
const totalSteps = pages.length;

function updateStepVisibility() {
    pages.forEach((page, index) => {
        if (index + 1 === currentStep) page.classList.add('active');
        else page.classList.remove('active');
    });
    
    if (stepCounter) stepCounter.textContent = `Bloque ${currentStep} de ${totalSteps}`;
    if (stepProgressFill) stepProgressFill.style.width = `${(currentStep / totalSteps) * 100}%`;
    if (prevBtn) prevBtn.style.visibility = currentStep === 1 ? 'hidden' : 'visible';
    if (nextBtn) nextBtn.textContent = currentStep === totalSteps ? 'Enviar respuestas ✓' : 'Siguiente →';
}

function validateCurrentStep() {
    const currentPage = document.querySelector(`.step-page[data-step="${currentStep}"]`);
    if (!currentPage) return true;
    
    const requiredFields = currentPage.querySelectorAll('[required]');
    let isValid = true;
    
    // Validar parroquia en paso 1
    if (currentStep === 1 && window.selectedParroquia !== undefined && window.selectedParroquia === '') {
        isValid = false;
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
            if (!Array.from(radioGroup).some(r => r.checked)) {
                isValid = false;
                field.classList.add('error');
            } else field.classList.remove('error');
        } else if (field.type === 'checkbox' && field.required && !field.checked) {
            isValid = false;
            field.classList.add('error');
        } else if ((field.value === '' || field.value === null) && field.type !== 'checkbox') {
            isValid = false;
            field.classList.add('error');
        } else field.classList.remove('error');
    });
    
    const permisoDiv = document.getElementById('permisoMenores');
    const permisoCheckbox = document.getElementById('permisoPadres');
    if (permisoDiv && permisoDiv.style.display === 'block' && permisoCheckbox && !permisoCheckbox.checked) {
        isValid = false;
        alert('⚠️ Debes marcar la casilla de autorización parental (eres menor de 18 años).');
    }
    
    if (!isValid) alert('Por favor, completa todos los campos obligatorios (marcados con *) antes de continuar.');
    return isValid;
}

function nextStep() {
    if (validateCurrentStep()) {
        if (currentStep < totalSteps) {
            currentStep++;
            updateStepVisibility();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else if (formulario) {
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

if (nextBtn) nextBtn.addEventListener('click', nextStep);
if (prevBtn) prevBtn.addEventListener('click', prevStep);

// Inicializar
updateStepVisibility();