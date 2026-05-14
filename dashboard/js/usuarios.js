// dashboard.js - Funciones comunes para todo el dashboard

// Cerrar sesión con confirmación
function confirmarLogout() {
    return confirm('¿Estás seguro de que quieres cerrar la sesión?');
}

// Mostrar mensaje temporal
function mostrarMensaje(mensaje, tipo) {
    const div = document.createElement('div');
    div.className = `mensaje mensaje-${tipo}`;
    div.innerHTML = `<i class="fas fa-${tipo === 'exito' ? 'check-circle' : 'exclamation-triangle'}"></i> ${mensaje}`;
    document.querySelector('.main-content').insertBefore(div, document.querySelector('.main-content').firstChild);
    setTimeout(() => div.remove(), 5000);
}

// Confirmar eliminación
function confirmarEliminacion(mensaje) {
    return confirm(mensaje || '¿Estás seguro de eliminar este elemento?');
}

// Formatear fecha
function formatearFecha(fecha) {
    const d = new Date(fecha);
    return d.toLocaleDateString('es-ES');
}