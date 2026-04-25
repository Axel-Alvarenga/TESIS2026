// ==================== LIMITAR A 2 SELECCIONES EN P9 ====================
const checkboxesP9 = document.querySelectorAll('input[name="p9_critica[]"]');
checkboxesP9.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const checked = document.querySelectorAll('input[name="p9_critica[]"]:checked');
        if (checked.length > 2) {
            this.checked = false;
            alert('Solo puedes seleccionar hasta 2 opciones');
        }
    });
});