<?php $css_files = []; require_once APP_PATH . '/Views/layouts/header.php'; ?>

<body class="simple-layout">

<?php require_once APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="app-main stats-page">
    <!-- Header -->
    <div class="stats-header">
        <div>
            <h1>Estadísticas y Gráficos</h1>
            <p class="stats-subtitle">Informe de Novedades</p>
        </div>
    </div>

    <style>
    .filtro-grafico {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        padding: 0.75rem;
        background: #f8fafc;
        border-radius: 8px;
    }
    
    .filtro-grafico label {
        font-weight: 600;
        color: #475569;
        font-size: 0.875rem;
        min-width: 60px;
    }
    
    .filtro-grafico select {
        flex: 1;
        padding: 0.5rem 0.75rem;
        border: 2px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.875rem;
        cursor: pointer;
        background: white;
        transition: all 0.2s;
    }
    
    .filtro-grafico select:hover {
        border-color: #cbd5e1;
    }
    
    .filtro-grafico select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    </style>

    <?php if ($stats['total_novedades'] == 0): ?>
    <div class="empty-state">
        <p>No hay novedades registradas aún.</p>
        <a href="<?php echo base_url('novedades/crear'); ?>" class="btn-primary">Registrar Primera Novedad</a>
    </div>
    <?php else: ?>

    <!-- Resumen Principal - Dos columnas -->
    <div class="main-stats-container">
        <div class="main-stat">
            <div class="main-stat-value"><?php echo number_format($stats['total_novedades'], 0, ',', '.'); ?></div>
            <div class="main-stat-label">Total de Novedades Registradas</div>
        </div>

        <div class="main-stat">
            <div class="main-stat-value"><?php echo number_format($totalEmpleadosConNovedades ?? 0, 0, ',', '.'); ?></div>
            <div class="main-stat-label">Empleados con Novedades</div>
            <div style="margin-top: 1.5rem;">
                <a href="<?php echo base_url('usuarios/lista'); ?>" class="btn-primary btn-usuarios" style="display: inline-flex; align-items: center; gap: 0.75rem; padding: 0.875rem 1.5rem; font-size: 0.95rem; background: linear-gradient(135deg, #3b82f6, #1e40af); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; transition: all 0.3s; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); border: none;">
                    Ver Estadísticas por Empleado
                </a>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="stats-grid">
        
        <!-- Novedades por Sede -->
        <div class="stat-card">
            <div class="stat-card-header">
                <h3>Novedades por Sede</h3>
            </div>
            <div class="stat-card-body">
                <div class="filtro-grafico">
                    <label>Período:</label>
                    <select onchange="aplicarFiltroGrafico('sede', this.value)">
                        <option value="todos" <?php echo ($filtros['sede'] === 'todos') ? 'selected' : ''; ?>>Todos</option>
                        <option value="ultimo_mes" <?php echo ($filtros['sede'] === 'ultimo_mes') ? 'selected' : ''; ?>>Último mes</option>
                        <option value="3_meses" <?php echo ($filtros['sede'] === '3_meses') ? 'selected' : ''; ?>>Últimos 3 meses</option>
                        <option value="2025" <?php echo ($filtros['sede'] === '2025') ? 'selected' : ''; ?>>2025</option>
                        <option value="2026" <?php echo ($filtros['sede'] === '2026') ? 'selected' : ''; ?>>2026</option>
                    </select>
                </div>
                <div class="chart-container">
                    <canvas id="chartSede"></canvas>
                </div>
                <div class="stat-list">
                    <?php foreach ($stats['por_sede'] as $item): ?>
                        <div class="stat-item">
                            <span class="stat-item-label"><?php echo htmlspecialchars($item['sede']); ?></span>
                            <span class="stat-item-value"><?php echo $item['total']; ?> <small>(<?php echo $stats['total_novedades'] > 0 ? round(($item['total'] / $stats['total_novedades']) * 100, 1) : 0; ?>%)</small></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Tipos de Novedad -->
        <div class="stat-card">
            <div class="stat-card-header">
                <h3>Tipos de Novedad</h3>
            </div>
            <div class="stat-card-body">
                <div class="filtro-grafico">
                    <label>Período:</label>
                    <select onchange="aplicarFiltroGrafico('tipo', this.value)">
                        <option value="todos" <?php echo ($filtros['tipo'] === 'todos') ? 'selected' : ''; ?>>Todos</option>
                        <option value="ultimo_mes" <?php echo ($filtros['tipo'] === 'ultimo_mes') ? 'selected' : ''; ?>>Último mes</option>
                        <option value="3_meses" <?php echo ($filtros['tipo'] === '3_meses') ? 'selected' : ''; ?>>Últimos 3 meses</option>
                        <option value="2025" <?php echo ($filtros['tipo'] === '2025') ? 'selected' : ''; ?>>2025</option>
                        <option value="2026" <?php echo ($filtros['tipo'] === '2026') ? 'selected' : ''; ?>>2026</option>
                    </select>
                </div>
                <div class="chart-container">
                    <canvas id="chartTipo"></canvas>
                </div>
                <div class="stat-list">
                    <?php foreach ($stats['por_tipo'] as $item): ?>
                        <div class="stat-item">
                            <span class="stat-item-label"><?php echo htmlspecialchars($item['tipo']); ?></span>
                            <span class="stat-item-value"><?php echo $item['total']; ?> <small>(<?php echo $stats['total_novedades'] > 0 ? round(($item['total'] / $stats['total_novedades']) * 100, 1) : 0; ?>%)</small></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Estado de Justificación -->
        <div class="stat-card">
            <div class="stat-card-header">
                <h3>Estado de Justificación</h3>
            </div>
            <div class="stat-card-body">
                <div class="filtro-grafico">
                    <label>Período:</label>
                    <select onchange="aplicarFiltroGrafico('justificacion', this.value)">
                        <option value="todos" <?php echo ($filtros['justificacion'] === 'todos') ? 'selected' : ''; ?>>Todos</option>
                        <option value="ultimo_mes" <?php echo ($filtros['justificacion'] === 'ultimo_mes') ? 'selected' : ''; ?>>Último mes</option>
                        <option value="3_meses" <?php echo ($filtros['justificacion'] === '3_meses') ? 'selected' : ''; ?>>Últimos 3 meses</option>
                        <option value="2025" <?php echo ($filtros['justificacion'] === '2025') ? 'selected' : ''; ?>>2025</option>
                        <option value="2026" <?php echo ($filtros['justificacion'] === '2026') ? 'selected' : ''; ?>>2026</option>
                    </select>
                </div>
                <div class="chart-container">
                    <canvas id="chartJustificacion"></canvas>
                </div>
                <div class="stat-list">
                    <?php foreach ($stats['por_justificacion'] as $item): ?>
                        <div class="stat-item">
                            <span class="stat-item-label"><?php echo htmlspecialchars($item['justificacion']); ?></span>
                            <span class="stat-item-value"><?php echo $item['total']; ?> <small>(<?php echo $stats['total_novedades'] > 0 ? round(($item['total'] / $stats['total_novedades']) * 100, 1) : 0; ?>%)</small></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Novedades por Turno -->
        <div class="stat-card">
            <div class="stat-card-header">
                <h3>Novedades por Turno</h3>
            </div>
            <div class="stat-card-body">
                <div class="filtro-grafico">
                    <label>Período:</label>
                    <select onchange="aplicarFiltroGrafico('turno', this.value)">
                        <option value="todos" <?php echo ($filtros['turno'] === 'todos') ? 'selected' : ''; ?>>Todos</option>
                        <option value="ultimo_mes" <?php echo ($filtros['turno'] === 'ultimo_mes') ? 'selected' : ''; ?>>Último mes</option>
                        <option value="3_meses" <?php echo ($filtros['turno'] === '3_meses') ? 'selected' : ''; ?>>Últimos 3 meses</option>
                        <option value="2025" <?php echo ($filtros['turno'] === '2025') ? 'selected' : ''; ?>>2025</option>
                        <option value="2026" <?php echo ($filtros['turno'] === '2026') ? 'selected' : ''; ?>>2026</option>
                    </select>
                </div>
                <div class="chart-container">
                    <canvas id="chartTurno"></canvas>
                </div>
                <div class="stat-list">
                    <?php foreach ($stats['por_turno'] as $item): ?>
                        <div class="stat-item">
                            <span class="stat-item-label"><?php echo htmlspecialchars($item['turno']); ?></span>
                            <span class="stat-item-value"><?php echo $item['total']; ?> <small>(<?php echo $stats['total_novedades'] > 0 ? round(($item['total'] / $stats['total_novedades']) * 100, 1) : 0; ?>%)</small></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Áreas de Trabajo -->
        <div class="stat-card stat-card-wide">
            <div class="stat-card-header">
                <h3>Áreas de Trabajo (Top 10)</h3>
            </div>
            <div class="stat-card-body">
                <div class="filtro-grafico">
                    <label>Período:</label>
                    <select onchange="aplicarFiltroGrafico('area', this.value)">
                        <option value="todos" <?php echo ($filtros['area'] === 'todos') ? 'selected' : ''; ?>>Todos</option>
                        <option value="ultimo_mes" <?php echo ($filtros['area'] === 'ultimo_mes') ? 'selected' : ''; ?>>Último mes</option>
                        <option value="3_meses" <?php echo ($filtros['area'] === '3_meses') ? 'selected' : ''; ?>>Últimos 3 meses</option>
                        <option value="2025" <?php echo ($filtros['area'] === '2025') ? 'selected' : ''; ?>>2025</option>
                        <option value="2026" <?php echo ($filtros['area'] === '2026') ? 'selected' : ''; ?>>2026</option>
                    </select>
                </div>
                <div class="chart-container-horizontal">
                    <canvas id="chartAreas"></canvas>
                </div>
            </div>
        </div>

        <!-- Tendencia Mensual -->
        <div class="stat-card stat-card-wide">
            <div class="stat-card-header">
                <h3>Tendencia Mensual</h3>
            </div>
            <div class="stat-card-body">
                <div class="filtro-grafico">
                    <label>Período:</label>
                    <select onchange="aplicarFiltroGrafico('mensual', this.value)">
                        <option value="todos" <?php echo ($filtros['mensual'] === 'todos') ? 'selected' : ''; ?>>Todos</option>
                        <option value="ultimo_mes" <?php echo ($filtros['mensual'] === 'ultimo_mes') ? 'selected' : ''; ?>>Último mes</option>
                        <option value="3_meses" <?php echo ($filtros['mensual'] === '3_meses') ? 'selected' : ''; ?>>Últimos 3 meses</option>
                        <option value="2025" <?php echo ($filtros['mensual'] === '2025') ? 'selected' : ''; ?>>2025</option>
                        <option value="2026" <?php echo ($filtros['mensual'] === '2026') ? 'selected' : ''; ?>>2026</option>
                    </select>
                </div>
                <div class="chart-container-line">
                    <canvas id="chartMensual"></canvas>
                </div>
            </div>
        </div>

        <!-- Comparativa 2025 vs 2026 -->
        <div class="stat-card stat-card-wide">
            <div class="stat-card-header">
                <h3>Comparativa 2025 vs 2026</h3>
            </div>
            <div class="stat-card-body">
                <div class="chart-container-line">
                    <canvas id="chartComparativa"></canvas>
                </div>
            </div>
        </div>

    </div>

    <!-- Conclusiones -->
    <div class="conclusions">
        <h3>Conclusiones de FIA</h3>
        <p class="conclusions-subtitle">Análisis basado en el total de <?php echo number_format($stats['total_novedades'], 0, ',', '.'); ?> novedades registradas</p>
        <div class="conclusions-content">
            <?php
            // USAR DATOS SIN FILTROS PARA CONCLUSIONES
            $total = $stats['total_novedades'];
            
            // Tipos más comunes (top 3) - SIN FILTROS
            $tipos_comunes = array_slice($stats['conclusiones']['por_tipo'], 0, 3);
            $texto_tipos = '';
            if (count($tipos_comunes) >= 3) {
                $tipo1 = htmlspecialchars($tipos_comunes[0]['tipo']);
                $tipo2 = htmlspecialchars($tipos_comunes[1]['tipo']);
                $tipo3 = htmlspecialchars($tipos_comunes[2]['tipo']);
                $pct1 = $total > 0 ? round(($tipos_comunes[0]['total'] / $total) * 100, 1) : 0;
                $pct2 = $total > 0 ? round(($tipos_comunes[1]['total'] / $total) * 100, 1) : 0;
                $pct3 = $total > 0 ? round(($tipos_comunes[2]['total'] / $total) * 100, 1) : 0;
                $texto_tipos = "$tipo1 ($pct1%), seguidas por $tipo2 ($pct2%) y $tipo3 ($pct3%)";
            } elseif (count($tipos_comunes) == 2) {
                $tipo1 = htmlspecialchars($tipos_comunes[0]['tipo']);
                $tipo2 = htmlspecialchars($tipos_comunes[1]['tipo']);
                $pct1 = $total > 0 ? round(($tipos_comunes[0]['total'] / $total) * 100, 1) : 0;
                $pct2 = $total > 0 ? round(($tipos_comunes[1]['total'] / $total) * 100, 1) : 0;
                $texto_tipos = "$tipo1 ($pct1%) y $tipo2 ($pct2%)";
            } elseif (count($tipos_comunes) == 1) {
                $tipo1 = htmlspecialchars($tipos_comunes[0]['tipo']);
                $pct1 = $total > 0 ? round(($tipos_comunes[0]['total'] / $total) * 100, 1) : 0;
                $texto_tipos = "$tipo1 ($pct1%)";
            }
            
            // Justificación - SIN FILTROS
            $justificadas = 0; 
            $sin_justificar = 0;
            foreach ($stats['conclusiones']['por_justificacion'] as $item) {
                if ($item['justificacion'] === 'SI') $justificadas = $item['total'];
                elseif ($item['justificacion'] === 'NO') $sin_justificar = $item['total'];
            }
            $pct_justificadas = $total > 0 ? round(($justificadas / $total) * 100, 1) : 0;
            $pct_sin_justificar = $total > 0 ? round(($sin_justificar / $total) * 100, 1) : 0;
            
            // Descuento dominical - SIN FILTROS
            $con_descuento = 0;
            $sin_descuento = 0;
            foreach ($stats['conclusiones']['descontar_dominical'] as $item) {
                if ($item['descontar_dominical'] === 'SI') $con_descuento = $item['total'];
                elseif ($item['descontar_dominical'] === 'NO') $sin_descuento = $item['total'];
            }
            $pct_descuento = $total > 0 ? round(($con_descuento / $total) * 100, 1) : 0;
            ?>
            <p>
                <?php if ($texto_tipos): ?>
                    Las novedades más recurrentes son <?php echo $texto_tipos; ?>. 
                <?php endif; ?>
                <?php if ($justificadas > 0 || $sin_justificar > 0): ?>
                    El <?php echo $pct_justificadas; ?>% están justificadas y el <?php echo $pct_sin_justificar; ?>% sin justificación. 
                <?php endif; ?>
                <?php if ($pct_descuento > 0): ?>
                    El <?php echo $pct_descuento; ?>% de las novedades implican considerar el descuento del dominical. 
                <?php endif; ?>
                Estos datos son útiles para mejorar la administración del personal.
            </p>
        </div>
    </div>

    <?php endif; ?>

