/**
 * cruces.js - Tabla Dinámica con mejoras
 * - Cruces predefinidos pastorales
 * - Análisis de sentimiento
 * - Insights automáticos
 */

// ==================== VARIABLES GLOBALES ====================
let datosOriginales = [];
let filtros = [];

const ordenVariables = [
    'p1_anio', 'p2_parroquia', 'p3_pertenencia', 'p4_atraccion', 
    'p5_espiritualidad', 'p6_familia', 'p7_proyecto', 'p8_vocacion', 
    'p10_esperanza', 'sentimiento', 'p9_critica_1'
];

const nombresVariables = {
    p1_anio: 'Año de nacimiento',
    p2_parroquia: 'Parroquia',
    p3_pertenencia: 'P3 - Pertenencia',
    p4_atraccion: 'P4 - Atracción',
    p5_espiritualidad: 'P5 - Espiritualidad',
    p6_familia: 'P6 - Familia',
    p7_proyecto: 'P7 - Proyecto de vida',
    p8_vocacion: 'P8 - Vocación',
    p10_esperanza: 'P10 - Esperanza',
    sentimiento: '😊 Sentimiento (NLP)',
    p9_critica_1: 'P9 - Crítica principal'
};

const etiquetas = {
    p3_pertenencia: { 
        'A': 'A. Amigos/confianza', 'B': 'B. Eucaristía', 'C': 'C. Ayudar', 
        'D': 'D. Redes sociales', 'E': 'E. Naturaleza', 'F': 'F. Deporte', 
        'G': 'G. Silencio', 'H': 'H. No recuerdo' 
    },
    p4_atraccion: { 
        'A': 'A. Vínculos confianza', 'B': 'B. Silencio', 'C': 'C. Liderazgo', 
        'D': 'D. Habilidades técnicas', 'E': 'E. Emprendimiento', 'F': 'F. Sin juicios', 
        'G': 'G. Cambio real' 
    },
    p5_espiritualidad: { 
        'A': 'A. Fe como referencia', 'B': 'B. A veces fe', 
        'C': 'C. Otros ámbitos', 'D': 'D. No me pregunto' 
    },
    p6_familia: { 
        'A': 'A. Apoyo y refugio', 'B': 'B. Tensiones', 
        'C': 'C. No me entienden', 'D': 'D. Motivación', 'E': 'E. Sin referencia' 
    },
    p7_proyecto: { 
        'A': 'A. Estabilidad económica', 'B': 'B. Formar familia', 
        'C': 'C. Impacto social', 'D': 'D. Paz interior', 'E': 'E. En proceso' 
    },
    p8_vocacion: { 
        'A': 'A. Misión clara', 'B': 'B. Miedo a equivocarme', 
        'C': 'C. Presión social', 'D': 'D. Plan de Dios', 'E': 'E. No lo pienso' 
    },
    p10_esperanza: { 
        '1': '1 - Muy bajo', '2': '2 - Bajo', '3': '3 - Medio', 
        '4': '4 - Alto', '5': '5 - Muy alto' 
    },
    sentimiento: {
        'positivo': '😊 Positivo', 'negativo': '😞 Negativo', 'neutral': '😐 Neutral'
    }
};

