// js/otro.js - Controla que el comentario sea obligatorio cuando se selecciona "Otro"
document.addEventListener('DOMContentLoaded', function() {

    // Función para manejar la visibilidad del campo "Otro"
    function manejarOtro(event) {
        const input = event.target;
        
        // Determinar si es radio o checkbox
        const esRadio = input.matches('input[type="radio"]');
        const esCheckbox = input.matches('input[type="checkbox"]');
        
        if (!esRadio && !esCheckbox) return;
        
        // Obtener el ID del contenedor del comentario (data-target)
        const targetId = input.getAttribute('data-target');
        if (!targetId) return;
        
        const comentarioContainer = document.getElementById(targetId);
        if (!comentarioContainer) return;
        
        const textarea = comentarioContainer.querySelector('textarea');
        const requiredMark = comentarioContainer.querySelector('.required-mark');
        const textoObligatorio = comentarioContainer.querySelector('[id^="texto_obligatorio_"]');
        const textoAyuda = comentarioContainer.querySelector('small');
        const mensajeError = comentarioContainer.querySelector('.mensaje-error-texto');
        
        if (!textarea) return;
        
        // Verificar si este input es "OTRO" y está seleccionado
        let esOtroSeleccionado = false;
        
        if (esRadio) {
            // Para radios: verificar si el radio seleccionado es OTRO
            const grupoRadios = document.querySelectorAll(`input[name="${input.name}"]`);
            grupoRadios.forEach(radio => {
                if (radio.checked && radio.value === 'OTRO') {
                    esOtroSeleccionado = true;
                }
            });
        } else if (esCheckbox) {
            // Para checkboxes: verificar si este checkbox OTRO está marcado
            if (input.value === 'OTRO' && input.checked) {
                esOtroSeleccionado = true;
            } else {
                // Verificar si hay otro checkbox OTRO marcado en el grupo
                const grupoCheckboxes = document.querySelectorAll(`input[name="${input.name}"]`);
                grupoCheckboxes.forEach(cb => {
                    if (cb.checked && cb.value === 'OTRO' && cb !== input) {
                        esOtroSeleccionado = true;
                    }
                });
            }
        }
        
        // Mostrar/ocultar el asterisco y el mensaje de obligatorio
        if (esOtroSeleccionado) {
            // Hacer obligatorio el textarea
            textarea.required = true;
            textarea.setAttribute('data-required', 'true');
            textarea.placeholder = 'Por favor, especifica tu respuesta aquí...';
            
            if (requiredMark) requiredMark.style.display = 'inline';
            if (textoObligatorio) textoObligatorio.style.display = 'inline';
            if (textoAyuda) textoAyuda.style.display = 'none';
            if (mensajeError) mensajeError.style.display = 'none';
            
            // Quitar error si existe
            textarea.classList.remove('error');
            
            // Enfocar el textarea
            setTimeout(() => textarea.focus(), 200);
        } else {
            // Hacer opcional el textarea
            textarea.required = false;
            textarea.removeAttribute('data-required');
            textarea.placeholder = 'Opcional: comparte aquí cualquier comentario...';
            
            if (requiredMark) requiredMark.style.display = 'none';
            if (textoObligatorio) textoObligatorio.style.display = 'none';
            if (textoAyuda) textoAyuda.style.display = 'inline';
            if (mensajeError) mensajeError.style.display = 'none';
            
            // Quitar error si existe
            textarea.classList.remove('error');
        }
    }

    // Agregar event listeners a todos los radios con clase radio-opcion
    document.querySelectorAll('.radio-opcion').forEach(radio => {
        radio.addEventListener('change', manejarOtro);
        radio.addEventListener('click', manejarOtro);
    });

    // Agregar event listeners a todos los checkboxes con clase checkbox-otro
    document.querySelectorAll('.checkbox-otro').forEach(checkbox => {
        checkbox.addEventListener('change', manejarOtro);
        checkbox.addEventListener('click', manejarOtro);
    });

    // Ejecutar al cargar para verificar estados iniciales
    document.querySelectorAll('.radio-opcion, .checkbox-otro').forEach(input => {
        // Disparar el evento manualmente
        const event = new Event('change');
        input.dispatchEvent(event);
    });

});