// ==================== SELECTOR MODERNO DE PARROQUIAS ====================
const parroquiasList = [
    "Nuestra Señora de la Santísima Encarnación", "Inmaculada Concepción de María",
    "San Roque González de Santa Cruz", "San Pedro Apóstol - Encarnación",
    "San Francisco de Asís", "Sagrado Corazón de Jesús - Cambyreta",
    "Santísimo Nombre de María", "Presentación de María en el Templo",
    "San Juan Bautista - San Juan del Paraná", "San Miguel Arcángel - Santuario Itacuá",
    "San Isidro Labrador", "Nuestra Señora del Carmen", "Espíritu Santo - Fram",
    "Santa Cruz", "San Luis Gonzága", "Santos Cosme y Damián", "Virgen del Rosario",
    "San Pedro Apóstol - San Pedro del Paraná", "Virgen de Lourdes",
    "San José Obrero - Cap. Miranda", "María Reina de la Paz", "Niño Jesús",
    "Cuasi Parroquia Santísima Trinidad", "Espíritu Santo - Hohenau", "Cristo Rey",
    "26 Santos Mártires del Japón", "Sagrado Corazón de Jesús - Caronay",
    "San Cristóbal", "San Juan Bautista - Yatytay", "Virgen de Fátima",
    "María Auxiliadora", "Inmaculado Corazón de María", "San José Obrero - Edelira",
    "San Antonio de Padua - Cap. Meza", "San Martín de Tours", "San José Obrero - Naranjito",
    "San Cayetano", "San Juan Bautista - Itapua Poty", "San Antonio de Padua - Carlos A. López"
];

let selectedParroquia = '';
let filteredParroquias = [...parroquiasList];

const selectorInput = document.getElementById('selectorInput');
const selectorDropdown = document.getElementById('selectorDropdown');
const selectedParroquiaText = document.getElementById('selectedParroquiaText');
const parroquiaSearch = document.getElementById('parroquiaSearch');
const parroquiaOptions = document.getElementById('parroquiaOptions');
const parroquiaHidden = document.getElementById('parroquiaHidden');
const parroquiaError = document.getElementById('parroquiaError');

// Variable global para validación en navegacion-pasos.js
window.selectedParroquia = selectedParroquia;

function renderizarOpciones() {
    if (!parroquiaOptions) return;
    parroquiaOptions.innerHTML = '';
    
    if (filteredParroquias.length === 0) {
        parroquiaOptions.innerHTML = '<div class="no-results">❌ No se encontraron parroquias</div>';
        return;
    }
    
    filteredParroquias.forEach(parroquia => {
        const option = document.createElement('div');
        option.className = 'parroquia-option';
        if (parroquia === selectedParroquia) option.classList.add('selected');
        option.textContent = parroquia;
        option.onclick = () => seleccionarParroquia(parroquia);
        parroquiaOptions.appendChild(option);
    });
}

function seleccionarParroquia(parroquia) {
    selectedParroquia = parroquia;
    window.selectedParroquia = parroquia;
    if (selectedParroquiaText) {
        selectedParroquiaText.textContent = parroquia;
        selectedParroquiaText.classList.remove('placeholder');
    }
    if (parroquiaHidden) parroquiaHidden.value = parroquia;
    if (parroquiaError) parroquiaError.style.display = 'none';
    if (selectorInput) selectorInput.classList.remove('error');
    cerrarDropdown();
    
    const allOptions = document.querySelectorAll('.parroquia-option');
    allOptions.forEach(opt => {
        opt.classList.remove('selected');
        if (opt.textContent === parroquia) opt.classList.add('selected');
    });
}

function abrirDropdown() {
    if (selectorDropdown) selectorDropdown.classList.add('show');
    if (selectorInput) selectorInput.classList.add('active');
    setTimeout(() => { if (parroquiaSearch) parroquiaSearch.focus(); }, 50);
}

function cerrarDropdown() {
    if (selectorDropdown) selectorDropdown.classList.remove('show');
    if (selectorInput) selectorInput.classList.remove('active');
}

function toggleParroquiaDropdown() {
    if (selectorDropdown && selectorDropdown.classList.contains('show')) {
        cerrarDropdown();
    } else {
        abrirDropdown();
    }
}

function filtrarParroquias() {
    if (!parroquiaSearch) return;
    const searchText = parroquiaSearch.value.toLowerCase().trim();
    filteredParroquias = searchText === '' ? [...parroquiasList] : parroquiasList.filter(p => p.toLowerCase().includes(searchText));
    renderizarOpciones();
}

// Cerrar dropdown al hacer clic fuera
document.addEventListener('click', function(e) {
    const selector = document.querySelector('.parroquia-selector');
    if (selector && !selector.contains(e.target)) {
        cerrarDropdown();
    }
});

function validarParroquia() {
    if (selectedParroquia === '') {
        if (parroquiaError) parroquiaError.style.display = 'block';
        if (selectorInput) selectorInput.classList.add('error');
        return false;
    } else {
        if (parroquiaError) parroquiaError.style.display = 'none';
        if (selectorInput) selectorInput.classList.remove('error');
        return true;
    }
}

// Eventos
if (parroquiaSearch) parroquiaSearch.addEventListener('input', filtrarParroquias);
if (selectorInput) {
    selectorInput.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleParroquiaDropdown();
    });
}

// Validación antes de enviar
const formularioEnv = document.getElementById('encuestaForm');
if (formularioEnv) {
    formularioEnv.addEventListener('submit', function(e) {
        if (!validarParroquia()) {
            e.preventDefault();
            alert('⚠️ Por favor, selecciona una parroquia válida.');
            return false;
        }
    });
}

// Inicializar
renderizarOpciones();