</main>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Almacenar instancias de gráficos
let charts = {};

// Función para aplicar filtro individual a cada gráfico SIN recargar la página
function aplicarFiltroGrafico(grafico, valor) {
    console.log('Aplicando filtro:', grafico, valor);
    
    // Mapear nombres de gráficos a IDs de canvas
    const graficosMap = {
        'sede': 'chartSede',
        'tipo': 'chartTipo',
        'justificacion': 'chartJustificacion',
        'turno': 'chartTurno',
        'area': 'chartAreas',  // Plural!
        'mensual': 'chartMensual'
    };
    
    const canvasId = graficosMap[grafico];
    if (!canvasId) {
        console.error('Gráfico no reconocido:', grafico);
        return;
    }
    
    // Mostrar indicador de carga
    const chartElement = document.getElementById(canvasId);
    if (!chartElement) {
        console.error('No se encontró el elemento del gráfico:', grafico, 'con ID:', canvasId);
        return;
    }
    
    const card = chartElement.closest('.stat-card');
    const body = card.querySelector('.stat-card-body');
    body.style.opacity = '0.5';
    body.style.pointerEvents = 'none';
    
    // Hacer petición AJAX
    const url = '<?php echo base_url('api/estadisticas'); ?>?grafico=' + grafico + '&filtro=' + valor;
    console.log('URL:', url);
    
    fetch(url)
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('Error en la respuesta: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);
            // Actualizar el gráfico específico
            actualizarGrafico(grafico, data);
            
            // Restaurar opacidad
            body.style.opacity = '1';
            body.style.pointerEvents = 'auto';
        })
        .catch(error => {
            console.error('Error al actualizar gráfico:', error);
            alert('Error al actualizar el gráfico. Revisa la consola para más detalles.');
            body.style.opacity = '1';
            body.style.pointerEvents = 'auto';
        });
}

