<?php
// views/auth/login.php
ob_start();
?>
<div class="auth-wrapper">
    <div class="panel auth-panel">
        <div style="text-align: center; margin-bottom: 2rem;">
            <?= Icons::dashboard() ?>
            <h1 class="text-gradient">CAIPA</h1>
            <p style="color: var(--text-muted); margin-top: 0.5rem;">Sistema de Gestión Escolar</p>
        </div>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'] ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success'] ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form action="index.php?action=login" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
            <div class="form-group">
                <label for="username">Usuario</label>
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

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                Ingresar
            </button>
            <div style="text-align: center; margin-top: 1.5rem;">
                <a href="index.php?action=register" style="color: var(--primary); text-decoration: none;">Crear cuenta nueva</a>
            </div>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
