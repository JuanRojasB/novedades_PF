<?php 
// Forzar UTF-8 en toda la respuesta
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

$css_files = []; 
require_once APP_PATH . '/Views/layouts/header.php'; 
?>

<body class="simple-layout">

<?php require_once APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="app-main">

    <!-- Header -->
    <div class="page-header">
        <div>
            <h1>Estadísticas por Empleado</h1>
            <p style="color: #64748b; margin: 0.5rem 0 0 0;">
                Total de empleados con novedades: <strong><?php echo $totalEmpleados; ?></strong>
            </p>
        </div>
        <a href="<?php echo base_url('estadisticas'); ?>" class="btn-secondary">← Volver al Dashboard</a>
    </div>

    <!-- Búsqueda -->
    <div class="search-section" style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <input type="text" id="busqueda-empleados" placeholder="Buscar por nombre, cédula, sede o área..." style="flex: 1; border: 2px solid #e2e8f0; border-radius: 8px; padding: 0.75rem 1rem; font-size: 1rem; outline: none; transition: all 0.3s;" autocomplete="off">
            <span id="empleados-count" class="search-count" style="font-size: 0.875rem; color: #3b82f6; font-weight: 600; background: #eff6ff; padding: 0.5rem 1rem; border-radius: 999px; white-space: nowrap; display: none;"></span>
        </div>
    </div>

    <!-- Gráfico de Distribución de Novedades -->
    <div class="chart-section" style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 2rem;">
        <div class="chart-header" style="text-align: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0 0 0.25rem 0; color: #1e293b; font-size: 1.25rem; font-weight: 700;">Distribución de Tipos de Novedad</h3>
            <p style="margin: 0; color: #64748b; font-size: 0.85rem;">Porcentaje de cada tipo de novedad del total registrado</p>
        </div>
        
        <?php if (empty($tiposNovedad)): ?>
            <div style="text-align: center; padding: 2rem; color: #dc2626;">
                <p>No hay datos de tipos de novedad disponibles</p>
                <pre style="text-align: left; background: #f1f5f9; padding: 1rem; border-radius: 4px; font-size: 0.75rem;">
                    <?php var_dump($tiposNovedad); ?>
                </pre>
            </div>
        <?php else: ?>
            <div style="display: flex; align-items: flex-start; gap: 2rem; justify-content: center; flex-wrap: wrap;">
                <!-- Gráfico -->
                <div style="flex-shrink: 0; width: 280px; height: 280px; position: relative; background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <canvas id="chartTiposNovedad"></canvas>
                    <div id="chart-loading" style="position: absolute; color: #64748b; font-size: 0.875rem;">Cargando gráfico...</div>
                </div>
                <!-- Leyenda personalizada compacta -->
                <div id="leyenda-tipos-novedad" style="flex: 1; min-width: 280px; max-width: 400px; align-self: center;">
                    <div style="color: #64748b; font-size: 0.875rem;">Cargando leyenda...</div>
                </div>
            </div>
        <?php endif; ?>
    </div>


    <!-- Tabla de Empleados -->
    <div class="table-container">
        <?php if (empty($empleados)): ?>
            <div class="empty-state">
                <p>No hay empleados con novedades registradas</p>
            </div>
        <?php else: ?>
            <div id="no-results-empleados" style="display:none; background: white; padding: 2rem; border-radius: 12px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 2rem;">
                <p style="color: #64748b; margin: 0;">No se encontraron empleados que coincidan con la búsqueda.</p>
            </div>
            <table class="data-table" id="tabla-empleados-completa">
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Cédula</th>
                        <th>Sede</th>
                        <th>Área</th>
                        <th style="text-align: center;">Total Novedades</th>
                        <th style="text-align: center;">Ausencias</th>
                        <th style="text-align: center;">Incapacidades</th>
                        <th style="text-align: center;">Vacaciones</th>
                        <th style="text-align: center;">Permisos</th>
                        <th style="text-align: center;">% Justif.</th>
                        <th>Última Novedad</th>
                    </tr>
                </thead>
                <tbody id="tabla-empleados">
                    <?php foreach ($empleados as $empleado): ?>
                    <tr class="fila-empleado empleado-clickeable"
                        data-nombre="<?php echo strtolower(htmlspecialchars($empleado['nombres_apellidos'])); ?>"
                        data-cedula="<?php echo $empleado['numero_cedula']; ?>"
                        data-sede="<?php echo strtolower(htmlspecialchars($empleado['sede'])); ?>"
                        data-area="<?php echo strtolower(htmlspecialchars($empleado['area_trabajo'])); ?>"
                        data-ausencias="<?php echo $empleado['ausencias']; ?>"
                        data-incapacidades="<?php echo $empleado['incapacidades']; ?>"
                        data-vacaciones="<?php echo $empleado['vacaciones']; ?>"
                        data-permisos="<?php echo ($empleado['permisos_remunerados'] + $empleado['permisos_no_remunerados']); ?>"
                        data-renuncias="<?php echo $empleado['renuncias'] ?? 0; ?>"
                        onclick="verDetalleEmpleado('<?php echo $empleado['numero_cedula']; ?>')"
                        style="cursor: pointer; transition: all 0.2s;"
                        onmouseover="this.style.backgroundColor='#f8fafc'"
                        onmouseout="this.style.backgroundColor=''"
                        title="Clic para ver todas las novedades de este empleado">
                        <td class="td-nombre">
                            <strong><?php echo htmlspecialchars($empleado['nombres_apellidos']); ?></strong>
                        </td>
                        <td>
                            <span class="cedula-badge"><?php echo htmlspecialchars($empleado['numero_cedula']); ?></span>
                        </td>
                        <td>
                            <span class="badge badge-sede"><?php echo htmlspecialchars($empleado['sede']); ?></span>
                        </td>
                        <td>
                            <span class="badge badge-area"><?php echo htmlspecialchars($empleado['area_trabajo']); ?></span>
                        </td>
                        <td style="text-align: center;">
                            <span class="total-badge"><?php echo $empleado['total_novedades']; ?></span>
                        </td>
                        <td style="text-align: center;">
                            <?php if ($empleado['ausencias'] > 0): ?>
                                <span class="novedad-badge ausencia"><?php echo $empleado['ausencias']; ?></span>
                            <?php else: ?>
                                <span style="color: #94a3b8;">0</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;">
                            <?php if ($empleado['incapacidades'] > 0): ?>
                                <span class="novedad-badge incapacidad"><?php echo $empleado['incapacidades']; ?></span>
                            <?php else: ?>
                                <span style="color: #94a3b8;">0</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;">
                            <?php if ($empleado['vacaciones'] > 0): ?>
                                <span class="novedad-badge vacacion"><?php echo $empleado['vacaciones']; ?></span>
                            <?php else: ?>
                                <span style="color: #94a3b8;">0</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;">
                            <?php 
                            $totalPermisos = $empleado['permisos_remunerados'] + $empleado['permisos_no_remunerados'];
                            if ($totalPermisos > 0): ?>
                                <span class="novedad-badge permiso"><?php echo $totalPermisos; ?></span>
                            <?php else: ?>
                                <span style="color: #94a3b8;">0</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;">
                            <?php 
                            $porcentajeJustif = $empleado['total_novedades'] > 0 ? 
                                round(($empleado['justificadas'] / $empleado['total_novedades']) * 100) : 0;
                            ?>
                            <?php if ($porcentajeJustif >= 70): ?>
                                <span class="justif-badge alta"><?php echo $porcentajeJustif; ?>%</span>
                            <?php elseif ($porcentajeJustif >= 40): ?>
                                <span class="justif-badge media"><?php echo $porcentajeJustif; ?>%</span>
                            <?php else: ?>
                                <span class="justif-badge baja"><?php echo $porcentajeJustif; ?>%</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <small style="color: #64748b;">
                                <?php echo date('d/m/Y', strtotime($empleado['ultima_novedad'])); ?>
                            </small>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Modal Detalle Empleado -->
    <div id="modal-detalle-empleado" class="modal">
        <div class="modal-content" style="max-width: 1200px; max-height: 90vh; overflow: auto;">
            <div class="modal-header">
                <h3 id="modal-empleado-titulo">Detalle de Empleado</h3>
                <button class="modal-close" onclick="cerrarModalEmpleado()">&times;</button>
            </div>
            <div class="modal-body" id="modal-empleado-body">
                <div style="text-align: center; padding: 2rem; color: #64748b;">
                    Cargando información del empleado...
                </div>
            </div>
        </div>
    </div>