// ==================== CRUCES PREDEFINIDOS PASTORALES ====================
function crucePredefinido(tipo) {
    const presets = {
        'esperanza_vs_edad': { filas: ['p10_esperanza'], columnas: ['p1_anio'], mensaje: '📊 ¿Los jóvenes de diferentes edades tienen niveles distintos de esperanza?' },
        'sentimiento_vs_parroquia': { filas: ['sentimiento'], columnas: ['p2_parroquia'], mensaje: '😊 ¿En qué parroquias hay más jóvenes con sentimiento negativo?' },
        'vocacion_vs_familia': { filas: ['p8_vocacion'], columnas: ['p6_familia'], mensaje: '🏠 ¿Los que tienen apoyo familiar tienen más clara su vocación?' },
        'critica_vs_edad': { filas: ['p9_critica_1'], columnas: ['p1_anio'], mensaje: '🗣️ ¿Qué críticas predominan según la edad?' },
        'pertenencia_vs_edad': { filas: ['p3_pertenencia'], columnas: ['p1_anio'], mensaje: '🤝 ¿Los jóvenes se sienten parte de algo según su edad?' },
        'esperanza_vs_parroquia': { filas: ['p10_esperanza'], columnas: ['p2_parroquia'], mensaje: '🏛️ ¿Qué parroquias tienen la esperanza más baja?' }
    };
    
    const preset = presets[tipo];
    if (!preset) return;
    
    // Limpiar selecciones actuales
    document.querySelectorAll('.chk-fila, .chk-columna').forEach(cb => cb.checked = false);
    
    // Marcar las nuevas
    preset.filas.forEach(f => {
        const cb = document.querySelector(`.chk-fila[value="${f}"]`);
        if (cb) {
            cb.checked = true;
            cb.dispatchEvent(new Event('change'));
        }
    });
    preset.columnas.forEach(c => {
        const cb = document.querySelector(`.chk-columna[value="${c}"]`);
        if (cb) {
            cb.checked = true;
            cb.dispatchEvent(new Event('change'));
        }
    });
    
    // Mostrar mensaje informativo
    const infoDiv = document.createElement('div');
    infoDiv.className = 'mensaje-info';
    infoDiv.innerHTML = `<i class="fas fa-info-circle"></i> ${preset.mensaje}`;
    infoDiv.style.cssText = 'background:#e6f7ff; padding:10px; border-radius:12px; margin:10px 0; font-size:13px;';
    document.querySelector('.selector-panel').insertBefore(infoDiv, document.querySelector('.filtros-extra'));
    setTimeout(() => infoDiv.remove(), 5000);
    
    // Generar tablas
    setTimeout(() => generarTablas(), 100);
}

// ==================== SELECTORES ====================
function cargarSelectores() {
    const containerFila = document.getElementById('selectoresFila');
    const containerColumna = document.getElementById('selectoresColumna');
    
    containerFila.innerHTML = '';
    containerColumna.innerHTML = '';
    
    for (const varId of ordenVariables) {
        if (!nombresVariables[varId]) continue;
        const nombre = nombresVariables[varId];
        
        const labelFila = document.createElement('label');
        labelFila.innerHTML = `<input type="checkbox" value="${varId}" class="chk-fila"> ${nombre}`;
        containerFila.appendChild(labelFila);
        
        const labelCol = document.createElement('label');
        labelCol.innerHTML = `<input type="checkbox" value="${varId}" class="chk-columna"> ${nombre}`;
        containerColumna.appendChild(labelCol);
    }
    
    // Mutual exclusion
    document.querySelectorAll('.chk-fila').forEach(cb => {
        cb.addEventListener('change', function() {
            const valor = this.value;
            const colCheckbox = document.querySelector(`.chk-columna[value="${valor}"]`);
            if (colCheckbox && this.checked) {
                colCheckbox.disabled = true;
                colCheckbox.closest('label')?.classList.add('disabled');
            } else if (colCheckbox && !this.checked) {
                colCheckbox.disabled = false;
                colCheckbox.closest('label')?.classList.remove('disabled');
            }
        });
    });
    
    document.querySelectorAll('.chk-columna').forEach(cb => {
        cb.addEventListener('change', function() {
            const valor = this.value;
            const filaCheckbox = document.querySelector(`.chk-fila[value="${valor}"]`);
            if (filaCheckbox && this.checked) {
                filaCheckbox.disabled = true;
                filaCheckbox.closest('label')?.classList.add('disabled');
            } else if (filaCheckbox && !this.checked) {
                filaCheckbox.disabled = false;
                filaCheckbox.closest('label')?.classList.remove('disabled');
            }
        });
    });
}

// ==================== DATOS ====================
async function cargarDatos() {
    const fd = document.getElementById('fecha_desde').value;
    const fh = document.getElementById('fecha_hasta').value;
    try {
        const res = await fetch(`../ajax_datos_completos.php?fecha_desde=${fd}&fecha_hasta=${fh}`);
        datosOriginales = await res.json();
        return datosOriginales;
    } catch (error) {
        console.error('Error al cargar datos:', error);
        return [];
    }
}

function aplicarFiltros(datos) {
    let filtrados = [...datos];
    for (const f of filtros) {
        if (f.valor) {
            filtrados = filtrados.filter(row => String(row[f.campo]) === f.valor);
        }
    }
    return filtrados;
}

