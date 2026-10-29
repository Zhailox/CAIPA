<?php ob_start(); ?>
<div class="page-header">
    <h1><?= Icons::edit() ?> <span class="text-gradient">Editar Clase</span></h1>
    <a href="index.php?action=clases" class="btn btn-outline"><?= Icons::back() ?> Volver</a>
</div>
<div class="panel" style="max-width: 600px;">
    <form method="POST" action="index.php?action=clases_update">
        <input type="hidden" name="id_clase" value="<?= $claseData['id_clase'] ?>">
        <div class="form-group">
            <label>Nombre de la Clase</label>
            <input type="text" name="clase_nombre" value="<?= htmlspecialchars($claseData['clase_nombre']) ?>" required>
        </div>
        <div class="form-group">
            <label>Profesor</label>
            <select name="id_profesor" required>
                <option value="">Seleccione un profesor</option>
                <?php foreach ($profesores as $p): ?>
                <option value="<?= $p['id_profesor'] ?>" <?= ($claseData['id_profesor'] == $p['id_profesor']) ? 'selected' : '' ?>><?= htmlspecialchars($p['nombre_profesor']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Grado</label>
            <select name="id_grado" required>
                <option value="">Seleccione un grado</option>
                <?php foreach ($grados as $g): ?>
                <option value="<?= $g['id_grado'] ?>" <?= ($claseData['id_grado'] == $g['id_grado']) ? 'selected' : '' ?>><?= htmlspecialchars($g['nombre_grado']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><?= Icons::check() ?> Guardar</button>
    </form>
</div>
<?php $content = ob_get_clean(); require_once __DIR__ . '/../layouts/main.php'; ?>
