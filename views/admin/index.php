<?php
ob_start();
?>
<div>
    <h2 style="margin-bottom: 2rem;">Panel Administrativo</h2>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger" style="margin-bottom: 1rem;"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        
        <div class="panel">
            <h3 style="display:flex; align-items:center; gap:0.5rem; margin-bottom: 1rem;">
                <span style="color:var(--primary);"><?= Icons::dashboard() ?></span> Respaldo de Base de Datos
            </h3>
            <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Descarga un archivo SQL completo con toda la información registrada en el sistema (Estudiantes, Personal, Roles, etc.). Esto sirve como punto de recuperación ante desastres.</p>
            <a href="index.php?action=admin_backup" class="btn btn-primary">
                Descargar Respaldo (SQL)
            </a>
        </div>
        
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