// Función para actualizar un gráfico específico
function actualizarGrafico(tipo, data) {
    console.log('Actualizando gráfico:', tipo, data);
    
    switch(tipo) {
        case 'sede':
            actualizarGraficoSede(data);
            break;
        case 'tipo':
            actualizarGraficoTipo(data);
            break;
        case 'justificacion':
            actualizarGraficoJustificacion(data);
            break;
        case 'turno':
            actualizarGraficoTurno(data);
            break;
        case 'area':
            actualizarGraficoArea(data);
            break;
        case 'mensual':
            actualizarGraficoMensual(data);
            break;
        default:
            console.error('Tipo de gráfico no reconocido:', tipo);
    }
}

function actualizarGraficoSede(data) {
    if (!charts.sede) {
        console.error('Gráfico sede no inicializado');
        return;
    }
    charts.sede.data.labels = data.map(item => item.sede);
    charts.sede.data.datasets[0].data = data.map(item => item.total);
    charts.sede.update();
    
    // Actualizar lista
    const lista = document.querySelector('#chartSede').closest('.stat-card-body').querySelector('.stat-list');
    if (lista) {
        lista.innerHTML = data.map(item => `
            <div class="stat-item">
                <span class="stat-item-label">${item.sede}</span>
                <span class="stat-item-value">${item.total}</span>
            </div>
        `).join('');
    }
}

