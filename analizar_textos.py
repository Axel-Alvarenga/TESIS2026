import pymysql
import pandas as pd
import re
from collections import Counter

# Conectar a MySQL
conn = pymysql.connect(
    host='localhost',
    user='root',
    password='',
    database='voces_del_sur',
    charset='utf8mb4'
)

# Cargar respuestas
df = pd.read_sql("SELECT id, campo_libre FROM respuestas WHERE campo_libre IS NOT NULL AND campo_libre != ''", conn)
conn.close()

print(f"📊 Total de respuestas analizadas: {len(df)}")

if len(df) == 0:
    print("⚠️ No hay respuestas para analizar")
    exit()

# Mostrar textos
print("\n📝 TEXTOS A ANALIZAR:")
for i, row in df.iterrows():
    texto = row['campo_libre'][:80] if row['campo_libre'] else ''
    print(f"  ID {row['id']}: {texto}...")


palabras_negativas = {
    'malo', 'mal', 'problema', 'dificultad', 'triste', 'preocupación', 
    'miedo', 'injusticia', 'falta', 'no', 'nunca', 'nada', 'decepcion', 
    'cansado', 'agotado', 'frustra', 'solo', 'soledad', 'ignorado', 
    'abandonado', 'hipocresía', 'mentira', 'falso', 'odio', 'molesta', 
    'enoja', 'rabia', 'tristeza', 'llorar', 'sufro', 'difícil', 'duro', 
    'pesado', 'harto', 'feo', 'horrible', 'no me gusta'
}

palabras_positivas = {
    'bueno', 'bien', 'gracias', 'mejor', 'importante', 'ayuda', 'apoyo', 
    'agradezco', 'valioso', 'esperanza', 'positivo', 'feliz', 'contento', 
    'gusta', 'encanta', 'excelente', 'maravilloso', 'amor'
}

def limpiar_texto(texto):
    if not texto:
        return ''
    texto = texto.lower()
    texto = re.sub(r'[^a-záéíóúñü\s]', '', texto)
    texto = re.sub(r'\s+', ' ', texto)
    return texto.strip()

def analizar_sentimiento(texto_original):
    if not texto_original:
        return 'neutral'
    
    texto = limpiar_texto(texto_original)
    
    # Verificar frases negativas específicas
    frases_negativas = ['no me gusta', 'no me siento', 'me siento solo', 'no encuentro', 'no me siento']
    texto_lower = texto_original.lower()
    
    for frase in frases_negativas:
        if frase in texto_lower:
            return 'negativo'
    
    # Contar palabras
    negativas = sum(1 for p in palabras_negativas if p in texto)
    positivas = sum(1 for p in palabras_positivas if p in texto)
    
    # Reglas de clasificación
    if negativas > positivas:
        return 'negativo'
    elif positivas > negativas:
        return 'positivo'
    elif 'no' in texto and ('iglesia' in texto or 'iglesia' in texto_lower):
        return 'negativo'
    else:
        return 'neutral'

# Analizar cada respuesta
resultados = []
for idx, row in df.iterrows():
    sentimiento = analizar_sentimiento(row['campo_libre'])
    resultados.append({
        'id': row['id'],
        'texto': row['campo_libre'],
        'sentimiento': sentimiento
    })
    print(f"  ID {row['id']}: {sentimiento.upper()} - \"{row['campo_libre'][:60]}...\"")

# Estadísticas
print("\n📊 ESTADÍSTICAS FINALES:")
sentimientos = {'positivo': 0, 'negativo': 0, 'neutral': 0}
for r in resultados:
    sentimientos[r['sentimiento']] += 1

for sentimiento, count in sentimientos.items():
    if count > 0:
        porcentaje = (count / len(df)) * 100
        print(f"  {sentimiento}: {count} respuestas ({porcentaje:.1f}%)")

# Guardar en base de datos
conn2 = pymysql.connect(
    host='localhost',
    user='root',
    password='',
    database='voces_del_sur',
    charset='utf8mb4'
)
cursor = conn2.cursor()

# Crear tabla si no existe
cursor.execute("""
    CREATE TABLE IF NOT EXISTS analisis_texto (
        id INT AUTO_INCREMENT PRIMARY KEY,
        respuesta_id INT,
        texto_original TEXT,
        sentimiento VARCHAR(20),
        palabras_clave TEXT,
        tema_principal VARCHAR(100),
        UNIQUE KEY unique_respuesta (respuesta_id)
    )
""")

for r in resultados:
    cursor.execute("""
        INSERT INTO analisis_texto (respuesta_id, texto_original, sentimiento, palabras_clave, tema_principal) 
        VALUES (%s, %s, %s, %s, %s)
        ON DUPLICATE KEY UPDATE sentimiento = VALUES(sentimiento)
    """, (r['id'], r['texto'][:500], r['sentimiento'], '', 'general'))

conn2.commit()
conn2.close()

print("\n✅ Análisis completado y guardado en la base de datos")
print("\n💡 Ahora abre: http://localhost:8080/dashboard_nlp.php")

# Mostrar resumen de sentimientos negativos
negativos = [r for r in resultados if r['sentimiento'] == 'negativo']
if negativos:
    print("\n⚠️ RESPUESTAS NEGATIVAS DETECTADAS:")
    for r in negativos:
        print(f"  • {r['texto'][:100]}...")