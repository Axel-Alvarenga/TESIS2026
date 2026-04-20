<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Voces del Sur - Encuesta</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <div class="container">
        <!-- Pantalla de bienvenida -->
        <div class="welcome-card" id="welcomeCard">
            <div class="logos-header">
                <div class="logo-left"><img src="img/LOGOUCCAMPUSITAPÚA.png" alt="UC" class="logo-img"></div>
                <div class="logo-center"><img src="img/bie-cat.jpeg" alt="Bienvenida" class="logo-img-center"></div>
                <div class="logo-right"><img src="img/logodio.png" alt="Diócesis" class="logo-img"></div>
            </div>
            <div class="logo">
                <h1>Voces del Sur</h1>
                <p>Proyecto de escucha genuina</p>
            </div>
            <div class="message">
                <p><strong>Hola, soy Felipe de la Parroquia.</strong></p>
                <p>Te invito a sumar tu voz a un laboratorio de escucha impulsado por la Diócesis de Encarnación y la Universidad Católica. No hay respuestas correctas ni incorrectas. Solo queremos escucharte de verdad.</p>
                <div class="info-box">⏱ 5 a 7 minutos · 100% anónimo · Sin apellidos ni cédula · Caduca el 31/12/2026</div>
                <div class="consent">
                    <p><em>Al continuar, acepto que mis respuestas sean usadas de forma anónima y agregada con fines académicos y pastorales por el equipo de investigación de la Universidad Católica y la Diócesis de Encarnación. No se almacenarán datos que permitan mi identificación personal.</em></p>
                    <label class="checkbox-label"><input type="checkbox" id="consentCheckbox"><span>Acepto y quiero participar</span></label>
                </div>
                <button class="btn-primary" id="startBtn" disabled>Comenzar encuesta</button>
                <div class="footer-note"><small>Si tienes dudas sobre la autenticidad de este enlace, puedes verificarlo en la web oficial de la Diócesis (diocesisencarnacion.org) o de la Universidad Católica.</small></div>
            </div>
        </div>

        <!-- Formulario por pasos -->
        <div class="survey-form" id="surveyForm" style="display:none;">
            <form action="procesar.php" method="POST" id="encuestaForm">
                <!-- LOGOS DENTRO DEL FORMULARIO -->
                <div class="logos-header">
                    <div class="logo-left"><img src="img/LOGOUCCAMPUSITAPÚA.png" alt="UC" class="logo-img"></div>
                    <div class="logo-center"><img src="img/bie-cat.jpeg" alt="Bienvenida" class="logo-img-center"></div>
                    <div class="logo-right"><img src="img/logodio.png" alt="Diócesis" class="logo-img"></div>
                </div>
                
                <div class="step-indicator">
                    <span class="step-text" id="stepCounter">Bloque 1 de 8</span>
                    <div class="step-progress"><div class="step-progress-fill" id="stepProgressFill"></div></div>
                </div>
                
                <!-- BLOQUE 1 -->
                <div class="step-page active" data-step="1">
                    <div class="block"><h2>Bloque I · Datos de clasificación</h2></div>
                    <div class="question">
                        <label>P1. ¿En qué año naciste? <span class="required-mark">*</span></label>
                        <select name="p1_anio" id="anioNacimiento" required>
                            <option value="">Selecciona tu año</option>
                            <option value="antes_1991">Antes de 1992</option>
                            <?php for($i = 1991; $i <= 2011; $i++) echo "<option value='$i'>$i</option>"; ?>
                            <option value="despues_2011">Después de 2011</option>
                        </select>
                    </div>
                    <div class="question">
                        <label>P2. ¿A qué parroquia o capilla estás más cerca? <span class="required-mark">*</span></label>
                        <input type="text" name="p2_parroquia" list="parroquiasList" id="parroquiaInput" placeholder="Escribe o selecciona una parroquia..." autocomplete="off" required>
                        <datalist id="parroquiasList">
                            <option value="Nuestra Señora de la Santísima Encarnación">
                            <option value="Inmaculada Concepción de María">
                            <option value="San Roque González de Santa Cruz">
                            <option value="San Pedro Apóstol - Encarnación">
                            <option value="San Francisco de Asís">
                            <option value="Sagrado Corazón de Jesús - Cambyreta">
                            <option value="Santísimo Nombre de María">
                            <option value="Presentación de María en el Templo">
                            <option value="San Juan Bautista - San Juan del Paraná">
                            <option value="San Miguel Arcángel - Santuario Itacuá">
                            <option value="San Isidro Labrador">
                            <option value="Nuestra Señora del Carmen">
                            <option value="Espíritu Santo - Fram">
                            <option value="Santa Cruz">
                            <option value="San Luis Gonzága">
                            <option value="Santos Cosme y Damián">
                            <option value="Virgen del Rosario">
                            <option value="San Pedro Apóstol - San Pedro del Paraná">
                            <option value="Virgen de Lourdes">
                            <option value="San José Obrero - Cap. Miranda">
                            <option value="María Reina de la Paz">
                            <option value="Niño Jesús">
                            <option value="Cuasi Parroquia Santísima Trinidad">
                            <option value="Espíritu Santo - Hohenau">
                            <option value="Cristo Rey">
                            <option value="26 Santos Mártires del Japón">
                            <option value="Sagrado Corazón de Jesús - Caronay">
                            <option value="San Cristóbal">
                            <option value="San Juan Bautista - Yatytay">
                            <option value="Virgen de Fátima">
                            <option value="María Auxiliadora">
                            <option value="Inmaculado Corazón de María">
                            <option value="San José Obrero - Edelira">
                            <option value="San Antonio de Padua - Cap. Meza">
                            <option value="San Martín de Tours">
                            <option value="San José Obrero - Naranjito">
                            <option value="San Cayetano">
                            <option value="San Juan Bautista - Itapua Poty">
                            <option value="San Antonio de Padua - Carlos A. López">
                            <option value="No frecuento ninguna comunidad">
                        </datalist>
                        <small id="parroquiaError" style="color: #e53e3e; display: none;">⚠️ Por favor, selecciona una parroquia válida de la lista.</small>
                        <small>Escribe el nombre de tu parroquia o selecciona de la lista</small>
                    </div>
                    <div id="permisoMenores" style="display: none; background: #fef9e6; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <label style="display: flex; align-items: center; gap: 10px;">
                            <input type="checkbox" name="permiso_padres" id="permisoPadres" value="si">
                            <span>📋 Declaro que soy menor de 18 años y cuento con la autorización de mis padres o tutores.</span>
                        </label>
                    </div>
                </div>

                <!-- BLOQUE 2 -->
                <div class="step-page" data-step="2">
                    <div class="block"><h2>Bloque II · Vínculos y pertenencia</h2></div>
                    <div class="question">
                        <label>P3. En el último mes, ¿en qué momento sentiste que pertenecías a algo más grande que vos mismo/a? <span class="required-mark">*</span></label>
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
                        <label>P4. Si hoy te invitáramos a un espacio nuevo, ¿qué es lo que más te atraería? <span class="required-mark">*</span></label>
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

                <!-- BLOQUE 3 -->
                <div class="step-page" data-step="3">
                    <div class="block"><h2>Bloque III · Espiritualidad</h2></div>
                    <div class="question">
                        <label>P5. ¿Con qué frecuencia buscás respuestas a tus grandes preguntas ---la vida, la muerte, el amor, el sentido--- en la fe? <span class="required-mark">*</span></label>
                        <div class="options">
                            <label><input type="radio" name="p5_espiritualidad" value="A" required> A. Es mi principal referencia: la fe me ayuda a comprender la vida</label>
                            <label><input type="radio" name="p5_espiritualidad" value="B"> B. A veces recurro a la fe, pero no siempre comprendo el lenguaje de la Iglesia</label>
                            <label><input type="radio" name="p5_espiritualidad" value="C"> C. Prefiero buscar respuestas en otros ámbitos: la filosofía, los libros, la ciencia, otras personas</label>
                            <label><input type="radio" name="p5_espiritualidad" value="D"> D. No suelo hacerme esas preguntas; vivo el día a día</label>
                        </div>
                    </div>
                </div>

                <!-- BLOQUE 4 -->
                <div class="step-page" data-step="4">
                    <div class="block"><h2>Bloque IV · Familia</h2></div>
                    <div class="question">
                        <label>P6. En los momentos de crisis o decisiones importantes, ¿qué representa tu familia para vos? <span class="required-mark">*</span></label>
                        <div class="options">
                            <label><input type="radio" name="p6_familia" value="A" required> A. Mi principal apoyo y refugio emocional en situaciones difíciles</label>
                            <label><input type="radio" name="p6_familia" value="B"> B. Un lugar de tensiones que prefiero evitar cuando hay un problema</label>
                            <label><input type="radio" name="p6_familia" value="C"> C. Personas a las que quiero, aunque siento que no entienden completamente mi realidad</label>
                            <label><input type="radio" name="p6_familia" value="D"> D. Una fuente de motivación que me impulsa a seguir adelante cada día</label>
                            <label><input type="radio" name="p6_familia" value="E"> E. No tengo una familia de referencia clara en este momento de mi vida</label>
                        </div>
                    </div>
                </div>

                <!-- BLOQUE 5 -->
                <div class="step-page" data-step="5">
                    <div class="block"><h2>Bloque V · Proyecto de vida</h2></div>
                    <div class="question">
                        <label>P7. Al proyectar tu vida a 10 años, ¿cuál es tu prioridad fundamental? <span class="required-mark">*</span></label>
                        <div class="options">
                            <label><input type="radio" name="p7_proyecto" value="A" required> A. Alcanzar estabilidad económica y desarrollo profesional</label>
                            <label><input type="radio" name="p7_proyecto" value="B"> B. Formar una familia sólida y estar presente para ella</label>
                            <label><input type="radio" name="p7_proyecto" value="C"> C. Tener un impacto positivo real en mi comunidad o en el mundo</label>
                            <label><input type="radio" name="p7_proyecto" value="D"> D. Encontrar paz interior y encontrar un sentido profundo de mi existencia</label>
                            <label><input type="radio" name="p7_proyecto" value="E"> E. Todavía no tengo una dirección clara; estoy en proceso de descubrirla</label>
                        </div>
                    </div>
                </div>

                <!-- BLOQUE 6 -->
                <div class="step-page" data-step="6">
                    <div class="block"><h2>Bloque VI · Vocación</h2></div>
                    <div class="question">
                        <label>P8. Al pensar en tu futuro, ¿cuál de estas frases describe mejor cómo te sentís respecto a tu vocación ---tu misión en el mundo? <span class="required-mark">*</span></label>
                        <div class="options">
                            <label><input type="radio" name="p8_vocacion" value="A" required> A. Siento que tengo una misión clara y estoy trabajando para cumplirla</label>
                            <label><input type="radio" name="p8_vocacion" value="B"> B. Tengo miedo de tomar decisiones equivocadas y desperdiciar mi vida</label>
                            <label><input type="radio" name="p8_vocacion" value="C"> C. Busco algo que me apasione, pero me siento presionado por lo que esperan de mí</label>
                            <label><input type="radio" name="p8_vocacion" value="D"> D. Me gustaría saber si Dios tiene un plan para mí, pero no sé cómo descubrirlo</label>
                            <label><input type="radio" name="p8_vocacion" value="E"> E. No suelo pensar en mi vocación; busco una profesión que me dé estabilidad</label>
                        </div>
                    </div>
                </div>

                <!-- BLOQUE 7 -->
                <div class="step-page" data-step="7">
                    <div class="block"><h2>Bloque VII · Crítica institucional</h2></div>
                    <div class="question">
                        <label>P9. Si tuvieras que señalar qué es lo que más aleja a los jóvenes de la Iglesia hoy, ¿qué elegirías? (Puedes elegir hasta dos opciones)</label>
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

                <!-- BLOQUE 8 -->
                <div class="step-page" data-step="8">
                    <div class="block"><h2>Bloque VIII · Esperanza social</h2></div>
                    <div class="question">
                        <label>P10. Mirando al Paraguay de los próximos 5 años, ¿qué sentimiento predomina en vos? <span class="required-mark">*</span></label>
                        <div class="scale">
                            <label class="scale-option"><input type="radio" name="p10_esperanza" value="1" required><span>1 - Muy bajo<br><small>Miedo o angustia. Siento que mi futuro está fuera del país.</small></span></label>
                            <label class="scale-option"><input type="radio" name="p10_esperanza" value="2"><span>2 - Bajo<br><small>Preocupación. No veo muchas oportunidades de futuro aquí.</small></span></label>
                            <label class="scale-option"><input type="radio" name="p10_esperanza" value="3"><span>3 - Medio<br><small>Ni optimismo ni pesimismo; prefiero esperar y ver.</small></span></label>
                            <label class="scale-option"><input type="radio" name="p10_esperanza" value="4"><span>4 - Alto<br><small>Esperanza. Creo que las cosas pueden mejorar si trabajamos.</small></span></label>
                            <label class="scale-option"><input type="radio" name="p10_esperanza" value="5"><span>5 - Muy alto<br><small>Entusiasmo. Quiero construir mi futuro en Paraguay.</small></span></label>
                        </div>
                    </div>
                    <div class="question">
                        <label>¿Hay algo que quisieras decirnos que ninguna de estas preguntas te permitió decir?</label>
                        <textarea name="campo_libre" rows="4" placeholder="Escribe aquí tus comentarios (máximo 300 caracteres)" maxlength="300"></textarea>
                    </div>
                </div>

                <div class="step-buttons">
                    <button type="button" class="btn-secondary" id="prevBtn" style="visibility: hidden;">← Anterior</button>
                    <button type="button" class="btn-primary" id="nextBtn">Siguiente →</button>
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
            } else {
                nextBtn.textContent = 'Siguiente →';
            }
        }
        
        // ==================== VALIDACIÓN DE PARROQUIA (debe coincidir con la lista) ====================
        const parroquiaInput = document.getElementById('parroquiaInput');
        const parroquiaError = document.getElementById('parroquiaError');
        
        const opcionesValidas = Array.from(document.querySelectorAll('#parroquiasList option')).map(opt => opt.value);
        
        function validarParroquia() {
            const valor = parroquiaInput.value.trim();
            if (valor === "") {
                parroquiaError.style.display = 'none';
                return false;
            }
            
            if (opcionesValidas.includes(valor)) {
                parroquiaError.style.display = 'none';
                return true;
            } else {
                parroquiaError.style.display = 'block';
                return false;
            }
        }
        
        parroquiaInput.addEventListener('input', validarParroquia);
        parroquiaInput.addEventListener('blur', validarParroquia);
        parroquiaInput.addEventListener('change', validarParroquia);
        
        function validateCurrentStep() {
            const currentPage = document.querySelector(`.step-page[data-step="${currentStep}"]`);
            const requiredFields = currentPage.querySelectorAll('[required]');
            let isValid = true;
            
            if (currentStep === 1) {
                const parroquiaEsValida = validarParroquia();
                if (!parroquiaEsValida && parroquiaInput.value.trim() !== "") {
                    isValid = false;
                    parroquiaInput.classList.add('error');
                } else if (parroquiaInput.value.trim() === "") {
                    isValid = false;
                    parroquiaInput.classList.add('error');
                    parroquiaError.style.display = 'none';
                } else {
                    parroquiaInput.classList.remove('error');
                }
            }
            
            requiredFields.forEach(field => {
                if (field.id === 'parroquiaInput') return;
                
                if (field.type === 'radio') {
                    const radioGroup = document.querySelectorAll(`input[name="${field.name}"]`);
                    const isChecked = Array.from(radioGroup).some(r => r.checked);
                    if (!isChecked) {
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
                    alert('⚠️ Debes marcar la casilla de autorización parental (eres menor de 18 años).');
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