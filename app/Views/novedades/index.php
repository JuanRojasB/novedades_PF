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

    <!-- Barra de búsqueda en tiempo real -->
    <div class="search-bar-card">
        <div class="search-bar-inner">
            <span class="search-icon-svg">
                <svg width="18" height="18" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
            </span>
            <input type="text" id="busqueda-rapida" placeholder="Buscar por ID, nombre, cédula, área, sede, novedad, responsable..." autocomplete="off">
            <span id="busqueda-count" class="search-count"></span>
            <button type="button" id="btn-limpiar-busqueda" onclick="limpiarBusqueda()" style="display:none;">✕</button>
        </div>
    </div>

    <!-- Filtros avanzados (colapsables) -->
    <details class="filters-card">
        <summary class="filters-summary">Filtros avanzados</summary>
        <form method="GET" action="<?php echo base_url('novedades'); ?>" class="filters-form" style="margin-top:1rem;">
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
    </details>

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
                        <th style="width:60px;">ID</th>
                        <th>Fecha</th>
                        <th>Empleado</th>
                        <th>Cédula</th>
                        <th>Área</th>
                        <th>Sede</th>
                        <th>Novedad</th>
                        <th>Turno</th>
                        <th>Justif.</th>
                        <th>Responsable</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-body">
                    <?php foreach ($novedades as $novedad): ?>
                    <tr class="fila-novedad"
                        data-id="<?php echo $novedad['id']; ?>"
                        data-nombre="<?php echo strtolower(htmlspecialchars($novedad['nombres_apellidos'])); ?>"
                        data-cedula="<?php echo $novedad['numero_cedula']; ?>"
                        data-area="<?php echo strtolower(htmlspecialchars($novedad['area_trabajo'])); ?>"
                        data-sede="<?php echo strtolower(htmlspecialchars($novedad['sede'])); ?>"
                        data-novedad="<?php echo strtolower(htmlspecialchars($novedad['novedad'])); ?>"
                        data-responsable="<?php echo strtolower(htmlspecialchars($novedad['responsable'])); ?>"
                        data-turno="<?php echo strtolower($novedad['turno']); ?>"
                        data-justificacion="<?php echo strtolower($novedad['justificacion']); ?>">
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
/* ---- Header ---- */
.page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem; flex-wrap:wrap; gap:0.75rem; }
.page-header h1 { margin:0; font-size:1.5rem; color:#1e293b; }
.total-badge { display:inline-block; background:#e0f2fe; color:#0369a1; font-size:0.78rem; font-weight:600; padding:0.2rem 0.6rem; border-radius:999px; margin-left:0.5rem; }

/* ---- Search bar ---- */
.search-bar-card { background:#fff; border-radius:10px; box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:0.75rem 1rem; margin-bottom:1rem; }
.search-bar-inner { display:flex; align-items:center; gap:0.75rem; }
.search-icon-svg { flex-shrink:0; }
#busqueda-rapida {
    flex:1; border:none; outline:none; font-size:0.95rem; color:#1e293b;
    background:transparent; min-width:0;
}
#busqueda-rapida::placeholder { color:#94a3b8; }
.search-count { font-size:0.8rem; color:#64748b; white-space:nowrap; }
#btn-limpiar-busqueda { background:none; border:none; cursor:pointer; color:#94a3b8; font-size:1.1rem; padding:0 0.25rem; }
#btn-limpiar-busqueda:hover { color:#ef4444; }

/* ---- Filtros colapsables ---- */
.filters-card { background:#fff; border-radius:10px; box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:1rem 1.25rem; margin-bottom:1rem; border:none; }
.filters-summary { cursor:pointer; font-weight:600; color:#475569; font-size:0.9rem; list-style:none; user-select:none; }
.filters-summary::-webkit-details-marker { display:none; }
.filters-summary::before { content:'▶ '; font-size:0.7rem; }
details[open] .filters-summary::before { content:'▼ '; }

/* ---- ID badge ---- */
.id-badge { display:inline-block; background:#f1f5f9; color:#475569; font-size:0.75rem; font-weight:700; padding:0.2rem 0.5rem; border-radius:5px; font-family:monospace; }

/* ---- Tabla ---- */
.td-nombre { font-weight:500; max-width:180px; }
.td-acciones { white-space:nowrap; }

/* ---- Highlight búsqueda ---- */
.highlight { background:#fef08a; border-radius:2px; }

/* ---- Archivos modal ---- */
.archivos-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:1rem; margin-top:0.5rem; }
.archivo-item { border:2px solid #e2e8f0; border-radius:8px; padding:1rem; text-align:center; background:#f8fafc; transition:all 0.15s; }
.archivo-item:hover { border-color:#3b82f6; background:#eff6ff; }
.archivo-icon { width:100%; height:120px; display:flex; align-items:center; justify-content:center; font-size:2.5rem; font-weight:700; border-radius:6px; margin-bottom:0.5rem; }
.archivo-icon.pdf { background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; }
.archivo-icon.doc { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; }
.archivo-icon.img { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
.archivo-preview { width:100%; height:120px; object-fit:cover; border-radius:6px; margin-bottom:0.5rem; }
.archivo-nombre { font-size:0.8rem; color:#475569; word-break:break-all; margin-bottom:0.5rem; }
.archivo-acciones { display:flex; gap:0.4rem; justify-content:center; flex-wrap:wrap; }
.btn-ver { display:inline-block; padding:0.4rem 0.8rem; background:#10b981; color:#fff; border-radius:5px; text-decoration:none; font-size:0.8rem; font-weight:600; }
.btn-ver:hover { background:#059669; }
.btn-descargar { display:inline-block; padding:0.4rem 0.8rem; background:#3b82f6; color:#fff; border-radius:5px; text-decoration:none; font-size:0.8rem; font-weight:600; }
.btn-descargar:hover { background:#2563eb; }

/* ---- Botones ---- */
.btn-icon-green { background:#dcfce7; color:#15803d; }
.btn-icon-green:hover { background:#10b981; color:#fff; }
</style>

<script>
window.APP_BASE_URL = '<?php echo BASE_URL; ?>';
const novedadesData = <?php echo json_encode($novedades); ?>;

/* ============================================================
   BÚSQUEDA EN TIEMPO REAL
   ============================================================ */
const inputBusqueda = document.getElementById('busqueda-rapida');
const btnLimpiar    = document.getElementById('btn-limpiar-busqueda');
const countEl       = document.getElementById('busqueda-count');
const noResults     = document.getElementById('no-results');

if (inputBusqueda) {
    inputBusqueda.addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        btnLimpiar.style.display = q ? 'block' : 'none';
        filtrarTabla(q);
    });
}

function limpiarBusqueda() {
    inputBusqueda.value = '';
    btnLimpiar.style.display = 'none';
    filtrarTabla('');
    inputBusqueda.focus();
}

function filtrarTabla(q) {
    const filas = document.querySelectorAll('.fila-novedad');
    let visibles = 0;

    filas.forEach(fila => {
        if (!q) {
            fila.style.display = '';
            // Quitar highlights
            fila.querySelectorAll('.highlight').forEach(el => {
                el.outerHTML = el.textContent;
            });
            visibles++;
            return;
        }

        const campos = [
            fila.dataset.id,
            fila.dataset.nombre,
            fila.dataset.cedula,
            fila.dataset.area,
            fila.dataset.sede,
            fila.dataset.novedad,
            fila.dataset.responsable,
            fila.dataset.turno,
            fila.dataset.justificacion
        ].join(' ');

        if (campos.includes(q)) {
            fila.style.display = '';
            visibles++;
        } else {
            fila.style.display = 'none';
        }
    });

    // Mostrar contador
    if (q) {
        countEl.textContent = visibles + ' resultado' + (visibles !== 1 ? 's' : '');
    } else {
        countEl.textContent = '';
    }

    // Mostrar mensaje si no hay resultados
    if (noResults) {
        noResults.style.display = (q && visibles === 0) ? 'block' : 'none';
    }
}

/* ============================================================
   MODAL DETALLE
   ============================================================ */
function verDetalle(id) {
    const n = novedadesData.find(x => x.id == id);
    if (!n) return;

    document.getElementById('modal-detalle-titulo').textContent = 'Novedad #' + n.id;

    const rows = [
        ['ID',              '#' + n.id],
        ['Empleado',        n.nombres_apellidos],
        ['Cédula',          n.numero_cedula],
        ['Sede',            n.sede],
        n.zona_geografica ? ['Zona',  n.zona_geografica] : null,
        ['Área',            n.area_trabajo],
        ['Fecha Novedad',   n.fecha_novedad],
        ['Turno',           n.turno],
        ['Novedad',         n.novedad],
        ['Justificación',   n.justificacion === 'SI'
            ? '<span style="color:#15803d;font-weight:600;">SI</span>'
            : '<span style="color:#dc2626;font-weight:600;">NO</span>'],
        ['Desc. Dominical', n.descontar_dominical],
        ['Observación',     n.observacion_novedad],
        n.nota ? ['Nota', n.nota] : null,
        ['Responsable',     n.responsable],
        ['Registrado',      n.created_at],
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

/* ============================================================
   MODAL ARCHIVOS
   ============================================================ */
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
                const ext  = archivo.nombre_archivo.split('.').pop().toLowerCase();
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
            modalBody.innerHTML = '<p style="color:#dc2626;padding:1rem;">Error al cargar los archivos.</p>';
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
