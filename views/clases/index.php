<?php ob_start(); ?>
<div class="page-header">
    <h1><?= Icons::book() ?> <span class="text-gradient">Clases</span></h1>
    <?php if ($es_administrador): ?>
    <a href="index.php?action=clases_create" class="btn btn-primary"><?= Icons::plus() ?> Registrar Clase</a>
    <?php endif; ?>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success"><?= Icons::check() ?> <?= htmlspecialchars($_SESSION['success']) ?></div>
<?php unset($_SESSION['success']); endif; ?>
<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger"><?= Icons::warning() ?> <?= htmlspecialchars($_SESSION['error']) ?></div>
<?php unset($_SESSION['error']); endif; ?>

<div class="panel">
    <h2>Listado de Clases</h2>
    <?php if (!empty($result['data'])): ?>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <?php if ($es_administrador): ?><th>ID</th><?php endif; ?>
                    <th>Nombre</th><th>Grado</th><th>Profesor</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result['data'] as $fila): ?>
                <tr>
                    <?php if ($es_administrador): ?><td><?= htmlspecialchars($fila['id_clase']) ?></td><?php endif; ?>
                    <td><?= htmlspecialchars($fila['clase_nombre']) ?></td>
                    <td><?= htmlspecialchars($fila['nombre_grado'] ?? 'Sin grado') ?></td>
                    <td><?= htmlspecialchars($fila['nombre_profesor'] ?? 'Sin profesor') ?></td>
                    <td class="actions-cell">
                        <a href="index.php?action=clases_manage&id_clase=<?= $fila['id_clase'] ?>" class="btn btn-sm btn-outline"><?= Icons::eye() ?> Gestionar</a>
                        <?php if ($es_administrador): ?>
                        <a href="index.php?action=clases_edit&id_clase=<?= $fila['id_clase'] ?>" class="btn btn-sm btn-outline"><?= Icons::edit() ?></a>
                        <form method="POST" action="index.php?action=clases_delete&id_clase=<?= $fila['id_clase'] ?>" style="display:inline" onsubmit="return confirm('¿Eliminar esta clase?')">
                            <button type="submit" class="btn btn-sm btn-danger"><?= Icons::trash() ?></button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    <?php if ($result['pages'] > 1): ?>
    <div class="pagination">
        <?php if ($result['current'] > 1): ?>
        <a href="index.php?action=clases&page=<?= $result['current'] - 1 ?>" class="btn btn-sm btn-outline">&laquo; Anterior</a>
        <?php endif; ?>
        <span class="pagination-info">Página <?= $result['current'] ?> de <?= $result['pages'] ?></span>
        <?php if ($result['current'] < $result['pages']): ?>
        <a href="index.php?action=clases&page=<?= $result['current'] + 1 ?>" class="btn btn-sm btn-outline">Siguiente &raquo;</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php else: ?>
    <p style="color: var(--text-muted); padding: 2rem; text-align: center;">No se encontraron clases.</p>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
