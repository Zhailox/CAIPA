<?php
ob_start();
?>
<div>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger" style="margin-bottom: 1rem;"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2><?= Icons::file() ?> Reportes PDF Centralizados</h2>
    </div>

    <div class="panel" style="max-width: 800px; margin: 0 auto; padding: 2rem;">
        <form method="POST" action="index.php?action=reportes_generate" target="_blank">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>¿Qué módulo deseas exportar?</label>
                    <select name="modulo" id="modulo" required onchange="toggleCedula(this.value)">
                        <option value="estudiantes">Lista General de Estudiantes</option>
                        <option value="personal">Lista General de Personal</option>
                        <option value="profesores">Lista General de Profesores</option>
                        <option value="clases">Lista de Clases Activas</option>
                        <option value="ficha_estudiante">Ficha Individual de Estudiante</option>
                    </select>
                </div>

                <div class="form-group" id="cedula_group" style="display: none; grid-column: 1 / -1;">
                    <label>Cédula del Estudiante</label>
                    <input type="text" name="cedula" id="cedula" placeholder="Ej: 12345678">
                    <small class="text-muted">Obligatorio si seleccionaste Ficha Individual.</small>
                </div>

                <div class="form-group" id="desde_group">
                    <label>Registrados Desde (Fecha)</label>
                    <input type="date" name="fecha_desde">
                    <small class="text-muted">Opcional. Deja en blanco para ignorar.</small>
                </div>

                <div class="form-group" id="hasta_group">
                    <label>Registrados Hasta (Fecha)</label>
                    <input type="date" name="fecha_hasta">
                    <small class="text-muted">Opcional. Deja en blanco para ignorar.</small>
                </div>

                <div class="form-group" id="limite_group" style="grid-column: 1 / -1;">
                    <label>Límite de Registros</label>
                    <select name="limite">
                        <option value="all">Todos los registros</option>
                        <option value="50">Últimos 50</option>
                        <option value="100">Últimos 100</option>
                    </select>
                </div>
            </div>

            <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary" style="font-size: 1.1rem; padding: 0.75rem 2rem;">
                    <?= Icons::check() ?> Generar PDF
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleCedula(val) {
    const cedulaGroup = document.getElementById('cedula_group');
    const desdeGroup = document.getElementById('desde_group');
    const hastaGroup = document.getElementById('hasta_group');
    const limiteGroup = document.getElementById('limite_group');
    
    if(val === 'ficha_estudiante') {
        cedulaGroup.style.display = 'block';
        document.getElementById('cedula').setAttribute('required', 'required');
        
        desdeGroup.style.display = 'none';
        hastaGroup.style.display = 'none';
        limiteGroup.style.display = 'none';
    } else {
        cedulaGroup.style.display = 'none';
        document.getElementById('cedula').removeAttribute('required');
        
        desdeGroup.style.display = 'block';
        hastaGroup.style.display = 'block';
        limiteGroup.style.display = 'block';
    }
}
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
