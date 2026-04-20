<nav class="navbar-simple">
    <div class="nav-container">
        <div class="nav-brand">
            <span class="brand-name">Pollo Fiesta - Sistema de Novedades</span>
        </div>
        <div class="nav-actions">
            <a href="<?php echo base_url('novedades'); ?>" class="nav-link">Ver Novedades</a>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['rol'] === 'admin'): ?>
                <a href="<?php echo base_url('admin'); ?>" class="nav-link">Administración</a>
            <?php endif; ?>
            <a href="<?php echo base_url('logout'); ?>" class="btn-logout-nav">Cerrar Sesión</a>
        </div>
    </div>
</nav>
