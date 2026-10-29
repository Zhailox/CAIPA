<?php
// views/auth/register.php
ob_start();
?>
<div class="auth-wrapper">
    <div class="panel auth-panel">
        <div style="text-align: center; margin-bottom: 2rem;">
            <?= Icons::dashboard() ?>
            <h1 class="text-gradient">Registrar Usuario</h1>
        </div>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'] ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="index.php?action=register" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
            <div class="form-group">
                <label for="username">Nombre de Usuario</label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);">
                        <?= Icons::user() ?>
                    </span>
                    <input type="text" id="username" name="username" style="padding-left: 3rem;" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);">
                        <?= Icons::lock() ?>
                    </span>
                    <input type="password" id="password" name="password" style="padding-left: 3rem;" required>
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirmar Contraseña</label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);">
                        <?= Icons::lock() ?>
                    </span>
                    <input type="password" id="confirm_password" name="confirm_password" style="padding-left: 3rem;" required>
                </div>
            </div>



            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                Crear Usuario
            </button>
            <div style="text-align: center; margin-top: 1.5rem;">
                <a href="index.php?action=login" style="color: var(--primary); text-decoration: none;">Volver al Login</a>
            </div>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
