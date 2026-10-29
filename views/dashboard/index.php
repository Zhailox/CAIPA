<?php ob_start(); ?>
<div class="page-header">
    <h1>Bienvenido, <span class="text-gradient"><?= htmlspecialchars($_SESSION['username']) ?></span></h1>
    <p style="color: var(--text-muted);">¿Qué deseas hacer hoy?</p>
</div>

<?php if (isset($error)): ?>
<div class="alert alert-danger"><?= Icons::warning() ?> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success"><?= Icons::check() ?> <?= htmlspecialchars($_SESSION['success']) ?></div>
<?php unset($_SESSION['success']); endif; ?>

<div class="cards-grid animate-cascade">
    <a href="index.php?action=estudiantes" class="dash-card">
        <div class="dash-card-icon"><?= Icons::students() ?></div>
        <h3>Buscar Estudiantes</h3>
        <p>Accede al listado completo con filtros de búsqueda por nombre, apellido o cédula.</p>
    </a>

    <div class="dash-card" id="card-perfil">
        <div class="dash-card-icon"><?= Icons::user() ?></div>
        <h3>Ir al Perfil</h3>
        <p>Ingresa la cédula del estudiante para ir directamente a su perfil.</p>
        <form method="POST" action="index.php?action=dashboard" class="dash-card-form">
            <input type="number" name="cedula" placeholder="Cédula" required min="1" max="999999999">
            <button type="submit" name="buscar_cedula" class="btn btn-primary btn-sm">Buscar</button>
        </form>
    </div>

    <div class="dash-card" id="card-clase">
        <div class="dash-card-icon"><?= Icons::book() ?></div>
        <h3>Gestionar Clase</h3>
        <p>Selecciona una clase para ver y administrar los estudiantes.</p>
        <form method="POST" action="index.php?action=dashboard" class="dash-card-form">
            <select name="id_clase" required>
                <option value="">Seleccione una clase</option>
                <?php foreach ($clases as $c): ?>
                    <option value="<?= $c['id_clase'] ?>"><?= htmlspecialchars($c['clase_nombre']) ?> (<?= htmlspecialchars($c['nombre_grado'] ?? 'Sin grado') ?>)</option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="gestionar_clase" class="btn btn-primary btn-sm">Ir</button>
        </form>
    </div>

    <?php if ($es_administrador): ?>
    <a href="index.php?action=clases_create" class="dash-card">
        <div class="dash-card-icon"><?= Icons::plus() ?></div>
        <h3>Crear Clase</h3>
        <p>Crea una nueva clase asignando un profesor y un grado.</p>
    </a>

    <a href="index.php?action=register" class="dash-card">
        <div class="dash-card-icon"><?= Icons::shield() ?></div>
        <h3>Crear Usuario</h3>
        <p>Crea cuentas de usuario para administradores o invitados.</p>
    </a>

    <a href="index.php?action=configuraciones" class="dash-card">
        <div class="dash-card-icon"><?= Icons::settings() ?></div>
        <h3>Configuraciones</h3>
        <p>Gestiona municipios, instituciones y usuarios del sistema.</p>
    </a>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