function actualizarGraficoTipo(data) {
    if (!charts.tipo) {
        console.error('Gráfico tipo no inicializado');
        return;
    }
    charts.tipo.data.labels = data.map(item => item.tipo);
    charts.tipo.data.datasets[0].data = data.map(item => item.total);
    charts.tipo.update();
    
    // Actualizar lista
    const lista = document.querySelector('#chartTipo').closest('.stat-card-body').querySelector('.stat-list');
    if (lista) {
        lista.innerHTML = data.map(item => `
            <div class="stat-item">
                <span class="stat-item-label">${item.tipo}</span>
                <span class="stat-item-value">${item.total}</span>
            </div>
        `).join('');
    }
}

function actualizarGraficoJustificacion(data) {
    if (!charts.justificacion) {
        console.error('Gráfico justificacion no inicializado');
        return;
    }
    charts.justificacion.data.labels = data.map(item => item.justificacion);
    charts.justificacion.data.datasets[0].data = data.map(item => item.total);
    charts.justificacion.update();
    
    // Actualizar lista
    const lista = document.querySelector('#chartJustificacion').closest('.stat-card-body').querySelector('.stat-list');
    if (lista) {
        lista.innerHTML = data.map(item => `
            <div class="stat-item">
                <span class="stat-item-label">${item.justificacion}</span>
                <span class="stat-item-value">${item.total}</span>
            </div>
        `).join('');
    }
}

