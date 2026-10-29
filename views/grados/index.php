<?php ob_start(); ?>
<div class="page-header">
    <h1><?= Icons::book() ?> <span class="text-gradient">Grados</span></h1>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success"><?= Icons::check() ?> <?= htmlspecialchars($_SESSION['success']) ?></div>
<?php unset($_SESSION['success']); endif; ?>
<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger"><?= Icons::warning() ?> <?= htmlspecialchars($_SESSION['error']) ?></div>
<?php unset($_SESSION['error']); endif; ?>

<?php if ($es_administrador): ?>
<div class="panel" style="margin-bottom: 1.5rem;">
    <h3><?= Icons::plus() ?> Registrar Grado</h3>
    <form method="POST" action="index.php?action=grados_store" class="inline-form">
        <input type="text" name="nombre_grado" placeholder="Nombre del Grado" required>
        <button type="submit" class="btn btn-primary"><?= Icons::check() ?> Registrar</button>
    </form>
</div>
<?php endif; ?>

<div class="panel">
    <h2>Listado de Grados</h2>
    <?php if (!empty($grados)): ?>
    <div class="table-container">
        <table>
            <thead><tr><th>ID</th><th>Nombre</th><?php if ($es_administrador): ?><th>Acciones</th><?php endif; ?></tr></thead>
            <tbody>
                <?php foreach ($grados as $g): ?>
                <tr>
                    <td><?= $g['id_grado'] ?></td>
                    <td><?= htmlspecialchars($g['nombre_grado']) ?></td>
                    <?php if ($es_administrador): ?>
                    <td class="actions-cell">
                        <a href="index.php?action=grados&editar_grado=1&id_grado=<?= $g['id_grado'] ?>" class="btn btn-sm btn-outline"><?= Icons::edit() ?></a>
                        <a href="index.php?action=grados&eliminar_grado=1&id_grado=<?= $g['id_grado'] ?>" class="btn btn-sm btn-danger"><?= Icons::trash() ?></a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <p style="color: var(--text-muted); padding: 2rem; text-align: center;">No se encontraron grados.</p>
    <?php endif; ?>
</div>

<!-- Edit Modal -->
<?php if (isset($editData) && $editData): ?>
<div class="modal-overlay">
    <div class="modal-box">
        <h3>Editar Grado</h3>
        <a href="index.php?action=grados" class="modal-close">&times;</a>
        <form method="POST" action="index.php?action=grados_update">
            <input type="hidden" name="id_grado" value="<?= $editData['id_grado'] ?>">
            <div class="form-group"><label>Nombre</label><input type="text" name="nombre_grado" value="<?= htmlspecialchars($editData['nombre_grado']) ?>" required></div>
            <button type="submit" class="btn btn-primary"><?= Icons::check() ?> Guardar</button>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- Delete Modal -->
<?php if (isset($deleteData) && $deleteData): ?>
<div class="modal-overlay">
    <div class="modal-box">
        <h3>Eliminar Grado</h3>
        <a href="index.php?action=grados" class="modal-close">&times;</a>
        <p>¿Eliminar <strong><?= htmlspecialchars($deleteData['nombre_grado']) ?></strong>?</p>
        <form method="POST" action="index.php?action=grados_delete">
            <input type="hidden" name="id_grado" value="<?= $deleteData['id_grado'] ?>">
            <button type="submit" class="btn btn-danger">Sí, Eliminar</button>
            <a href="index.php?action=grados" class="btn btn-outline">Cancelar</a>
        </form>
    </div>
</div>
<?php endif; ?>

<?php $content = ob_get_clean(); require_once __DIR__ . '/../layouts/main.php'; ?>
