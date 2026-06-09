// selector-parroquia.js - Selector moderno de parroquias con búsqueda
// Ignora acentos y mayúsculas/minúsculas

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
    "San Cayetano", "San Juan Bautista - Itapua Poty", "San Antonio de Padua - Carlos A. López",
    "No frecuento ninguna comunidad"
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

// ==================== FUNCIÓN PARA NORMALIZAR TEXTO (SIN ACENTOS) ====================
function normalizarTexto(texto) {
    return texto
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .replace(/ñ/g, "n")
        .replace(/ü/g, "u");
}

function renderizarOpciones() {
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
    selectedParroquiaText.textContent = parroquia;
    selectedParroquiaText.classList.remove('placeholder');
    parroquiaHidden.value = parroquia;
    if (parroquiaError) parroquiaError.style.display = 'none';
    if (selectorInput) selectorInput.classList.remove('error');
    cerrarDropdown();
    document.querySelectorAll('.parroquia-option').forEach(opt => {
        opt.classList.remove('selected');
        if (opt.textContent === parroquia) opt.classList.add('selected');
    });
}

function abrirDropdown() { 
    if (selectorDropdown) selectorDropdown.classList.add('show'); 
    if (selectorInput) selectorInput.classList.add('active'); 
    setTimeout(() => parroquiaSearch?.focus(), 50); 
}

function cerrarDropdown() { 
    if (selectorDropdown) selectorDropdown.classList.remove('show'); 
    if (selectorInput) selectorInput.classList.remove('active'); 
}

function toggleParroquiaDropdown() { 
    if (selectorDropdown.classList.contains('show')) cerrarDropdown(); 
    else abrirDropdown(); 
}

// ==================== BÚSQUEDA CON IGNORAR ACENTOS Y MAYÚSCULAS ====================
function filtrarParroquias() {
    const searchText = parroquiaSearch.value.trim();
    const searchNormalizado = normalizarTexto(searchText);
    
    if (searchText === '') {
        filteredParroquias = [...parroquiasList];
    } else {
        filteredParroquias = parroquiasList.filter(parroquia => {
            const parroquiaNormalizada = normalizarTexto(parroquia);
            return parroquiaNormalizada.includes(searchNormalizado);
        });
    }
    
    renderizarOpciones();
}

document.addEventListener('click', (e) => { 
    const selector = document.querySelector('.parroquia-selector'); 
    if (selector && !selector.contains(e.target)) cerrarDropdown(); 
});

if (parroquiaSearch) parroquiaSearch.addEventListener('input', filtrarParroquias);
if (selectorInput) selectorInput.addEventListener('click', (e) => { e.stopPropagation(); toggleParroquiaDropdown(); });

renderizarOpciones();