function actualizarGraficoTurno(data) {
    if (!charts.turno) {
        console.error('Gráfico turno no inicializado');
        return;
    }
    charts.turno.data.labels = data.map(item => item.turno);
    charts.turno.data.datasets[0].data = data.map(item => item.total);
    charts.turno.update();
    
    // Actualizar lista
    const lista = document.querySelector('#chartTurno').closest('.stat-card-body').querySelector('.stat-list');
    if (lista) {
        lista.innerHTML = data.map(item => `
            <div class="stat-item">
                <span class="stat-item-label">${item.turno}</span>
                <span class="stat-item-value">${item.total}</span>
            </div>
        `).join('');
    }
}

function actualizarGraficoArea(data) {
    if (!charts.areas) {
        console.error('Gráfico areas no inicializado');
        return;
    }
    charts.areas.data.labels = data.map(item => item.area_trabajo);
    charts.areas.data.datasets[0].data = data.map(item => item.total_novedades);
    charts.areas.update();
}

function actualizarGraficoMensual(data) {
    if (!charts.mensual) {
        console.error('Gráfico mensual no inicializado');
        return;
    }
    charts.mensual.data.labels = data.map(item => item.mes).reverse();
    charts.mensual.data.datasets[0].data = data.map(item => item.total).reverse();
    charts.mensual.update();
}

