<?php $css_files = []; require_once APP_PATH . '/Views/layouts/header.php'; ?>

<body class="simple-layout">

<?php require_once APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="app-main">

    <!-- Header -->
    <div class="page-header">
        <div>
            <h1>Novedades Registradas</h1>
            <span class="total-badge"><?php echo count($novedades); ?> registros</span>
        </div>
        <a href="<?php echo base_url('novedades/crear'); ?>" class="btn-primary">+ Nueva Novedad</a>
    </div>

    <!-- Búsqueda y Filtros -->
    <div class="search-filters-container">
        <!-- Búsqueda -->
        <div class="search-section">
            <input type="text" id="busqueda-rapida" placeholder="Buscar por ID, nombre, cédula, área, sede..." autocomplete="off">
            <span id="busqueda-count" class="search-count"></span>
        </div>

        <!-- Filtros -->
        <div class="filters-section">
            <form method="GET" action="<?php echo base_url('novedades'); ?>" class="filters-form">
                <div class="filter-group">
                    <label>Área</label>
                    <select name="area_trabajo">
                        <option value="">Todas</option>
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
                        <option value="">Todas</option>
                        <?php foreach ($sedes as $sede): ?>
                            <option value="<?php echo htmlspecialchars($sede['nombre']); ?>"
                                    <?php echo ($filters['sede'] ?? '') === $sede['nombre'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($sede['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Desde</label>
                    <input type="date" name="fecha_desde" value="<?php echo $filters['fecha_desde'] ?? ''; ?>">
                </div>

                <div class="filter-group">
                    <label>Hasta</label>
                    <input type="date" name="fecha_hasta" value="<?php echo $filters['fecha_hasta'] ?? ''; ?>">
                </div>

                <div class="filter-group">
                    <label>Justificación</label>
                    <select name="justificacion">
                        <option value="">Todas</option>
                        <option value="SI" <?php echo ($filters['justificacion'] ?? '') === 'SI' ? 'selected' : ''; ?>>Sí</option>
                        <option value="NO" <?php echo ($filters['justificacion'] ?? '') === 'NO' ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter">Filtrar</button>
                    <a href="<?php echo base_url('novedades'); ?>" class="btn-clear">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats (solo director) -->
    <?php if ($user['rol'] === 'director' && !empty($estadisticas)): ?>
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

    <!-- Tabla -->
    <div class="table-container">
        <?php if (empty($novedades)): ?>
            <div class="empty-state">
                <p>No hay novedades registradas</p>
                <a href="<?php echo base_url('novedades/crear'); ?>" class="btn-primary">Registrar Primera Novedad</a>
            </div>
        <?php else: ?>
            <div id="no-results" style="display:none;padding:2rem;text-align:center;color:#64748b;">
                No se encontraron resultados para la búsqueda.
            </div>
            <table class="data-table" id="tabla-novedades">
                <thead>
                    <tr>
                        <th class="sortable" data-column="id" style="width:60px;">
                            ID <span class="sort-icon">⇅</span>
                        </th>
                        <th class="sortable" data-column="fecha">
                            Fecha <span class="sort-icon">⇅</span>
                        </th>
                        <th class="sortable" data-column="empleado">
                            Empleado <span class="sort-icon">⇅</span>
                        </th>
                        <th class="sortable" data-column="cedula">
                            Cédula <span class="sort-icon">⇅</span>
                        </th>
                        <th class="sortable" data-column="area">
                            Área <span class="sort-icon">⇅</span>
                        </th>
                        <th class="sortable" data-column="sede">
                            Sede <span class="sort-icon">⇅</span>
                        </th>
                        <th class="sortable" data-column="novedad">
                            Novedad <span class="sort-icon">⇅</span>
                        </th>
                        <th class="sortable" data-column="turno">
                            Turno <span class="sort-icon">⇅</span>
                        </th>
                        <th>Justif.</th>
                        <th class="sortable" data-column="responsable">
                            Responsable <span class="sort-icon">⇅</span>
                        </th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-body">
                    <?php foreach ($novedades as $novedad): ?>
                    <tr class="fila-novedad"
                        data-id="<?php echo $novedad['id']; ?>"
                        data-fecha="<?php echo $novedad['fecha_novedad']; ?>"
                        data-empleado="<?php echo strtolower(htmlspecialchars($novedad['nombres_apellidos'])); ?>"
                        data-cedula="<?php echo $novedad['numero_cedula']; ?>"
                        data-area="<?php echo strtolower(htmlspecialchars($novedad['area_trabajo'])); ?>"
                        data-sede="<?php echo strtolower(htmlspecialchars($novedad['sede'])); ?>"
                        data-novedad="<?php echo strtolower(htmlspecialchars($novedad['novedad'])); ?>"
                        data-turno="<?php echo strtolower($novedad['turno']); ?>"
                        data-justificacion="<?php echo strtolower($novedad['justificacion']); ?>"
                        data-responsable="<?php echo strtolower(htmlspecialchars($novedad['responsable'])); ?>">
                        <td>
                            <span class="id-badge">#<?php echo $novedad['id']; ?></span>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($novedad['fecha_novedad'])); ?></td>
                        <td class="td-nombre"><?php echo htmlspecialchars($novedad['nombres_apellidos']); ?></td>
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
                        <td class="td-acciones">
                            <button class="btn-icon" onclick="verDetalle(<?php echo $novedad['id']; ?>)">Ver</button>
                            <?php if ((int)$novedad['total_archivos'] > 0): ?>
                                <button class="btn-icon btn-icon-green" onclick="verArchivos(<?php echo $novedad['id']; ?>)">
                                    Archivos (<?php echo $novedad['total_archivos']; ?>)
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

<!-- Modal Detalle -->
<div id="modal-detalle" class="modal">
    <div class="modal-content" style="max-width:680px;">
        <div class="modal-header">
            <h3 id="modal-detalle-titulo">Detalle de Novedad</h3>
            <button class="modal-close" onclick="cerrarModalDetalle()">&times;</button>
        </div>
        <div class="modal-body" id="modal-detalle-body"></div>
    </div>
</div>

<!-- Modal Archivos -->
<div id="modal-archivos" class="modal">
    <div class="modal-content" style="max-width:700px;">
        <div class="modal-header">
            <h3>Archivos Adjuntos</h3>
            <button class="modal-close" onclick="cerrarModalArchivos()">&times;</button>
        </div>
        <div class="modal-body" id="modal-archivos-body"></div>
    </div>
</div>

<style>
/* Header */
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
.page-header h1 { margin: 0; font-size: 1.5rem; color: #1e293b; font-weight: 600; }
.total-badge { background: #dbeafe; color: #1e40af; font-size: 0.8rem; font-weight: 600; padding: 0.3rem 0.7rem; border-radius: 999px; }

/* Búsqueda y Filtros */
.search-filters-container { background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.25rem; margin-bottom: 1.5rem; }

.search-section { margin-bottom: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 0.75rem; }
#busqueda-rapida { flex: 1; border: 1px solid #cbd5e1; border-radius: 6px; padding: 0.65rem 0.9rem; font-size: 0.9rem; outline: none; }
#busqueda-rapida:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
.search-count { font-size: 0.8rem; color: #3b82f6; font-weight: 600; background: #eff6ff; padding: 0.3rem 0.7rem; border-radius: 999px; white-space: nowrap; }

.filters-section { }
.filters-form { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; align-items: end; }
.filter-group { display: flex; flex-direction: column; gap: 0.4rem; }
.filter-group label { font-size: 0.8rem; font-weight: 600; color: #475569; }
.filter-group select, .filter-group input { padding: 0.65rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem; outline: none; }
.filter-group select:focus, .filter-group input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
.filter-actions { display: flex; gap: 0.5rem; }
.btn-filter { padding: 0.65rem 1.25rem; background: #3b82f6; color: #fff; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; white-space: nowrap; }
.btn-filter:hover { background: #2563eb; }
.btn-clear { padding: 0.65rem 1.25rem; background: #fff; color: #64748b; border: 1px solid #cbd5e1; border-radius: 6px; font-weight: 500; text-decoration: none; display: flex; align-items: center; white-space: nowrap; }
.btn-clear:hover { background: #f8fafc; }

/* Tabla */
.id-badge { background: #f1f5f9; color: #475569; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.55rem; border-radius: 4px; font-family: monospace; }
.td-nombre { font-weight: 500; }
.td-acciones { white-space: nowrap; }

/* Ordenamiento de columnas */
.sortable {
    cursor: pointer;
    user-select: none;
    position: relative;
    transition: background 0.15s ease;
}

.sortable:hover {
    background: #f8fafc;
}

.sort-icon {
    font-size: 0.7rem;
    color: #94a3b8;
    margin-left: 0.25rem;
}

.sortable.asc .sort-icon::before {
    content: '▲';
    color: #3b82f6;
}

.sortable.desc .sort-icon::before {
    content: '▼';
    color: #3b82f6;
}

.sortable.asc .sort-icon,
.sortable.desc .sort-icon {
    display: none;
}

.sortable.asc .sort-icon::before,
.sortable.desc .sort-icon::before {
    display: inline;
}

/* Modales */
.modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; }
.modal.active { display: flex; }
.modal-content { background: #fff; border-radius: 8px; width: 90%; max-width: 600px; max-height: 90vh; overflow: auto; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2); }
.modal-header { display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid #e2e8f0; }
.modal-header h3 { margin: 0; font-size: 1.125rem; font-weight: 600; color: #1e293b; }
.modal-close { background: none; border: none; font-size: 1.5rem; color: #64748b; cursor: pointer; padding: 0; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 4px; }
.modal-close:hover { background: #f1f5f9; color: #1e293b; }
.modal-body { padding: 1.5rem; }

/* Archivos */
.archivos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem; }
.archivo-item { border: 2px solid #e2e8f0; border-radius: 8px; padding: 1rem; text-align: center; background: #f8fafc; transition: all 0.15s; }
.archivo-item:hover { border-color: #3b82f6; background: #eff6ff; }
.archivo-icon { width: 100%; height: 100px; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 700; border-radius: 6px; margin-bottom: 0.5rem; }
.archivo-icon.pdf { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; }
.archivo-icon.doc { background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff; }
.archivo-preview { width: 100%; height: 100px; object-fit: cover; border-radius: 6px; margin-bottom: 0.5rem; }
.archivo-nombre { font-size: 0.75rem; color: #475569; word-break: break-all; margin-bottom: 0.5rem; }
.archivo-acciones { display: flex; gap: 0.4rem; justify-content: center; flex-wrap: wrap; }
.btn-ver { padding: 0.4rem 0.75rem; background: #10b981; color: #fff; border-radius: 5px; text-decoration: none; font-size: 0.75rem; font-weight: 600; }
.btn-ver:hover { background: #059669; }
.btn-descargar { padding: 0.4rem 0.75rem; background: #3b82f6; color: #fff; border-radius: 5px; text-decoration: none; font-size: 0.75rem; font-weight: 600; }
.btn-descargar:hover { background: #2563eb; }

/* Botones */
.btn-icon-green { background: #dcfce7; color: #15803d; }
.btn-icon-green:hover { background: #10b981; color: #fff; }

/* Responsive */
@media (max-width: 768px) {
    .page-header { flex-direction: column; align-items: flex-start; }
    .search-section { flex-direction: column; align-items: stretch; }
    .filters-form { grid-template-columns: 1fr; }
    .filter-actions { flex-direction: column; }
    .btn-filter, .btn-clear { width: 100%; justify-content: center; }
}
</style>

<script>
window.APP_BASE_URL = '<?php echo BASE_URL; ?>';
const novedadesData = <?php echo json_encode($novedades); ?>;

/* Ordenamiento de tabla */
let currentSort = { column: null, direction: 'asc' };

document.querySelectorAll('.sortable').forEach(th => {
    th.addEventListener('click', function() {
        const column = this.getAttribute('data-column');
        
        // Determinar dirección
        if (currentSort.column === column) {
            currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort.direction = 'asc';
        }
        currentSort.column = column;
        
        // Actualizar iconos
        document.querySelectorAll('.sortable').forEach(header => {
            header.classList.remove('asc', 'desc');
        });
        this.classList.add(currentSort.direction);
        
        // Ordenar tabla
        sortTable(column, currentSort.direction);
    });
});

function sortTable(column, direction) {
    const tbody = document.getElementById('tabla-body');
    const rows = Array.from(tbody.querySelectorAll('.fila-novedad'));
    
    rows.sort((a, b) => {
        let aVal = a.getAttribute('data-' + column);
        let bVal = b.getAttribute('data-' + column);
        
        // Convertir a número si es ID o cédula
        if (column === 'id' || column === 'cedula') {
            aVal = parseInt(aVal) || 0;
            bVal = parseInt(bVal) || 0;
        }
        
        // Comparar
        if (aVal < bVal) return direction === 'asc' ? -1 : 1;
        if (aVal > bVal) return direction === 'asc' ? 1 : -1;
        return 0;
    });
    
    // Reordenar filas
    rows.forEach(row => tbody.appendChild(row));
}

/* Búsqueda en tiempo real */
const inputBusqueda = document.getElementById('busqueda-rapida');
const countEl = document.getElementById('busqueda-count');
const noResults = document.getElementById('no-results');

if (inputBusqueda) {
    inputBusqueda.addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        filtrarTabla(q);
    });
}

function filtrarTabla(q) {
    const filas = document.querySelectorAll('.fila-novedad');
    let visibles = 0;

    filas.forEach(fila => {
        if (!q) {
            fila.style.display = '';
            visibles++;
            return;
        }

        const campos = [
            fila.dataset.id,
            fila.dataset.empleado,
            fila.dataset.cedula,
            fila.dataset.area,
            fila.dataset.sede,
            fila.dataset.novedad,
            fila.dataset.responsable
        ].join(' ');

        if (campos.includes(q)) {
            fila.style.display = '';
            visibles++;
        } else {
            fila.style.display = 'none';
        }
    });

    if (q) {
        countEl.textContent = visibles + ' resultado' + (visibles !== 1 ? 's' : '');
        countEl.style.display = 'inline-block';
    } else {
        countEl.style.display = 'none';
    }

    if (noResults) {
        noResults.style.display = (q && visibles === 0) ? 'block' : 'none';
    }
}

/* Modal Detalle */
function verDetalle(id) {
    const n = novedadesData.find(x => x.id == id);
    if (!n) return;

    document.getElementById('modal-detalle-titulo').textContent = 'Novedad #' + n.id;

    const rows = [
        ['ID', '#' + n.id],
        ['Empleado', n.nombres_apellidos],
        ['Cédula', n.numero_cedula],
        ['Sede', n.sede],
        n.zona_geografica ? ['Zona', n.zona_geografica] : null,
        ['Área', n.area_trabajo],
        ['Fecha', n.fecha_novedad],
        ['Turno', n.turno],
        ['Novedad', n.novedad],
        ['Justificación', n.justificacion === 'SI' ? '<span style="color:#15803d;font-weight:600;">SI</span>' : '<span style="color:#dc2626;font-weight:600;">NO</span>'],
        ['Desc. Dominical', n.descontar_dominical],
        ['Observación', n.observacion_novedad],
        n.nota ? ['Nota', n.nota] : null,
        ['Responsable', n.responsable],
        ['Registrado', n.created_at],
    ].filter(Boolean);

    const html = `<table style="width:100%;border-collapse:collapse;font-size:0.875rem;">
        ${rows.map((r, i) => `
        <tr style="background:${i % 2 === 0 ? '#f8fafc' : '#fff'}">
            <td style="padding:0.55rem 1rem;font-weight:600;width:38%;border:1px solid #e2e8f0;color:#334155;">${r[0]}</td>
            <td style="padding:0.55rem 1rem;border:1px solid #e2e8f0;color:#1e293b;">${r[1]}</td>
        </tr>`).join('')}
    </table>`;

    document.getElementById('modal-detalle-body').innerHTML = html;
    document.getElementById('modal-detalle').classList.add('active');
}

function cerrarModalDetalle() {
    document.getElementById('modal-detalle').classList.remove('active');
}

document.getElementById('modal-detalle').addEventListener('click', e => {
    if (e.target === e.currentTarget) cerrarModalDetalle();
});

/* Modal Archivos */
function verArchivos(id) {
    const modalBody = document.getElementById('modal-archivos-body');
    modalBody.innerHTML = '<p style="text-align:center;color:#64748b;padding:1rem;">Cargando...</p>';
    document.getElementById('modal-archivos').classList.add('active');

    fetch(`${window.APP_BASE_URL}/api/archivos/novedad/${id}`)
        .then(r => r.json())
        .then(archivos => {
            if (!archivos.length) {
                modalBody.innerHTML = '<p style="text-align:center;color:#64748b;padding:1rem;">No hay archivos adjuntos.</p>';
                return;
            }

            const grid = document.createElement('div');
            grid.className = 'archivos-grid';

            archivos.forEach(archivo => {
                const ext = archivo.nombre_archivo.split('.').pop().toLowerCase();
                const urlV = `${window.APP_BASE_URL}/api/archivo/${archivo.id}`;
                const urlD = `${window.APP_BASE_URL}/api/descargar/${archivo.id}`;
                const item = document.createElement('div');
                item.className = 'archivo-item';

                let preview = '';
                if (['jpg','jpeg','png','gif'].includes(ext)) {
                    preview = `<img src="${urlV}" alt="${archivo.nombre_archivo}" class="archivo-preview">`;
                } else if (ext === 'pdf') {
                    preview = `<div class="archivo-icon pdf">PDF</div>`;
                } else {
                    preview = `<div class="archivo-icon doc">DOC</div>`;
                }

                const kb = Math.round(archivo.tamanio / 1024);
                item.innerHTML = `
                    ${preview}
                    <div class="archivo-nombre">${archivo.nombre_archivo}</div>
                    <div style="font-size:0.75rem;color:#94a3b8;margin-bottom:0.5rem;">${kb} KB</div>
                    <div class="archivo-acciones">
                        <a href="${urlV}" target="_blank" class="btn-ver">Ver</a>
                        <a href="${urlD}" class="btn-descargar">Descargar</a>
                    </div>`;
                grid.appendChild(item);
            });

            modalBody.innerHTML = '';
            modalBody.appendChild(grid);
        })
        .catch(() => {
            modalBody.innerHTML = '<p style="color:#dc2626;padding:1rem;">Error al cargar archivos.</p>';
        });
}

function cerrarModalArchivos() {
    document.getElementById('modal-archivos').classList.remove('active');
}

document.getElementById('modal-archivos').addEventListener('click', e => {
    if (e.target === e.currentTarget) cerrarModalArchivos();
});
</script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
