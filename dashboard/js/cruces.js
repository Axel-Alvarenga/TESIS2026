/**
 * cruces.js - Funciones para la tabla dinamica
 */

// ==================== VARIABLES GLOBALES ====================
let datosOriginales = [];
let filtros = [];

const ordenVariables = ['p1_anio', 'p2_parroquia', 'p3_pertenencia', 'p4_atraccion', 'p5_espiritualidad', 'p6_familia', 'p7_proyecto', 'p8_vocacion', 'p10_esperanza'];

const nombresVariables = {
    p1_anio: 'Ano de nacimiento',
    p2_parroquia: 'Parroquia',
    p3_pertenencia: 'P3 - Pertenencia',
    p4_atraccion: 'P4 - Atraccion',
    p5_espiritualidad: 'P5 - Espiritualidad',
    p6_familia: 'P6 - Familia',
    p7_proyecto: 'P7 - Proyecto de vida',
    p8_vocacion: 'P8 - Vocacion',
    p10_esperanza: 'P10 - Esperanza'
};

const etiquetas = {
    p3_pertenencia: { 'A': 'A. Amigos/confianza', 'B': 'B. Eucaristia', 'C': 'C. Ayudar', 'D': 'D. Redes sociales', 'E': 'E. Naturaleza', 'F': 'F. Deporte', 'G': 'G. Silencio', 'H': 'H. No recuerdo' },
    p4_atraccion: { 'A': 'A. Vinculos confianza', 'B': 'B. Silencio', 'C': 'C. Liderazgo', 'D': 'D. Habilidades tecnicas', 'E': 'E. Emprendimiento', 'F': 'F. Sin juicios', 'G': 'G. Cambio real' },
    p5_espiritualidad: { 'A': 'A. Fe como referencia', 'B': 'B. A veces fe', 'C': 'C. Otros ambitos', 'D': 'D. No me pregunto' },
    p6_familia: { 'A': 'A. Apoyo y refugio', 'B': 'B. Tensiones', 'C': 'C. No me entienden', 'D': 'D. Motivacion', 'E': 'E. Sin referencia' },
    p7_proyecto: { 'A': 'A. Estabilidad economica', 'B': 'B. Formar familia', 'C': 'C. Impacto social', 'D': 'D. Paz interior', 'E': 'E. En proceso' },
    p8_vocacion: { 'A': 'A. Mision clara', 'B': 'B. Miedo a equivocarme', 'C': 'C. Presion social', 'D': 'D. Plan de Dios', 'E': 'E. No lo pienso' },
    p10_esperanza: { '1': '1 - Muy bajo', '2': '2 - Bajo', '3': '3 - Medio', '4': '4 - Alto', '5': '5 - Muy alto' }
};

let resumenActual = {};

// ==================== FUNCIONES AUXILIARES ====================
function obtenerEtiqueta(variable, valor) {
    if (!valor || valor === 'null') return '-';
    if (etiquetas[variable] && etiquetas[variable][valor]) return etiquetas[variable][valor];
    if (variable === 'p1_anio') return `${valor} (${2026 - parseInt(valor)}a)`;
    if (variable === 'p2_parroquia') return valor.length > 25 ? valor.substring(0, 22) + '...' : valor;
    return valor;
}

// ==================== SELECTORES ====================
function cargarSelectores() {
    const containerFila = document.getElementById('selectoresFila');
    const containerColumna = document.getElementById('selectoresColumna');
    
    containerFila.innerHTML = '';
    containerColumna.innerHTML = '';
    
    for (const varId of ordenVariables) {
        const nombre = nombresVariables[varId];
        
        const labelFila = document.createElement('label');
        labelFila.innerHTML = `<input type="checkbox" value="${varId}" class="chk-fila"> ${nombre}`;
        containerFila.appendChild(labelFila);
        
        const labelCol = document.createElement('label');
        labelCol.innerHTML = `<input type="checkbox" value="${varId}" class="chk-columna"> ${nombre}`;
        containerColumna.appendChild(labelCol);
    }
    
    document.querySelectorAll('.chk-fila').forEach(cb => {
        cb.addEventListener('change', function() {
            const valor = this.value;
            const colCheckbox = document.querySelector(`.chk-columna[value="${valor}"]`);
            if (colCheckbox && this.checked) {
                colCheckbox.disabled = true;
                colCheckbox.closest('label').classList.add('disabled');
            } else if (colCheckbox && !this.checked) {
                colCheckbox.disabled = false;
                colCheckbox.closest('label').classList.remove('disabled');
            }
        });
    });
    
    document.querySelectorAll('.chk-columna').forEach(cb => {
        cb.addEventListener('change', function() {
            const valor = this.value;
            const filaCheckbox = document.querySelector(`.chk-fila[value="${valor}"]`);
            if (filaCheckbox && this.checked) {
                filaCheckbox.disabled = true;
                filaCheckbox.closest('label').classList.add('disabled');
            } else if (filaCheckbox && !this.checked) {
                filaCheckbox.disabled = false;
                filaCheckbox.closest('label').classList.remove('disabled');
            }
        });
    });
}

