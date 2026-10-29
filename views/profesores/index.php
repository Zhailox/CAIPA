<?php ob_start(); ?>
<div class="page-header">
    <h1><?= Icons::staff() ?> <span class="text-gradient">Profesores</span></h1>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success"><?= Icons::check() ?> <?= htmlspecialchars($_SESSION['success']) ?></div>
<?php unset($_SESSION['success']); endif; ?>
<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger"><?= Icons::warning() ?> <?= htmlspecialchars($_SESSION['error']) ?></div>
<?php unset($_SESSION['error']); endif; ?>

<?php if ($es_administrador): ?>
<div class="panel" style="margin-bottom: 1.5rem;">
    <h3><?= Icons::plus() ?> Registrar Profesor</h3>
    <form method="POST" action="index.php?action=profesores_store" class="inline-form">
        <select name="id_personal" required>
            <option value="">Seleccione Docente...</option>
            <?php foreach ($docentes as $d): ?>
            <option value="<?= $d['id_personal'] ?>"><?= htmlspecialchars($d['nombres_apellidos']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="id_turno" required>
            <option value="">Turno</option>
            <?php foreach ($turnos as $t): ?>
            <option value="<?= $t['id_turno'] ?>"><?= htmlspecialchars($t['descripcion_turno']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary"><?= Icons::check() ?> Registrar</button>
    </form>
</div>
<?php endif; ?>

<div class="panel">
    <h2>Listado de Profesores</h2>
    <?php if (!empty($result['data'])): ?>
    <div class="table-container">
        <table>
            <thead><tr><?php if ($es_administrador): ?><th>ID</th><?php endif; ?><th>Nombre</th><th>Turno</th><?php if ($es_administrador): ?><th>Acciones</th><?php endif; ?></tr></thead>
            <tbody>
                <?php foreach ($result['data'] as $f): ?>
                <tr>
                    <?php if ($es_administrador): ?><td><?= $f['id_profesor'] ?></td><?php endif; ?>
                    <td><?= htmlspecialchars($f['nombre_profesor']) ?></td>
                    <td><span class="badge"><?= htmlspecialchars($f['descripcion_turno'] ?? 'N/A') ?></span></td>
                    <?php if ($es_administrador): ?>
                    <td class="actions-cell">
                        <a href="index.php?action=profesores&editar_profesor=1&id_profesor=<?= $f['id_profesor'] ?>" class="btn btn-sm btn-outline"><?= Icons::edit() ?></a>
                        <a href="index.php?action=profesores&eliminar_profesor=1&id_profesor=<?= $f['id_profesor'] ?>" class="btn btn-sm btn-danger"><?= Icons::trash() ?></a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($result['pages'] > 1): ?>
    <div class="pagination">
        <?php if ($result['current'] > 1): ?><a href="index.php?action=profesores&page=<?= $result['current'] - 1 ?>" class="btn btn-sm btn-outline">&laquo;</a><?php endif; ?>
        <span class="pagination-info">Página <?= $result['current'] ?> de <?= $result['pages'] ?></span>
        <?php if ($result['current'] < $result['pages']): ?><a href="index.php?action=profesores&page=<?= $result['current'] + 1 ?>" class="btn btn-sm btn-outline">&raquo;</a><?php endif; ?>
    </div>
    <?php endif; ?>
    <?php else: ?>
    <p style="color: var(--text-muted); padding: 2rem; text-align: center;">No se encontraron profesores.</p>
    <?php endif; ?>
</div>

<!-- Edit Modal -->
<?php if ($editData): ?>
<div class="modal-overlay">
    <div class="modal-box">
        <h3>Editar Profesor</h3>
        <a href="index.php?action=profesores" class="modal-close">&times;</a>
        <form method="POST" action="index.php?action=profesores_update">
            <input type="hidden" name="id_profesor" value="<?= $editData['id_profesor'] ?>">
            <div class="form-group"><label>Docente</label>
                <select name="id_personal" required>
                    <option value="">Seleccione Docente...</option>
                    <?php foreach ($docentes as $d): ?>
                    <option value="<?= $d['id_personal'] ?>" <?= $editData['id_personal'] == $d['id_personal'] ? 'selected' : '' ?>><?= htmlspecialchars($d['nombres_apellidos']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label>Turno</label>
                <select name="id_turno" required>
                    <?php foreach ($turnos as $t): ?>
                    <option value="<?= $t['id_turno'] ?>" <?= $editData['id_turno'] == $t['id_turno'] ? 'selected' : '' ?>><?= htmlspecialchars($t['descripcion_turno']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><?= Icons::check() ?> Guardar</button>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- Delete Modal -->
<?php if ($deleteData): ?>
<div class="modal-overlay">
    <div class="modal-box">
        <h3>Eliminar Profesor</h3>
        <a href="index.php?action=profesores" class="modal-close">&times;</a>
        <p>¿Eliminar a <strong><?= htmlspecialchars($deleteData['nombre_profesor']) ?></strong>?</p>
        <form method="POST" action="index.php?action=profesores_delete">
            <input type="hidden" name="id_profesor" value="<?= $deleteData['id_profesor'] ?>">
            <button type="submit" class="btn btn-danger">Sí, Eliminar</button>
            <a href="index.php?action=profesores" class="btn btn-outline">Cancelar</a>
        </form>
    </div>
</div>
<?php endif; ?>

<?php $content = ob_get_clean(); require_once __DIR__ . '/../layouts/main.php'; ?>
