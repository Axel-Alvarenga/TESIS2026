// ==================== CONTROL DE CONSENTIMIENTO ====================
const consentCheckbox = document.getElementById('consentCheckbox');
const startBtn = document.getElementById('startBtn');
const welcomeCard = document.getElementById('welcomeCard');
const surveyForm = document.getElementById('surveyForm');

if (consentCheckbox && startBtn) {
    consentCheckbox.addEventListener('change', function() {
        startBtn.disabled = !this.checked;
    });
}

if (startBtn) {
    startBtn.addEventListener('click', function() {
        if (welcomeCard && surveyForm) {
            welcomeCard.style.display = 'none';
            surveyForm.style.display = 'block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
}