function obtenerValoresUnicos(datos, variable) {
    const valores = new Set();
    for (const row of datos) {
        if (row[variable] && row[variable] !== '') valores.add(row[variable]);
    }
    let arr = Array.from(valores);
    if (variable === 'p10_esperanza') arr.sort((a, b) => parseInt(a) - parseInt(b));
    if (variable === 'p1_anio') arr.sort((a, b) => parseInt(a) - parseInt(b));
    if (variable === 'sentimiento') {
        const orden = ['positivo', 'neutral', 'negativo'];
        arr.sort((a, b) => orden.indexOf(a) - orden.indexOf(b));
    }
    return arr;
}

// ==================== GENERAR TABLAS (optimizado) ====================
function generarTablaUnica(datos, varFila, variablesColumna, titulo) {
    const valoresFila = obtenerValoresUnicos(datos, varFila);
    const columnasData = [];
    
    for (const varCol of variablesColumna) {
        const valores = obtenerValoresUnicos(datos, varCol);
        columnasData.push({ variable: varCol, valores });
    }
    
    let html = `<div class="tabla-individual" id="tabla_${varFila}"><h4>${titulo}</h4>`;
    html += '<div class="table-responsive"><table class="tabla-dinamica"><thead>';
    html += '<tr><th>' + (nombresVariables[varFila] || varFila) + '</th>';
    
    for (const col of columnasData) {
        for (const val of col.valores) {
            html += `<th>${obtenerEtiqueta(col.variable, val)}</th>`;
        }
    }
    html += '<th>Total</th></tr></thead><tbody>';
    
    let totalGeneral = 0;
    const counts = {};
    
    // Pre-calcular counts para optimizar
    for (const row of datos) {
        const filaVal = row[varFila];
        if (!filaVal) continue;
        for (const col of columnasData) {
            const colVal = row[col.variable];
            if (!colVal) continue;
            const key = `${filaVal}|${col.variable}|${colVal}`;
            counts[key] = (counts[key] || 0) + 1;
        }
    }
    
    for (const valorFila of valoresFila) {
        let rowHtml = `<tr><td style="text-align:left;">${obtenerEtiqueta(varFila, valorFila)}</td>`;
        let totalFila = 0;
        
        for (const col of columnasData) {
            for (const valorCol of col.valores) {
                const key = `${valorFila}|${col.variable}|${valorCol}`;
                const count = counts[key] || 0;
                totalFila += count;
                totalGeneral += count;
                rowHtml += `<td>${count}</td>`;
            }
        }
        rowHtml += `<td style="background:#f3f4f6; font-weight:500;">${totalFila}</td></tr>`;
        html += rowHtml;
    }
    
    let totalRow = '<tr class="total-row"><td style="font-weight:700;">Total</td>';
    for (const col of columnasData) {
        for (const valorCol of col.valores) {
            let totalCol = 0;
            for (const row of datos) {
                if (row[col.variable] === valorCol) totalCol++;
            }
            totalRow += `<td style="font-weight:600;">${totalCol}</td>`;
        }
    }
    totalRow += `<td style="font-weight:700;">${totalGeneral}</td></tr>`;
    html += totalRow;
    html += '</tbody></table></div></div>';
    
    return { html, totalGeneral };
}

