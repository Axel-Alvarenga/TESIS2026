// ==================== CONTROL DE PERMISO PARA MENORES ====================
const anioSelect = document.getElementById('anioNacimiento');
const permisoDiv = document.getElementById('permisoMenores');
const permisoCheckbox = document.getElementById('permisoPadres');

function verificarEdadYPermiso() {
    if (!anioSelect) return;
    
    const anioSeleccionado = anioSelect.value;
    const anioActual = 2026;
    let esMenor = false;
    
    if (anioSeleccionado && !isNaN(anioSeleccionado) && anioSeleccionado.length === 4) {
        const edad = anioActual - parseInt(anioSeleccionado);
        if (edad < 18 && edad > 0) esMenor = true;
    }
    if (anioSeleccionado === 'despues_2011') esMenor = true;
    
    if (permisoDiv && permisoCheckbox) {
        if (esMenor) {
            permisoDiv.style.display = 'block';
            permisoCheckbox.required = true;
        } else {
            permisoDiv.style.display = 'none';
            permisoCheckbox.required = false;
            permisoCheckbox.checked = false;
        }
    }
}

if (anioSelect) {
    anioSelect.addEventListener('change', verificarEdadYPermiso);
}