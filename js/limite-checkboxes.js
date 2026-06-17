// ==================== LIMITAR A 2 SELECCIONES EN P9 ====================
const checkboxesP9 = document.querySelectorAll('input[name="p9_critica[]"]');

// Función para mostrar notificación moderna (compartida)
function mostrarNotificacionCheckbox(mensaje) {
    const notificacionesAnteriores = document.querySelectorAll('.notificacion-flotante');
    notificacionesAnteriores.forEach(n => n.remove());
    
    const notificacion = document.createElement('div');
    notificacion.className = 'notificacion-flotante';
    
    notificacion.innerHTML = `
        <div class="notificacion-contenido">
            <div class="notificacion-icono">⚠️</div>
            <div class="notificacion-mensaje">${mensaje}</div>
            <button class="notificacion-cerrar" onclick="this.parentElement.parentElement.remove()">✕</button>
        </div>
    `;
    
    notificacion.style.cssText = `
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        width: 90%;
        max-width: 500px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        border-left: 5px solid #e53e3e;
        animation: notificacionEntrada 0.3s ease;
        padding: 16px 20px;
    `;
    
    const contenido = notificacion.querySelector('.notificacion-contenido');
    contenido.style.cssText = `
        display: flex;
        align-items: center;
        gap: 14px;
    `;
    
    const iconoEl = notificacion.querySelector('.notificacion-icono');
    iconoEl.style.cssText = `
        font-size: 1.8em;
        line-height: 1;
        flex-shrink: 0;
    `;
    
    const mensajeEl = notificacion.querySelector('.notificacion-mensaje');
    mensajeEl.style.cssText = `
        flex: 1;
        font-size: 0.95em;
        color: #2d3748;
        font-weight: 500;
        line-height: 1.4;
    `;
    
    const cerrarBtn = notificacion.querySelector('.notificacion-cerrar');
    cerrarBtn.style.cssText = `
        background: none;
        border: none;
        font-size: 1.2em;
        color: #a0aec0;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 8px;
        transition: all 0.2s;
    `;
    cerrarBtn.onmouseover = () => { cerrarBtn.style.background = '#f7fafc'; };
    cerrarBtn.onmouseout = () => { cerrarBtn.style.background = 'none'; };
    
    document.body.appendChild(notificacion);
    
    setTimeout(() => {
        if (notificacion.parentElement) {
            notificacion.style.animation = 'notificacionSalida 0.3s ease forwards';
            setTimeout(() => notificacion.remove(), 300);
        }
    }, 4000);
}

// Estilos para la notificación (si no existen)
if (!document.getElementById('notificacion-estilos')) {
    const style = document.createElement('style');
    style.id = 'notificacion-estilos';
    style.textContent = `
        @keyframes notificacionEntrada {
            from { opacity: 0; transform: translateX(-50%) translateY(-20px); }
            to { opacity: 1; transform: translateX(-50%) translateY(0); }
        }
        @keyframes notificacionSalida {
            from { opacity: 1; transform: translateX(-50%) translateY(0); }
            to { opacity: 0; transform: translateX(-50%) translateY(-20px); }
        }
    `;
    document.head.appendChild(style);
}

checkboxesP9.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const checked = document.querySelectorAll('input[name="p9_critica[]"]:checked');
        if (checked.length > 2) {
            this.checked = false;
            mostrarNotificacionCheckbox('⚠️ Solo puedes seleccionar hasta 2 opciones en esta pregunta.');
        }
    });
});