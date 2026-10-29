<?php
// views/layouts/main.php
?>
<!DOCTYPE html>
<html lang="es" data-theme="<?= htmlspecialchars($_SESSION['tema'] ?? 'dark') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAIPA - Sistema de Gestión</title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>

<!-- AMBIENT BACKGROUND LIGHTS -->
<div class="ambient-bg">
    <div class="ambient-blob blob-1"></div>
    <div class="ambient-blob blob-2"></div>
    <div class="ambient-blob blob-3"></div>
</div>

<?php 
$current_action = $_GET['action'] ?? 'dashboard';
if(isset($_SESSION['user_id'])): 
?>
<div class="app-container">
    <aside class="sidebar">
        <div class="logo">
            <?= Icons::dashboard() ?>
            <span class="text-gradient">CAIPA</span>
        </div>
        
        <ul class="nav-links">
            <li>
                <a href="index.php?action=dashboard" class="nav-link <?= $current_action == 'dashboard' ? 'active' : '' ?>">
                    <?= Icons::dashboard() ?> Inicio
                </a>
            </li>
            <li>
                <a href="index.php?action=estudiantes" class="nav-link <?= strpos($current_action, 'estudiante') !== false || strpos($current_action, 'perfil') !== false ? 'active' : '' ?>">
                    <?= Icons::students() ?> Estudiantes
                </a>
            </li>
            <li>
                <a href="index.php?action=clases" class="nav-link <?= strpos($current_action, 'clases') !== false ? 'active' : '' ?>">
                    <?= Icons::book() ?> Clases
                </a>
            </li>
            <li>
                <a href="index.php?action=profesores" class="nav-link <?= strpos($current_action, 'profesores') !== false ? 'active' : '' ?>">
                    <?= Icons::staff() ?> Profesores
                </a>
            </li>
            <li>
                <a href="index.php?action=grados" class="nav-link <?= strpos($current_action, 'grados') !== false ? 'active' : '' ?>">
                    <?= Icons::book() ?> Grados
                </a>
            </li>
            <li>
                <a href="index.php?action=personal" class="nav-link <?= strpos($current_action, 'personal') !== false ? 'active' : '' ?>">
                    <?= Icons::staff() ?> Personal
                </a>
            </li>
            <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
            <li style="margin-top: 1rem; border-top: 1px solid var(--border-color); padding-top: 1rem;">
                <a href="index.php?action=reportes" class="nav-link <?= strpos($current_action, 'reportes') !== false ? 'active' : '' ?>">
                    <?= Icons::file() ?> Reportes PDF
                </a>
            </li>
            <li>
                <a href="index.php?action=configuraciones" class="nav-link <?= strpos($current_action, 'config') !== false ? 'active' : '' ?>">
                    <?= Icons::settings() ?> Configuraciones
                </a>
            </li>
            <?php endif; ?>
        </ul>
        
        <div style="margin-top: auto;">
            <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] != 1): ?>
            <div style="margin-bottom: 1rem;">
                <form action="index.php?action=config_update_tema" method="POST" style="display: flex; flex-direction: column; gap: 0.25rem;">
                    <label style="color: var(--text-muted); font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Tema Visual</label>
                    <select name="tema" onchange="this.form.submit()" style="padding: 0.5rem; font-size: 0.85rem; background: var(--bg-input); color: var(--text-main); border: 1px solid var(--border-color); border-radius: var(--radius); width: 100%;">
                        <?php $current_theme = $_SESSION['tema'] ?? 'nord-light'; ?>
                        <option value="dark" <?= $current_theme == 'dark' ? 'selected' : '' ?>>Dark</option>
                        <option value="light" <?= $current_theme == 'light' ? 'selected' : '' ?>>Light</option>
                        <option value="tokyo-night" <?= $current_theme == 'tokyo-night' ? 'selected' : '' ?>>Tokyo Night</option>
                        <option value="dracula" <?= $current_theme == 'dracula' ? 'selected' : '' ?>>Dracula</option>
                        <option value="nord-light" <?= $current_theme == 'nord-light' ? 'selected' : '' ?>>Nord Light</option>
                        <option value="solarized-light" <?= $current_theme == 'solarized-light' ? 'selected' : '' ?>>Solarized Light</option>
                    </select>
                </form>
            </div>
            <?php endif; ?>

            <div class="user-info" style="margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <?= Icons::user() ?>
                <span style="color: var(--text-main); font-weight: 500;">
                    <?= htmlspecialchars($_SESSION['username'] ?? 'Usuario') ?>
                </span>
            </div>
            <a href="index.php?action=logout" class="btn btn-outline" style="width: 100%; justify-content: center;">
                <?= Icons::logOut() ?> Salir
            </a>
        </div>
    </aside>
    
    <main class="main-content">
        <?= $content ?>
    </main>
</div>
<?php else: ?>
    <?= $content ?>
<?php endif; ?>

<script src="assets/js/steppers.js"></script>
</body>
</html>
