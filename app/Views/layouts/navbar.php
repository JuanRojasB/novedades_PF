<nav class="navbar-simple">
    <div class="nav-container">
        <div class="nav-brand">
            <img src="<?php echo asset_url('img/logo-pollo-fiesta.png'); ?>" alt="Pollo Fiesta" class="nav-logo">
            <span class="brand-name">Sistema de Novedades</span>
        </div>
        
        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
            <span></span>
            <span></span>
            <span></span>
        </button>
        
        <div class="nav-menu" id="navMenu">
            <div class="nav-center">
                <?php if (isset($_SESSION['user']) && stripos($_SESSION['user']['nombre'], 'johanna') !== false): ?>
                    <a href="<?php echo base_url('novedades'); ?>" class="nav-link">Ver Novedades</a>
                    <a href="<?php echo base_url('estadisticas'); ?>" class="nav-link">Estadísticas</a>
                    <a href="<?php echo base_url('admin'); ?>" class="nav-link">Administración</a>
                    <?php if ($_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false): ?>
                        <a href="/informe-novedades/public/ver_correos_simulados.php" class="nav-link" style="background: rgba(255, 255, 255, 0.15);">📧 Correos Dev</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="nav-actions">
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="user-info">
                        <div class="user-details">
                            <span class="user-name"><?php echo htmlspecialchars($_SESSION['user']['nombre']); ?></span>
                            <span class="user-role"><?php echo htmlspecialchars($_SESSION['user']['cargo'] ?? ($_SESSION['user']['rol'] === 'director' ? 'Director' : 'Jefe')); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                <a href="<?php echo base_url('logout'); ?>" class="btn-logout-nav">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</nav>

<style>
.navbar-simple {
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 100;
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
    position: relative;
}

.nav-brand {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-shrink: 0;
    z-index: 101;
}

.nav-logo {
    width: 45px;
    height: 45px;
    object-fit: contain;
}

.brand-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: white;
    white-space: nowrap;
}

.nav-toggle {
    display: none;
    flex-direction: column;
    gap: 5px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
    z-index: 101;
}

.nav-toggle span {
    width: 25px;
    height: 3px;
    background: white;
    border-radius: 3px;
    transition: all 0.3s ease;
}

.nav-toggle.active span:nth-child(1) {
    transform: rotate(45deg) translate(7px, 7px);
}

.nav-toggle.active span:nth-child(2) {
    opacity: 0;
}

.nav-toggle.active span:nth-child(3) {
    transform: rotate(-45deg) translate(7px, -7px);
}

.nav-menu {
    display: flex;
    align-items: center;
    gap: 2rem;
    flex: 1;
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
        padding: 0 1.5rem;
    }
    
    .brand-name {
        font-size: 1rem;
    }
    
    .nav-toggle {
        display: flex;
    }
    
    .nav-menu {
        position: fixed;
        top: 70px;
        left: 0;
        right: 0;
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        flex-direction: column;
        align-items: stretch;
        gap: 0;
        padding: 0;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, padding 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 99;
        pointer-events: none;
    }
    
    .nav-menu.active {
        max-height: 500px;
        padding: 1rem;
        pointer-events: auto;
    }
    
    .nav-center {
        flex-direction: column;
        gap: 0;
        width: 100%;
    }
    
    .nav-link {
        width: 100%;
        text-align: left;
        padding: 0.875rem 1rem;
        border-radius: 6px;
    }
    
    .nav-actions {
        flex-direction: column;
        gap: 0.75rem;
        width: 100%;
        margin-top: 0.5rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .user-info {
        width: 100%;
        justify-content: space-between;
    }
    
    .user-details {
        align-items: flex-start;
    }
    
    .btn-logout-nav {
        width: 100%;
        text-align: center;
        justify-content: center;
        display: flex;
    }
}

@media (max-width: 768px) {
    .nav-container {
        padding: 0 1rem;
        min-height: 60px;
    }
    
    .nav-logo {
        width: 40px;
        height: 40px;
    }
    
    .brand-name {
        font-size: 0.9rem;
    }
    
    .nav-menu {
        top: 60px;
    }
    
    .user-name {
        font-size: 0.875rem;
    }
    
    .user-role {
        font-size: 0.75rem;
    }
}

@media (max-width: 480px) {
    .brand-name {
        display: none;
    }
    
    .nav-container {
        gap: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');
    
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            this.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
        
        // Cerrar menú al hacer click en un enlace
        const navLinks = navMenu.querySelectorAll('.nav-link, .btn-logout-nav');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navToggle.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });
        
        // Cerrar menú al hacer click fuera
        document.addEventListener('click', function(e) {
            if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
                navToggle.classList.remove('active');
                navMenu.classList.remove('active');
            }
        });
        
        // Asegurar que el menú esté cerrado al cargar la página
        navToggle.classList.remove('active');
        navMenu.classList.remove('active');
    }
});
</script>