</main>

<style>
/* Estilos específicos para estadísticas de empleados */
.page-header { 
    display: flex; 
    justify-content: space-between; 
    align-items: flex-start; 
    margin-bottom: 2rem; 
    flex-wrap: wrap; 
    gap: 1rem; 
}

.page-header h1 { 
    margin: 0; 
    font-size: 1.75rem; 
    color: #1e293b; 
    font-weight: 700; 
}

.btn-secondary {
    padding: 0.75rem 1.25rem;
    background: #64748b;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-secondary:hover {
    background: #475569;
    transform: translateY(-1px);
}

.td-nombre { 
    font-weight: 500; 
}

.cedula-badge {
    background: #f1f5f9;
    color: #475569;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-family: monospace;
}

.total-badge {
    background: #3b82f6;
    color: white;
    font-size: 0.875rem;
    font-weight: 700;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    display: inline-block;
    min-width: 30px;
}

.novedad-badge {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    display: inline-block;
    min-width: 25px;
    text-align: center;
}

.novedad-badge.ausencia {
    background: #fee2e2;
    color: #dc2626;
}

.novedad-badge.incapacidad {
    background: #fef3c7;
    color: #d97706;
}

.novedad-badge.vacacion {
    background: #dcfce7;
    color: #16a34a;
}

.novedad-badge.permiso {
    background: #e0e7ff;
    color: #4f46e5;
}

.justif-badge {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    display: inline-block;
    min-width: 40px;
    text-align: center;
}

.justif-badge.alta {
    background: #dcfce7;
    color: #16a34a;
}

.justif-badge.media {
    background: #fef3c7;
    color: #d97706;
}

.justif-badge.baja {
    background: #fee2e2;
    color: #dc2626;
}

.badge {
    padding: 0.25rem 0.75rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.badge-sede {
    background: #f3e8ff;
    color: #7c3aed;
}

.badge-area {
    background: #f0f9ff;
    color: #0369a1;
}

/* Mini estadísticas */
.stat-card-mini {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
    transition: all 0.3s;
}

.stat-card-mini:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: #3b82f6;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.875rem;
    color: #64748b;
    font-weight: 600;
}



/* Empty state */
.empty-state {
    background: white;
    padding: 3rem;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
}

.empty-state p {
    font-size: 1.1rem;
    color: #64748b;
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    .data-table th,
    .data-table td {
        white-space: nowrap;
        padding: 0.5rem;
        font-size: 0.875rem;
    }
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    align-items: center;
    justify-content: center;
}

.modal.active {
    display: flex;
}

.modal-content {
    background: #fff;
    border-radius: 12px;
    width: 95%;
    max-width: 1400px;
    max-height: 95vh;
    overflow: auto;
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    border-bottom: 2px solid #f1f5f9;
    background: #f8fafc;
    border-radius: 12px 12px 0 0;
}

.modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #64748b;
    cursor: pointer;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: all 0.2s;
}

.modal-close:hover {
    background: #f1f5f9;
    color: #1e293b;
}

.modal-body {
    padding: 2rem;
}

/* Tabla de novedades en modal */
.modal-novedades-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
    font-size: 0.875rem;
}

