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
            <!-- HEADER: SOLO LOGOS SIN TEXTOS - TAMAÑO UNIFORME -->
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
                    y agregada con fines académicos y pastorales por el CGC de la UC y la 
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
            <!-- HEADER: SOLO LOGOS SIN TEXTOS - MISMO TAMAÑO QUE INICIO -->
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
            
            <!-- Título pequeño dentro del formulario -->
            <div class="titulo-principal" style="margin-bottom: 20px;">
                <h2 style="font-size: 1.3em;">Voces del Sur</h2>
                <p style="font-size: 0.7em;">Proyecto de escucha genuina</p>
            </div>
            
            <form action="procesar.php" method="POST" id="encuestaForm" autocomplete="off">
                
                <!-- Bloque I: Datos de clasificación -->
                <div class="block">
                    <h2>Bloque I · Datos de clasificación</h2>
                    
                    <div class="question">
                        <label><strong>P1. ¿En qué año naciste?</strong></label>
                        <select name="p1_anio" required>
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
                        <label><strong>P2. ¿A qué parroquia estás más cerca, o en cuál participás?</strong></label>
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
                    
                    <div class="question">
                        <label><strong>Escribe aquí tu capilla</strong></label>
                        <p class="help-text">En este campo puedes escribir tu capilla.</p>
                        <input type="text" name="nombre_capilla" maxlength="50" 
                               placeholder="Ej: Catedral de Encarnación"
                               autocomplete="off"
                               autocorrect="off"
                               autocapitalize="none"
                               spellcheck="false">
                        <small>Este campo es opcional.</small>
                    </div>
                </div>

                <!-- Bloque II: Vínculos y pertenencia -->
                <div class="block">
                    <h2>Bloque II · Vínculos y pertenencia</h2>
                    
                    <div class="question">
                        <label><strong>P3. En el último mes, ¿en qué momento sentiste que pertenecías a algo más grande que vos mismo/a?</strong></label>
                        <div class="options">
                            <label><input type="radio" name="p3_pertenencia" value="A" required> A. En un grupo de amigos o de gente en quien confío</label>
                            <label><input type="radio" name="p3_pertenencia" value="B"> B. En la Eucaristía u otro momento de oración o liturgia</label>
                            <label><input type="radio" name="p3_pertenencia" value="C"> C. Cuando ayudé a alguien que lo necesitaba</label>
                            <label><input type="radio" name="p3_pertenencia" value="D"> D. En las redes sociales, siguiendo algo que me apasiona</label>
                            <label><input type="radio" name="p3_pertenencia" value="E"> E. En la naturaleza, el deporte o una experiencia de silencio</label>
                            <label><input type="radio" name="p3_pertenencia" value="F"> F. No recuerdo haber sentido eso en el último mes</label>
                        </div>
                    </div>
                    
                    <div class="question">
                        <label><strong>P4. Si hoy te invitáramos a un espacio nuevo, ¿qué es lo que más te atraería?</strong></label>
                        <div class="options">
                            <label><input type="radio" name="p4_atraccion" value="A" required> A. Conocer gente con mis mismos valores y poder confiar en ella</label>
                            <label><input type="radio" name="p4_atraccion" value="B"> B. Un lugar donde pueda estar en silencio y pensar sin presiones</label>
                            <label><input type="radio" name="p4_atraccion" value="C"> C. Aprender algo útil: liderazgo, habilidades técnicas, emprendimiento</label>
                            <label><input type="radio" name="p4_atraccion" value="D"> D. Un espacio donde no me juzguen y pueda ser como soy</label>
                            <label><input type="radio" name="p4_atraccion" value="E"> E. Un proyecto concreto donde mi participación cambie algo real</label>
                        </div>
                    </div>
                </div>

                <!-- Bloque III: Espiritualidad -->
                <div class="block">
                    <h2>Bloque III · Espiritualidad</h2>
                    
                    <div class="question">
                        <label><strong>P5. ¿Con qué frecuencia buscás respuestas a tus grandes preguntas ---la vida, la muerte, el amor, el sentido--- en la fe?</strong></label>
                        <div class="options">
                            <label><input type="radio" name="p5_espiritualidad" value="A" required> A. Es mi primera opción: la fe me da un marco para entender todo</label>
                            <label><input type="radio" name="p5_espiritualidad" value="B"> B. A veces busco, pero no siempre entiendo el lenguaje de la Iglesia</label>
                            <label><input type="radio" name="p5_espiritualidad" value="C"> C. Prefiero buscar en otros lugares: libros, personas, filosofía, ciencia</label>
                            <label><input type="radio" name="p5_espiritualidad" value="D"> D. No suelo hacerme esas preguntas; vivo el día a día</label>
                        </div>
                    </div>
                </div>

                <!-- Bloque IV: Familia -->
                <div class="block">
                    <h2>Bloque IV · Familia</h2>
                    
                    <div class="question">
                        <label><strong>P6. En los momentos de crisis o decisiones importantes, ¿qué representa tu familia para vos?</strong></label>
                        <div class="options">
                            <label><input type="radio" name="p6_familia" value="A" required> A. Mi refugio y principal apoyo: ahí me contengo cuando todo se complica</label>
                            <label><input type="radio" name="p6_familia" value="B"> B. Un lugar de tensiones que prefiero evitar cuando hay un problema</label>
                            <label><input type="radio" name="p6_familia" value="C"> C. Personas a las que quiero, pero que no entienden bien mi realidad</label>
                            <label><input type="radio" name="p6_familia" value="D"> D. El motivo principal por el que me esfuerzo y sigo adelante cada día</label>
                            <label><input type="radio" name="p6_familia" value="E"> E. No tengo una familia de referencia clara en este momento de mi vida</label>
                        </div>
                    </div>
                </div>

                <!-- Bloque V: Proyecto de vida -->
                <div class="block">
                    <h2>Bloque V · Proyecto de vida</h2>
                    
                    <div class="question">
                        <label><strong>P7. Al proyectar tu vida a 10 años, ¿cuál es tu prioridad fundamental?</strong></label>
                        <div class="options">
                            <label><input type="radio" name="p7_proyecto" value="A" required> A. Estabilidad económica y desarrollo profesional</label>
                            <label><input type="radio" name="p7_proyecto" value="B"> B. Formar una familia sólida y estar presente para ella</label>
                            <label><input type="radio" name="p7_proyecto" value="C"> C. Tener un impacto positivo real en mi comunidad o en el mundo</label>
                            <label><input type="radio" name="p7_proyecto" value="D"> D. Encontrar paz interior y el sentido profundo de mi existencia</label>
                            <label><input type="radio" name="p7_proyecto" value="E"> E. Todavía no tengo clara esa dirección; estoy en proceso de descubrirlo</label>
                        </div>
                    </div>
                </div>

                <!-- Bloque VI: Vocación -->
                <div class="block">
                    <h2>Bloque VI · Vocación</h2>
                    
                    <div class="question">
                        <label><strong>P8. Al pensar en tu futuro, ¿cuál de estas frases describe mejor cómo te sentís respecto a tu vocación ---tu misión en el mundo?</strong></label>
                        <div class="options">
                            <label><input type="radio" name="p8_vocacion" value="A" required> A. Siento que tengo una misión clara y estoy trabajando para cumplirla</label>
                            <label><input type="radio" name="p8_vocacion" value="B"> B. Tengo miedo de elegir mal y desperdiciar mi vida</label>
                            <label><input type="radio" name="p8_vocacion" value="C"> C. Busco algo que me apasione, pero me siento presionado por lo que esperan de mí</label>
                            <label><input type="radio" name="p8_vocacion" value="D"> D. Me gustaría saber si Dios tiene un plan para mí, pero no sé cómo descubrirlo</label>
                            <label><input type="radio" name="p8_vocacion" value="E"> E. No pienso en vocación; busco una profesión que me dé estabilidad</label>
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

                <!-- Bloque VIII: Esperanza social -->
                <div class="block">
                    <h2>Bloque VIII · Esperanza social</h2>
                    
                    <div class="question">
                        <label><strong>P10. Mirando al Paraguay de los próximos 5 años, ¿qué sentimiento predomina en vos?</strong></label>
                        <div class="scale">
                            <div class="scale-option">
                                <input type="radio" name="p10_esperanza" value="1" id="esperanza1" required>
                                <label for="esperanza1">1 - Muy bajo<br><small>Miedo o angustia. Me gustaría irme del país.</small></label>
                            </div>
                            <div class="scale-option">
                                <input type="radio" name="p10_esperanza" value="2" id="esperanza2">
                                <label for="esperanza2">2 - Bajo<br><small>Preocupación. No veo mucho espacio para el futuro aquí.</small></label>
                            </div>
                            <div class="scale-option">
                                <input type="radio" name="p10_esperanza" value="3" id="esperanza3">
                                <label for="esperanza3">3 - Medio<br><small>Incertidumbre. Ni optimismo ni pesimismo; espero y veo.</small></label>
                            </div>
                            <div class="scale-option">
                                <input type="radio" name="p10_esperanza" value="4" id="esperanza4">
                                <label for="esperanza4">4 - Alto<br><small>Esperanza. Creo que las cosas pueden mejorar si trabajamos.</small></label>
                            </div>
                            <div class="scale-option">
                                <input type="radio" name="p10_esperanza" value="5" id="esperanza5">
                                <label for="esperanza5">5 - Muy alto<br><small>Entusiasmo. Quiero ser parte del cambio y construir aquí.</small></label>
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
    </script>
</body>
</html>