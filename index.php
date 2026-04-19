<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voces del Sur - Encuesta</title>
    <link rel="stylesheet" href="css/estilo.css">
    <style>
        /* Estilos adicionales para el formulario por pasos */
        .step-indicator {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 10px 0;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .step-progress {
            flex: 1;
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .step-progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            width: 0%;
            transition: width 0.3s ease;
        }
        
        .step-text {
            font-size: 14px;
            color: #4a5568;
            margin-right: 15px;
        }
        
        .step-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        
        .btn-step {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: transform 0.2s;
        }
        
        .btn-step:hover {
            transform: translateY(-2px);
        }
        
        .btn-step-prev {
            background: #a0aec0;
        }
        
        .btn-step-submit {
            background: linear-gradient(135deg, #48bb78 0%, #2c7a4d 100%);
        }
        
        .step-page {
            display: none;
            animation: fadeInStep 0.3s ease;
        }
        
        .step-page.active {
            display: block;
        }
        
        @keyframes fadeInStep {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .required-mark {
            color: #e53e3e;
            margin-left: 5px;
        }
        
        .step-title {
            font-size: 1.8em;
            color: #4a5568;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 3px solid #667eea;
            display: inline-block;
        }

        .error {
            border: 2px solid #e53e3e !important;
            background-color: #fff5f5 !important;
        }

        select.error, input.error, textarea.error {
            border: 2px solid #e53e3e !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Pantalla de bienvenida -->
        <div class="welcome-card" id="welcomeCard">
            <div class="logo">
                <h1>Voces del Sur</h1>
                <p>Proyecto de escucha genuina</p>
            </div>
            
            <div class="message">
                <p><strong>Hola, soy Felipe de la Parroquia.</strong></p>
                <p>Te invito a sumar tu voz a un laboratorio de escucha impulsado por la 
                Diócesis de Encarnación y la Universidad Católica. No hay respuestas 
                correctas ni incorrectas. Solo queremos escucharte de verdad.</p>
                
                <div class="info-box">
                    ⏱ 5 a 7 minutos · 100% anónimo · Sin apellidos ni cédula · Caduca el 31/12/2026
                </div>
                
                <div class="consent">
                    <p><em>Al continuar, acepto que mis respuestas sean usadas de forma anónima 
                    y agregada con fines académicos y pastorales por el equipo de investigación 
                    de la Universidad Católica y la Diócesis de Encarnación. No se almacenarán 
                    datos que permitan mi identificación personal.</em></p>
                    
                    <label class="checkbox-label">
                        <input type="checkbox" id="consentCheckbox">
                        <span>Acepto y quiero participar</span>
                    </label>
                </div>
                
                <button class="btn-primary" id="startBtn" disabled>Comenzar encuesta</button>
                
                <div class="footer-note">
                    <small>Si tienes dudas sobre la autenticidad de este enlace, puedes 
                    verificarlo en la web oficial de la Diócesis (diocesisencarnacion.org) 
                    o de la Universidad Católica.</small>
                </div>
            </div>
        </div>

        <!-- Formulario de encuesta por pasos -->
        <div class="survey-form" id="surveyForm" style="display:none;">
            <form action="procesar.php" method="POST" id="encuestaForm">
                
                <!-- Indicador de progreso -->
                <div class="step-indicator">
                    <span class="step-text" id="stepCounter">Bloque 1 de 8</span>
                    <div class="step-progress">
                        <div class="step-progress-fill" id="stepProgressFill"></div>
                    </div>
                </div>
                
                <!-- ==================== BLOQUE 1 ==================== -->
                <div class="step-page active" data-step="1">
                    <h2 class="step-title">Bloque I · Datos de clasificación</h2>
                    
                    <div class="question">
                        <label><strong>P1. ¿En qué año naciste? <span class="required-mark">*</span></strong></label>
                        <select name="p1_anio" id="anioNacimiento" required>
                            <option value="">Selecciona tu año de nacimiento</option>
                            <?php
                            for($i = 1991; $i <= 2011; $i++) {
                                echo "<option value='$i'>$i</option>";
                            }
                            ?>
                            <option value="antes_1991">Antes de 1991</option>
                            <option value="despues_2011">Después de 2011</option>
                        </select>
                    </div>
                    
                    <div class="question">
                        <label><strong>P2. ¿A qué parroquia o capilla estás más cerca, o en cuál participás? <span class="required-mark">*</span></strong></label>
                        <select name="p2_parroquia" required>
                            <option value="">Selecciona una opción</option>
                            <option value="catedral">Catedral de Encarnación</option>
                            <option value="santuario">Santuario de Itapúa</option>
                            <option value="fram">Parroquia San José de Fram</option>
                            <option value="trinidad">Parroquia Santísima Trinidad</option>
                            <option value="encarnacion_centro">Parroquia Encarnación Centro</option>
                            <option value="otra">Otra parroquia</option>
                            <option value="no_frecuento">No frecuento ninguna comunidad, pero vivo en [barrio/compañía]</option>
                        </select>
                    </div>
                    
                    <!-- Campo condicional para menores de edad -->
                    <div id="permisoMenores" style="display: none; margin-top: 20px; margin-bottom: 20px; padding: 15px; background: #fef9e6; border-left: 4px solid #e67e22; border-radius: 8px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" name="permiso_padres" id="permisoPadres" value="si">
                            <span>📋 <strong>Autorización requerida:</strong> Declaro que soy menor de 18 años y cuento con la autorización de mis padres o tutores para participar en esta encuesta.</span>
                        </label>
                        <p style="font-size: 0.8em; margin-top: 8px; color: #7f8c8d; margin-left: 28px;">Si no tienes esta autorización, por favor no continúes.</p>
                    </div>
                </div>
                
                <!-- ==================== BLOQUE 2 ==================== -->
                <div class="step-page" data-step="2">
                    <h2 class="step-title">Bloque II · Vínculos y pertenencia</h2>
                    
                    <div class="question">
                        <label><strong>P3. En el último mes, ¿en qué momento sentiste que pertenecías a algo más grande que vos mismo/a? <span class="required-mark">*</span></strong></label>
                        <div class="options">
                            <label><input type="radio" name="p3_pertenencia" value="A" required> A. En un grupo de amigos o de gente en quien confío</label>
                            <label><input type="radio" name="p3_pertenencia" value="B"> B. En la Eucaristía u otro momento de oración o liturgia</label>
                            <label><input type="radio" name="p3_pertenencia" value="C"> C. Cuando ayudé a alguien que lo necesitaba</label>
                            <label><input type="radio" name="p3_pertenencia" value="D"> D. En las redes sociales, siguiendo algo que me apasiona</label>
                            <label><input type="radio" name="p3_pertenencia" value="E"> E. En la naturaleza</label>
                            <label><input type="radio" name="p3_pertenencia" value="F"> F. En la práctica de un deporte</label>
                            <label><input type="radio" name="p3_pertenencia" value="G"> G. En experiencias de silencio o reflexión personal</label>
                            <label><input type="radio" name="p3_pertenencia" value="H"> H. No recuerdo haber sentido eso en el último mes</label>
                        </div>
                    </div>
                    
                    <div class="question">
                        <label><strong>P4. Si hoy te invitáramos a un espacio nuevo, ¿qué es lo que más te atraería? <span class="required-mark">*</span></strong></label>
                        <div class="options">
                            <label><input type="radio" name="p4_atraccion" value="A" required> A. Conocer personas con valores similares y generar vínculos de confianza</label>
                            <label><input type="radio" name="p4_atraccion" value="B"> B. Un espacio donde pueda estar en silencio y pensar sin presiones</label>
                            <label><input type="radio" name="p4_atraccion" value="C"> C. Aprender sobre liderazgo</label>
                            <label><input type="radio" name="p4_atraccion" value="D"> D. Desarrollar habilidades técnicas</label>
                            <label><input type="radio" name="p4_atraccion" value="E"> E. Aprender sobre emprendimiento</label>
                            <label><input type="radio" name="p4_atraccion" value="F"> F. Participar en un espacio donde no me juzguen y pueda ser como soy</label>
                            <label><input type="radio" name="p4_atraccion" value="G"> G. Integrarme a un proyecto concreto donde mi participación genere un cambio real</label>
                        </div>
                    </div>
                </div>
                
                <!-- ==================== BLOQUE 3 ==================== -->
                <div class="step-page" data-step="3">
                    <h2 class="step-title">Bloque III · Espiritualidad</h2>
                    
                    <div class="question">
                        <label><strong>P5. ¿Con qué frecuencia buscás respuestas a tus grandes preguntas ---la vida, la muerte, el amor, el sentido--- en la fe? <span class="required-mark">*</span></strong></label>
                        <div class="options">
                            <label><input type="radio" name="p5_espiritualidad" value="A" required> A. Es mi principal referencia: la fe me ayuda a comprender la vida</label>
                            <label><input type="radio" name="p5_espiritualidad" value="B"> B. A veces recurro a la fe, pero no siempre comprendo el lenguaje de la Iglesia</label>
                            <label><input type="radio" name="p5_espiritualidad" value="C"> C. Prefiero buscar respuestas en otros ámbitos: la filosofía, los libros, la ciencia, otras personas</label>
                            <label><input type="radio" name="p5_espiritualidad" value="D"> D. No suelo hacerme esas preguntas; vivo el día a día</label>
                        </div>
                    </div>
                </div>
                
                <!-- ==================== BLOQUE 4 ==================== -->
                <div class="step-page" data-step="4">
                    <h2 class="step-title">Bloque IV · Familia</h2>
                    
                    <div class="question">
                        <label><strong>P6. En los momentos de crisis o decisiones importantes, ¿qué representa tu familia para vos? <span class="required-mark">*</span></strong></label>
                        <div class="options">
                            <label><input type="radio" name="p6_familia" value="A" required> A. Mi principal apoyo y refugio emocional en situaciones difíciles</label>
                            <label><input type="radio" name="p6_familia" value="B"> B. Un lugar de tensiones que prefiero evitar cuando hay un problema</label>
                            <label><input type="radio" name="p6_familia" value="C"> C. Personas a las que quiero, aunque siento que no entienden completamente mi realidad</label>
                            <label><input type="radio" name="p6_familia" value="D"> D. Una fuente de motivación que me impulsa a seguir adelante cada día</label>
                            <label><input type="radio" name="p6_familia" value="E"> E. No tengo una familia de referencia clara en este momento de mi vida</label>
                        </div>
                    </div>
                </div>
                
                <!-- ==================== BLOQUE 5 ==================== -->
                <div class="step-page" data-step="5">
                    <h2 class="step-title">Bloque V · Proyecto de vida</h2>
                    
                    <div class="question">
                        <label><strong>P7. Al proyectar tu vida a 10 años, ¿cuál es tu prioridad fundamental? <span class="required-mark">*</span></strong></label>
                        <div class="options">
                            <label><input type="radio" name="p7_proyecto" value="A" required> A. Alcanzar estabilidad económica y desarrollo profesional</label>
                            <label><input type="radio" name="p7_proyecto" value="B"> B. Formar una familia sólida y estar presente para ella</label>
                            <label><input type="radio" name="p7_proyecto" value="C"> C. Tener un impacto positivo real en mi comunidad o en el mundo</label>
                            <label><input type="radio" name="p7_proyecto" value="D"> D. Encontrar paz interior y encontrar un sentido profundo de mi existencia</label>
                            <label><input type="radio" name="p7_proyecto" value="E"> E. Todavía no tengo una dirección clara; estoy en proceso de descubrirla</label>
                        </div>
                    </div>
                </div>
                
                <!-- ==================== BLOQUE 6 ==================== -->
                <div class="step-page" data-step="6">
                    <h2 class="step-title">Bloque VI · Vocación</h2>
                    
                    <div class="question">
                        <label><strong>P8. Al pensar en tu futuro, ¿cuál de estas frases describe mejor cómo te sentís respecto a tu vocación ---tu misión en el mundo? <span class="required-mark">*</span></strong></label>
                        <div class="options">
                            <label><input type="radio" name="p8_vocacion" value="A" required> A. Siento que tengo una misión clara y estoy trabajando para cumplirla</label>
                            <label><input type="radio" name="p8_vocacion" value="B"> B. Tengo miedo de tomar decisiones equivocadas y desperdiciar mi vida</label>
                            <label><input type="radio" name="p8_vocacion" value="C"> C. Busco algo que me apasione, pero me siento presionado por lo que esperan de mí</label>
                            <label><input type="radio" name="p8_vocacion" value="D"> D. Me gustaría saber si Dios tiene un plan para mí, pero no sé cómo descubrirlo</label>
                            <label><input type="radio" name="p8_vocacion" value="E"> E. No suelo pensar en mi vocación; busco una profesión que me dé estabilidad</label>
                        </div>
                    </div>
                </div>
                
                <!-- ==================== BLOQUE 7 ==================== -->
                <div class="step-page" data-step="7">
                    <h2 class="step-title">Bloque VII · Crítica institucional</h2>
                    
                    <div class="question">
                        <label><strong>P9. Si tuvieras que señalar qué es lo que más aleja a los jóvenes de la Iglesia hoy, ¿qué elegirías? (Puedes elegir hasta dos opciones)</strong></label>
                        <div class="options">
                            <label><input type="checkbox" name="p9_critica[]" value="A"> A. El lenguaje anticuado: no habla como hablamos</label>
                            <label><input type="checkbox" name="p9_critica[]" value="B"> B. La falta de coherencia entre lo que predica y lo que hacen sus representantes</label>
                            <label><input type="checkbox" name="p9_critica[]" value="C"> C. Que no trata temas que me importan: trabajo, tecnología, afectividad, ecología</label>
                            <label><input type="checkbox" name="p9_critica[]" value="D"> D. Que se siente como un lugar de reglas y prohibiciones más que de vida</label>
                            <label><input type="checkbox" name="p9_critica[]" value="E"> E. Que las decisiones importantes las toman siempre los adultos, sin escucharnos</label>
                            <label><input type="checkbox" name="p9_critica[]" value="F"> F. Malas experiencias personales que me dejaron lastimado/a o decepcionado/a</label>
                            <label><input type="checkbox" name="p9_critica[]" value="G"> G. No me siento alejado/a; la Iglesia sigue siendo importante en mi vida</label>
                        </div>
                        <small>Selecciona hasta dos opciones</small>
                    </div>
                </div>
                
                <!-- ==================== BLOQUE 8 ==================== -->
                <div class="step-page" data-step="8">
                    <h2 class="step-title">Bloque VIII · Esperanza social + Campo libre</h2>
                    
                    <div class="question">
                        <label><strong>P10. Mirando al Paraguay de los próximos 5 años, ¿qué sentimiento predomina en vos? <span class="required-mark">*</span></strong></label>
                        <div class="scale">
                            <div class="scale-option">
                                <input type="radio" name="p10_esperanza" value="1" id="esperanza1" required>
                                <label for="esperanza1">1 - Muy bajo<br><small>Miedo o angustia. Siento que mi futuro está fuera del país.</small></label>
                            </div>
                            <div class="scale-option">
                                <input type="radio" name="p10_esperanza" value="2" id="esperanza2">
                                <label for="esperanza2">2 - Bajo<br><small>Preocupación. No veo muchas oportunidades de futuro aquí.</small></label>
                            </div>
                            <div class="scale-option">
                                <input type="radio" name="p10_esperanza" value="3" id="esperanza3">
                                <label for="esperanza3">3 - Medio<br><small>Ni optimismo ni pesimismo; prefiero esperar y ver.</small></label>
                            </div>
                            <div class="scale-option">
                                <input type="radio" name="p10_esperanza" value="4" id="esperanza4">
                                <label for="esperanza4">4 - Alto<br><small>Esperanza. Creo que las cosas pueden mejorar si trabajamos.</small></label>
                            </div>
                            <div class="scale-option">
                                <input type="radio" name="p10_esperanza" value="5" id="esperanza5">
                                <label for="esperanza5">5 - Muy alto<br><small>Entusiasmo. Quiero construir mi futuro en Paraguay.</small></label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question">
                        <label><strong>¿Hay algo que quisieras decirnos que ninguna de estas preguntas te permitió decir?</strong></label>
                        <textarea name="campo_libre" rows="4" placeholder="Escribe aquí tus comentarios (máximo 300 caracteres)" maxlength="300"></textarea>
                    </div>
                </div>
                
                <!-- Botones de navegación -->
                <div class="step-buttons">
                    <button type="button" class="btn-step btn-step-prev" id="prevBtn" style="visibility: hidden;">← Anterior</button>
                    <button type="button" class="btn-step" id="nextBtn">Siguiente →</button>
                </div>
                
            </form>
        </div>
    </div>

    <script>
        // ==================== FORMULARIO POR PASOS ====================
        const pages = document.querySelectorAll('.step-page');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const stepCounter = document.getElementById('stepCounter');
        const stepProgressFill = document.getElementById('stepProgressFill');
        const formulario = document.getElementById('encuestaForm');
        
        let currentStep = 1;
        const totalSteps = pages.length;
        
        function updateStepVisibility() {
            pages.forEach((page, index) => {
                if (index + 1 === currentStep) {
                    page.classList.add('active');
                } else {
                    page.classList.remove('active');
                }
            });
            
            stepCounter.textContent = `Bloque ${currentStep} de ${totalSteps}`;
            const progress = (currentStep / totalSteps) * 100;
            stepProgressFill.style.width = `${progress}%`;
            
            if (currentStep === 1) {
                prevBtn.style.visibility = 'hidden';
            } else {
                prevBtn.style.visibility = 'visible';
            }
            
            if (currentStep === totalSteps) {
                nextBtn.textContent = 'Enviar respuestas ✓';
                nextBtn.classList.add('btn-step-submit');
            } else {
                nextBtn.textContent = 'Siguiente →';
                nextBtn.classList.remove('btn-step-submit');
            }
        }
        
        function validateCurrentStep() {
            const currentPage = document.querySelector(`.step-page[data-step="${currentStep}"]`);
            const requiredFields = currentPage.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (field.type === 'radio') {
                    const radioGroup = document.querySelectorAll(`input[name="${field.name}"]`);
                    const isChecked = Array.from(radioGroup).some(r => r.checked);
                    if (!isChecked) {
                        isValid = false;
                        field.classList.add('error');
                    } else {
                        field.classList.remove('error');
                    }
                } else if (field.type === 'checkbox' && field.required) {
                    if (!field.checked) {
                        isValid = false;
                        field.classList.add('error');
                    } else {
                        field.classList.remove('error');
                    }
                } else if (field.value === '' || field.value === null) {
                    isValid = false;
                    field.classList.add('error');
                } else {
                    field.classList.remove('error');
                }
            });
            
            const permisoDiv = document.getElementById('permisoMenores');
            if (permisoDiv && permisoDiv.style.display === 'block') {
                const permisoCheck = document.getElementById('permisoPadres');
                if (permisoCheck && !permisoCheck.checked) {
                    isValid = false;
                    alert('⚠️ Debes marcar la casilla de autorización parental para continuar (eres menor de 18 años).');
                }
            }
            
            if (!isValid) {
                alert('Por favor, completa todos los campos obligatorios (marcados con *) antes de continuar.');
            }
            
            return isValid;
        }
        
        function nextStep() {
            if (validateCurrentStep()) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    updateStepVisibility();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    formulario.submit();
                }
            }
        }
        
        function prevStep() {
            if (currentStep > 1) {
                currentStep--;
                updateStepVisibility();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }
        
        nextBtn.addEventListener('click', nextStep);
        prevBtn.addEventListener('click', prevStep);
        
        // ==================== CONTROL DE CONSENTIMIENTO ====================
        const consentCheckbox = document.getElementById('consentCheckbox');
        const startBtn = document.getElementById('startBtn');
        const welcomeCard = document.getElementById('welcomeCard');
        const surveyForm = document.getElementById('surveyForm');
        
        consentCheckbox.addEventListener('change', function() {
            startBtn.disabled = !this.checked;
        });
        
        startBtn.addEventListener('click', function() {
            welcomeCard.style.display = 'none';
            surveyForm.style.display = 'block';
            currentStep = 1;
            updateStepVisibility();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        // ==================== LIMITAR A 2 SELECCIONES EN P9 ====================
        const checkboxes = document.querySelectorAll('input[name="p9_critica[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const checked = document.querySelectorAll('input[name="p9_critica[]"]:checked');
                if (checked.length > 2) {
                    this.checked = false;
                    alert('Solo puedes seleccionar hasta 2 opciones');
                }
            });
        });
        
        // ==================== CONTROL DE PERMISO PARA MENORES ====================
        const anioSelect = document.getElementById('anioNacimiento');
        const permisoDiv = document.getElementById('permisoMenores');
        const permisoCheckbox = document.getElementById('permisoPadres');
        
        function verificarEdadYPermiso() {
            const anioSeleccionado = anioSelect.value;
            const anioActual = 2026;
            let esMenor = false;
            
            if (anioSeleccionado && !isNaN(anioSeleccionado) && anioSeleccionado.length === 4) {
                const edad = anioActual - parseInt(anioSeleccionado);
                if (edad < 18 && edad > 0) {
                    esMenor = true;
                }
            }
            
            if (anioSeleccionado === 'despues_2011') {
                esMenor = true;
            }
            
            if (esMenor) {
                permisoDiv.style.display = 'block';
                if (permisoCheckbox) {
                    permisoCheckbox.required = true;
                }
            } else {
                permisoDiv.style.display = 'none';
                if (permisoCheckbox) {
                    permisoCheckbox.required = false;
                    permisoCheckbox.checked = false;
                }
            }
        }
        
        if (anioSelect) {
            anioSelect.addEventListener('change', verificarEdadYPermiso);
        }
    </script>
</body>
</html>