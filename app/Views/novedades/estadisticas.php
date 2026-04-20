<?php $css_files = []; require_once APP_PATH . '/Views/layouts/header.php'; ?>

<body class="simple-layout">

<?php require_once APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="app-main">
    <div class="page-header">
        <h1>Estadísticas y Gráficos - Informe de Novedades</h1>
        <button onclick="window.print()" class="btn-primary">Imprimir Informe</button>
    </div>

    <!-- Resumen General -->
    <div class="stats-summary">
        <div class="stat-card-large">
            <div class="stat-content">
                <div class="stat-value-large"><?php echo number_format($stats['total_novedades']); ?></div>
                <div class="stat-label-large">Total de Novedades Registradas</div>
            </div>
        </div>
    </div>

    <?php if ($stats['total_novedades'] == 0): ?>
    <div class="empty-state" style="text-align:center;padding:3rem;">
        <p style="font-size:1.2rem;color:#64748b;">No hay novedades registradas aún.</p>
        <a href="<?php echo base_url('novedades/crear'); ?>" class="btn-primary" style="margin-top:1rem;display:inline-block;">Registrar Primera Novedad</a>
    </div>
    <?php else: ?>

    <!-- Gráficos en Grid -->
    <div class="charts-grid">
        
        <!-- Novedades por Sede -->
        <div class="chart-card">
            <h3>Novedades por Sede</h3>
            <canvas id="chartSede"></canvas>
            <div class="chart-data">
                <?php foreach ($stats['por_sede'] as $item): ?>
                    <div class="data-row">
                        <span class="data-label"><?php echo htmlspecialchars($item['sede']); ?></span>
                        <span class="data-value"><?php echo $item['total']; ?> (<?php echo $stats['total_novedades'] > 0 ? round(($item['total'] / $stats['total_novedades']) * 100, 1) : 0; ?>%)</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Novedades por Tipo -->
        <div class="chart-card">
            <h3>Tipos de Novedad</h3>
            <canvas id="chartTipo"></canvas>
            <div class="chart-data">
                <?php foreach ($stats['por_tipo'] as $item): ?>
                    <div class="data-row">
                        <span class="data-label"><?php echo htmlspecialchars($item['tipo']); ?></span>
                        <span class="data-value"><?php echo $item['total']; ?> (<?php echo $stats['total_novedades'] > 0 ? round(($item['total'] / $stats['total_novedades']) * 100, 1) : 0; ?>%)</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Justificación -->
        <div class="chart-card">
            <h3>Estado de Justificación</h3>
            <canvas id="chartJustificacion"></canvas>
            <div class="chart-data">
                <?php foreach ($stats['por_justificacion'] as $item): ?>
                    <div class="data-row">
                        <span class="data-label"><?php echo htmlspecialchars($item['justificacion']); ?></span>
                        <span class="data-value"><?php echo $item['total']; ?> (<?php echo $stats['total_novedades'] > 0 ? round(($item['total'] / $stats['total_novedades']) * 100, 1) : 0; ?>%)</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Turnos -->
        <div class="chart-card">
            <h3>Novedades por Turno</h3>
            <canvas id="chartTurno"></canvas>
            <div class="chart-data">
                <?php foreach ($stats['por_turno'] as $item): ?>
                    <div class="data-row">
                        <span class="data-label"><?php echo htmlspecialchars($item['turno']); ?></span>
                        <span class="data-value"><?php echo $item['total']; ?> (<?php echo $stats['total_novedades'] > 0 ? round(($item['total'] / $stats['total_novedades']) * 100, 1) : 0; ?>%)</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Descuento Dominical -->
        <div class="chart-card">
            <h3>Descuento Dominical</h3>
            <canvas id="chartDominical"></canvas>
            <div class="chart-data">
                <?php foreach ($stats['descontar_dominical'] as $item): ?>
                    <div class="data-row">
                        <span class="data-label"><?php echo htmlspecialchars($item['descontar_dominical']); ?></span>
                        <span class="data-value"><?php echo $item['total']; ?> (<?php echo $stats['total_novedades'] > 0 ? round(($item['total'] / $stats['total_novedades']) * 100, 1) : 0; ?>%)</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Tendencia Mensual -->
        <div class="chart-card chart-wide">
            <h3>Tendencia Mensual (Últimos 12 meses)</h3>
            <canvas id="chartMensual"></canvas>
        </div>

        <!-- Top Áreas -->
        <div class="chart-card chart-wide">
            <h3>Top 10 Áreas con Más Novedades</h3>
            <canvas id="chartAreas"></canvas>
        </div>

        <!-- Top Responsables -->
        <div class="chart-card">
            <h3>Top 10 Responsables</h3>
            <div class="chart-data">
                <?php foreach ($stats['top_responsables'] as $index => $item): ?>
                    <div class="data-row">
                        <span class="data-label"><?php echo ($index + 1) . '. ' . htmlspecialchars($item['responsable']); ?></span>
                        <span class="data-value"><?php echo $item['total']; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <!-- Conclusiones -->
    <div class="conclusions-card">
        <h3>Conclusiones Automáticas</h3>
        <div class="conclusion-content">
            <?php
            $tipo_mas_comun = $stats['por_tipo'][0] ?? null;
            $justificadas    = 0; $sin_justificar = 0; $pendientes = 0;
            foreach ($stats['por_justificacion'] as $item) {
                if ($item['justificacion'] === 'SI')        $justificadas    = $item['total'];
                elseif ($item['justificacion'] === 'NO')    $sin_justificar  = $item['total'];
                elseif ($item['justificacion'] === 'PENDIENTE') $pendientes  = $item['total'];
            }
            $total = $stats['total_novedades'];
            $pct_justificadas   = $total > 0 ? round(($justificadas   / $total) * 100, 1) : 0;
            $pct_sin_justificar = $total > 0 ? round(($sin_justificar / $total) * 100, 1) : 0;
            $pct_pendientes     = $total > 0 ? round(($pendientes     / $total) * 100, 1) : 0;
            ?>
            <p><strong>Tipo de novedad más recurrente:</strong> <?php echo $tipo_mas_comun ? htmlspecialchars($tipo_mas_comun['tipo']) . ' con ' . $tipo_mas_comun['total'] . ' casos' : 'N/A'; ?></p>
            <p><strong>Estado de justificaciones:</strong> <?php echo $pct_justificadas; ?>% justificadas, <?php echo $pct_sin_justificar; ?>% sin justificación, <?php echo $pct_pendientes; ?>% pendientes.</p>
            <p><strong>Recomendación:</strong> <?php
                if ($pct_pendientes > 40) {
                    echo 'Se recomienda hacer seguimiento a las justificaciones pendientes para mejorar el control administrativo.';
                } elseif ($pct_sin_justificar > 30) {
                    echo 'Alto porcentaje de ausencias sin justificar. Se sugiere reforzar las políticas de notificación.';
                } else {
                    echo 'El nivel de justificaciones es adecuado. Continuar con el seguimiento actual.';
                }
            ?></p>
        </div>
    </div>

    <?php endif; // fin del else total_novedades > 0 ?>

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
            backgroundColor: colors
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
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
            backgroundColor: colors
        }]
    },
    options: {
        responsive: true
    }
});

