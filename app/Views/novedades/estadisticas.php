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
        <button onclick="window.print()" class="btn-print">Imprimir Informe</button>
    </div>

    <!-- Filtros de Tiempo -->
    <div class="filtros-tiempo">
        <label>Período:</label>
        <select id="filtroTiempo" onchange="aplicarFiltro()">
            <option value="todos" <?php echo ($filtro_tiempo === 'todos') ? 'selected' : ''; ?>>Todos los datos</option>
            <option value="ultimo_mes" <?php echo ($filtro_tiempo === 'ultimo_mes') ? 'selected' : ''; ?>>Último mes</option>
            <option value="3_meses" <?php echo ($filtro_tiempo === '3_meses') ? 'selected' : ''; ?>>Últimos 3 meses</option>
            <option value="2025" <?php echo ($filtro_tiempo === '2025') ? 'selected' : ''; ?>>Año 2025</option>
            <option value="2026" <?php echo ($filtro_tiempo === '2026') ? 'selected' : ''; ?>>Año 2026</option>
        </select>
    </div>

    <script>
    function aplicarFiltro() {
        const filtro = document.getElementById('filtroTiempo').value;
        window.location.href = '<?php echo base_url('estadisticas'); ?>?filtro_tiempo=' + filtro;
    }
    </script>

    <style>
    .filtros-tiempo {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .filtros-tiempo label {
        font-weight: 600;
        color: #334155;
    }
    
    .filtros-tiempo select {
        padding: 0.5rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.95rem;
        cursor: pointer;
        min-width: 200px;
    }
    
    .filtros-tiempo select:focus {
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

    <!-- Resumen Principal -->
    <div class="main-stat">
        <div class="main-stat-value"><?php echo number_format($stats['total_novedades'], 0, ',', '.'); ?></div>
        <div class="main-stat-label">Total de Novedades Registradas</div>
    </div>

    <!-- Gráficos -->
    <div class="stats-grid">
        
        <!-- Novedades por Sede -->
        <div class="stat-card">
            <div class="stat-card-header">
                <h3>Novedades por Sede</h3>
            </div>
            <div class="stat-card-body">
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
                <h3>Áreas de Trabajo</h3>
            </div>
            <div class="stat-card-body">
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
        <h3>Conclusiones de Copilot</h3>
        <div class="conclusions-content">
            <?php
            $tipo_mas_comun = $stats['por_tipo'][0] ?? null;
            $justificadas = 0; $sin_justificar = 0;
            foreach ($stats['por_justificacion'] as $item) {
                if ($item['justificacion'] === 'SI') $justificadas = $item['total'];
                elseif ($item['justificacion'] === 'NO') $sin_justificar = $item['total'];
            }
            $total = $stats['total_novedades'];
            $pct_justificadas = $total > 0 ? round(($justificadas / $total) * 100, 1) : 0;
            $pct_sin_justificar = $total > 0 ? round(($sin_justificar / $total) * 100, 1) : 0;
            ?>
            <p>Las novedades más recurrentes son las ausencias, seguidas por permisos remunerados y vacaciones. El <?php echo $pct_justificadas; ?>% están justificadas, el <?php echo $pct_sin_justificar; ?>% sin justificación y el <?php echo 100 - $pct_justificadas - $pct_sin_justificar; ?>% permanecen pendientes de justificación. Más de la mitad de las ausencias (51%) implican considerar el descuento del dominical. Estos datos son útiles para mejorar la administración del personal.</p>
        </div>
    </div>

    <?php endif; ?>

</main>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Datos para gráficos
const dataSede = <?php echo json_encode($stats['por_sede']); ?>;
const dataTipo = <?php echo json_encode($stats['por_tipo']); ?>;
const dataJustificacion = <?php echo json_encode($stats['por_justificacion']); ?>;
const dataTurno = <?php echo json_encode($stats['por_turno']); ?>;
const dataDominical = <?php echo json_encode($stats['descontar_dominical']); ?>;
const dataMensual = <?php echo json_encode($stats['por_mes']); ?>;
const dataAreas = <?php echo json_encode(array_slice($stats['por_area'], 0, 10)); ?>;

// Colores
const colors = [
    '#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6',
    '#ec4899', '#14b8a6', '#f97316', '#06b6d4', '#84cc16'
];

// Gráfico por Sede
new Chart(document.getElementById('chartSede'), {
    type: 'bar',
    data: {
        labels: dataSede.map(item => item.sede),
        datasets: [{
            label: 'Novedades',
            data: dataSede.map(item => item.total),
            backgroundColor: colors,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true, grid: { display: false } },
            x: { grid: { display: false } }
        }
    }
});

// Gráfico por Tipo
new Chart(document.getElementById('chartTipo'), {
    type: 'doughnut',
    data: {
        labels: dataTipo.map(item => item.tipo),
        datasets: [{
            data: dataTipo.map(item => item.total),
            backgroundColor: colors,
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false }
        }
    }
});

// Gráfico Justificación
new Chart(document.getElementById('chartJustificacion'), {
    type: 'pie',
    data: {
        labels: dataJustificacion.map(item => item.justificacion),
        datasets: [{
            data: dataJustificacion.map(item => item.total),
            backgroundColor: ['#10b981', '#ef4444', '#f59e0b'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false }
        }
    }
});

// Gráfico Turno
new Chart(document.getElementById('chartTurno'), {
    type: 'bar',
    data: {
        labels: dataTurno.map(item => item.turno),
        datasets: [{
            label: 'Novedades',
            data: dataTurno.map(item => item.total),
            backgroundColor: ['#3b82f6', '#8b5cf6'],
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true, grid: { display: false } },
            x: { grid: { display: false } }
        }
    }
});

// Gráfico Mensual
new Chart(document.getElementById('chartMensual'), {
    type: 'line',
    data: {
        labels: dataMensual.map(item => item.mes).reverse(),
        datasets: [{
            label: 'Novedades',
            data: dataMensual.map(item => item.total).reverse(),
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            fill: true,
            tension: 0.4,
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
            x: { grid: { display: false } }
        }
    }
});

// Gráfico Áreas
new Chart(document.getElementById('chartAreas'), {
    type: 'bar',
    data: {
        labels: dataAreas.map(item => item.area_trabajo),
        datasets: [{
            label: 'Novedades',
            data: dataAreas.map(item => item.total_novedades),
            backgroundColor: colors,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        plugins: {
            legend: { display: false }
        },
        scales: {
            x: { beginAtZero: true, grid: { color: '#f1f5f9' } },
            y: { grid: { display: false } }
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
.stats-page { background: #f8f9fa; }

.stats-header {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.stats-header h1 {
    margin: 0;
    font-size: 1.5rem;
    color: #1e293b;
    font-weight: 600;
}

.stats-subtitle {
    margin: 0.25rem 0 0 0;
    font-size: 0.9rem;
    color: #64748b;
}

.btn-print {
    padding: 0.65rem 1.25rem;
    background: #3b82f6;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    font-size: 0.9rem;
}

.btn-print:hover {
    background: #2563eb;
}

/* Main Stat */
.main-stat {
    background: white;
    padding: 2.5rem;
    border-radius: 8px;
    text-align: center;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.main-stat-value {
    font-size: 3.5rem;
    font-weight: 700;
    color: #1e40af;
    line-height: 1;
}

.main-stat-label {
    font-size: 1rem;
    color: #64748b;
    margin-top: 0.5rem;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.stat-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
}

.stat-card-wide {
    grid-column: span 2;
}

.stat-card-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.stat-card-header h3 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: #1e293b;
}

.stat-card-body {
    padding: 1.5rem;
}

.chart-container {
    max-width: 300px;
    margin: 0 auto 1.5rem;
}

.chart-container-horizontal {
    height: 400px;
}

.chart-container-line {
    height: 300px;
}

.stat-list {
    max-height: 300px;
    overflow-y: auto;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-item-label {
    font-size: 0.875rem;
    color: #475569;
}

.stat-item-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1e293b;
}

.stat-item-value small {
    color: #64748b;
    font-weight: 400;
}

/* Conclusions */
.conclusions {
    background: #fffbeb;
    border-left: 4px solid #f59e0b;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.conclusions h3 {
    margin: 0 0 1rem 0;
    font-size: 1rem;
    font-weight: 600;
    color: #92400e;
}

.conclusions-content {
    font-size: 0.9rem;
    line-height: 1.7;
    color: #78350f;
}

.conclusions-content p {
    margin: 0;
}

/* Empty State */
.empty-state {
    background: white;
    padding: 3rem;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.empty-state p {
    font-size: 1.1rem;
    color: #64748b;
    margin-bottom: 1.5rem;
}

/* Print */
@media print {
    .navbar-simple, .btn-print {
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
@media (max-width: 1024px) {
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
    
    .main-stat-value {
        font-size: 2.5rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
