<?php $css_files = ['style']; require_once APP_PATH . '/Views/layouts/header.php'; ?>

<div class="auth-body">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <img src="<?php echo asset_url('img/logo-pollo-fiesta.png'); ?>" alt="Pollo Fiesta" style="width: 80px; height: 80px; object-fit: contain; margin: 0 auto 1rem; display: block;">
                </div>
                <h1 class="auth-title">Bienvenido</h1>
                <p class="auth-subtitle">Ingresa tus credenciales para acceder al sistema</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo base_url('login'); ?>" class="auth-form">
                <div class="form-field">
                    <label for="username">Usuario</label>
                    <input type="text" id="username" name="username" required autofocus placeholder="Ingresa tu usuario">
                </div>
                
                <div class="form-field">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required placeholder="Ingresa tu contraseña">
                </div>
                
                <div class="auth-actions">
                    <button type="submit" class="primary-button">Iniciar Sesión</button>
                </div>
            </form>
            
            <div class="auth-footer" style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e2e8f0; text-align: center;">
                <p style="font-size: 0.8rem; color: #64748b; margin: 0 0 0.5rem;">Credenciales de acceso:</p>
                <p style="font-size: 0.85rem; color: #475569; margin: 0;"><strong>Usuario:</strong> usuario | <strong>Contraseña:</strong> 123456</p>
            </div>
        </div>
    </div>
</div>

<style>
.auth-body {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ffffff;
    padding: 2rem 1rem;
}

.auth-container {
    width: 100%;
    max-width: 420px;
}
</style>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
