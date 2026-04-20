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
</head>
<body>
    <div class="container">
        <!-- Pantalla de bienvenida -->
        <div class="welcome-card" id="welcomeCard">
            <!-- HEADER: SOLO LOGOS SIN TEXTOS -->
            <div class="header-principal">
                <div class="logo-izquierda">
                    <img src="img/LOGOUCCAMPUSITAPÚA.png" alt="Universidad Católica Campus Itapúa" class="logo-img">
                </div>
                <div class="logo-central">
                    <img src="img/bie-cat.jpeg" alt="BIE CAT" class="logo-img-central">
                </div>
                <div class="logo-derecha">
                    <img src="img/logodio.png" alt="Diócesis de Encarnación" class="logo-img">
                </div>
            </div>

            <!-- TÍTULO PRINCIPAL -->
            <div class="titulo-principal">
                <h2>Voces del Sur</h2>
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
                
                <!-- MENSAJE DE ADVERTENCIA -->
                <div class="warning-message">
                    <span class="warning-icon">⚠️</span>
                    <div class="warning-text">
                        <strong>Importante:</strong> Esta encuesta está diseñada para ser respondida <strong>UNA SOLA VEZ por persona</strong>. 
                        Si ya la completaste anteriormente, por favor no la vuelvas a responder. 
                        Esto nos ayuda a mantener la calidad y representatividad de los datos.
                    </div>
                </div>
                
                <div class="consent">
                    <p><em>Al continuar, acepto que mis respuestas sean usadas de forma anónima 
                    y agregada con fines académicos y pastorales por el Centro de gestión del conocimiento de la UC y la 
                    Diócesis de Encarnación. No se almacenarán datos que permitan mi 
                    identificación personal.</em></p>
                    
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

        <!-- Formulario de encuesta (inicialmente oculto) -->
        <div class="survey-form" id="surveyForm" style="display:none;">
            <!-- HEADER: SOLO LOGOS - MISMO TAMAÑO QUE EL INICIO -->
            <div class="header-principal">
                <div class="logo-izquierda">
                    <img src="img/LOGOUCCAMPUSITAPÚA.png" alt="Universidad Católica" class="logo-img">
                </div>
                <div class="logo-central">
                    <img src="img/bie-cat.jpeg" alt="BIE CAT" class="logo-img-central">
                </div>
                <div class="logo-derecha">
                    <img src="img/logodio.png" alt="Diócesis de Encarnación" class="logo-img">
                </div>
            </div>
            
            <form action="procesar.php" method="POST" id="encuestaForm" autocomplete="off">
                
                <!-- Bloque I: Datos de clasificación -->
                <div class="block">
                    <h2>Bloque I · Datos de clasificación</h2>
                    
                    <div class="question">
                        <label><strong>P1. ¿En qué año naciste? <span class="required-mark">*</span></strong></label>
                        <select name="p1_anio" id="anioNacimiento" required>
                            <option value="">Selecciona tu año de nacimiento</option>
                            <option value="antes_1991">📅 Antes de 1991</option>
                            <?php
                            for($i = 1991; $i <= 2011; $i++) {
                                echo "<option value='$i'>$i</option>";
                            }
                            ?>
                            <option value="despues_2011">📅 Después de 2011</option>
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
                    
                    <div class="question">
                        <label><strong>P2. ¿A qué parroquia o capilla estás más cerca, o en cuál participás? <span class="required-mark">*</span></strong></label>
                        
                        <!-- Selector moderno de parroquias -->
                        <div class="parroquia-selector">
                            <div class="selector-input" id="selectorInput">
                                <span id="selectedParroquiaText" class="placeholder">Selecciona una parroquia</span>
                                <span class="selector-arrow">▼</span>
                            </div>
                            <div class="selector-dropdown" id="selectorDropdown">
                                <div class="selector-search">
                                    <input type="text" id="parroquiaSearch" placeholder="🔍 Buscar parroquia..." autocomplete="off">
                                </div>
                                <div class="selector-options" id="parroquiaOptions">
                                    <!-- Las opciones se generan con JavaScript -->
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" name="p2_parroquia" id="parroquiaHidden" required>
                        
                        <div class="parroquia-error" id="parroquiaError">
                            ⚠️ Por favor, selecciona una parroquia válida de la lista.
                        </div>
                        
                        <small>🔍 Escribe para buscar o haz clic para desplegar la lista</small>
                    </div>
                </div>

                <!-- Bloque II: Vínculos y pertenencia -->
                <div class="block">
                    <h2>Bloque II · Vínculos y pertenencia</h2>
                    
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

                <!-- Bloque III: Espiritualidad -->
                <div class="block">
                    <h2>Bloque III · Espiritualidad</h2>
                    
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

                <!-- Bloque IV: Familia -->
                <div class="block">
                    <h2>Bloque IV · Familia</h2>
                    
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

                <!-- Bloque V: Proyecto de vida -->
                <div class="block">
                    <h2>Bloque V · Proyecto de vida</h2>
                    
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

                <!-- Bloque VI: Vocación -->
                <div class="block">
                    <h2>Bloque VI · Vocación</h2>
                    
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

                <!-- Bloque VII: Crítica institucional -->
                <div class="block">
                    <h2>Bloque VII · Crítica institucional</h2>
                    
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

                <!-- Bloque VIII: Esperanza social con colores -->
                <div class="block">
                    <h2>Bloque VIII · Esperanza social</h2>
                    
                    <div class="question">
                        <label><strong>P10. Mirando al Paraguay de los próximos 5 años, ¿qué sentimiento predomina en vos? <span class="required-mark">*</span></strong></label>
                        <div class="scale">
                            <div class="scale-option scale-1">
                                <input type="radio" name="p10_esperanza" value="1" id="esperanza1" required>
                                <label for="esperanza1">1 - Muy bajo<br><small>Miedo o angustia. Siento que mi futuro está fuera del país.</small></label>
                            </div>
                            <div class="scale-option scale-2">
                                <input type="radio" name="p10_esperanza" value="2" id="esperanza2">
                                <label for="esperanza2">2 - Bajo<br><small>Preocupación. No veo muchas oportunidades de futuro aquí.</small></label>
                            </div>
                            <div class="scale-option scale-3">
                                <input type="radio" name="p10_esperanza" value="3" id="esperanza3">
                                <label for="esperanza3">3 - Medio<br><small>Ni optimismo ni pesimismo; prefiero esperar y ver.</small></label>
                            </div>
                            <div class="scale-option scale-4">
                                <input type="radio" name="p10_esperanza" value="4" id="esperanza4">
                                <label for="esperanza4">4 - Alto<br><small>Esperanza. Creo que las cosas pueden mejorar si trabajamos.</small></label>
                            </div>
                            <div class="scale-option scale-5">
                                <input type="radio" name="p10_esperanza" value="5" id="esperanza5">
                                <label for="esperanza5">5 - Muy alto<br><small>Entusiasmo. Quiero construir mi futuro en Paraguay.</small></label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Campo libre -->
                <div class="block">
                    <h2>¿Algo más que quieras decirnos?</h2>
                    <div class="question">
                        <label><strong>¿Hay algo que quisieras decirnos que ninguna de estas preguntas te permitió decir?</strong></label>
                        <textarea name="campo_libre" rows="4" placeholder="Escribe aquí tus comentarios (máximo 300 caracteres)" maxlength="300"></textarea>
                    </div>
                </div>

                <div class="submit-section">
                    <button type="submit" class="btn-submit">Enviar mis respuestas</button>
                </div>
            </form>
        </div>
    </div>

    <script>
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
            const anioActual = new Date().getFullYear();
            let esMenor = false;
            
            if (anioSeleccionado && anioSeleccionado !== 'antes_1991' && anioSeleccionado !== 'despues_2011') {
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
        
        // ==================== SELECTOR MODERNO DE PARROQUIAS ====================
        const parroquiasList = [
            "Nuestra Señora de la Santísima Encarnación",
            "Inmaculada Concepción de María",
            "San Roque González de Santa Cruz",
            "San Pedro Apóstol - Encarnación",
            "San Francisco de Asís",
            "Sagrado Corazón de Jesús - Cambyreta",
            "Santísimo Nombre de María",
            "Presentación de María en el Templo",
            "San Juan Bautista - San Juan del Paraná",
            "San Miguel Arcángel - Santuario Itacuá",
            "San Isidro Labrador",
            "Nuestra Señora del Carmen",
            "Espíritu Santo - Fram",
            "Santa Cruz",
            "San Luis Gonzága",
            "Santos Cosme y Damián",
            "Virgen del Rosario",
            "San Pedro Apóstol - San Pedro del Paraná",
            "Virgen de Lourdes",
            "San José Obrero - Cap. Miranda",
            "María Reina de la Paz",
            "Niño Jesús",
            "Cuasi Parroquia Santísima Trinidad",
            "Espíritu Santo - Hohenau",
            "Cristo Rey",
            "26 Santos Mártires del Japón",
            "Sagrado Corazón de Jesús - Caronay",
            "San Cristóbal",
            "San Juan Bautista - Yatytay",
            "Virgen de Fátima",
            "María Auxiliadora",
            "Inmaculado Corazón de María",
            "San José Obrero - Edelira",
            "San Antonio de Padua - Cap. Meza",
            "San Martín de Tours",
            "San José Obrero - Naranjito",
            "San Cayetano",
            "San Juan Bautista - Itapua Poty",
            "San Antonio de Padua - Carlos A. López"
        ];

        let selectedParroquia = '';
        let filteredParroquias = [...parroquiasList];

        // Elementos del DOM
        const selectorInput = document.getElementById('selectorInput');
        const selectorDropdown = document.getElementById('selectorDropdown');
        const selectedParroquiaText = document.getElementById('selectedParroquiaText');
        const parroquiaSearch = document.getElementById('parroquiaSearch');
        const parroquiaOptions = document.getElementById('parroquiaOptions');
        const parroquiaHidden = document.getElementById('parroquiaHidden');
        const parroquiaError = document.getElementById('parroquiaError');

        // Función para renderizar opciones
        function renderizarOpciones() {
            parroquiaOptions.innerHTML = '';
            
            if (filteredParroquias.length === 0) {
                parroquiaOptions.innerHTML = '<div class="no-results">❌ No se encontraron parroquias</div>';
                return;
            }
            
            filteredParroquias.forEach(parroquia => {
                const option = document.createElement('div');
                option.className = 'parroquia-option';
                if (parroquia === selectedParroquia) {
                    option.classList.add('selected');
                }
                option.textContent = parroquia;
                option.onclick = () => seleccionarParroquia(parroquia);
                parroquiaOptions.appendChild(option);
            });
        }

        // Función para seleccionar una parroquia
        function seleccionarParroquia(parroquia) {
            selectedParroquia = parroquia;
            selectedParroquiaText.textContent = parroquia;
            selectedParroquiaText.classList.remove('placeholder');
            parroquiaHidden.value = parroquia;
            
            // Limpiar error
            parroquiaError.style.display = 'none';
            selectorInput.classList.remove('error');
            
            // Cerrar dropdown
            cerrarDropdown();
            
            // Actualizar opciones destacadas
            const allOptions = document.querySelectorAll('.parroquia-option');
            allOptions.forEach(opt => {
                opt.classList.remove('selected');
                if (opt.textContent === parroquia) {
                    opt.classList.add('selected');
                }
            });
        }

        // Función para abrir dropdown
        function abrirDropdown() {
            selectorDropdown.classList.add('show');
            selectorInput.classList.add('active');
            setTimeout(() => {
                if (parroquiaSearch) parroquiaSearch.focus();
            }, 50);
        }

        // Función para cerrar dropdown
        function cerrarDropdown() {
            selectorDropdown.classList.remove('show');
            selectorInput.classList.remove('active');
        }

        // Función para toggle dropdown
        function toggleParroquiaDropdown() {
            if (selectorDropdown.classList.contains('show')) {
                cerrarDropdown();
            } else {
                abrirDropdown();
            }
        }

        // Función para filtrar parroquias
        function filtrarParroquias() {
            const searchText = parroquiaSearch.value.toLowerCase().trim();
            
            if (searchText === '') {
                filteredParroquias = [...parroquiasList];
            } else {
                filteredParroquias = parroquiasList.filter(p => 
                    p.toLowerCase().includes(searchText)
                );
            }
            
            renderizarOpciones();
        }

        // Cerrar dropdown al hacer clic fuera
        document.addEventListener('click', function(e) {
            const selector = document.querySelector('.parroquia-selector');
            if (selector && !selector.contains(e.target)) {
                cerrarDropdown();
            }
        });

        // Validar que se haya seleccionado una parroquia
        function validarParroquia() {
            if (selectedParroquia === '') {
                parroquiaError.style.display = 'block';
                selectorInput.classList.add('error');
                return false;
            } else {
                parroquiaError.style.display = 'none';
                selectorInput.classList.remove('error');
                return true;
            }
        }

        // Eventos
        if (parroquiaSearch) {
            parroquiaSearch.addEventListener('input', filtrarParroquias);
        }
        
        if (selectorInput) {
            selectorInput.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleParroquiaDropdown();
            });
        }

        // Validación antes de enviar
        const formulario = document.getElementById('encuestaForm');
        if (formulario) {
            formulario.addEventListener('submit', function(e) {
                if (!validarParroquia()) {
                    e.preventDefault();
                    alert('⚠️ Por favor, selecciona una parroquia válida.');
                    return false;
                }
            });
        }

        // Inicializar
        renderizarOpciones();
    </script>
</body>
</html>