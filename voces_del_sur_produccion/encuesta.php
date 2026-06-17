<?php
session_start();

// ==================== VERIFICAR ACCESO ====================
// Si el usuario no pasó por index.php con reCAPTCHA, redirigir
if (!isset($_SESSION['acceso_verificado']) || $_SESSION['acceso_verificado'] !== true) {
    header('Location: index.php?error=acceso_no_autorizado');
    exit;
}

// Opcional: tiempo de expiración de la sesión (30 minutos)
$tiempo_expiracion = 1800;
if (isset($_SESSION['acceso_verificado_timestamp']) && (time() - $_SESSION['acceso_verificado_timestamp'] > $tiempo_expiracion)) {
    session_unset();
    session_destroy();
    header('Location: index.php?error=sesion_expirada');
    exit;
}

// Generar token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
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
            <div class="header-principal">
                <div class="logo-izquierda">
                    <img src="img/LOGOUCCAMPUSITAPÚA.png" alt="Universidad Católica Campus Itapúa" class="logo-img">
                </div>
                <div class="logo-central">
                    <img src="img/bie-cat.jpeg" alt="BIE CAT" class="logo-img">
                </div>
                <div class="logo-derecha">
                    <img src="img/logodio.png" alt="Diócesis de Encarnación" class="logo-img">
                </div>
            </div>

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
                
                <div class="warning-message">
                    <span class="warning-icon">⚠️</span>
                    <div class="warning-text">
                        <strong>Importante:</strong> Esta encuesta está diseñada para ser respondida <strong>UNA SOLA VEZ por persona</strong>. 
                        Si ya la completaste anteriormente, por favor no la vuelvas a responder.
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

        <!-- Formulario de encuesta por pasos -->
        <div class="survey-form" id="surveyForm" style="display:none;">
            <div class="header-principal">
                <div class="logo-izquierda">
                    <img src="img/LOGOUCCAMPUSITAPÚA.png" alt="Universidad Católica" class="logo-img">
                </div>
                <div class="logo-central">
                    <img src="img/bie-cat.jpeg" alt="BIE CAT" class="logo-img">
                </div>
                <div class="logo-derecha">
                    <img src="img/logodio.png" alt="Diócesis de Encarnación" class="logo-img">
                </div>
            </div>
            
            <form action="procesar.php" method="POST" id="encuestaForm" autocomplete="off">
                <!-- Token CSRF para seguridad -->
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <div class="step-indicator">
                    <span class="step-text" id="stepCounter">Bloque 1 de 12</span>
                    <div class="step-progress"><div class="step-progress-fill" id="stepProgressFill"></div></div>
                </div>
                
                <!-- ==================== BLOQUE 1 (P1, P2) ==================== -->
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
                        
                        <div class="parroquia-selector">
                            <div class="selector-input" id="selectorInput">
                                <span id="selectedParroquiaText" class="placeholder">Selecciona una parroquia</span>
                                <span class="selector-arrow">▼</span>
                            </div>
                            <div class="selector-dropdown" id="selectorDropdown">
                                <div class="selector-search">
                                    <input type="text" id="parroquiaSearch" placeholder="🔍 Buscar parroquia..." autocomplete="off">
                                </div>
                                <div class="selector-options" id="parroquiaOptions"></div>
                            </div>
                        </div>
                        
                        <input type="hidden" name="p2_parroquia" id="parroquiaHidden" required>
                        <div class="parroquia-error" id="parroquiaError">⚠️ Por favor, selecciona una parroquia válida de la lista.</div>
                        <small>🔍 Escribe para buscar o haz clic para desplegar la lista</small>
                    </div>
                    
                    <div id="permisoMenores" class="permiso-menores" style="display: none;">
                        <label>
                            <input type="checkbox" name="permiso_padres" id="permisoPadres" value="si">
                            <span>📋 Declaro que soy menor de 18 años y cuento con la autorización de mis padres o tutores.</span>
                        </label>
                    </div>
                </div>

                <!-- ==================== BLOQUE 2 (P3) ==================== -->
                <div class="step-page" data-step="2">
                    <div class="block">
                        <h2>Bloque II-A · Vínculos y pertenencia (Parte 1)</h2>
                    </div>
                    <div class="question">
                        <label>P3. En el último mes, ¿en qué momento sentiste que pertenecías a algo más grande que vos mismo/a? <span class="required-mark">*</span></label>
                        <div class="options">
                            <label><input type="radio" name="p3_pertenencia" value="A" required class="radio-opcion" data-comentario="comentario_p3"> A. En un grupo de amigos o de gente en quien confío</label>
                            <label><input type="radio" name="p3_pertenencia" value="B" class="radio-opcion" data-comentario="comentario_p3"> B. En la Eucaristía u otro momento de oración o liturgia</label>
                            <label><input type="radio" name="p3_pertenencia" value="C" class="radio-opcion" data-comentario="comentario_p3"> C. Cuando ayudé a alguien que lo necesitaba</label>
                            <label><input type="radio" name="p3_pertenencia" value="D" class="radio-opcion" data-comentario="comentario_p3"> D. En las redes sociales, siguiendo algo que me apasiona</label>
                            <label><input type="radio" name="p3_pertenencia" value="E" class="radio-opcion" data-comentario="comentario_p3"> E. En la naturaleza</label>
                            <label><input type="radio" name="p3_pertenencia" value="F" class="radio-opcion" data-comentario="comentario_p3"> F. En la práctica de un deporte</label>
                            <label><input type="radio" name="p3_pertenencia" value="G" class="radio-opcion" data-comentario="comentario_p3"> G. En experiencias de silencio o reflexión personal</label>
                            <label><input type="radio" name="p3_pertenencia" value="H" class="radio-opcion" data-comentario="comentario_p3"> H. No recuerdo haber sentido eso en el último mes</label>
                            <label><input type="radio" name="p3_pertenencia" value="OTRO" class="radio-opcion radio-otro" data-comentario="comentario_p3"> I. Otro (especificar en el campo de abajo)</label>
                        </div>

                        <div class="comentario-original" id="comentario_p3">
                            <label><strong>¿Quieres añadir algo sobre tu experiencia de pertenencia?</strong> 
                                <span id="requerido_p3" class="required-mark" style="display: none;">*</span>
                            </label>
                            <textarea name="comentario_bloque2" id="textarea_p3" rows="3" placeholder="Opcional: comparte aquí cualquier comentario, opinión o experiencia relacionada con tu respuesta..." maxlength="500"></textarea>
                            <small id="texto_ayuda_p3">Este campo es opcional y nos ayuda a entender mejor tus respuestas.</small>
                        </div>
                    </div>
                </div>

                <!-- ==================== BLOQUE 3 (P4) ==================== -->
                <div class="step-page" data-step="3">
                    <div class="block">
                        <h2>Bloque II-B · Vínculos y pertenencia (Parte 2)</h2>
                    </div>
                    <div class="question">
                        <label>P4. Si hoy te invitáramos a un espacio nuevo, ¿qué es lo que más te atraería? <span class="required-mark">*</span></label>
                        <div class="options">
                            <label><input type="radio" name="p4_atraccion" value="A" required class="radio-opcion" data-comentario="comentario_p4"> A. Conocer personas con valores similares y generar vínculos de confianza</label>
                            <label><input type="radio" name="p4_atraccion" value="B" class="radio-opcion" data-comentario="comentario_p4"> B. Un espacio donde pueda estar en silencio y pensar sin presiones</label>
                            <label><input type="radio" name="p4_atraccion" value="C" class="radio-opcion" data-comentario="comentario_p4"> C. Aprender sobre liderazgo</label>
                            <label><input type="radio" name="p4_atraccion" value="D" class="radio-opcion" data-comentario="comentario_p4"> D. Desarrollar habilidades técnicas</label>
                            <label><input type="radio" name="p4_atraccion" value="E" class="radio-opcion" data-comentario="comentario_p4"> E. Aprender sobre emprendimiento</label>
                            <label><input type="radio" name="p4_atraccion" value="F" class="radio-opcion" data-comentario="comentario_p4"> F. Participar en un espacio donde no me juzguen y pueda ser como soy</label>
                            <label><input type="radio" name="p4_atraccion" value="G" class="radio-opcion" data-comentario="comentario_p4"> G. Integrarme a un proyecto concreto donde mi participación genere un cambio real</label>
                            <label><input type="radio" name="p4_atraccion" value="OTRO" class="radio-opcion radio-otro" data-comentario="comentario_p4"> I. Otro (especificar en el campo de abajo)</label>
                        </div>

                        <div class="comentario-original" id="comentario_p4">
                            <label><strong>¿Quieres añadir algo sobre qué te atraería a un espacio nuevo?</strong> 
                                <span id="requerido_p4" class="required-mark" style="display: none;">*</span>
                            </label>
                            <textarea name="comentario_bloque3" id="textarea_p4" rows="3" placeholder="Opcional: comparte aquí cualquier comentario, opinión o experiencia relacionada con tu respuesta..." maxlength="500"></textarea>
                            <small id="texto_ayuda_p4">Este campo es opcional y nos ayuda a entender mejor tus respuestas.</small>
                        </div>
                    </div>
                </div>

                <!-- ==================== BLOQUE 4 (P4b-1) ==================== -->
                <div class="step-page" data-step="4">
                    <div class="block">
                        <h2>Bloque II-C1 · Contexto de vida (Parte 1)</h2>
                        <p style="font-size: 0.85em; color: #718096; margin-top: -10px; margin-bottom: 15px;">Pregunta para el cruce con actores económicos e institucionales del departamento.</p>
                    </div>
                    
                    <div class="question">
                        <label>P4b-1. ¿Cuál es tu situación principal ahora? <span class="required-mark">*</span></label>
                        <div class="options">
                            <label><input type="radio" name="p4b_situacion" value="A" required class="radio-opcion" data-comentario="comentario_p4b1"> A. Estudio (secundaria, terciario o universidad)</label>
                            <label><input type="radio" name="p4b_situacion" value="B" class="radio-opcion" data-comentario="comentario_p4b1"> B. Trabajo</label>
                            <label><input type="radio" name="p4b_situacion" value="C" class="radio-opcion" data-comentario="comentario_p4b1"> C. Estudio y trabajo</label>
                            <label><input type="radio" name="p4b_situacion" value="D" class="radio-opcion" data-comentario="comentario_p4b1"> D. Busco trabajo o estudio</label>
                            <label><input type="radio" name="p4b_situacion" value="E" class="radio-opcion" data-comentario="comentario_p4b1"> E. Hogar o cuidado de familia</label>
                            <label><input type="radio" name="p4b_situacion" value="F" class="radio-opcion" data-comentario="comentario_p4b1"> F. Otra situación</label>
                            <label><input type="radio" name="p4b_situacion" value="OTRO" class="radio-opcion radio-otro" data-comentario="comentario_p4b1"> I. Otro (especificar en el campo de abajo)</label>
                        </div>

                        <div class="comentario-original" id="comentario_p4b1">
                            <label><strong>¿Quieres añadir algo sobre tu situación principal actual?</strong> 
                                <span id="requerido_p4b1" class="required-mark" style="display: none;">*</span>
                            </label>
                            <textarea name="comentario_p4b1" id="textarea_p4b1" rows="3" placeholder="Opcional: comparte aquí cualquier comentario..." maxlength="500"></textarea>
                            <small id="texto_ayuda_p4b1">Este campo es opcional y nos ayuda a entender mejor tu realidad.</small>
                        </div>
                    </div>
                </div>

                <!-- ==================== BLOQUE 5 (P4b-2) ==================== -->
                <div class="step-page" data-step="5">
                    <div class="block">
                        <h2>Bloque II-C2 · Contexto de vida (Parte 2)</h2>
                        <p style="font-size: 0.85em; color: #718096; margin-top: -10px; margin-bottom: 15px;">Pregunta para el cruce con actores económicos e institucionales del departamento.</p>
                    </div>
                    
                    <div class="question">
                        <label>P4b-2. Si pensás en formarte o trabajar en los próximos años, ¿en qué área te ves más? <span class="required-mark">*</span></label>
                        <div class="options">
                            <label><input type="radio" name="p4b_area" value="A" required class="radio-opcion" data-comentario="comentario_p4b2"> A. Salud y cuidado de personas</label>
                            <label><input type="radio" name="p4b_area" value="B" class="radio-opcion" data-comentario="comentario_p4b2"> B. Tecnología, sistemas o datos</label>
                            <label><input type="radio" name="p4b_area" value="C" class="radio-opcion" data-comentario="comentario_p4b2"> C. Agro, campo o medio ambiente</label>
                            <label><input type="radio" name="p4b_area" value="D" class="radio-opcion" data-comentario="comentario_p4b2"> D. Educación o trabajo social</label>
                            <label><input type="radio" name="p4b_area" value="E" class="radio-opcion" data-comentario="comentario_p4b2"> E. Comercio, servicios o logística</label>
                            <label><input type="radio" name="p4b_area" value="F" class="radio-opcion" data-comentario="comentario_p4b2"> F. Arte, comunicación o medios</label>
                            <label><input type="radio" name="p4b_area" value="G" class="radio-opcion" data-comentario="comentario_p4b2"> G. Construcción, industria o energía</label>
                            <label><input type="radio" name="p4b_area" value="H" class="radio-opcion" data-comentario="comentario_p4b2"> H. Emprendimiento propio</label>
                            <label><input type="radio" name="p4b_area" value="I" class="radio-opcion" data-comentario="comentario_p4b2"> I. Todavía no lo tengo claro</label>
                            <label><input type="radio" name="p4b_area" value="OTRO" class="radio-opcion radio-otro" data-comentario="comentario_p4b2"> I. Otro (especificar en el campo de abajo)</label>
                        </div>

                        <div class="comentario-original" id="comentario_p4b2">
                            <label><strong>¿Quieres añadir algo sobre tu área de interés o tus metas a futuro?</strong> 
                                <span id="requerido_p4b2" class="required-mark" style="display: none;">*</span>
                            </label>
                            <textarea name="comentario_p4b2" id="textarea_p4b2" rows="3" placeholder="Opcional: comparte aquí cualquier comentario..." maxlength="500"></textarea>
                            <small id="texto_ayuda_p4b2">Este campo es opcional y nos ayuda a entender mejor tus intereses.</small>
                        </div>
                    </div>
                </div>

                <!-- ==================== BLOQUE 6 (P4b-3) ==================== -->
                <div class="step-page" data-step="6">
                    <div class="block">
                        <h2>Bloque II-C3 · Contexto de vida (Parte 3)</h2>
                        <p style="font-size: 0.85em; color: #718096; margin-top: -10px; margin-bottom: 15px;">Pregunta para el cruce con actores económicos e institucionales del departamento.</p>
                    </div>
                    
                    <div class="question">
                        <label>P4b-3. ¿Estarías dispuesto/a a formarte o trabajar en otro distrito de Itapúa si hubiera una buena oportunidad? <span class="required-mark">*</span></label>
                        <div class="options">
                            <label><input type="radio" name="p4b_movilidad" value="A" required class="radio-opcion" data-comentario="comentario_p4b3"> A. Sí, sin problema</label>
                            <label><input type="radio" name="p4b_movilidad" value="B" class="radio-opcion" data-comentario="comentario_p4b3"> B. Sí, pero solo si es realmente buena</label>
                            <label><input type="radio" name="p4b_movilidad" value="C" class="radio-opcion" data-comentario="comentario_p4b3"> C. Preferiría quedarme cerca</label>
                            <label><input type="radio" name="p4b_movilidad" value="D" class="radio-opcion" data-comentario="comentario_p4b3"> D. No, necesito quedarme en mi zona</label>
                            <label><input type="radio" name="p4b_movilidad" value="OTRO" class="radio-opcion radio-otro" data-comentario="comentario_p4b3"> I. Otro (especificar en el campo de abajo)</label>
                        </div>

                        <div class="comentario-original" id="comentario_p4b3">
                            <label><strong>¿Quieres añadir algo sobre tu disponibilidad para moverte o tus motivos para quedarte?</strong> 
                                <span id="requerido_p4b3" class="required-mark" style="display: none;">*</span>
                            </label>
                            <textarea name="comentario_p4b3" id="textarea_p4b3" rows="3" placeholder="Opcional: comparte aquí cualquier comentario..." maxlength="500"></textarea>
                            <small id="texto_ayuda_p4b3">Este campo es opcional y nos ayuda a entender mejor las barreras o facilitadores para la movilidad.</small>
                        </div>
                    </div>
                </div>

                <!-- ==================== BLOQUE 7 (P5) ==================== -->
                <div class="step-page" data-step="7">
                    <div class="block">
                        <h2>Bloque III · Espiritualidad</h2>
                    </div>
                    <div class="question">
                        <label>P5. ¿Con qué frecuencia buscás respuestas a tus grandes preguntas en la fe? <span class="required-mark">*</span></label>
                        <div class="options">
                            <label><input type="radio" name="p5_espiritualidad" value="A" required class="radio-opcion" data-comentario="comentario_p5"> A. Es mi principal referencia</label>
                            <label><input type="radio" name="p5_espiritualidad" value="B" class="radio-opcion" data-comentario="comentario_p5"> B. A veces recurro a la fe, pero no siempre comprendo el lenguaje</label>
                            <label><input type="radio" name="p5_espiritualidad" value="C" class="radio-opcion" data-comentario="comentario_p5"> C. Prefiero buscar en otros ámbitos</label>
                            <label><input type="radio" name="p5_espiritualidad" value="D" class="radio-opcion" data-comentario="comentario_p5"> D. No suelo hacerme esas preguntas</label>
                            <label><input type="radio" name="p5_espiritualidad" value="OTRO" class="radio-opcion radio-otro" data-comentario="comentario_p5"> I. Otro (especificar en el campo de abajo)</label>
                        </div>

                        <div class="comentario-original" id="comentario_p5">
                            <label><strong>¿Quieres añadir algo sobre tu espiritualidad o búsqueda de sentido?</strong> 
                                <span id="requerido_p5" class="required-mark" style="display: none;">*</span>
                            </label>
                            <textarea name="comentario_bloque4" id="textarea_p5" rows="3" placeholder="Opcional: comparte aquí cualquier comentario..." maxlength="500"></textarea>
                            <small id="texto_ayuda_p5">Este campo es opcional y nos ayuda a entender mejor tus respuestas.</small>
                        </div>
                    </div>
                </div>

                <!-- ==================== BLOQUE 8 (P6) ==================== -->
                <div class="step-page" data-step="8">
                    <div class="block">
                        <h2>Bloque IV · Familia</h2>
                    </div>
                    <div class="question">
                        <label>P6. En momentos de crisis o decisiones importantes, ¿qué representa tu familia para vos? <span class="required-mark">*</span></label>
                        <div class="options">
                            <label><input type="radio" name="p6_familia" value="A" required class="radio-opcion" data-comentario="comentario_p6"> A. Mi principal apoyo y refugio</label>
                            <label><input type="radio" name="p6_familia" value="B" class="radio-opcion" data-comentario="comentario_p6"> B. Un lugar de tensiones</label>
                            <label><input type="radio" name="p6_familia" value="C" class="radio-opcion" data-comentario="comentario_p6"> C. No entienden completamente mi realidad</label>
                            <label><input type="radio" name="p6_familia" value="D" class="radio-opcion" data-comentario="comentario_p6"> D. Una fuente de motivación</label>
                            <label><input type="radio" name="p6_familia" value="E" class="radio-opcion" data-comentario="comentario_p6"> E. No tengo una familia de referencia clara</label>
                            <label><input type="radio" name="p6_familia" value="OTRO" class="radio-opcion radio-otro" data-comentario="comentario_p6"> I. Otro (especificar en el campo de abajo)</label>
                        </div>

                        <div class="comentario-original" id="comentario_p6">
                            <label><strong>¿Quieres añadir algo sobre tu experiencia familiar?</strong> 
                                <span id="requerido_p6" class="required-mark" style="display: none;">*</span>
                            </label>
                            <textarea name="comentario_bloque5" id="textarea_p6" rows="3" placeholder="Opcional: comparte aquí cualquier comentario..." maxlength="500"></textarea>
                            <small id="texto_ayuda_p6">Este campo es opcional y nos ayuda a entender mejor tus respuestas.</small>
                        </div>
                    </div>
                </div>

                <!-- ==================== BLOQUE 9 (P7) ==================== -->
                <div class="step-page" data-step="9">
                    <div class="block">
                        <h2>Bloque V · Proyecto de vida</h2>
                    </div>
                    <div class="question">
                        <label>P7. Al proyectar tu vida a 10 años, ¿cuál es tu prioridad fundamental? <span class="required-mark">*</span></label>
                        <div class="options">
                            <label><input type="radio" name="p7_proyecto" value="A" required class="radio-opcion" data-comentario="comentario_p7"> A. Estabilidad económica y desarrollo profesional</label>
                            <label><input type="radio" name="p7_proyecto" value="B" class="radio-opcion" data-comentario="comentario_p7"> B. Formar una familia sólida</label>
                            <label><input type="radio" name="p7_proyecto" value="C" class="radio-opcion" data-comentario="comentario_p7"> C. Impacto positivo en mi comunidad</label>
                            <label><input type="radio" name="p7_proyecto" value="D" class="radio-opcion" data-comentario="comentario_p7"> D. Paz interior y sentido profundo</label>
                            <label><input type="radio" name="p7_proyecto" value="E" class="radio-opcion" data-comentario="comentario_p7"> E. Todavía no tengo una dirección clara</label>
                            <label><input type="radio" name="p7_proyecto" value="OTRO" class="radio-opcion radio-otro" data-comentario="comentario_p7"> I. Otro (especificar en el campo de abajo)</label>
                        </div>

                        <div class="comentario-original" id="comentario_p7">
                            <label><strong>¿Quieres añadir algo sobre tu proyecto de vida a futuro?</strong> 
                                <span id="requerido_p7" class="required-mark" style="display: none;">*</span>
                            </label>
                            <textarea name="comentario_bloque6" id="textarea_p7" rows="3" placeholder="Opcional: comparte aquí cualquier comentario..." maxlength="500"></textarea>
                            <small id="texto_ayuda_p7">Este campo es opcional y nos ayuda a entender mejor tus respuestas.</small>
                        </div>
                    </div>
                </div>

                <!-- ==================== BLOQUE 10 (P8) ==================== -->
                <div class="step-page" data-step="10">
                    <div class="block">
                        <h2>Bloque VI · Vocación</h2>
                    </div>
                    <div class="question">
                        <label>P8. ¿Cómo te sentís respecto a tu vocación o misión en el mundo? <span class="required-mark">*</span></label>
                        <div class="options">
                            <label><input type="radio" name="p8_vocacion" value="A" required class="radio-opcion" data-comentario="comentario_p8"> A. Tengo una misión clara y trabajo para cumplirla</label>
                            <label><input type="radio" name="p8_vocacion" value="B" class="radio-opcion" data-comentario="comentario_p8"> B. Miedo a equivocarme y desperdiciar mi vida</label>
                            <label><input type="radio" name="p8_vocacion" value="C" class="radio-opcion" data-comentario="comentario_p8"> C. Presionado por lo que esperan de mí</label>
                            <label><input type="radio" name="p8_vocacion" value="D" class="radio-opcion" data-comentario="comentario_p8"> D. Me gustaría saber si Dios tiene un plan para mí</label>
                            <label><input type="radio" name="p8_vocacion" value="E" class="radio-opcion" data-comentario="comentario_p8"> E. Busco una profesión que me dé estabilidad</label>
                            <label><input type="radio" name="p8_vocacion" value="OTRO" class="radio-opcion radio-otro" data-comentario="comentario_p8"> I. Otro (especificar en el campo de abajo)</label>
                        </div>

                        <div class="comentario-original" id="comentario_p8">
                            <label><strong>¿Quieres añadir algo sobre tu vocación o misión en el mundo?</strong> 
                                <span id="requerido_p8" class="required-mark" style="display: none;">*</span>
                            </label>
                            <textarea name="comentario_bloque7" id="textarea_p8" rows="3" placeholder="Opcional: comparte aquí cualquier comentario..." maxlength="500"></textarea>
                            <small id="texto_ayuda_p8">Este campo es opcional y nos ayuda a entender mejor tus respuestas.</small>
                        </div>
                    </div>
                </div>

                <!-- ==================== BLOQUE 11 (P9) ==================== -->
                <div class="step-page" data-step="11">
                    <div class="block">
                        <h2>Bloque VII · Crítica institucional</h2>
                    </div>
                    <div class="question">
                        <label>P9. ¿Qué es lo que más aleja a los jóvenes de la Iglesia? (Puedes elegir hasta dos opciones)</label>
                        <div class="options">
                            <label><input type="checkbox" name="p9_critica[]" value="A"> A. Lenguaje anticuado</label>
                            <label><input type="checkbox" name="p9_critica[]" value="B"> B. Falta de coherencia</label>
                            <label><input type="checkbox" name="p9_critica[]" value="C"> C. No trata temas importantes</label>
                            <label><input type="checkbox" name="p9_critica[]" value="D"> D. Lugar de reglas y prohibiciones</label>
                            <label><input type="checkbox" name="p9_critica[]" value="E"> E. Los adultos no nos escuchan</label>
                            <label><input type="checkbox" name="p9_critica[]" value="F"> F. Malas experiencias personales</label>
                            <label><input type="checkbox" name="p9_critica[]" value="G"> G. No me siento alejado</label>
                            <label><input type="checkbox" name="p9_critica[]" value="OTRO" class="checkbox-otro" data-comentario="comentario_p9"> I. Otro (especificar en el campo de abajo)</label>
                        </div>

                        <div class="comentario-original" id="comentario_p9">
                            <label><strong>¿Quieres añadir algo sobre tu visión de la Iglesia?</strong> 
                                <span id="requerido_p9" class="required-mark" style="display: none;">*</span>
                            </label>
                            <textarea name="comentario_bloque8" id="textarea_p9" rows="3" placeholder="Opcional: comparte aquí cualquier comentario..." maxlength="500"></textarea>
                            <small id="texto_ayuda_p9">Este campo es opcional y nos ayuda a entender mejor tus respuestas.</small>
                        </div>
                        <small>Selecciona hasta dos opciones</small>
                    </div>
                </div>

                <!-- ==================== BLOQUE 12 (P10) ==================== -->
                <div class="step-page" data-step="12">
                    <div class="block">
                        <h2>Bloque VIII · Esperanza social</h2>
                    </div>
                    <div class="question">
                        <label>P10. Mirando al Paraguay de los próximos 5 años, ¿qué sentimiento predomina en vos? <span class="required-mark">*</span></label>
                        <div class="scale">
                            <label class="scale-option scale-1"><input type="radio" name="p10_esperanza" value="1" required><span>1 - Muy bajo<br><small>Miedo o angustia. Siento que mi futuro está fuera del país.</small></span></label>
                            <label class="scale-option scale-2"><input type="radio" name="p10_esperanza" value="2"><span>2 - Bajo<br><small>Preocupación. No veo muchas oportunidades de futuro aquí.</small></span></label>
                            <label class="scale-option scale-3"><input type="radio" name="p10_esperanza" value="3"><span>3 - Medio<br><small>Ni optimismo ni pesimismo; prefiero esperar y ver.</small></span></label>
                            <label class="scale-option scale-4"><input type="radio" name="p10_esperanza" value="4"><span>4 - Alto<br><small>Esperanza. Creo que las cosas pueden mejorar si trabajamos.</small></span></label>
                            <label class="scale-option scale-5"><input type="radio" name="p10_esperanza" value="5"><span>5 - Muy alto<br><small>Entusiasmo. Quiero construir mi futuro en Paraguay.</small></span></label>
                        </div>
                    </div>
                    
                    <div class="question">
                        <label><strong>¿Quieres añadir algo sobre tu esperanza en el Paraguay?</strong></label>
                        <textarea name="comentario_bloque9" rows="3" placeholder="Opcional: comparte aquí cualquier comentario..." maxlength="500"></textarea>
                        <small>Este campo es opcional y nos ayuda a entender mejor tus respuestas.</small>
                    </div>
                    
                    <div class="question">
                        <label><strong>¿Hay algo que quisieras decirnos que ninguna de estas preguntas te permitió decir?</strong></label>
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

    <!-- ==================== SCRIPTS ORGANIZADOS ==================== -->
    <script src="js/consentimiento.js"></script>
    <script src="js/limite-checkboxes.js"></script>
    <script src="js/validador-edad.js"></script>
    <script src="js/selector-parroquia.js"></script>
    <script src="js/navegacion-pasos.js"></script>
    <script src="js/otro.js"></script>
</body>
</html>