// ==================== DATOS ====================
async function cargarDatos() {
    const fd = document.getElementById('fecha_desde').value;
    const fh = document.getElementById('fecha_hasta').value;
    const res = await fetch(`ajax_datos_completos.php?fecha_desde=${fd}&fecha_hasta=${fh}`);
    datosOriginales = await res.json();
    return datosOriginales;
}

function aplicarFiltros(datos) {
    let filtrados = [...datos];
    for (const f of filtros) {
        if (f.valor) filtrados = filtrados.filter(row => String(row[f.campo]) === f.valor);
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
    return arr;
}

// ==================== GENERAR TABLAS ====================
function generarTablaUnica(datos, varFila, variablesColumna, titulo) {
    const valoresFila = obtenerValoresUnicos(datos, varFila);
    const columnasData = [];
    
    for (const varCol of variablesColumna) {
        const valores = obtenerValoresUnicos(datos, varCol);
        columnasData.push({ variable: varCol, valores });
    }
    
    let html = `<div class="tabla-individual" id="tabla_${varFila}"><h4>${titulo}</h4>`;
    html += '<table class="tabla-dinamica"><thead><tr>';
    html += '<th>' + nombresVariables[varFila] + '</th>';
    
    for (const col of columnasData) {
        for (const val of col.valores) {
            html += `<th>${obtenerEtiqueta(col.variable, val)}</th>`;
        }
    }
    html += '<th>Total</th></tr></thead><tbody>';
    
    let totalGeneral = 0;
    
    for (const valorFila of valoresFila) {
        let rowHtml = `<tr><td style="text-align:left;">${obtenerEtiqueta(varFila, valorFila)}</td>`;
        let totalFila = 0;
        
        for (const col of columnasData) {
            for (const valorCol of col.valores) {
                let count = 0;
                for (const row of datos) {
                    if (row[varFila] === valorFila && row[col.variable] === valorCol) count++;
                }
                totalFila += count;
                totalGeneral += count;
                rowHtml += `<td>${count}</td>`;
            }
        }
        rowHtml += `<td style="background:#f3f4f6; font-weight:500;">${totalFila}</td>`;
        rowHtml += `</tr>`;
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
    totalRow += `<td style="font-weight:700;">${totalGeneral}</td>`;
    totalRow += '</tr>';
    html += totalRow;
    html += '</tbody></table></div>';
    
    return { html, totalGeneral };
}

function generarResumenHtml(datosFiltrados, variablesFila, variablesColumna) {
    const fechaDesde = document.getElementById('fecha_desde').value;
    const fechaHasta = document.getElementById('fecha_hasta').value;
    
    return `
        <div><strong>Total registros:</strong> ${datosFiltrados.length}</div>
        <div><strong>Periodo:</strong> ${fechaDesde} al ${fechaHasta}</div>
        <div><strong>Variables en filas:</strong> ${variablesFila.length} (${variablesFila.map(v => nombresVariables[v]).join(', ')})</div>
        <div><strong>Variables en columnas:</strong> ${variablesColumna.length} (${variablesColumna.map(v => nombresVariables[v]).join(', ')})</div>
        <div><strong>Filtros activos:</strong> ${filtros.length}</div>
    `;
}

async function generarTablas() {
    await cargarDatos();
    
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
        const titulo = `Cruce: ${nombresVariables[varFila]} vs columnas seleccionadas`;
        const resultado = generarTablaUnica(datosFiltrados, varFila, variablesColumna, titulo);
        tablasHtml += resultado.html;
    }
    
    document.getElementById('tablasResultado').innerHTML = tablasHtml;
    document.getElementById('resumenTabla').innerHTML = generarResumenHtml(datosFiltrados, variablesFila, variablesColumna);
    
    resumenActual = {
        datosFiltrados: datosFiltrados.length,
        fechaDesde: document.getElementById('fecha_desde').value,
        fechaHasta: document.getElementById('fecha_hasta').value,
        variablesFila: variablesFila,
        variablesColumna: variablesColumna,
        filtrosActivos: filtros.length
    };
}

// ==================== EXPORTACION ====================
function copiarTodasLasTablas() {
    let contenido = '';
    
    const resumenDiv = document.getElementById('resumenTabla');
    if (resumenDiv) {
        contenido += 'RESUMEN DE EXPORTACION\n';
        contenido += resumenDiv.innerText + '\n\n';
    }
    
    const tablas = document.querySelectorAll('#tablasResultado .tabla-individual');
    tablas.forEach((tabla) => {
        const titulo = tabla.querySelector('h4')?.innerText || 'Tabla';
        const tablaHtml = tabla.querySelector('.tabla-dinamica');
        if (tablaHtml) {
            contenido += titulo + '\n';
            contenido += tablaHtml.innerText + '\n\n';
        }
    });
    
    navigator.clipboard.writeText(contenido).then(() => {
        alert('Tablas y resumen copiados al portapapeles');
    }).catch(() => {
        alert('Error al copiar. Intenta manualmente.');
    });
}

function exportarTodasLasTablas() {
    try {
        const wb = XLSX.utils.book_new();
        let datosHoja = [];
        
        // Encabezado principal
        datosHoja.push(['VOCES DEL SUR']);
        datosHoja.push(['Tabla Dinamica de Cruces']);
        datosHoja.push(['']);
        datosHoja.push(['FECHA DE EXPORTACION: ' + new Date().toLocaleString()]);
        datosHoja.push(['']);
        datosHoja.push(['']);
        
        // Filtros aplicados
        const fechaDesde = document.getElementById('fecha_desde').value;
        const fechaHasta = document.getElementById('fecha_hasta').value;
        
        datosHoja.push(['=== FILTROS APLICADOS ===']);
        datosHoja.push(['Periodo:', fechaDesde + ' al ' + fechaHasta]);
        datosHoja.push(['Total registros:', resumenActual.datosFiltrados || datosOriginales.length]);
        datosHoja.push(['Variables en filas:', (resumenActual.variablesFila || []).map(v => nombresVariables[v]).join(', ') || 'Ninguna']);
        datosHoja.push(['Variables en columnas:', (resumenActual.variablesColumna || []).map(v => nombresVariables[v]).join(', ') || 'Ninguna']);
        datosHoja.push(['Filtros adicionales:', filtros.length > 0 ? filtros.map(f => `${f.campo}=${f.valor}`).join(', ') : 'Ninguno']);
        datosHoja.push(['']);
        datosHoja.push(['']);
        
        // Tablas
        const tablas = document.querySelectorAll('#tablasResultado .tabla-individual');
        
        tablas.forEach((tabla, idx) => {
            const titulo = tabla.querySelector('h4')?.innerText || `Tabla ${idx + 1}`;
            const tablaHtml = tabla.querySelector('.tabla-dinamica');
            
            if (tablaHtml) {
                // Separador
                datosHoja.push(['--------------------------------------------------']);
                datosHoja.push([titulo]);
                datosHoja.push(['--------------------------------------------------']);
                datosHoja.push([]);
                
                const filas = tablaHtml.querySelectorAll('tr');
                filas.forEach(fila => {
                    const celdas = fila.querySelectorAll('th, td');
                    const filaDatos = [];
                    celdas.forEach(celda => {
                        let texto = celda.innerText.trim();
                        texto = texto.replace(/[\\/?*[\]:]/g, '');
                        filaDatos.push(texto);
                    });
                    datosHoja.push(filaDatos);
                });
                
                datosHoja.push([]);
                datosHoja.push([]);
                datosHoja.push([]);
            }
        });
        
        datosHoja.push(['']);
        datosHoja.push(['']);
        datosHoja.push(['=== FIN DEL REPORTE ===']);
        datosHoja.push(['Generado por el sistema Voces del Sur']);
        
        const ws = XLSX.utils.aoa_to_sheet(datosHoja);
        
        const colWidths = [];
        if (datosHoja.length > 0) {
            const maxCols = Math.max(...datosHoja.map(row => row.length));
            for (let i = 0; i < maxCols; i++) {
                let maxWidth = 20;
                for (let j = 0; j < datosHoja.length; j++) {
                    if (datosHoja[j][i]) {
                        const cellLength = String(datosHoja[j][i]).length;
                        if (cellLength > maxWidth) maxWidth = Math.min(cellLength, 60);
                    }
                }
                colWidths.push({ wch: maxWidth + 2 });
            }
        }
        ws['!cols'] = colWidths;
        
        XLSX.utils.book_append_sheet(wb, ws, 'Cruces');
        
        const fileName = `cruces_${new Date().toISOString().slice(0, 19).replace(/:/g, '-')}.xlsx`;
        XLSX.writeFile(wb, fileName);
        
        alert('Exportacion completada con exito');
    } catch (error) {
        console.error('Error al exportar:', error);
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
    if (!valor) {
        alert('Ingresa un valor');
        return;
    }
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
        c.innerHTML += `<div class="filtro-tag">${f.campo} = "${f.valor}" <button onclick="eliminarFiltro(${i})">x</button></div>`;
    });
}

// ==================== INICIALIZACION ====================
document.addEventListener('DOMContentLoaded', function() {
    cargarSelectores();
    cargarDatos();
    
    setTimeout(() => {
        const p3Fila = document.querySelector('.chk-fila[value="p3_pertenencia"]');
        const p6Fila = document.querySelector('.chk-fila[value="p6_familia"]');
        const p10Col = document.querySelector('.chk-columna[value="p10_esperanza"]');
        if (p3Fila) p3Fila.checked = true;
        if (p6Fila) p6Fila.checked = true;
        if (p10Col) p10Col.checked = true;
        if (p3Fila) p3Fila.dispatchEvent(new Event('change'));
        if (p6Fila) p6Fila.dispatchEvent(new Event('change'));
        if (p10Col) p10Col.dispatchEvent(new Event('change'));
        
        generarTablas();
    }, 100);
});