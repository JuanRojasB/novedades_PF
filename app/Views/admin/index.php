<?php $css_files = []; require_once APP_PATH . '/Views/layouts/header.php'; ?>

<body class="simple-layout">

<?php require_once APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="app-main">
    <div class="page-header">
        <h1>Administración de Catálogos</h1>
        <a href="<?php echo base_url('novedades'); ?>" class="btn-secondary">Volver a Novedades</a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Tabs -->
    <div class="tabs-container">
        <div class="tabs">
            <button class="tab-btn active" onclick="showTab('sedes')">Sedes</button>
            <button class="tab-btn" onclick="showTab('areas')">Áreas de Trabajo</button>
            <button class="tab-btn" onclick="showTab('tipos')">Tipos de Novedad</button>
        </div>
    </div>

    <!-- Tab: Sedes -->
    <div id="tab-sedes" class="tab-content active">
        <div class="admin-section">
            <div class="section-header">
                <h2>Sedes</h2>
                <button class="btn-primary" onclick="showModal('modal-crear-sede')">Agregar Sede</button>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Fecha Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sedes as $sede): ?>
                        <tr>
                            <td><?php echo $sede['id']; ?></td>
                            <td><?php echo htmlspecialchars($sede['nombre']); ?></td>
                            <td>
                                <?php if ($sede['activo']): ?>
                                    <span class="badge badge-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge badge-error">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($sede['created_at'])); ?></td>
                            <td>
                                <button class="btn-icon" onclick="editarSede(<?php echo htmlspecialchars(json_encode($sede)); ?>)" title="Editar">✏️</button>
                                <button class="btn-icon" onclick="eliminarSede(<?php echo $sede['id']; ?>)" title="Eliminar">🗑️</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab: Áreas -->
    <div id="tab-areas" class="tab-content">
        <div class="admin-section">
            <div class="section-header">
                <h2>Áreas de Trabajo</h2>
                <button class="btn-primary" onclick="showModal('modal-crear-area')">Agregar Área</button>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Fecha Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($areas as $area): ?>
                        <tr>
                            <td><?php echo $area['id']; ?></td>
                            <td><?php echo htmlspecialchars($area['nombre']); ?></td>
                            <td>
                                <?php if ($area['activo']): ?>
                                    <span class="badge badge-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge badge-error">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($area['created_at'])); ?></td>
                            <td>
                                <button class="btn-icon" onclick="editarArea(<?php echo htmlspecialchars(json_encode($area)); ?>)" title="Editar">✏️</button>
                                <button class="btn-icon" onclick="eliminarArea(<?php echo $area['id']; ?>)" title="Eliminar">🗑️</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab: Tipos de Novedad -->
    <div id="tab-tipos" class="tab-content">
        <div class="admin-section">
            <div class="section-header">
                <h2>Tipos de Novedad</h2>
                <button class="btn-primary" onclick="showModal('modal-crear-tipo')">Agregar Tipo</button>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Fecha Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tipos_novedad as $tipo): ?>
                        <tr>
                            <td><?php echo $tipo['id']; ?></td>
                            <td><?php echo htmlspecialchars($tipo['nombre']); ?></td>
                            <td>
                                <?php if ($tipo['activo']): ?>
                                    <span class="badge badge-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge badge-error">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($tipo['created_at'])); ?></td>
                            <td>
                                <button class="btn-icon" onclick="editarTipo(<?php echo htmlspecialchars(json_encode($tipo)); ?>)" title="Editar">✏️</button>
                                <button class="btn-icon" onclick="eliminarTipo(<?php echo $tipo['id']; ?>)" title="Eliminar">🗑️</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Modales -->
<?php require_once APP_PATH . '/Views/admin/modales.php'; ?>

<script>window.APP_BASE_URL = '<?php echo BASE_URL; ?>';</script>
<script src="<?php echo asset_url('js/admin.js'); ?>"></script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
