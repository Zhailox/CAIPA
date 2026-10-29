<?php ob_start(); ?>
<div class="page-header">
    <h1><?= Icons::book() ?> <span class="text-gradient"><?= htmlspecialchars($claseData['clase_nombre']) ?></span></h1>
    <a href="index.php?action=clases" class="btn btn-outline"><?= Icons::back() ?> Volver a Clases</a>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success"><?= Icons::check() ?> <?= htmlspecialchars($_SESSION['success']) ?></div>
<?php unset($_SESSION['success']); endif; ?>
<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger"><?= Icons::warning() ?> <?= htmlspecialchars($_SESSION['error']) ?></div>
<?php unset($_SESSION['error']); endif; ?>

<div class="panel" style="margin-bottom: 1.5rem;">
    <div class="ficha-info-row">
        <div><strong>Grado:</strong> <?= htmlspecialchars($claseData['nombre_grado'] ?? 'N/A') ?></div>
        <div><strong>Profesor:</strong> <?= htmlspecialchars($claseData['nombre_profesor'] ?? 'N/A') ?></div>
        <div><strong>Turno:</strong> <?= htmlspecialchars($claseData['descripcion_turno'] ?? 'N/A') ?></div>
        <div><strong>Total Alumnos:</strong> <?= count($students) ?></div>
    </div>
</div>

<?php if ($es_administrador): ?>
<div class="panel" style="margin-bottom: 1.5rem;">
    <h3>Registrar Alumno en la Clase</h3>
    <form method="POST" action="index.php?action=clases_manage&id_clase=<?= $claseData['id_clase'] ?>" class="inline-form">
        <input type="number" name="cedula" placeholder="Cédula del alumno" required min="1" max="999999999">
        <button type="submit" name="registrar_alumno" class="btn btn-primary"><?= Icons::plus() ?> Registrar</button>
    </form>
</div>
<?php endif; ?>

<div class="panel">
    <h3>Alumnos en la Clase</h3>
    <?php if (!empty($students)): ?>
    <div class="table-container">
        <table>
            <thead><tr><th>Nombre</th><th>Apellido</th><th>Cédula</th><th>Acciones</th></tr></thead>
            <tbody>
                <?php foreach ($students as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s['nombre']) ?></td>
                    <td><?= htmlspecialchars($s['apellido']) ?></td>
                    <td><?= htmlspecialchars($s['cedula']) ?></td>
                    <td class="actions-cell">
                        <a href="index.php?action=perfil&id_estudiante=<?= $s['id_estudiante'] ?>" class="btn btn-sm btn-outline"><?= Icons::eye() ?> Perfil</a>
                        <?php if ($es_administrador): ?>
                        <form method="POST" action="index.php?action=clases_manage&id_clase=<?= $claseData['id_clase'] ?>" style="display:inline" onsubmit="return confirm('¿Eliminar de la clase?')">
                            <input type="hidden" name="id_estudiante_clase" value="<?= $s['id_estudiante_clase'] ?>">
                            <button type="submit" name="borrar_de_clase" class="btn btn-sm btn-danger"><?= Icons::trash() ?></button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <p style="color: var(--text-muted); padding: 1rem; text-align: center;">No hay alumnos registrados en esta clase.</p>
    <?php endif; ?>
</div>
<?php $content = ob_get_clean(); require_once __DIR__ . '/../layouts/main.php'; ?>
