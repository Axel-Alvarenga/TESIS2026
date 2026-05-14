// menu.js - Control del menú lateral en móvil
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('.sidebar');
    
    // Solo agregar botón en móvil
    if (window.innerWidth <= 768 && sidebar) {
        // Crear botón
        const menuBtn = document.createElement('button');
        menuBtn.innerHTML = '<i class="fas fa-bars"></i>';
        menuBtn.className = 'menu-toggle';
        menuBtn.setAttribute('aria-label', 'Menú');
        menuBtn.style.cssText = 'position:fixed;top:15px;left:15px;z-index:1001;background:#00093e;color:white;border:none;padding:12px 16px;border-radius:12px;cursor:pointer;font-size:1.2em;box-shadow:0 2px 8px rgba(0,0,0,0.2);';
        document.body.appendChild(menuBtn);
        
        // Abrir/cerrar menú
        menuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.toggle('open');
        });
        
        // Cerrar menú al hacer clic fuera
        document.addEventListener('click', (e) => {
            if (sidebar.classList.contains('open')) {
                if (!sidebar.contains(e.target) && !menuBtn.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });
        
        // Cerrar menú al redimensionar a escritorio
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('open');
                if (menuBtn) menuBtn.style.display = 'none';
            } else {
                if (menuBtn) menuBtn.style.display = 'block';
            }
        });
    }
});