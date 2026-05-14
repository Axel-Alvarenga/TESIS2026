// estadisticas.js - Gráficos y funcionalidad de estadísticas

// Función para renderizar todos los gráficos
function renderizarGraficos(datos) {
    // Edad
    new Chart(document.getElementById('edadChart'), {
        type: 'bar',
        data: { labels: datos.edadLabels, datasets: [{ label: 'Respuestas', data: datos.edadData, backgroundColor: '#667eea', borderRadius: 10 }] }
    });
    
    // Sentimiento
    new Chart(document.getElementById('sentimientoChart'), {
        type: 'doughnut',
        data: { labels: datos.sentimientoLabels, datasets: [{ data: datos.sentimientoData, backgroundColor: ['#48bb78', '#e53e3e', '#a0aec0'] }] }
    });
    
    // Parroquias
    new Chart(document.getElementById('parroquiaChart'), {
        type: 'bar',
        data: { labels: datos.parroquiaLabels, datasets: [{ label: 'Respuestas', data: datos.parroquiaData, backgroundColor: '#9f7aea', borderRadius: 10 }] },
        options: { indexAxis: 'y', responsive: true }
    });
    
    // Evolución
    new Chart(document.getElementById('evolucionChart'), {
        type: 'line',
        data: { labels: datos.evolucionLabels, datasets: [{ label: 'Respuestas', data: datos.evolucionData, borderColor: '#667eea', backgroundColor: 'rgba(102,126,234,0.1)', fill: true, tension: 0.3 }] }
    });
    
    // P3 a P8 y P10
    const colores = ['#48bb78', '#ed8936', '#ecc94b', '#4fd1c5', '#9f7aea', '#f687b3'];
    const charts = ['p3Chart', 'p4Chart', 'p5Chart', 'p6Chart', 'p7Chart', 'p8Chart'];
    charts.forEach((id, i) => {
        if (datos[id]) {
            new Chart(document.getElementById(id), {
                type: 'bar',
                data: { labels: datos[id].labels, datasets: [{ label: 'Respuestas', data: datos[id].data, backgroundColor: colores[i % colores.length], borderRadius: 10 }] }
            });
        }
    });
    
    // P10 esperanza
    if (datos.p10Chart) {
        new Chart(document.getElementById('p10Chart'), {
            type: 'bar',
            data: { labels: datos.p10Chart.labels, datasets: [{ label: 'Respuestas', data: datos.p10Chart.data, backgroundColor: ['#e53e3e', '#ed8936', '#ecc94b', '#48bb78', '#38a169'], borderRadius: 10 }] }
        });
    }
}

// Exportar a Excel
function exportarExcel() {
    const tabla = document.getElementById('tablaRespuestas');
    if (!tabla) return;
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.table_to_sheet(tabla);
    XLSX.utils.book_append_sheet(wb, ws, 'Respuestas');
    XLSX.writeFile(wb, `estadisticas_voces_sur_${new Date().toISOString().slice(0, 19)}.xlsx`);
}

// Ordenar tabla
function sortTable(columna) {
    const tabla = document.getElementById('tablaRespuestas');
    if (!tabla) return;
    const tbody = tabla.tBodies[0];
    const filas = Array.from(tbody.rows);
    let ascendente = tabla.dataset.sortAsc !== 'true';
    
    filas.sort((a, b) => {
        let aVal = a.cells[columna].innerText;
        let bVal = b.cells[columna].innerText;
        if (columna === 0 || columna === 4) { aVal = parseInt(aVal); bVal = parseInt(bVal); }
        if (columna === 1) { aVal = new Date(aVal.split('/').reverse().join('-')); bVal = new Date(bVal.split('/').reverse().join('-')); }
        return ascendente ? (aVal > bVal ? 1 : -1) : (aVal < bVal ? 1 : -1);
    });
    
    filas.forEach(fila => tbody.appendChild(fila));
    tabla.dataset.sortAsc = ascendente;
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    const exportBtn = document.getElementById('exportarExcel');
    if (exportBtn) exportBtn.addEventListener('click', exportarExcel);
});