<?php $css_files = []; require_once APP_PATH . '/Views/layouts/header.php'; ?>

<body class="simple-layout">

<?php require_once APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="app-main">
    <div class="page-header">
        <h1>Novedades Registradas</h1>
        <a href="<?php echo base_url('novedades/crear'); ?>" class="btn-primary">Nueva Novedad</a>
    </div>

    <!-- Filtros -->
    <div class="filters-card">
        <form method="GET" action="<?php echo base_url('novedades'); ?>" class="filters-form">
            <div class="filter-group">
                <label>Área de Trabajo</label>
                <select name="area_trabajo">
                    <option value="">Todas las áreas</option>
                    <?php foreach ($areas as $area): ?>
                        <option value="<?php echo htmlspecialchars($area['nombre']); ?>" 
                                <?php echo ($filters['area_trabajo'] ?? '') === $area['nombre'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($area['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label>Sede</label>
                <select name="sede">
                    <option value="">Todas las sedes</option>
                    <?php foreach ($sedes as $sede): ?>
                        <option value="<?php echo htmlspecialchars($sede['nombre']); ?>" 
                                <?php echo ($filters['sede'] ?? '') === $sede['nombre'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($sede['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label>Fecha Desde</label>
                <input type="date" name="fecha_desde" value="<?php echo $filters['fecha_desde'] ?? ''; ?>">
            </div>

            <div class="filter-group">
                <label>Fecha Hasta</label>
                <input type="date" name="fecha_hasta" value="<?php echo $filters['fecha_hasta'] ?? ''; ?>">
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter">Filtrar</button>
                <a href="<?php echo base_url('novedades'); ?>" class="btn-clear">Limpiar</a>
            </div>
        </form>
    </div>


    <!-- Estadísticas (solo para admin) -->
    <?php if ($user['rol'] === 'admin' && !empty($estadisticas)): ?>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value"><?php echo count($novedades); ?></div>
            <div class="stat-label">Total Novedades</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo count($estadisticas); ?></div>
            <div class="stat-label">Áreas Activas</div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Tabla de Novedades -->
    <div class="table-container">
        <?php if (empty($novedades)): ?>
            <div class="empty-state">
                <p>No hay novedades registradas</p>
                <a href="<?php echo base_url('novedades/crear'); ?>" class="btn-primary">Registrar Primera Novedad</a>
            </div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Empleado</th>
                        <th>Cédula</th>
                        <th>Área</th>
                        <th>Sede</th>
                        <th>Novedad</th>
                        <th>Turno</th>
                        <th>Justificación</th>
                        <th>Responsable</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($novedades as $novedad): ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($novedad['fecha_novedad'])); ?></td>
                        <td><?php echo htmlspecialchars($novedad['nombres_apellidos']); ?></td>
                        <td><?php echo htmlspecialchars($novedad['numero_cedula']); ?></td>
                        <td><span class="badge badge-area"><?php echo htmlspecialchars($novedad['area_trabajo']); ?></span></td>
                        <td><?php echo htmlspecialchars($novedad['sede']); ?></td>
                        <td><?php echo htmlspecialchars($novedad['novedad']); ?></td>
                        <td><?php echo htmlspecialchars($novedad['turno']); ?></td>
                        <td>
                            <?php if ($novedad['justificacion'] === 'SI'): ?>
                                <span class="badge badge-success">SI</span>
                            <?php else: ?>
                                <span class="badge badge-error">NO</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($novedad['responsable']); ?></td>
                        <td>
                            <button class="btn-icon" onclick="verDetalle(<?php echo $novedad['id']; ?>)" title="Ver detalle">
                                Ver
                            </button>
                            <?php if ($novedad['total_archivos'] > 0): ?>
                                <button class="btn-icon" onclick="verArchivos(<?php echo $novedad['id']; ?>)" title="Ver archivos">
                                    Descargar
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>

<!-- Modal para ver archivos -->
<div id="modal-archivos" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Archivos Adjuntos</h3>
            <button class="modal-close" onclick="cerrarModal()">&times;</button>
        </div>
        <div class="modal-body" id="modal-archivos-body">
            <!-- Contenido dinámico -->
        </div>
    </div>
</div>

<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    align-items: center;
    justify-content: center;
}

.modal.active {
    display: flex;
}

.archivos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.archivo-item {
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
    text-align: center;
    background: #f8fafc;
    transition: all 0.15s ease;
}

.archivo-item:hover {
    border-color: #3b82f6;
    background: #eff6ff;
}

.archivo-preview {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 6px;
    margin-bottom: 0.5rem;
}

.archivo-icon {
    width: 100%;
    height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    border-radius: 6px;
    margin-bottom: 0.5rem;
}

.archivo-icon.pdf {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.archivo-icon.doc {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
}

.archivo-nombre {
    font-size: 0.875rem;
    color: #475569;
    word-break: break-word;
    margin-bottom: 0.5rem;
}

.btn-descargar {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: #3b82f6;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 600;
    transition: all 0.15s ease;
    margin: 0.25rem;
}

.btn-descargar:hover {
    background: #2563eb;
}

.btn-ver {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: #10b981;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 600;
    transition: all 0.15s ease;
    margin: 0.25rem;
}

.btn-ver:hover {
    background: #059669;
}

.archivo-acciones {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    flex-wrap: wrap;
}
</style>

<script>
const novedadesData = <?php echo json_encode($novedades); ?>;

function verDetalle(id) {
    alert('Funcionalidad de detalle en desarrollo. ID: ' + id);
}

function verArchivos(id) {
    const novedad = novedadesData.find(n => n.id == id);
    if (!novedad) return;
    
    // Obtener archivos desde la API
    fetch(`<?php echo BASE_URL; ?>/api/archivos/novedad/${id}`)
        .then(response => response.json())
        .then(archivos => {
            if (archivos.length === 0) {
                alert('No hay archivos adjuntos');
                return;
            }
            
            const modalBody = document.getElementById('modal-archivos-body');
            modalBody.innerHTML = '<div class="archivos-grid"></div>';
            const grid = modalBody.querySelector('.archivos-grid');
            
            archivos.forEach(archivo => {
                const extension = archivo.nombre_archivo.split('.').pop().toLowerCase();
                const archivoItem = document.createElement('div');
                archivoItem.className = 'archivo-item';
                
                // Usar la API para servir archivos (tanto para ver como para descargar)
                const urlArchivo = `<?php echo BASE_URL; ?>/api/archivo/${archivo.id}`;
                const urlDescarga = `<?php echo BASE_URL; ?>/api/descargar/${archivo.id}`;
                
                if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
                    archivoItem.innerHTML = `
                        <img src="${urlArchivo}" alt="${archivo.nombre_archivo}" class="archivo-preview">
                        <div class="archivo-nombre">${archivo.nombre_archivo}</div>
                        <div class="archivo-acciones">
                            <a href="${urlArchivo}" target="_blank" class="btn-ver">Ver</a>
                            <a href="${urlDescarga}" class="btn-descargar">Descargar</a>
                        </div>
                    `;
                } else if (extension === 'pdf') {
                    archivoItem.innerHTML = `
                        <div class="archivo-icon pdf">PDF</div>
                        <div class="archivo-nombre">${archivo.nombre_archivo}</div>
                        <div class="archivo-acciones">
                            <a href="${urlArchivo}" target="_blank" class="btn-ver">Ver</a>
                            <a href="${urlDescarga}" class="btn-descargar">Descargar</a>
                        </div>
                    `;
                } else {
                    archivoItem.innerHTML = `
                        <div class="archivo-icon doc">DOC</div>
                        <div class="archivo-nombre">${archivo.nombre_archivo}</div>
                        <div class="archivo-acciones">
                            <a href="${urlArchivo}" target="_blank" class="btn-ver">Abrir</a>
                            <a href="${urlDescarga}" class="btn-descargar">Descargar</a>
                        </div>
                    `;
                }
                
                grid.appendChild(archivoItem);
            });
            
            document.getElementById('modal-archivos').classList.add('active');
        })
        .catch(error => {
            console.error('Error al cargar archivos:', error);
            alert('Error al cargar los archivos');
        });
}

function cerrarModal() {
    document.getElementById('modal-archivos').classList.remove('active');
}

// Cerrar modal al hacer clic fuera
document.getElementById('modal-archivos').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModal();
    }
});
</script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