// ==================== GENERAR INSIGHTS AUTOMÁTICOS ====================
function generarInsights(datos) {
    if (!datos.length) return '<p>No hay suficientes datos para generar insights</p>';
    
    const insights = [];
    const total = datos.length;
    
    // 1. Esperanza baja por parroquia
    const esperanzaBaja = datos.filter(r => r.p10_esperanza <= 2);
    if (esperanzaBaja.length > 0) {
        const parroquiasBaja = {};
        esperanzaBaja.forEach(r => {
            if (r.p2_parroquia) parroquiasBaja[r.p2_parroquia] = (parroquiasBaja[r.p2_parroquia] || 0) + 1;
        });
        const peorParroquia = Object.entries(parroquiasBaja).sort((a,b) => b[1] - a[1])[0];
        if (peorParroquia) {
            insights.push(`🔍 <strong>${peorParroquia[0]}</strong> es la parroquia con más jóvenes con esperanza baja (${peorParroquia[1]} respuestas)`);
        }
    }
    
    // 2. Sentimiento negativo por parroquia
    const sentimientoNegativo = datos.filter(r => r.sentimiento === 'negativo');
    if (sentimientoNegativo.length > 0) {
        const porcentaje = ((sentimientoNegativo.length / total) * 100).toFixed(1);
        insights.push(`😞 <strong>${porcentaje}%</strong> de los jóvenes expresan sentimiento negativo en sus comentarios`);
    }
    
    // 3. Jóvenes con miedo vocacional
    const miedoVocacional = datos.filter(r => r.p8_vocacion === 'B');
    if (miedoVocacional.length > 0) {
        const porcentaje = ((miedoVocacional.length / total) * 100).toFixed(1);
        insights.push(`🎯 <strong>${porcentaje}%</strong> de los jóvenes tienen miedo a equivocarse en su vocación`);
    }
    
    // 4. Crítica más común
    const criticas = {};
    datos.forEach(r => {
        if (r.p9_critica_1) criticas[r.p9_critica_1] = (criticas[r.p9_critica_1] || 0) + 1;
    });
    const criticaTop = Object.entries(criticas).sort((a,b) => b[1] - a[1])[0];
    if (criticaTop) {
        const letra = criticaTop[0];
        const textos = { 'A': 'Lenguaje anticuado', 'B': 'Falta de coherencia', 'C': 'No trata temas importantes', 'D': 'Lugar de reglas', 'E': 'Adultos no escuchan', 'F': 'Malas experiencias', 'G': 'No me siento alejado' };
        insights.push(`🗣️ La crítica más mencionada es: <strong>${textos[letra] || letra}</strong>`);
    }
    
    // 5. Esperanza promedio general
    const esperanzaPromedio = (datos.reduce((sum, r) => sum + (parseInt(r.p10_esperanza) || 0), 0) / total).toFixed(1);
    insights.push(`📈 El nivel de esperanza promedio es de <strong>${esperanzaPromedio}/5</strong>`);
    
    return `
        <div class="insights-card">
            <h4><i class="fas fa-lightbulb"></i> Insights pastorales automáticos</h4>
            <ul>
                ${insights.map(i => `<li>${i}</li>`).join('')}
            </ul>
            <small style="color:#718096;">Basado en ${total} respuestas analizadas</small>
        </div>
    `;
}

// ==================== GENERAR TABLAS PRINCIPAL ====================
async function generarTablas() {
    await cargarDatos();
    if (!datosOriginales.length) {
        document.getElementById('tablasResultado').innerHTML = '<div class="mensaje-error">No hay datos para el período seleccionado</div>';
        return;
    }
    
    const filasCheck = document.querySelectorAll('.chk-fila:checked');
    const columnasCheck = document.querySelectorAll('.chk-columna:checked');
    const variablesFila = Array.from(filasCheck).map(cb => cb.value);
    const variablesColumna = Array.from(columnasCheck).map(cb => cb.value);
    
    if (variablesFila.length === 0) {
        alert('Selecciona al menos una variable en filas');
        return;
    }
    if (variablesColumna.length === 0) {
        alert('Selecciona al menos una variable en columnas');
        return;
    }
    
    const datosFiltrados = aplicarFiltros(datosOriginales);
    
    let tablasHtml = '';
    for (const varFila of variablesFila) {
        const titulo = `📊 Cruce: ${nombresVariables[varFila]} vs columnas seleccionadas`;
        const resultado = generarTablaUnica(datosFiltrados, varFila, variablesColumna, titulo);
        tablasHtml += resultado.html;
    }
    
    // Agregar insights
    tablasHtml = generarInsights(datosFiltrados) + tablasHtml;
    
    document.getElementById('tablasResultado').innerHTML = tablasHtml;
    document.getElementById('resumenTabla').innerHTML = generarResumenHtml(datosFiltrados, variablesFila, variablesColumna);
}

function generarResumenHtml(datosFiltrados, variablesFila, variablesColumna) {
    const fechaDesde = document.getElementById('fecha_desde').value;
    const fechaHasta = document.getElementById('fecha_hasta').value;
    
    return `
        <div class="resumen-card" style="background:#f8fafc; padding:15px; border-radius:16px; margin-top:20px;">
            <strong>📋 Resumen:</strong>
            <div style="display:flex; flex-wrap:wrap; gap:15px; margin-top:10px;">
                <span>📄 ${datosFiltrados.length} registros</span>
                <span>📅 ${fechaDesde} al ${fechaHasta}</span>
                <span>📊 Filas: ${variablesFila.map(v => nombresVariables[v]).join(', ')}</span>
                <span>📊 Columnas: ${variablesColumna.map(v => nombresVariables[v]).join(', ')}</span>
                <span>🔍 Filtros: ${filtros.length}</span>
            </div>
        </div>
    `;
}