// Gráfico Justificación
new Chart(document.getElementById('chartJustificacion'), {
    type: 'pie',
    data: {
        labels: dataJustificacion.map(item => item.justificacion),
        datasets: [{
            data: dataJustificacion.map(item => item.total),
            backgroundColor: ['#10b981', '#ef4444', '#f59e0b']
        }]
    },
    options: {
        responsive: true
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
            backgroundColor: ['#3b82f6', '#8b5cf6']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        }
    }
});

// Gráfico Dominical
new Chart(document.getElementById('chartDominical'), {
    type: 'pie',
    data: {
        labels: dataDominical.map(item => item.descontar_dominical),
        datasets: [{
            data: dataDominical.map(item => item.total),
            backgroundColor: ['#ef4444', '#10b981']
        }]
    },
    options: {
        responsive: true
    }
});

// Gráfico Mensual
new Chart(document.getElementById('chartMensual'), {
    type: 'line',
    data: {
        labels: dataMensual.map(item => item.mes).reverse(),
        datasets: [{
            label: 'Novedades por Mes',
            data: dataMensual.map(item => item.total).reverse(),
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true
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
            backgroundColor: colors
        }]
    },
    options: {
        responsive: true,
        indexAxis: 'y',
        plugins: {
            legend: { display: false }
        }
    }
});
</script>

<style>
.stats-summary {
    margin-bottom: 2rem;
}

.stat-card-large {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 2rem;
}

.stat-icon {
    font-size: 4rem;
}

.stat-value-large {
    font-size: 3rem;
    font-weight: bold;
    color: #1e40af;
}

.stat-label-large {
    font-size: 1.2rem;
    color: #64748b;
}

.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.chart-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.chart-wide {
    grid-column: span 2;
}

.chart-card h3 {
    margin: 0 0 1rem 0;
    color: #1e293b;
    font-size: 1.1rem;
}

.chart-data {
    margin-top: 1rem;
    max-height: 300px;
    overflow-y: auto;
}

.data-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.data-label {
    color: #475569;
}

.data-value {
    font-weight: 600;
    color: #1e40af;
}

.conclusions-card {
    background: #eff6ff;
    border-left: 4px solid #3b82f6;
    border-radius: 8px;
    padding: 1.5rem;
    margin-top: 2rem;
}

.conclusions-card h3 {
    margin: 0 0 1rem 0;
    color: #1e40af;
}

.conclusion-content p {
    margin: 0.75rem 0;
    line-height: 1.6;
}

@media print {
    .navbar-simple, .btn-primary {
        display: none;
    }
    
    .chart-card {
        page-break-inside: avoid;
    }
}

@media (max-width: 768px) {
    .charts-grid {
        grid-template-columns: 1fr;
    }
    
    .chart-wide {
        grid-column: span 1;
    }
}
</style>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
