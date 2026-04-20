<nav class="navbar-simple">
    <div class="nav-container">
        <div class="nav-brand">
            <img src="<?php echo asset_url('img/logo-pollo-fiesta.png'); ?>" alt="Pollo Fiesta" class="nav-logo">
            <span class="brand-name">Sistema de Novedades</span>
        </div>
        <div class="nav-center">
            <a href="<?php echo base_url('novedades'); ?>" class="nav-link">Ver Novedades</a>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['rol'] === 'admin'): ?>
                <a href="<?php echo base_url('estadisticas'); ?>" class="nav-link">Estadísticas</a>
                <a href="<?php echo base_url('admin'); ?>" class="nav-link">Administración</a>
            <?php endif; ?>
        </div>
        <div class="nav-actions">
            <?php if (isset($_SESSION['user'])): ?>
                <div class="user-info">
                    <div class="user-details">
                        <span class="user-name"><?php echo htmlspecialchars($_SESSION['user']['nombre']); ?></span>
                        <span class="user-role"><?php echo htmlspecialchars($_SESSION['user']['cargo'] ?? ($_SESSION['user']['rol'] === 'admin' ? 'Administrador' : 'Jefe')); ?></span>
                    </div>
                </div>
            <?php endif; ?>
            <a href="<?php echo base_url('logout'); ?>" class="btn-logout-nav">Cerrar Sesión</a>
        </div>
    </div>
</nav>

<style>
.navbar-simple {
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.nav-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 70px;
    gap: 2rem;
}

.nav-brand {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-shrink: 0;
}

.nav-logo {
    width: 45px;
    height: 45px;
    object-fit: contain;
    background: white;
    border-radius: 8px;
    padding: 4px;
}

.brand-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: white;
    white-space: nowrap;
}

.nav-center {
    display: flex;
    gap: 0.5rem;
    flex: 1;
    justify-content: center;
}

.nav-link {
    color: white;
    text-decoration: none;
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s;
    white-space: nowrap;
}

.nav-link:hover {
    background: rgba(255, 255, 255, 0.15);
}

.nav-actions {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex-shrink: 0;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.user-details {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

.user-name {
    color: white;
    font-weight: 600;
    font-size: 0.95rem;
    line-height: 1.2;
}

.user-role {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.8rem;
    line-height: 1.2;
}

.btn-logout-nav {
    background: rgba(239, 68, 68, 0.9);
    color: white;
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
    white-space: nowrap;
}

.btn-logout-nav:hover {
    background: #dc2626;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

@media (max-width: 1024px) {
    .nav-container {
        flex-wrap: wrap;
        padding: 1rem;
    }
    
    .nav-center {
        order: 3;
        width: 100%;
        justify-content: flex-start;
        padding-top: 0.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }
}

@media (max-width: 768px) {
    .brand-name {
        font-size: 0.9rem;
    }
    
    .user-name {
        font-size: 0.85rem;
    }
    
    .user-role {
        font-size: 0.75rem;
    }
    
    .nav-link {
        padding: 0.5rem 0.8rem;
        font-size: 0.9rem;
    }
}
</style>