// ==================== EXPORTACIÓN ====================
function copiarTodasLasTablas() {
    let contenido = '';
    const resumenDiv = document.getElementById('resumenTabla');
    if (resumenDiv) contenido += resumenDiv.innerText + '\n\n';
    
    const tablas = document.querySelectorAll('#tablasResultado .tabla-individual');
    tablas.forEach((tabla) => {
        const titulo = tabla.querySelector('h4')?.innerText || 'Tabla';
        const tablaHtml = tabla.querySelector('.tabla-dinamica');
        if (tablaHtml) contenido += titulo + '\n' + tablaHtml.innerText + '\n\n';
    });
    
    navigator.clipboard.writeText(contenido).then(() => alert('Tablas copiadas al portapapeles'));
}

function exportarTodasLasTablas() {
    try {
        const wb = XLSX.utils.book_new();
        const datosHoja = [['VOCES DEL SUR - TABLA DINÁMICA'], ['Generado: ' + new Date().toLocaleString()], ['']];
        
        const tablas = document.querySelectorAll('#tablasResultado .tabla-individual');
        tablas.forEach((tabla, idx) => {
            datosHoja.push([''], ['=== ' + (tabla.querySelector('h4')?.innerText || `Tabla ${idx + 1}`) + ' ==='], ['']);
            const filas = tabla.querySelectorAll('tr');
            filas.forEach(fila => {
                const celdas = fila.querySelectorAll('th, td');
                datosHoja.push(Array.from(celdas).map(c => c.innerText.trim()));
            });
        });
        
        const ws = XLSX.utils.aoa_to_sheet(datosHoja);
        XLSX.utils.book_append_sheet(wb, ws, 'Cruces');
        XLSX.writeFile(wb, `cruces_${new Date().toISOString().slice(0, 19)}.xlsx`);
        alert('Exportación completada');
    } catch (error) {
        alert('Error al exportar: ' + error.message);
    }
}

// ==================== FILTROS ====================
function mostrarModalFiltros() {
    document.getElementById('modalFiltros').style.display = 'flex';
}

function cerrarModalFiltros() {
    document.getElementById('modalFiltros').style.display = 'none';
}

function agregarFiltro() {
    const campo = document.getElementById('filtroCampo').value;
    const valor = document.getElementById('filtroValor').value.trim();
    if (!valor) { alert('Ingresa un valor'); return; }
    filtros.push({ campo, valor });
    actualizarListaFiltros();
    cerrarModalFiltros();
    document.getElementById('filtroValor').value = '';
}

function eliminarFiltro(i) {
    filtros.splice(i, 1);
    actualizarListaFiltros();
}

function actualizarListaFiltros() {
    const c = document.getElementById('filtrosLista');
    if (filtros.length === 0) {
        c.innerHTML = '<span>No hay filtros activos</span>';
        return;
    }
    c.innerHTML = '';
    filtros.forEach((f, i) => {
        c.innerHTML += `<div class="filtro-tag">${f.campo} = "${f.valor}" <button onclick="eliminarFiltro(${i})">✕</button></div>`;
    });
}

function obtenerEtiqueta(variable, valor) {
    if (!valor || valor === 'null') return '-';
    if (etiquetas[variable] && etiquetas[variable][valor]) return etiquetas[variable][valor];
    if (variable === 'p1_anio') return `${valor} (${2026 - parseInt(valor)}a)`;
    if (variable === 'p2_parroquia') return valor.length > 25 ? valor.substring(0, 22) + '...' : valor;
    if (variable === 'sentimiento' && etiquetas.sentimiento[valor]) return etiquetas.sentimiento[valor];
    return valor;
}

// ==================== INICIALIZACIÓN ====================
document.addEventListener('DOMContentLoaded', function() {
    cargarSelectores();
    cargarDatos();
    
    // Selección por defecto
    setTimeout(() => {
        const p3Fila = document.querySelector('.chk-fila[value="p3_pertenencia"]');
        const sentimientoCol = document.querySelector('.chk-columna[value="sentimiento"]');
        if (p3Fila) p3Fila.checked = true;
        if (sentimientoCol) sentimientoCol.checked = true;
        if (p3Fila) p3Fila.dispatchEvent(new Event('change'));
        if (sentimientoCol) sentimientoCol.dispatchEvent(new Event('change'));
        generarTablas();
    }, 100);
});