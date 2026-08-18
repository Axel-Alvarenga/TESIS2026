// js/otro.js - Controla que el comentario sea obligatorio cuando se selecciona "Otro"
document.addEventListener('DOMContentLoaded', function() {

    // Función para manejar la visibilidad del campo "Otro"
    function manejarOtro(event) {
        const input = event.target;
        
        // Determinar si es radio o checkbox
        const esRadio = input.matches('input[type="radio"]');
        const esCheckbox = input.matches('input[type="checkbox"]');
        
        if (!esRadio && !esCheckbox) return;
        
        // Obtener el ID del contenedor del comentario (data-comentario)
        const comentarioId = input.getAttribute('data-comentario');
        if (!comentarioId) return;
        
        const comentarioContainer = document.getElementById(comentarioId);
        if (!comentarioContainer) return;
        
        const textarea = comentarioContainer.querySelector('textarea');
        const requiredMark = comentarioContainer.querySelector('.required-mark');
        const textoObligatorio = comentarioContainer.querySelector('[id^="texto_obligatorio_"]');
        const textoAyuda = comentarioContainer.querySelector('small');
        const mensajeError = comentarioContainer.querySelector('.mensaje-error-texto');
        
        // Buscar el campo "Otro" asociado a este radio
        // El campo "Otro" tiene el mismo nombre que el radio pero con _otro_texto
        const nombreRadio = input.name;
        let nombreOtro = '';
        
        // Mapeo de nombres de campos "OTRO"
        if (nombreRadio === 'p3_pertenencia') nombreOtro = 'p3_otro_texto';
        else if (nombreRadio === 'p4_atraccion') nombreOtro = 'p4_otro_texto';
        else if (nombreRadio === 'p4b_situacion') nombreOtro = 'p4b1_otro_texto';
        else if (nombreRadio === 'p4b_area') nombreOtro = 'p4b2_otro_texto';
        else if (nombreRadio === 'p4b_movilidad') nombreOtro = 'p4b3_otro_texto';
        else if (nombreRadio === 'p5_espiritualidad') nombreOtro = 'p5_otro_texto';
        else if (nombreRadio === 'p6_familia') nombreOtro = 'p6_otro_texto';
        else if (nombreRadio === 'p7_proyecto') nombreOtro = 'p7_otro_texto';
        else if (nombreRadio === 'p8_vocacion') nombreOtro = 'p8_otro_texto';
        else if (nombreRadio === 'p9_critica[]') nombreOtro = 'p9_otro_texto';
        
        // Buscar el campo de texto "Otro" por su name
        const otroInput = document.querySelector(`input[name="${nombreOtro}"]`);
        const otroContainer = otroInput ? otroInput.closest('.otro-texto') : null;
        
        // Verificar si este input es "OTRO" y está seleccionado
        let esOtroSeleccionado = false;
        
        if (esRadio) {
            const grupoRadios = document.querySelectorAll(`input[name="${input.name}"]`);
            grupoRadios.forEach(radio => {
                if (radio.checked && radio.value === 'OTRO') {
                    esOtroSeleccionado = true;
                }
            });
        } else if (esCheckbox) {
            if (input.value === 'OTRO' && input.checked) {
                esOtroSeleccionado = true;
            } else {
                const grupoCheckboxes = document.querySelectorAll(`input[name="${input.name}"]`);
                grupoCheckboxes.forEach(cb => {
                    if (cb.checked && cb.value === 'OTRO' && cb !== input) {
                        esOtroSeleccionado = true;
                    }
                });
            }
        }
        
        // Mostrar/ocultar el campo "Otro" y el asterisco
        if (esOtroSeleccionado) {
            // Mostrar el campo "Otro"
            if (otroContainer) {
                otroContainer.style.display = 'block';
                if (otroInput) {
                    otroInput.required = true;
                    otroInput.setAttribute('data-required', 'true');
                    // Ocultar mensaje de error si existe
                    const errorMsg = otroContainer.querySelector('.mensaje-error');
                    if (errorMsg) errorMsg.style.display = 'none';
                    otroInput.classList.remove('error');
                    setTimeout(() => otroInput.focus(), 200);
                }
            }
            
            // Hacer obligatorio el textarea de comentario
            if (textarea) {
                textarea.required = true;
                textarea.setAttribute('data-required', 'true');
                textarea.placeholder = 'Por favor, especifica tu respuesta aquí...';
            }
            
            if (requiredMark) requiredMark.style.display = 'inline';
            if (textoObligatorio) textoObligatorio.style.display = 'inline';
            if (textoAyuda) textoAyuda.style.display = 'none';
            if (mensajeError) mensajeError.style.display = 'none';
            if (textarea) textarea.classList.remove('error');
            
        } else {
            // Ocultar el campo "Otro"
            if (otroContainer) {
                otroContainer.style.display = 'none';
                if (otroInput) {
                    otroInput.required = false;
                    otroInput.removeAttribute('data-required');
                    otroInput.value = '';
                    otroInput.classList.remove('error');
                    const errorMsg = otroContainer.querySelector('.mensaje-error');
                    if (errorMsg) errorMsg.style.display = 'none';
                }
            }
            
            // Hacer opcional el textarea
            if (textarea) {
                textarea.required = false;
                textarea.removeAttribute('data-required');
                textarea.placeholder = 'Opcional: comparte aquí cualquier comentario...';
            }
            
            if (requiredMark) requiredMark.style.display = 'none';
            if (textoObligatorio) textoObligatorio.style.display = 'none';
            if (textoAyuda) textoAyuda.style.display = 'inline';
            if (mensajeError) mensajeError.style.display = 'none';
            if (textarea) textarea.classList.remove('error');
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
        const event = new Event('change');
        input.dispatchEvent(event);
    });

});