// Datos para gráficos
const dataSede = <?php echo json_encode($stats['por_sede']); ?>;
const dataTipo = <?php echo json_encode($stats['por_tipo']); ?>;
const dataJustificacion = <?php echo json_encode($stats['por_justificacion']); ?>;
const dataTurno = <?php echo json_encode($stats['por_turno']); ?>;
const dataDominical = <?php echo json_encode($stats['descontar_dominical']); ?>;
const dataMensual = <?php echo json_encode($stats['por_mes']); ?>;
const dataAreas = <?php echo json_encode(array_slice($stats['por_area'], 0, 10)); ?>;

// Colores profesionales con gradientes
const colors = [
    '#667eea', '#764ba2', '#f093fb', '#4facfe',
    '#43e97b', '#fa709a', '#fee140', '#30cfd0',
    '#a8edea', '#fed6e3', '#c471f5', '#fa71cd'
];

const gradientColors = [
    'rgba(102, 126, 234, 0.8)',
    'rgba(118, 75, 162, 0.8)',
    'rgba(240, 147, 251, 0.8)',
    'rgba(79, 172, 254, 0.8)',
    'rgba(67, 233, 123, 0.8)',
    'rgba(250, 112, 154, 0.8)',
    'rgba(254, 225, 64, 0.8)',
    'rgba(48, 207, 208, 0.8)',
    'rgba(168, 237, 234, 0.8)',
    'rgba(254, 214, 227, 0.8)',
    'rgba(196, 113, 245, 0.8)',
    'rgba(250, 113, 205, 0.8)'
];

// Configuración común para todos los gráficos
const commonOptions = {
    responsive: true,
    maintainAspectRatio: true,
    animation: {
        duration: 800,
        easing: 'easeInOutQuart'
    },
    plugins: {
        legend: {
            display: false,
            labels: {
                font: {
                    family: "'Inter', sans-serif",
                    size: 12,
                    weight: '600'
                },
                padding: 15,
                usePointStyle: true,
                pointStyle: 'circle'
            }
        },
        tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleFont: {
                family: "'Inter', sans-serif",
                size: 13,
                weight: '600'
            },
            bodyFont: {
                family: "'Inter', sans-serif",
                size: 12
            },
            padding: 12,
            cornerRadius: 8,
            displayColors: true,
            boxPadding: 6
        }
    }
};

// Gráfico por Sede
charts.sede = new Chart(document.getElementById('chartSede'), {
    type: 'bar',
    data: {
        labels: dataSede.map(item => item.sede),
        datasets: [{
            label: 'Novedades',
            data: dataSede.map(item => item.total),
            backgroundColor: gradientColors,
            borderColor: colors,
            borderWidth: 2,
            borderRadius: 8,
            hoverBackgroundColor: colors
        }]
    },
    options: {
        ...commonOptions,
        scales: {
            y: { 
                beginAtZero: true, 
                grid: { 
                    color: '#f1f5f9',
                    drawBorder: false
                },
                ticks: {
                    font: {
                        family: "'Inter', sans-serif",
                        size: 11
                    },
                    color: '#64748b'
                }
            },
            x: { 
                grid: { display: false },
                ticks: {
                    font: {
                        family: "'Inter', sans-serif",
                        size: 11
                    },
                    color: '#64748b'
                }
            }
        }
    }
});

// Gráfico por Tipo
charts.tipo = new Chart(document.getElementById('chartTipo'), {
    type: 'doughnut',
    data: {
        labels: dataTipo.map(item => item.tipo),
        datasets: [{
            data: dataTipo.map(item => item.total),
            backgroundColor: gradientColors,
            borderColor: '#ffffff',
            borderWidth: 3,
            hoverOffset: 15
        }]
    },
    options: {
        ...commonOptions,
        cutout: '65%',
        plugins: {
            ...commonOptions.plugins,
            legend: {
                display: false
            }
        }
    }
});

