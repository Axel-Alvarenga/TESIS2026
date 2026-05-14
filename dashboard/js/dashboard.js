/**
 * dashboard.js - Funciones comunes para todo el panel de administración
 */

// ==================== UTILIDADES GENERALES ====================

// Función para mostrar mensajes temporales
function mostrarMensaje(mensaje, tipo) {
    const div = document.createElement('div');
    div.className = `mensaje mensaje-${tipo}`;
    div.innerHTML = `<i class="fas fa-${tipo === 'exito' ? 'check-circle' : 'exclamation-triangle'}"></i> ${mensaje}`;
    
    const mainContent = document.querySelector('.main-content');
    if (mainContent) {
        mainContent.insertBefore(div, mainContent.firstChild);
        setTimeout(() => div.remove(), 5000);
    }
}

// Función para confirmar acciones
function confirmarAccion(mensaje) {
    return confirm(mensaje || '¿Estás seguro de realizar esta acción?');
}

// Formatear fecha
function formatearFecha(fecha) {
    if (!fecha) return '-';
    const d = new Date(fecha);
    return d.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// ==================== ORDENAMIENTO DE TABLAS ====================

function ordenarTabla(tablaId, columna, tipo = 'string') {
    const tabla = document.getElementById(tablaId);
    if (!tabla) return;
    
    const tbody = tabla.tBodies[0];
    const filas = Array.from(tbody.rows);
    let ascendente = tabla.dataset.sortAsc !== 'true';
    
    filas.sort((a, b) => {
        let aVal = a.cells[columna].innerText.trim();
        let bVal = b.cells[columna].innerText.trim();
        
        if (tipo === 'number') {
            aVal = parseInt(aVal) || 0;
            bVal = parseInt(bVal) || 0;
        } else if (tipo === 'date') {
            aVal = new Date(aVal.split('/').reverse().join('-'));
            bVal = new Date(bVal.split('/').reverse().join('-'));
        }
        
        if (ascendente) {
            return aVal > bVal ? 1 : -1;
        } else {
            return aVal < bVal ? 1 : -1;
        }
    });
    
    filas.forEach(fila => tbody.appendChild(fila));
    tabla.dataset.sortAsc = ascendente;
}

// ==================== FILTROS EN TABLAS ====================

function filtrarTabla(tablaId, inputId, columnIndex) {
    const input = document.getElementById(inputId);
    const filter = input.value.toLowerCase();
    const tabla = document.getElementById(tablaId);
    const rows = tabla.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const cell = row.cells[columnIndex];
        if (cell) {
            const text = cell.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        }
    });
}

// ==================== GRÁFICOS REUTILIZABLES ====================

function crearGraficoBarra(canvasId, labels, datos, titulo, color = '#667eea') {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;
    
    return new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: titulo,
                data: datos,
                backgroundColor: color,
                borderRadius: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top' }
            }
        }
    });
}

function crearGraficoTorta(canvasId, labels, datos, colores = ['#48bb78', '#e53e3e', '#a0aec0']) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;
    
    return new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: datos,
                backgroundColor: colores,
                borderRadius: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true
        }
    });
}

function crearGraficoLinea(canvasId, labels, datos, titulo, color = '#667eea') {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;
    
    return new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: titulo,
                data: datos,
                borderColor: color,
                backgroundColor: 'rgba(102,126,234,0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true
        }
    });
}

// ==================== EXPORTACIÓN DE TABLAS ====================

function exportarTablaACSV(tablaId, nombreArchivo = 'exportacion') {
    const tabla = document.getElementById(tablaId);
    if (!tabla) return;
    
    let csv = [];
    const rows = tabla.querySelectorAll('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = [];
        const cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length; j++) {
            let texto = cols[j].innerText.replace(/,/g, ';');
            row.push(texto);
        }
        csv.push(row.join(','));
    }
    
    const blob = new Blob(["\uFEFF" + csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.href = url;
    link.setAttribute('download', `${nombreArchivo}_${new Date().toISOString().slice(0, 19)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
    
    mostrarMensaje('Exportación completada', 'exito');
}

// ==================== EXPORTAR TABLA ESPECÍFICA A EXCEL ====================

function exportarTablaExcel(tablaId, nombreArchivo) {
    const tabla = document.getElementById(tablaId);
    if (!tabla) return;
    
    try {
        // Crear libro de Excel
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.table_to_sheet(tabla, { raw: true });
        
        // Ajustar anchos de columna
        ws['!cols'] = [];
        const colCount = tabla.querySelector('tr')?.cells.length || 10;
        for (let i = 0; i < colCount; i++) {
            ws['!cols'].push({ wch: 15 });
        }
        
        XLSX.utils.book_append_sheet(wb, ws, nombreArchivo || 'datos');
        XLSX.writeFile(wb, `${nombreArchivo || 'exportacion'}_${new Date().toISOString().slice(0, 19)}.xlsx`);
        
        mostrarMensaje('Exportación a Excel completada', 'exito');
    } catch (error) {
        console.error('Error al exportar:', error);
        mostrarMensaje('Error al exportar. Intenta de nuevo.', 'error');
    }
}

// ==================== COPIAR TABLA AL PORTAPAPELES ====================

function copiarTabla(tablaId) {
    const tabla = document.getElementById(tablaId);
    if (!tabla) return;
    
    try {
        const range = document.createRange();
        range.selectNode(tabla);
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
        document.execCommand('copy');
        window.getSelection().removeAllRanges();
        mostrarMensaje('Tabla copiada al portapapeles', 'exito');
    } catch (error) {
        console.error('Error al copiar:', error);
        mostrarMensaje('Error al copiar la tabla', 'error');
    }
}

// ==================== FORMATEAR NÚMEROS ====================

function formatearNumero(numero) {
    return new Intl.NumberFormat('es-ES').format(numero);
}

function formatearPorcentaje(valor, total) {
    if (total === 0) return '0%';
    return ((valor / total) * 100).toFixed(1) + '%';
}

// ==================== COLORES PARA ESPERANZA ====================

function getColorEsperanza(nivel) {
    const colores = {
        1: '#e53e3e',
        2: '#ed8936',
        3: '#ecc94b',
        4: '#48bb78',
        5: '#38a169'
    };
    return colores[nivel] || '#a0aec0';
}

function getIconoEsperanza(nivel) {
    const iconos = {
        1: '😞 Muy bajo',
        2: '😟 Bajo',
        3: '😐 Medio',
        4: '🙂 Alto',
        5: '😊 Muy alto'
    };
    return iconos[nivel] || '⭐ ' + nivel;
}

// ==================== INICIALIZACIÓN ====================

document.addEventListener('DOMContentLoaded', function() {
    // Marcar enlace activo en el sidebar
    const currentPath = window.location.pathname.split('/').pop();
    const navLinks = document.querySelectorAll('.sidebar-nav .nav-item');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPath) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
    
    // Cerrar menú móvil al hacer clic en enlace
    const mobileMenuBtn = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (mobileMenuBtn && sidebar) {
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('open');
                }
            });
        });
    }
    
    console.log('✅ Dashboard inicializado correctamente');
});