.modal-novedades-table th,
.modal-novedades-table td {
    padding: 1rem 0.75rem;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
    vertical-align: top;
}

.modal-novedades-table th {
    background: #1e293b;
    color: white;
    font-weight: 600;
    position: sticky;
    top: 0;
    z-index: 10;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.modal-novedades-table tr:hover {
    background: #f8fafc;
}

.modal-novedades-table td {
    border-right: 1px solid #f1f5f9;
}

.modal-novedades-table td:last-child {
    border-right: none;
}

/* Empleado clickeable */
.empleado-clickeable:hover {
    background: #f8fafc !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Botones de acción */
.btn-icon {
    background: #e0f2fe;
    border: none;
    color: #0369a1;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    padding: 0.3rem 0.75rem;
    border-radius: 5px;
    transition: all 0.15s ease;
    margin-right: 0.25rem;
}

.btn-icon:hover {
    background: #3b82f6;
    color: #ffffff;
}

.btn-icon-green {
    background: #dcfce7;
    color: #15803d;
}

.btn-icon-green:hover {
    background: #10b981;
    color: #fff;
}
</style>



<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Función para ver detalle completo del empleado
function verDetalleEmpleado(cedula) {
    const modal = document.getElementById('modal-detalle-empleado');
    const titulo = document.getElementById('modal-empleado-titulo');
    const body = document.getElementById('modal-empleado-body');
    
    // Mostrar modal
    modal.classList.add('active');
    titulo.textContent = `Detalle de Empleado - Cédula: ${cedula}`;
    body.innerHTML = '<div style="text-align: center; padding: 2rem; color: #64748b;">Cargando información del empleado...</div>';
    
    // Hacer petición AJAX para obtener todas las novedades del empleado por cédula
    fetch(`<?php echo base_url('api/empleado-detalle'); ?>?cedula=${encodeURIComponent(cedula)}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                body.innerHTML = `<div style="color: #dc2626; text-align: center; padding: 2rem;">${data.error}</div>`;
                return;
            }
            
            // Actualizar título con el nombre más reciente
            titulo.textContent = `Detalle de ${data.empleado.nombres_apellidos}`;
            mostrarDetalleEmpleado(data);
        })
        .catch(error => {
            console.error('Error:', error);
            body.innerHTML = '<div style="color: #dc2626; text-align: center; padding: 2rem;">Error al cargar la información del empleado.</div>';
        });
}

function mostrarDetalleEmpleado(data) {
    const body = document.getElementById('modal-empleado-body');
    
    // Verificar si hay zonas geográficas con datos
    const tieneZonasGeograficas = data.novedades.some(novedad => novedad.zona_geografica && novedad.zona_geografica.trim() !== '');
    
    let html = `
        <!-- Información General del Empleado -->
        <div style="background: #f8fafc; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                <div><strong>Nombre Completo:</strong> ${data.empleado.nombres_apellidos}</div>
                <div><strong>Número de Cédula:</strong> ${data.empleado.numero_cedula}</div>
                <div><strong>Sede:</strong> ${data.empleado.sede}</div>
                <div><strong>Área de Trabajo:</strong> ${data.empleado.area_trabajo}</div>
                <div><strong>Total de Novedades:</strong> <span style="color: #3b82f6; font-weight: 700;">${data.novedades.length}</span></div>
            </div>
        </div>
        
        <!-- Gráfico de Distribución Individual -->
        ${data.tiposNovedad && data.tiposNovedad.length > 0 ? `
        <div style="background: white; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #e2e8f0;">
            <div style="text-align: center; margin-bottom: 1rem;">
                <h4 style="margin: 0 0 0.25rem 0; color: #1e293b; font-size: 1.1rem; font-weight: 700;">Distribución de Novedades</h4>
                <p style="margin: 0; color: #64748b; font-size: 0.8rem;">Tipos de novedad registrados para este empleado</p>
            </div>
            <div style="display: flex; align-items: flex-start; gap: 1.5rem; justify-content: center; flex-wrap: wrap;">
                <!-- Gráfico -->
                <div style="flex-shrink: 0; width: 220px; height: 220px; position: relative;">
                    <canvas id="chartEmpleadoIndividual"></canvas>
                </div>
                <!-- Leyenda personalizada -->
                <div id="leyenda-empleado-individual" style="flex: 1; min-width: 200px; max-width: 300px; align-self: center;">
                    <!-- Se llenará dinámicamente -->
                </div>
            </div>
        </div>` : ''}
        
        <!-- Todas las Novedades Completas -->
        <h4 style="margin: 2rem 0 1rem 0; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
            Historial Completo de Novedades 
            <span style="background: #3b82f6; color: white; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.875rem;">${data.novedades.length}</span>
        </h4>
        
        <div style="overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 8px;">
            <table class="modal-novedades-table">
                <thead>
                    <tr>
                        <th style="min-width: 120px; text-align: center;">Acciones</th>
                        <th style="min-width: 80px;">ID</th>
                        <th style="min-width: 100px;">Fecha Novedad</th>
                        <th style="min-width: 200px;">Empleado</th>
                        <th style="min-width: 120px;">Cédula</th>
                        <th style="min-width: 120px;">Sede</th>
                        ${tieneZonasGeograficas ? '<th style="min-width: 150px;">Zona Geográfica</th>' : ''}
                        <th style="min-width: 180px;">Área de Trabajo</th>
                        <th style="min-width: 80px;">Turno</th>
                        <th style="min-width: 180px;">Tipo de Novedad</th>
                        <th style="min-width: 100px;">Justificación</th>
                        <th style="min-width: 140px;">Descontar Dominical</th>
                        <th style="min-width: 150px;">Responsable</th>
                        <th style="min-width: 140px;">Fecha Registro</th>
                    </tr>
                </thead>
                <tbody>`;
    
    data.novedades.forEach((novedad, index) => {
        const justifBadge = novedad.justificacion === 'SI' 
            ? '<span style="background: #dcfce7; color: #16a34a; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.875rem; font-weight: 600; display: inline-block;">SÍ</span>'
            : '<span style="background: #fee2e2; color: #dc2626; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.875rem; font-weight: 600; display: inline-block;">NO</span>';
            
        const domBadge = novedad.descontar_dominical === 'SI'
            ? '<span style="background: #fef3c7; color: #d97706; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.875rem; font-weight: 600; display: inline-block;">SÍ</span>'
            : '<span style="background: #f1f5f9; color: #64748b; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.875rem; font-weight: 600; display: inline-block;">NO</span>';
        
        // Determinar color del tipo de novedad
        let novedadColor = '#4f46e5';
        let novedadBg = '#e0e7ff';
        if (novedad.novedad === 'AUSENCIA') { novedadColor = '#dc2626'; novedadBg = '#fee2e2'; }
        else if (novedad.novedad === 'INCAPACIDAD') { novedadColor = '#d97706'; novedadBg = '#fef3c7'; }
        else if (novedad.novedad === 'VACACIONES') { novedadColor = '#16a34a'; novedadBg = '#dcfce7'; }
        else if (novedad.novedad.includes('PERMISO')) { novedadColor = '#7c3aed'; novedadBg = '#f3e8ff'; }
        
        const rowBg = index % 2 === 0 ? '#ffffff' : '#f8fafc';
        
        html += `
            <tr style="background: ${rowBg};">
                <td style="text-align: center;">
                    <button class="btn-icon" onclick="verDetalleNovedad(${novedad.id}, event)">Detalle</button>
                    <button class="btn-icon btn-icon-green" onclick="verAdjuntosNovedad(${novedad.id}, event)">Adjuntos</button>
                </td>
                <td>
                    <span style="background: #1e293b; color: white; padding: 0.375rem 0.75rem; border-radius: 6px; font-family: monospace; font-size: 0.875rem; font-weight: 700; display: inline-block;">
                        #${novedad.id}
                    </span>
                </td>
                <td style="font-weight: 600; color: #1e293b;">
                    ${new Date(novedad.fecha_novedad).toLocaleDateString('es-ES', {
                        weekday: 'short',
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    })}
                </td>
                <td style="font-weight: 600; color: #1e293b;">
                    ${novedad.nombres_apellidos}
                </td>
                <td style="font-family: monospace; font-weight: 600; color: #475569;">
                    ${novedad.numero_cedula}
                </td>
                <td>
                    <span style="background: #f3e8ff; color: #7c3aed; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.875rem; font-weight: 600; display: inline-block;">
                        ${novedad.sede}
                    </span>
                </td>
                ${tieneZonasGeograficas ? `
                <td style="color: #64748b;">
                    ${novedad.zona_geografica && novedad.zona_geografica.trim() !== '' ? novedad.zona_geografica : '<span style="color: #94a3b8; font-style: italic;">Sin zona</span>'}
                </td>` : ''}
                <td>
                    <span style="background: #f0f9ff; color: #0369a1; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.875rem; font-weight: 600; display: inline-block;">
                        ${novedad.area_trabajo}
                    </span>
                </td>
                <td>
                    <span style="background: ${novedad.turno === 'DÍA' ? '#fef3c7' : '#1e293b'}; color: ${novedad.turno === 'DÍA' ? '#d97706' : '#ffffff'}; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.875rem; font-weight: 600; display: inline-block;">
                        ${novedad.turno}
                    </span>
                </td>
                <td>
                    <span style="background: ${novedadBg}; color: ${novedadColor}; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.875rem; font-weight: 600; display: inline-block;">
                        ${novedad.novedad}
                    </span>
                </td>
                <td>${justifBadge}</td>
                <td>${domBadge}</td>
                <td style="font-weight: 600; color: #1e293b;">
                    ${novedad.responsable}
                </td>
                <td style="font-size: 0.875rem; color: #64748b;">
                    ${new Date(novedad.created_at).toLocaleDateString('es-ES')}<br>
                    ${new Date(novedad.created_at).toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'})}
                </td>
            </tr>`;
    });
    
    html += `
                </tbody>
            </table>
        </div>
        
        <!-- Información adicional -->
        <div style="margin-top: 2rem; padding: 1rem; background: #f0f9ff; border-radius: 8px; border-left: 4px solid #3b82f6;">
            <p style="margin: 0; font-size: 0.875rem; color: #1e40af;">
                <strong>Información:</strong> Esta tabla muestra TODOS los registros de novedades para la cédula <strong>${data.empleado.numero_cedula}</strong>. 
                Si el nombre aparece escrito de diferentes maneras, es porque fue registrado así en diferentes ocasiones. 
                El sistema agrupa por cédula para evitar duplicados del mismo empleado.
                ${!tieneZonasGeograficas ? ' La columna "Zona Geográfica" se oculta porque no hay datos registrados.' : ''}
            </p>
        </div>`;
    
    body.innerHTML = html;
    
    // Crear gráfico individual si hay datos de tipos de novedad
    if (data.tiposNovedad && data.tiposNovedad.length > 0) {
        // Esperar un poco para que el DOM se actualice
        setTimeout(() => {
            const canvas = document.getElementById('chartEmpleadoIndividual');
            const leyendaContainer = document.getElementById('leyenda-empleado-individual');
            
            if (canvas && leyendaContainer) {
                // Crear leyenda personalizada - COMPACTA
                let leyendaHTML = '<div style="display: grid; grid-template-columns: 1fr; gap: 0.4rem;">';
                
                data.tiposNovedad.forEach((item, index) => {
                    const color = pieColors[index % pieColors.length];
                    leyendaHTML += `
                        <div class="leyenda-item-individual" data-tipo="${item.tipo}" 
                             style="display: flex; align-items: center; gap: 0.6rem; padding: 0.4rem 0.6rem; background: #f8fafc; border-radius: 6px; border-left: 3px solid ${color}; transition: all 0.2s; cursor: pointer; font-size: 0.8rem;"
                             onmouseover="this.style.backgroundColor='#f1f5f9'; this.style.transform='translateX(2px)'" 
                             onmouseout="this.style.backgroundColor='#f8fafc'; this.style.transform='translateX(0)'"
                             onclick="filtrarHistorialPorTipo('${item.tipo}')">
                            <div style="width: 10px; height: 10px; background: ${color}; border-radius: 50%; flex-shrink: 0;"></div>
                            <div style="flex: 1; line-height: 1.2;">
                                <div style="font-weight: 600; color: #1e293b; font-size: 0.8rem;">${item.tipo}</div>
                                <div style="color: #64748b; font-size: 0.7rem;">
                                    <span style="font-weight: 600; color: #3b82f6;">${item.total}</span> registros 
                                    (<span style="font-weight: 600;">${item.porcentaje}%</span>)
                                </div>
                            </div>
                            <div style="font-size: 0.6rem; color: #94a3b8; font-weight: 500;">Clic para filtrar</div>
                        </div>`;
                });
                
                leyendaHTML += '</div>';
                leyendaContainer.innerHTML = leyendaHTML;
                
                // Crear gráfico sin leyenda - COMPACTO
                new Chart(canvas, {
                    type: 'pie',
                    data: {
                        labels: data.tiposNovedad.map(item => item.tipo),
                        datasets: [{
                            data: data.tiposNovedad.map(item => item.total),
                            backgroundColor: pieColors.slice(0, data.tiposNovedad.length),
                            borderColor: '#ffffff',
                            borderWidth: 2,
                            hoverOffset: 6,
                            hoverBorderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false // Desactivar leyenda por defecto
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleFont: {
                                    family: "'Inter', sans-serif",
                                    size: 11,
                                    weight: '600'
                                },
                                bodyFont: {
                                    family: "'Inter', sans-serif",
                                    size: 10
                                },
                                padding: 8,
                                cornerRadius: 4,
                                displayColors: true,
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        animation: {
                            animateRotate: true,
                            animateScale: true,
                            duration: 600,
                            easing: 'easeInOutQuart'
                        },
                        onClick: (event, activeElements) => {
                            if (activeElements.length > 0) {
                                const index = activeElements[0].index;
                                const tipoNovedad = data.tiposNovedad[index].tipo;
                                
                                // Filtrar historial por tipo de novedad
                                filtrarHistorialPorTipo(tipoNovedad);
                            }
                        }
                    }
                });
            }
        }, 100);
    }
}

// Variable global para el filtro activo en el modal
let filtroHistorialActivo = null;

// Función para filtrar el historial por tipo de novedad en el modal
function filtrarHistorialPorTipo(tipoNovedad) {
    const filasHistorial = document.querySelectorAll('.modal-novedades-table tbody tr');
    
    // Si ya está activo el mismo filtro, quitarlo
    if (filtroHistorialActivo === tipoNovedad) {
        // Quitar filtro
        filtroHistorialActivo = null;
        filasHistorial.forEach(fila => fila.style.display = '');
        
        // Restaurar estilos de todos los elementos de leyenda
        document.querySelectorAll('.leyenda-item-individual').forEach(item => {
            item.style.backgroundColor = '#f8fafc';
            item.style.borderLeftWidth = '3px';
            item.style.opacity = '1';
        });
        
        // Actualizar título
        actualizarTituloHistorial('Historial Completo de Novedades', filasHistorial.length);
        
        return;
    }
    
    // Aplicar nuevo filtro
    filtroHistorialActivo = tipoNovedad;
    let visibles = 0;
    
    filasHistorial.forEach(fila => {
        // Buscar la celda que contiene el tipo de novedad
        // Buscar en todas las celdas para encontrar el tipo de novedad (es más robusto)
        let tipoEnFila = '';
        for (let i = 0; i < fila.cells.length; i++) {
            const celda = fila.cells[i];
            const texto = celda.textContent.trim();
            
            // Verificar si esta celda contiene un tipo de novedad conocido
            if (texto === 'AUSENCIA' || texto === 'INCAPACIDAD' || texto === 'VACACIONES' || 
                texto.includes('PERMISO') || texto === 'RENUNCIA' || texto.includes('REINTEGRO')) {
                tipoEnFila = texto;
                break;
            }
        }
        
        if (tipoEnFila === tipoNovedad) {
            fila.style.display = '';
            visibles++;
        } else {
            fila.style.display = 'none';
        }
    });
    
    // Actualizar estilos de leyenda
    document.querySelectorAll('.leyenda-item-individual').forEach(item => {
        if (item.dataset.tipo === tipoNovedad) {
            item.style.backgroundColor = '#dbeafe';
            item.style.borderLeftWidth = '6px';
            item.style.opacity = '1';
        } else {
            item.style.backgroundColor = '#f8fafc';
            item.style.borderLeftWidth = '3px';
            item.style.opacity = '0.5';
        }
    });
    
    // Actualizar título del historial
    actualizarTituloHistorial(`Historial Filtrado: ${tipoNovedad}`, visibles);
}

// Función para actualizar el título del historial
function actualizarTituloHistorial(texto, cantidad) {
    const titulo = document.querySelector('h4');
    if (titulo && titulo.textContent.includes('Historial')) {
        titulo.innerHTML = `
            ${texto}
            <span style="background: #3b82f6; color: white; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.875rem;">${cantidad}</span>
        `;
    }
}

function cerrarModalEmpleado() {
    document.getElementById('modal-detalle-empleado').classList.remove('active');
}

// Cerrar modal al hacer clic fuera
document.getElementById('modal-detalle-empleado').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModalEmpleado();
    }
});

// Variable global para el filtro de tipo activo en la tabla principal
let filtroTipoActivoTabla = null;

// Búsqueda en tiempo real para empleados
const inputBusquedaEmpleados = document.getElementById('busqueda-empleados');
const countEmpleados = document.getElementById('empleados-count');
const noResultsEmpleados = document.getElementById('no-results-empleados');

if (inputBusquedaEmpleados) {
    // Efecto focus en el input
    inputBusquedaEmpleados.addEventListener('focus', function() {
        this.style.borderColor = '#3b82f6';
        this.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.1)';
    });
    
    inputBusquedaEmpleados.addEventListener('blur', function() {
        this.style.borderColor = '#e2e8f0';
        this.style.boxShadow = 'none';
    });

    inputBusquedaEmpleados.addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        filtrarEmpleados(q, filtroTipoActivoTabla);
    });
    
    // Permitir limpiar filtro con Escape
    inputBusquedaEmpleados.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (filtroTipoActivoTabla) {
                limpiarFiltroTipo();
            } else {
                this.value = '';
                filtrarEmpleados('', null);
            }
        }
    });
}

// Función para limpiar el filtro de tipo en la tabla principal
function limpiarFiltroTipo() {
    filtroTipoActivoTabla = null;
    const inputBusqueda = document.getElementById('busqueda-empleados');
    
    // Restaurar input pero mantener el texto de búsqueda
    const textoBusqueda = inputBusqueda.value;
    inputBusqueda.style.backgroundColor = '';
    inputBusqueda.placeholder = 'Buscar por nombre, cédula, sede o área...';
    
    // Restaurar estilos de leyenda
    document.querySelectorAll('.leyenda-item-general').forEach(item => {
        item.style.backgroundColor = '#f8fafc';
        item.style.borderLeftWidth = '4px';
        item.style.opacity = '1';
    });
    
    // Aplicar búsqueda sin filtro de tipo
    filtrarEmpleados(textoBusqueda.trim().toLowerCase(), null);
}

// Función mejorada para filtrar empleados (con filtro de tipo + búsqueda)
function filtrarEmpleados(q, tipoNovedad) {
    const filas = document.querySelectorAll('.fila-empleado');
    let visibles = 0;

    filas.forEach(fila => {
        let mostrar = true;
        
        // Aplicar filtro de tipo de novedad si existe
        if (tipoNovedad) {
            const tipoData = tipoNovedad.toLowerCase();
            let tieneEseTipo = false;
            
            // Verificar si el empleado tiene ese tipo de novedad
            if (tipoData.includes('ausencia') && parseInt(fila.dataset.ausencias) > 0) tieneEseTipo = true;
            if (tipoData.includes('incapacidad') && parseInt(fila.dataset.incapacidades) > 0) tieneEseTipo = true;
            if (tipoData.includes('vacacion') && parseInt(fila.dataset.vacaciones) > 0) tieneEseTipo = true;
            if (tipoData.includes('permiso') && parseInt(fila.dataset.permisos) > 0) tieneEseTipo = true;
            if (tipoData.includes('renuncia') && parseInt(fila.dataset.renuncias || 0) > 0) tieneEseTipo = true;
            
            if (!tieneEseTipo) mostrar = false;
        }
        
        // Aplicar búsqueda de texto si existe
        if (mostrar && q) {
            const campos = [
                fila.dataset.nombre,
                fila.dataset.cedula,
                fila.dataset.sede,
                fila.dataset.area
            ].join(' ');

            if (!campos.includes(q)) {
                mostrar = false;
            }
        }
        
        // Mostrar u ocultar fila
        if (mostrar) {
            fila.style.display = '';
            visibles++;
        } else {
            fila.style.display = 'none';
        }
    });

    // Actualizar contador
    if (q || tipoNovedad) {
        let mensaje = visibles + ' empleado' + (visibles !== 1 ? 's' : '');
        if (tipoNovedad) mensaje += ' con ' + tipoNovedad;
        if (q) mensaje += ' que coinciden con "' + q + '"';
        countEmpleados.textContent = mensaje;
        countEmpleados.style.display = 'inline-block';
    } else {
        countEmpleados.style.display = 'none';
    }

    // Mostrar/ocultar mensaje de no resultados
    if (noResultsEmpleados) {
        noResultsEmpleados.style.display = (q && visibles === 0) ? 'block' : 'none';
    }
    
    // Ocultar/mostrar tabla
    const tabla = document.getElementById('tabla-empleados-completa');
    if (tabla) {
        tabla.style.display = (q && visibles === 0) ? 'none' : 'table';
    }
}

// Datos para el gráfico de torta
const tiposNovedadData = <?php echo json_encode($tiposNovedad ?? []); ?>;

// Colores profesionales para el gráfico de torta
const pieColors = [
    '#ef4444',
    '#f59e0b',
    '#10b981',
    '#8b5cf6',
    '#6366f1',
    '#ec4899',
    '#06b6d4',
    '#84cc16',
    '#f97316',
    '#64748b'
];

// Crear gráfico cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, tiposNovedadData:', tiposNovedadData);
    
    if (!tiposNovedadData || tiposNovedadData.length === 0) {
        console.log('No hay datos para el gráfico');
        const loading = document.getElementById('chart-loading');
        if (loading) loading.textContent = 'No hay datos disponibles';
        return;
    }

    // Crear leyenda
    const leyendaContainer = document.getElementById('leyenda-tipos-novedad');
    if (leyendaContainer) {
        let html = '<div style="display: grid; grid-template-columns: 1fr; gap: 0.5rem;">';
        tiposNovedadData.forEach((item, index) => {
            const color = pieColors[index % pieColors.length];
            html += `
                <div class="leyenda-item-general" data-tipo="${item.tipo}" 
                     style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 0.75rem; background: #f8fafc; border-radius: 6px; border-left: 4px solid ${color}; transition: all 0.2s; cursor: pointer; font-size: 0.85rem;" 
                     onmouseover="this.style.backgroundColor='#f1f5f9'; this.style.transform='translateX(2px)'" 
                     onmouseout="this.style.backgroundColor='#f8fafc'; this.style.transform='translateX(0)'"
                     onclick="filtrarPorTipoNovedadTabla('${item.tipo}')">
                    <div style="width: 12px; height: 12px; background: ${color}; border-radius: 50%; flex-shrink: 0;"></div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: #1e293b; font-size: 0.85rem;">${item.tipo}</div>
                        <div style="color: #64748b; font-size: 0.75rem;">
                            <span style="font-weight: 600; color: #3b82f6;">${item.total}</span> registros 
                            (<span style="font-weight: 600;">${item.porcentaje}%</span>)
                        </div>
                    </div>
                    <div style="font-size: 0.65rem; color: #94a3b8; font-weight: 500;">Clic para filtrar</div>
                </div>`;
        });
        html += '</div>';
        leyendaContainer.innerHTML = html;
    }

    // Crear gráfico
    const canvas = document.getElementById('chartTiposNovedad');
    if (!canvas) {
        console.error('Canvas no encontrado');
        return;
    }
    
    console.log('Creando gráfico...');
    
    try {
        const ctx = canvas.getContext('2d');
        
        const chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: tiposNovedadData.map(item => item.tipo),
                datasets: [{
                    data: tiposNovedadData.map(item => item.total),
                    backgroundColor: pieColors.slice(0, tiposNovedadData.length),
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    hoverOffset: 10,
                    hoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = ((value / total) * 100).toFixed(1);
                                return `${context.label}: ${value} (${pct}%)`;
                            }
                        }
                    }
                },
                onClick: (event, activeElements) => {
                    if (activeElements.length > 0) {
                        const index = activeElements[0].index;
                        const tipoNovedad = tiposNovedadData[index].tipo;
                        
                        // Filtrar tabla por tipo de novedad
                        filtrarPorTipoNovedadTabla(tipoNovedad);
                    }
                }
            }
        });
        
        // Ocultar mensaje de cargando
        const loading = document.getElementById('chart-loading');
        if (loading) loading.style.display = 'none';
        
        console.log('Gráfico creado exitosamente', chart);
    } catch (error) {
        console.error('Error al crear el gráfico:', error);
        const loading = document.getElementById('chart-loading');
        if (loading) loading.textContent = 'Error al cargar el gráfico: ' + error.message;
    }
});

// Función para filtrar empleados por tipo de novedad en la tabla principal
function filtrarPorTipoNovedadTabla(tipoNovedad) {
    const inputBusqueda = document.getElementById('busqueda-empleados');
    
    // Si ya está activo el mismo filtro, quitarlo
    if (filtroTipoActivoTabla === tipoNovedad) {
        limpiarFiltroTipo();
        return;
    }
    
    // Aplicar nuevo filtro
    filtroTipoActivoTabla = tipoNovedad;
    
    // Actualizar estilos de leyenda
    document.querySelectorAll('.leyenda-item-general').forEach(item => {
        if (item.dataset.tipo === tipoNovedad) {
            item.style.backgroundColor = '#dbeafe';
            item.style.borderLeftWidth = '6px';
            item.style.opacity = '1';
        } else {
            item.style.backgroundColor = '#f8fafc';
            item.style.borderLeftWidth = '4px';
            item.style.opacity = '0.5';
        }
    });
    
    // Actualizar placeholder del input (no bloquearlo)
    inputBusqueda.placeholder = `🔍 Filtrando por: ${tipoNovedad} - Puedes seguir buscando...`;
    inputBusqueda.style.backgroundColor = '#eff6ff';
    inputBusqueda.style.borderColor = '#3b82f6';
    
    // Aplicar filtro con búsqueda actual
    const textoBusqueda = inputBusqueda.value.trim().toLowerCase();
    filtrarEmpleados(textoBusqueda, tipoNovedad);
}

// Función para ver el detalle completo de una novedad específica
function verDetalleNovedad(novedadId, event) {
    event.stopPropagation();
    
    // Buscar la novedad en los datos actuales
    fetch(`<?php echo base_url('api/novedad-detalle'); ?>?id=${novedadId}`)
        .then(response => response.json())
        .then(novedad => {
            if (novedad.error) {
                alert('Error: ' + novedad.error);
                return;
            }
            
            // Función helper para escapar HTML y manejar UTF-8 correctamente
            const escapeHtml = (text) => {
                if (!text) return '';
                // Crear un elemento temporal para escapar el texto
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            };
            
            // Función para decodificar entidades HTML si es necesario
            const decodeHtml = (html) => {
                if (!html) return '';
                const txt = document.createElement('textarea');
                txt.innerHTML = html;
                return txt.value;
            };
            
            // Crear modal con el detalle
            const modalHTML = `
                <div id="modal-detalle-novedad" class="modal active" style="display: flex;">
                    <div class="modal-content" style="max-width: 800px;">
                        <div class="modal-header">
                            <h3>Detalle de Novedad #${novedad.id}</h3>
                            <button class="modal-close" onclick="document.getElementById('modal-detalle-novedad').remove()">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                                <div>
                                    <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Empleado</label>
                                    <p style="margin: 0.25rem 0 0 0; font-weight: 600; color: #1e293b;">${escapeHtml(novedad.nombres_apellidos)}</p>
                                </div>
                                <div>
                                    <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Cédula</label>
                                    <p style="margin: 0.25rem 0 0 0; font-family: monospace; font-weight: 600; color: #475569;">${escapeHtml(novedad.numero_cedula)}</p>
                                </div>
                                <div>
                                    <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Fecha Novedad</label>
                                    <p style="margin: 0.25rem 0 0 0; color: #1e293b;">${new Date(novedad.fecha_novedad).toLocaleDateString('es-ES', {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'})}</p>
                                </div>
                                <div>
                                    <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Turno</label>
                                    <p style="margin: 0.25rem 0 0 0; color: #1e293b;">${escapeHtml(novedad.turno)}</p>
                                </div>
                                <div>
                                    <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Sede</label>
                                    <p style="margin: 0.25rem 0 0 0; color: #1e293b;">${escapeHtml(novedad.sede)}</p>
                                </div>
                                <div>
                                    <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Área de Trabajo</label>
                                    <p style="margin: 0.25rem 0 0 0; color: #1e293b;">${escapeHtml(novedad.area_trabajo)}</p>
                                </div>
                                ${novedad.zona_geografica ? `
                                <div style="grid-column: span 2;">
                                    <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Zona Geográfica</label>
                                    <p style="margin: 0.25rem 0 0 0; color: #1e293b;">${escapeHtml(novedad.zona_geografica)}</p>
                                </div>` : ''}
                                <div style="grid-column: span 2;">
                                    <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Tipo de Novedad</label>
                                    <p style="margin: 0.25rem 0 0 0; font-weight: 600; color: #3b82f6; font-size: 1.1rem;">${escapeHtml(novedad.novedad)}</p>
                                </div>
                                <div>
                                    <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Justificación</label>
                                    <p style="margin: 0.25rem 0 0 0; color: ${novedad.justificacion === 'SI' ? '#16a34a' : '#dc2626'}; font-weight: 600;">${escapeHtml(novedad.justificacion)}</p>
                                </div>
                                <div>
                                    <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Descontar Dominical</label>
                                    <p style="margin: 0.25rem 0 0 0; color: ${novedad.descontar_dominical === 'SI' ? '#d97706' : '#64748b'}; font-weight: 600;">${escapeHtml(novedad.descontar_dominical)}</p>
                                </div>
                                ${novedad.observacion_novedad ? `
                                <div style="grid-column: span 2;">
                                    <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Observación</label>
                                    <div style="margin: 0.5rem 0 0 0; background: #f8fafc; padding: 1rem; border-radius: 8px; border-left: 4px solid #3b82f6; line-height: 1.6;">
                                        ${escapeHtml(novedad.observacion_novedad)}
                                    </div>
                                </div>` : ''}
                                ${novedad.nota ? `
                                <div style="grid-column: span 2;">
                                    <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Nota Adicional</label>
                                    <div style="margin: 0.5rem 0 0 0; background: #fef3c7; padding: 1rem; border-radius: 8px; border-left: 4px solid #d97706; line-height: 1.6;">
                                        ${escapeHtml(novedad.nota)}
                                    </div>
                                </div>` : ''}
                                <div>
                                    <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Responsable</label>
                                    <p style="margin: 0.25rem 0 0 0; color: #1e293b;">${escapeHtml(novedad.responsable)}</p>
                                </div>
                                <div>
                                    <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Fecha de Registro</label>
                                    <p style="margin: 0.25rem 0 0 0; color: #64748b; font-size: 0.875rem;">${new Date(novedad.created_at).toLocaleString('es-ES')}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            
            // Cerrar al hacer clic fuera
            document.getElementById('modal-detalle-novedad').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.remove();
                }
            });
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar el detalle de la novedad');
        });
}

// Función para ver los archivos adjuntos de una novedad
function verAdjuntosNovedad(novedadId, event) {
    event.stopPropagation();
    
    fetch(`<?php echo base_url('api/novedad-adjuntos'); ?>?id=${novedadId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error: ' + data.error);
                return;
            }
            
            // Usar la función escapeHtml ya definida globalmente
            
            let adjuntosHTML = '';
            if (data.adjuntos && data.adjuntos.length > 0) {
                adjuntosHTML = '<div style="display: grid; gap: 0.75rem;">';
                data.adjuntos.forEach(adjunto => {
                    const icono = adjunto.tipo_mime.includes('pdf') ? '📄' : 
                                 adjunto.tipo_mime.includes('image') ? '🖼️' : 
                                 adjunto.tipo_mime.includes('word') ? '📝' : '📎';
                    
                    adjuntosHTML += `
                        <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <div style="font-size: 2rem;">${icono}</div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: #1e293b;">${escapeHtml(adjunto.nombre_archivo)}</div>
                                <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">${escapeHtml(adjunto.tipo_mime)}</div>
                            </div>
                            <a href="<?php echo base_url('storage/uploads/'); ?>${escapeHtml(adjunto.ruta_archivo)}" 
                               target="_blank" 
                               download="${escapeHtml(adjunto.nombre_archivo)}"
                               style="background: #3b82f6; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-size: 0.875rem; font-weight: 600; transition: all 0.2s;"
                               onmouseover="this.style.background='#2563eb'"
                               onmouseout="this.style.background='#3b82f6'">
                                Descargar
                            </a>
                        </div>
                    `;
                });
                adjuntosHTML += '</div>';
            } else {
                adjuntosHTML = '<div style="text-align: center; padding: 2rem; color: #64748b;"><p>No hay archivos adjuntos para esta novedad.</p></div>';
            }
            
            const modalHTML = `
                <div id="modal-adjuntos-novedad" class="modal active" style="display: flex;">
                    <div class="modal-content" style="max-width: 700px;">
                        <div class="modal-header">
                            <h3>Archivos Adjuntos - Novedad #${novedadId}</h3>
                            <button class="modal-close" onclick="document.getElementById('modal-adjuntos-novedad').remove()">&times;</button>
                        </div>
                        <div class="modal-body">
                            ${adjuntosHTML}
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            
            // Cerrar al hacer clic fuera
            document.getElementById('modal-adjuntos-novedad').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.remove();
                }
            });
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los archivos adjuntos');
        });
}

</script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>