// Gráfico Justificación
charts.justificacion = new Chart(document.getElementById('chartJustificacion'), {
    type: 'pie',
    data: {
        labels: dataJustificacion.map(item => item.justificacion),
        datasets: [{
            data: dataJustificacion.map(item => item.total),
            backgroundColor: ['#10b981', '#ef4444', '#f59e0b'],
            borderColor: '#ffffff',
            borderWidth: 3,
            hoverOffset: 12
        }]
    },
    options: {
        ...commonOptions,
        plugins: {
            ...commonOptions.plugins,
            legend: {
                display: false
            }
        }
    }
});

// Gráfico Turno
charts.turno = new Chart(document.getElementById('chartTurno'), {
    type: 'bar',
    data: {
        labels: dataTurno.map(item => item.turno),
        datasets: [{
            label: 'Novedades',
            data: dataTurno.map(item => item.total),
            backgroundColor: ['rgba(102, 126, 234, 0.8)', 'rgba(118, 75, 162, 0.8)'],
            borderColor: ['#667eea', '#764ba2'],
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        ...commonOptions,
        scales: {
            y: { 
                beginAtZero: true, 
                grid: { 
                    color: '#f1f5f9',
                    drawBorder: false
                },
                ticks: {
                    font: {
                        family: "'Inter', sans-serif",
                        size: 11
                    },
                    color: '#64748b'
                }
            },
            x: { 
                grid: { display: false },
                ticks: {
                    font: {
                        family: "'Inter', sans-serif",
                        size: 11
                    },
                    color: '#64748b'
                }
            }
        }
    }
});

// Gráfico Mensual
charts.mensual = new Chart(document.getElementById('chartMensual'), {
    type: 'line',
    data: {
        labels: dataMensual.map(item => item.mes).reverse(),
        datasets: [{
            label: 'Novedades',
            data: dataMensual.map(item => item.total).reverse(),
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            fill: true,
            tension: 0.4,
            borderWidth: 3,
            pointRadius: 5,
            pointBackgroundColor: '#667eea',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointHoverRadius: 7
        }]
    },
    options: {
        ...commonOptions,
        maintainAspectRatio: false,
        scales: {
            y: { 
                beginAtZero: true, 
                grid: { 
                    color: '#f1f5f9',
                    drawBorder: false
                },
                ticks: {
                    font: {
                        family: "'Inter', sans-serif",
                        size: 11
                    },
                    color: '#64748b'
                }
            },
            x: { 
                grid: { display: false },
                ticks: {
                    font: {
                        family: "'Inter', sans-serif",
                        size: 11
                    },
                    color: '#64748b'
                }
            }
        }
    }
});

// Gráfico Áreas
charts.areas = new Chart(document.getElementById('chartAreas'), {
    type: 'bar',
    data: {
        labels: dataAreas.map(item => item.area_trabajo),
        datasets: [{
            label: 'Novedades',
            data: dataAreas.map(item => item.total_novedades),
            backgroundColor: gradientColors,
            borderColor: colors,
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        ...commonOptions,
        maintainAspectRatio: false,
        indexAxis: 'y',
        scales: {
            x: { 
                beginAtZero: true, 
                grid: { 
                    color: '#f1f5f9',
                    drawBorder: false
                },
                ticks: {
                    font: {
                        family: "'Inter', sans-serif",
                        size: 11
                    },
                    color: '#64748b'
                }
            },
            y: { 
                grid: { display: false },
                ticks: {
                    font: {
                        family: "'Inter', sans-serif",
                        size: 11
                    },
                    color: '#64748b'
                }
            }
        }
    }
});

// Gráfico Comparativa 2025 vs 2026
const dataComparativa = <?php echo json_encode($stats['comparativa']); ?>;

// Procesar datos para el gráfico
const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
const datos2025 = new Array(12).fill(0);
const datos2026 = new Array(12).fill(0);

dataComparativa.forEach(item => {
    const mesIndex = parseInt(item.mes) - 1;
    if (item.anio == 2025) {
        datos2025[mesIndex] = parseInt(item.total);
    } else if (item.anio == 2026) {
        datos2026[mesIndex] = parseInt(item.total);
    }
});

new Chart(document.getElementById('chartComparativa'), {
    type: 'line',
    data: {
        labels: meses,
        datasets: [
            {
                label: '2025',
                data: datos2025,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 2
            },
            {
                label: '2026',
                data: datos2026,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 2
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { 
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
            x: { grid: { display: false } }
        }
    }
});
</script>

<style>
/* Stats Page */
.stats-page { 
    background: #ffffff;
    min-height: 100vh;
    padding-bottom: 2rem;
}

.stats-header {
    background: white;
    padding: 2rem;
    border-radius: 16px;
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid #e2e8f0;
}

.stats-header h1 {
    margin: 0;
    font-size: 2rem;
    color: #1e293b;
    font-weight: 700;
}

.stats-subtitle {
    margin: 0.5rem 0 0 0;
    font-size: 1rem;
    color: #64748b;
    font-weight: 500;
}

.btn-print {
    padding: 0.75rem 1.5rem;
    background: #3b82f6;
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    font-size: 0.95rem;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    transition: all 0.3s;
}

.btn-print:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    background: #2563eb;
}

.btn-usuarios:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
    background: linear-gradient(135deg, #2563eb, #1e3a8a);
}

/* Main Stats Container - Dos columnas */
.main-stats-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

/* Main Stat */
.main-stat {
    background: white;
    padding: 3rem;
    border-radius: 16px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid #e2e8f0;
    position: relative;
    overflow: hidden;
}

.main-stat::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 100%);
}

.main-stat-value {
    font-size: 4rem;
    font-weight: 800;
    color: #1e40af;
    line-height: 1;
}

.main-stat-label {
    font-size: 1.1rem;
    color: #64748b;
    margin-top: 1rem;
    font-weight: 600;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid #e2e8f0;
    overflow: hidden;
    transition: all 0.3s;
    position: relative;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 100%);
}

.stat-card-wide {
    grid-column: span 2;
}

.stat-card-header {
    padding: 1.5rem 2rem;
    border-bottom: 2px solid #f1f5f9;
    background: #f8fafc;
}

.stat-card-header h3 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
}

.stat-card-body {
    padding: 2rem;
}

.chart-container {
    max-width: 320px;
    margin: 0 auto 2rem;
}

.chart-container-horizontal {
    height: 450px;
}

.chart-container-line {
    height: 320px;
}

.stat-list {
    max-height: 320px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
}

.stat-list::-webkit-scrollbar {
    width: 6px;
}

.stat-list::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.stat-list::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.stat-list::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.2s;
}

