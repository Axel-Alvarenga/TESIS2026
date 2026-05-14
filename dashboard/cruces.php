<?php
require_once 'auth.php';
require_once 'functions.php';
require_once '../db_config.php';

$titulo = 'Tabla Dinamica';
$icono = 'fa-table';
$active = 'cruces';

$fecha_desde = $_GET['fecha_desde'] ?? date('Y-m-d', strtotime('-30 days'));
$fecha_hasta = $_GET['fecha_hasta'] ?? date('Y-m-d');

require_once 'header.php';
?>

<link rel="stylesheet" href="css/cruces.css">

<!-- FILTROS DE FECHA -->
<div class="filtros-card">
    <h3>Filtros de fecha</h3>
    <div class="filtros-grid">
        <div class="filtro-group">
            <label>Desde</label>
            <input type="date" id="fecha_desde" value="<?= $fecha_desde ?>">
        </div>
        <div class="filtro-group">
            <label>Hasta</label>
            <input type="date" id="fecha_hasta" value="<?= $fecha_hasta ?>">
        </div>
        <div class="filtro-group botones-group">
            <button class="btn-filtrar" onclick="cargarDatos()">Aplicar</button>
        </div>
    </div>
</div>

<!-- SELECTORES -->
<div class="selector-panel">
    <div class="grid-2cols">
        <div class="col">
            <label>Variables en FILAS</label>
            <div id="selectoresFila" class="multi-select"></div>
        </div>
        <div class="col">
            <label>Variables en COLUMNAS</label>
            <div id="selectoresColumna" class="multi-select"></div>
        </div>
    </div>

    <div class="filtros-extra">
        <div class="filtros-header">
            <h4>Filtros adicionales (opcional)</h4>
            <button class="btn-filtro" onclick="mostrarModalFiltros()">+ Agregar filtro</button>
        </div>
        <div id="filtrosLista" class="filtros-lista"></div>
    </div>

    <div style="display: flex; justify-content: flex-end;">
        <button class="btn-aplicar" onclick="generarTablas()">Generar tablas</button>
    </div>
</div>

<!-- TABLAS -->
<div id="tablasContainer" class="tabla-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px;">
        <h3>Resultados</h3>
        <div>
            <button class="boton-copiar" onclick="copiarTodasLasTablas()">Copiar todas</button>
            <button class="boton-excel" onclick="exportarTodasLasTablas()">Exportar todas a Excel</button>
        </div>
    </div>
    <div id="tablasResultado"></div>
    <div id="resumenTabla" class="resumen-card"></div>
</div>

<!-- Modal filtros -->
<div id="modalFiltros" class="modal">
    <div class="modal-content">
        <h3>Agregar filtro</h3>
        <label>Campo</label>
        <select id="filtroCampo">
            <option value="p3_pertenencia">P3 - Pertenencia</option>
            <option value="p4_atraccion">P4 - Atraccion</option>
            <option value="p5_espiritualidad">P5 - Espiritualidad</option>
            <option value="p6_familia">P6 - Familia</option>
            <option value="p7_proyecto">P7 - Proyecto de vida</option>
            <option value="p8_vocacion">P8 - Vocacion</option>
            <option value="p10_esperanza">P10 - Esperanza</option>
            <option value="p1_anio">Ano de nacimiento</option>
            <option value="p2_parroquia">Parroquia</option>
        </select>
        <label>Valor</label>
        <input type="text" id="filtroValor" placeholder="Ej: A, B, 1, 2...">
        <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 15px;">
            <button onclick="cerrarModalFiltros()" style="padding:8px 20px; background:#e5e7eb; border:none; border-radius:8px;">Cancelar</button>
            <button onclick="agregarFiltro()" style="padding:8px 20px; background:#4f46e5; color:white; border:none; border-radius:8px;">Agregar</button>
        </div>
    </div>
</div>

<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
<script src="js/cruces.js"></script>

<?php require_once 'footer.php'; ?>