.stat-item:hover {
    background: #f8fafc;
    padding-left: 0.5rem;
    padding-right: 0.5rem;
    border-radius: 8px;
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-item-label {
    font-size: 0.9rem;
    color: #475569;
    font-weight: 500;
}

.stat-item-value {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
}

.stat-item-value small {
    color: #64748b;
    font-weight: 500;
    margin-left: 0.25rem;
}

/* Conclusions */
.conclusions {
    background: white;
    border-left: 6px solid #3b82f6;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid #e2e8f0;
    border-left: 6px solid #3b82f6;
}

.conclusions h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.2rem;
    font-weight: 700;
    color: #1e40af;
}

.conclusions-subtitle {
    margin: 0 0 1rem 0;
    font-size: 0.9rem;
    color: #64748b;
    font-weight: 500;
    font-style: italic;
}

.conclusions-content {
    font-size: 1rem;
    line-height: 1.8;
    color: #475569;
}

.conclusions-content p {
    margin: 0;
}

/* Empty State */
.empty-state {
    background: white;
    padding: 4rem;
    border-radius: 16px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid #e2e8f0;
}

.empty-state p {
    font-size: 1.2rem;
    color: #64748b;
    margin-bottom: 2rem;
}

/* Print */
@media print {
    .navbar-simple, .btn-print, .filtro-grafico {
        display: none;
    }
    
    .stats-page {
        background: white;
    }
    
    .stat-card {
        page-break-inside: avoid;
    }
}

/* Responsive */
@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-card-wide {
        grid-column: span 1;
    }
}

@media (max-width: 768px) {
    .stats-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    /* Hacer que las estadísticas principales se apilen en móvil */
    .main-stats-container {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .main-stat {
        padding: 2rem;
    }
    
    .main-stat-value {
        font-size: 3rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .chart-container-horizontal {
        height: 350px;
    }
}
</style>



</main>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
