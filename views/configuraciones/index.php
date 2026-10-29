<?php ob_start(); ?>
<style>
.tabs-nav { display: flex; gap: 1rem; margin-bottom: 2rem; border-bottom: 1px solid var(--border-color); }
.tab-btn { background: transparent; border: none; color: var(--text-muted); padding: 0.75rem 1.5rem; cursor: pointer; font-weight: 600; font-size: 1rem; border-bottom: 2px solid transparent; transition: var(--transition); }
.tab-btn:hover { color: var(--primary); }
.tab-btn.active { color: var(--primary); border-bottom-color: var(--primary); }
.tab-content { display: none; animation: fadeIn 0.3s ease; }
.tab-content.active { display: block; }

.theme-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
.theme-card {
    border: 2px solid var(--border-color); border-radius: var(--radius); padding: 1rem;
    cursor: pointer; transition: var(--transition); text-align: center; font-weight: 600;
}
.theme-card:hover { border-color: var(--primary); box-shadow: 0 0 10px var(--primary-glow); }
.theme-card.active { border-color: var(--primary); background: rgba(14, 165, 233, 0.1); }
.theme-card input[type="radio"] { display: none; }
</style>

<div class="page-header">
    <h1><?= Icons::settings() ?> <span class="text-gradient">Configuraciones del Sistema</span></h1>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success"><?= Icons::check() ?> <?= htmlspecialchars($_SESSION['success']) ?></div>
<?php unset($_SESSION['success']); endif; ?>
<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger"><?= Icons::warning() ?> <?= htmlspecialchars($_SESSION['error']) ?></div>
<?php unset($_SESSION['error']); endif; ?>

<div class="tabs-nav">
    <button class="tab-btn active" onclick="openTab('apariencia')">Apariencia y Temas</button>
    <button class="tab-btn" onclick="openTab('catalogos')">Gestión de Catálogos</button>
    <button class="tab-btn" onclick="openTab('usuarios')">Gestión de Usuarios</button>
</div>

<!-- ==================== APARIENCIA ==================== -->
<div id="apariencia" class="tab-content active">
    <div class="panel">
        <div class="panel-header"><h3>Tema Visual</h3></div>
        <p class="text-muted" style="margin-bottom: 1.5rem;">Selecciona el tema visual para tu cuenta. Este cambio se aplicará a todas las pantallas.</p>
        
        <form method="POST" action="index.php?action=config_update_tema">
            <?php $current_theme = $_SESSION['tema'] ?? 'dark'; ?>
<div class="theme-grid animate-cascade">
                <label class="theme-card <?= $current_theme == 'dark' ? 'active' : '' ?>">
                    <input type="radio" name="tema" value="dark" <?= $current_theme == 'dark' ? 'checked' : '' ?>>
                    Modo Oscuro (Defecto)
                </label>
                <label class="theme-card <?= $current_theme == 'light' ? 'active' : '' ?>">
                    <input type="radio" name="tema" value="light" <?= $current_theme == 'light' ? 'checked' : '' ?>>
                    Modo Claro
                </label>
                <label class="theme-card <?= $current_theme == 'tokyo-night' ? 'active' : '' ?>">
                    <input type="radio" name="tema" value="tokyo-night" <?= $current_theme == 'tokyo-night' ? 'checked' : '' ?>>
                    Tokyo Night
                </label>
                <label class="theme-card <?= $current_theme == 'dracula' ? 'active' : '' ?>">
                    <input type="radio" name="tema" value="dracula" <?= $current_theme == 'dracula' ? 'checked' : '' ?>>
                    Dracula
                </label>
                <label class="theme-card <?= $current_theme == 'nord-light' ? 'active' : '' ?>">
                    <input type="radio" name="tema" value="nord-light" <?= $current_theme == 'nord-light' ? 'checked' : '' ?>>
                    Nord Light
                </label>
                <label class="theme-card <?= $current_theme == 'solarized-light' ? 'active' : '' ?>">
                    <input type="radio" name="tema" value="solarized-light" <?= $current_theme == 'solarized-light' ? 'checked' : '' ?>>
                    Solarized Light
                </label>
            </div>
            <div style="margin-top: 2rem; text-align: right;">
                <button type="submit" class="btn btn-primary"><?= Icons::check() ?> Aplicar Tema a mi Cuenta</button>
            </div>
        </form>

        <?php if ($_SESSION['role_id'] == 1): // Only admin can set global default ?>
        <hr style="margin: 2rem 0; border: none; border-top: 1px solid var(--border-color);">
        <div class="panel-header"><h3>Tema Global por Defecto</h3></div>
        <p class="text-muted" style="margin-bottom: 1.5rem;">Establece el tema predeterminado para la pantalla de inicio de sesión y usuarios sin preferencia.</p>
        
        <form method="POST" action="index.php?action=config_update_tema_default" class="inline-form">
            <?php 
                require_once __DIR__ . '/../../models/Configuracion.php';
                $default_theme_global = (new Configuracion())->getSistemaConfig('tema_default') ?? 'nord-light';
            ?>
            <select name="tema_default" required style="max-width: 300px;">
                <option value="dark" <?= $default_theme_global == 'dark' ? 'selected' : '' ?>>Modo Oscuro</option>
                <option value="light" <?= $default_theme_global == 'light' ? 'selected' : '' ?>>Modo Claro</option>
                <option value="tokyo-night" <?= $default_theme_global == 'tokyo-night' ? 'selected' : '' ?>>Tokyo Night</option>
                <option value="dracula" <?= $default_theme_global == 'dracula' ? 'selected' : '' ?>>Dracula</option>
                <option value="nord-light" <?= $default_theme_global == 'nord-light' ? 'selected' : '' ?>>Nord Light</option>
                <option value="solarized-light" <?= $default_theme_global == 'solarized-light' ? 'selected' : '' ?>>Solarized Light</option>
            </select>
            <button type="submit" class="btn btn-outline"><?= Icons::check() ?> Establecer por Defecto</button>
        </form>
        <?php endif; ?>
    </div>
</div>

<!-- ==================== CATALOGOS ==================== -->
<div id="catalogos" class="tab-content">
    <div class="config-grid animate-cascade">
        <!-- MUNICIPIOS -->
        <div class="panel">
            <div class="panel-header"><h3>Municipios</h3>
                <a href="index.php?action=configuraciones&registrar=municipio" class="btn btn-sm btn-primary"><?= Icons::plus() ?></a>
            </div>
            <div class="table-container"><table>
                <thead><tr><th>Nombre</th><th>Acciones</th></tr></thead>
                <tbody>
                    <?php foreach ($municipios as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['nombre_municipio']) ?></td>
                        <td class="actions-cell">
                            <a href="index.php?action=configuraciones&editar=municipio&id_municipio=<?= $m['id_municipio'] ?>" class="btn btn-sm btn-outline"><?= Icons::edit() ?></a>
                            <a href="index.php?action=configuraciones&eliminar=municipio&id_municipio=<?= $m['id_municipio'] ?>" class="btn btn-sm btn-danger"><?= Icons::trash() ?></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table></div>
        </div>

        <!-- INSTITUCIONES -->
        <div class="panel">
            <div class="panel-header"><h3>Instituciones</h3>
                <a href="index.php?action=configuraciones&registrar=institucion" class="btn btn-sm btn-primary"><?= Icons::plus() ?></a>
            </div>
            <div class="table-container"><table>
                <thead><tr><th>Nombre</th><th>Acciones</th></tr></thead>
                <tbody>
                    <?php foreach ($instituciones as $i): ?>
                    <tr>
                        <td><?= htmlspecialchars($i['nombre_institucion']) ?></td>
                        <td class="actions-cell">
                            <a href="index.php?action=configuraciones&editar=institucion&id_institucion=<?= $i['id_institucion'] ?>" class="btn btn-sm btn-outline"><?= Icons::edit() ?></a>
                            <a href="index.php?action=configuraciones&eliminar=institucion&id_institucion=<?= $i['id_institucion'] ?>" class="btn btn-sm btn-danger"><?= Icons::trash() ?></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table></div>
        </div>

        <!-- TURNOS -->
        <div class="panel">
            <div class="panel-header"><h3>Turnos</h3>
                <a href="index.php?action=configuraciones&registrar=turno" class="btn btn-sm btn-primary"><?= Icons::plus() ?></a>
            </div>
            <div class="table-container"><table>
                <thead><tr><th>Nombre</th><th>Acciones</th></tr></thead>
                <tbody>
                    <?php foreach ($turnos as $t): ?>
                    <tr>
                        <td><?= htmlspecialchars($t['nombre']) ?></td>
                        <td class="actions-cell">
                            <a href="index.php?action=configuraciones&editar=turno&id_catalog=<?= $t['id'] ?>" class="btn btn-sm btn-outline"><?= Icons::edit() ?></a>
                            <a href="index.php?action=configuraciones&eliminar=turno&id_catalog=<?= $t['id'] ?>" class="btn btn-sm btn-danger"><?= Icons::trash() ?></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table></div>
        </div>

        <!-- GRADOS -->
        <div class="panel">
            <div class="panel-header"><h3>Grados</h3>
                <a href="index.php?action=configuraciones&registrar=grados" class="btn btn-sm btn-primary"><?= Icons::plus() ?></a>
            </div>
            <div class="table-container"><table>
                <thead><tr><th>Nombre</th><th>Acciones</th></tr></thead>
                <tbody>
                    <?php foreach ($grados as $g): ?>
                    <tr>
                        <td><?= htmlspecialchars($g['nombre']) ?></td>
                        <td class="actions-cell">
                            <a href="index.php?action=configuraciones&editar=grados&id_catalog=<?= $g['id'] ?>" class="btn btn-sm btn-outline"><?= Icons::edit() ?></a>
                            <a href="index.php?action=configuraciones&eliminar=grados&id_catalog=<?= $g['id'] ?>" class="btn btn-sm btn-danger"><?= Icons::trash() ?></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table></div>
        </div>

        <!-- DEPENDENCIAS -->
        <div class="panel">
            <div class="panel-header"><h3>Dependencias</h3>
                <a href="index.php?action=configuraciones&registrar=dependencias" class="btn btn-sm btn-primary"><?= Icons::plus() ?></a>
            </div>
            <div class="table-container"><table>
                <thead><tr><th>Nombre</th><th>Acciones</th></tr></thead>
                <tbody>
                    <?php foreach ($dependencias as $d): ?>
                    <tr>
                        <td><?= htmlspecialchars($d['nombre']) ?></td>
                        <td class="actions-cell">
                            <a href="index.php?action=configuraciones&editar=dependencias&id_catalog=<?= $d['id'] ?>" class="btn btn-sm btn-outline"><?= Icons::edit() ?></a>
                            <a href="index.php?action=configuraciones&eliminar=dependencias&id_catalog=<?= $d['id'] ?>" class="btn btn-sm btn-danger"><?= Icons::trash() ?></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table></div>
        </div>

        <!-- SEXO -->
        <div class="panel">
            <div class="panel-header"><h3>Sexo / Género</h3>
                <a href="index.php?action=configuraciones&registrar=sexo" class="btn btn-sm btn-primary"><?= Icons::plus() ?></a>
            </div>
            <div class="table-container"><table>
                <thead><tr><th>Nombre</th><th>Acciones</th></tr></thead>
                <tbody>
                    <?php foreach ($sexos as $s): ?>
                    <tr>
                        <td><?= htmlspecialchars($s['nombre']) ?></td>
                        <td class="actions-cell">
                            <a href="index.php?action=configuraciones&editar=sexo&id_catalog=<?= $s['id'] ?>" class="btn btn-sm btn-outline"><?= Icons::edit() ?></a>
                            <a href="index.php?action=configuraciones&eliminar=sexo&id_catalog=<?= $s['id'] ?>" class="btn btn-sm btn-danger"><?= Icons::trash() ?></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table></div>
        </div>
    </div>
</div>

<!-- ==================== USUARIOS ==================== -->
<div id="usuarios" class="tab-content">
    <div class="panel">
        <div class="panel-header"><h3>Usuarios del Sistema</h3></div>
        <div class="table-container"><table>
            <thead><tr><th>Usuario</th><th>Rol</th><th>Acciones</th></tr></thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['nombre_usuario']) ?></td>
                    <td><span class="badge badge-<?= $u['id_rol'] == 1 ? 'primary' : 'muted' ?>"><?= htmlspecialchars($u['descripcion_rol']) ?></span></td>
                    <td class="actions-cell">
                        <a href="index.php?action=configuraciones&editar=usuario&id_usuario=<?= $u['id_usuario'] ?>" class="btn btn-sm btn-outline"><?= Icons::edit() ?></a>
                        <?php if ($u['id_usuario'] != $_SESSION['user_id']): ?>
                        <a href="index.php?action=configuraciones&eliminar=usuario&id_usuario=<?= $u['id_usuario'] ?>" class="btn btn-sm btn-danger"><?= Icons::trash() ?></a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table></div>
    </div>
</div>

<script>
function openTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    event.currentTarget.classList.add('active');
}

// Select cards behavior
document.querySelectorAll('.theme-card').forEach(card => {
    card.addEventListener('click', function() {
        document.querySelectorAll('.theme-card').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
    });
});
</script>

<!-- Modals for Legacy Catalogs -->
<?php if ($registrarType == 'municipio'): ?>
<div class="modal-overlay"><div class="modal-box"><h3>Nuevo Municipio</h3><a href="index.php?action=configuraciones" class="modal-close">&times;</a>
    <form method="POST" action="index.php?action=config_store_municipio">
        <div class="form-group"><label>Nombre</label><input type="text" name="nombre" required></div>
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div></div>
<?php endif; ?>
<?php if ($registrarType == 'institucion'): ?>
<div class="modal-overlay"><div class="modal-box"><h3>Nueva Institución</h3><a href="index.php?action=configuraciones" class="modal-close">&times;</a>
    <form method="POST" action="index.php?action=config_store_institucion">
        <div class="form-group"><label>Nombre</label><input type="text" name="nombre" required></div>
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div></div>
<?php endif; ?>

<!-- Modals for Generic Catalogs -->
<?php if (in_array($registrarType, ['turno', 'grados', 'sexo', 'dependencias'])): ?>
<div class="modal-overlay"><div class="modal-box">
    <h3>Registrar en <?= ucfirst($registrarType) ?></h3><a href="index.php?action=configuraciones" class="modal-close">&times;</a>
    <form method="POST" action="index.php?action=config_catalog_store">
        <input type="hidden" name="type" value="<?= $registrarType ?>">
        <div class="form-group"><label>Valor</label><input type="text" name="nombre" required></div>
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div></div>
<?php endif; ?>

<!-- Modals for Editing -->
<?php if ($editType == 'municipio' && $editData): ?>
<div class="modal-overlay"><div class="modal-box"><h3>Editar Municipio</h3><a href="index.php?action=configuraciones" class="modal-close">&times;</a>
    <form method="POST" action="index.php?action=config_update_municipio">
        <input type="hidden" name="id" value="<?= $editData['id_municipio'] ?>">
        <div class="form-group"><label>Nombre</label><input type="text" name="nombre" value="<?= htmlspecialchars($editData['nombre_municipio']) ?>" required></div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div></div>
<?php endif; ?>
<?php if ($editType == 'institucion' && $editData): ?>
<div class="modal-overlay"><div class="modal-box"><h3>Editar Institución</h3><a href="index.php?action=configuraciones" class="modal-close">&times;</a>
    <form method="POST" action="index.php?action=config_update_institucion">
        <input type="hidden" name="id" value="<?= $editData['id_institucion'] ?>">
        <div class="form-group"><label>Nombre</label><input type="text" name="nombre" value="<?= htmlspecialchars($editData['nombre_institucion']) ?>" required></div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div></div>
<?php endif; ?>
<?php if (in_array($editType, ['turno', 'grados', 'sexo', 'dependencias']) && $editData): ?>
<div class="modal-overlay"><div class="modal-box">
    <h3>Editar <?= ucfirst($editType) ?></h3><a href="index.php?action=configuraciones" class="modal-close">&times;</a>
    <form method="POST" action="index.php?action=config_catalog_update">
        <input type="hidden" name="type" value="<?= $editType ?>">
        <input type="hidden" name="id" value="<?= $editData['id'] ?? $editData['id_turno'] ?? $editData['id_grado'] ?? $editData['id_sexo'] ?? $editData['id_dependencia'] ?>">
        <div class="form-group"><label>Valor</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($editData['nombre'] ?? $editData['descripcion_turno'] ?? $editData['nombre_grado'] ?? $editData['descripcion_sexo'] ?? $editData['nombre_dependencia']) ?>" required></div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div></div>
<?php endif; ?>

<!-- Modals for Users -->
<?php if ($editType == 'usuario' && $editData): ?>
<div class="modal-overlay"><div class="modal-box"><h3>Editar Usuario</h3><a href="index.php?action=configuraciones" class="modal-close">&times;</a>
    <form method="POST" action="index.php?action=config_update_usuario">
        <input type="hidden" name="id_usuario_edit" value="<?= $editData['id_usuario'] ?>">
        <div class="form-group"><label>Usuario</label><input type="text" name="nombre_usuario" value="<?= htmlspecialchars($editData['nombre_usuario']) ?>" required></div>
        <div class="form-group"><label>Clave Nueva (Opcional)</label><input type="password" name="clave_nueva"></div>
        <div class="form-group"><label>Rol</label>
            <select name="id_rol">
                <?php foreach ($roles as $r): ?>
                <option value="<?= $r['id_rol'] ?>" <?= $editData['id_rol'] == $r['id_rol'] ? 'selected' : '' ?>><?= $r['descripcion_rol'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div></div>
<?php endif; ?>

<!-- Modals for Deleting -->
<?php if (in_array($deleteType, ['municipio', 'institucion'])): ?>
<div class="modal-overlay"><div class="modal-box">
    <h3>Confirmar Eliminación</h3><a href="index.php?action=configuraciones" class="modal-close">&times;</a>
    <form method="POST" action="index.php?action=config_delete_<?= $deleteType ?>">
        <input type="hidden" name="id" value="<?= $deleteType == 'municipio' ? $deleteData['id_municipio'] : $deleteData['id_institucion'] ?>">
        <p>¿Estás seguro de que quieres eliminar este elemento?</p>
        <button type="submit" class="btn btn-danger">Eliminar</button>
    </form>
</div></div>
<?php endif; ?>
<?php if (in_array($deleteType, ['turno', 'grados', 'sexo', 'dependencias']) && $deleteData): ?>
<div class="modal-overlay"><div class="modal-box">
    <h3>Confirmar Eliminación</h3><a href="index.php?action=configuraciones" class="modal-close">&times;</a>
    <form method="POST" action="index.php?action=config_catalog_delete">
        <input type="hidden" name="type" value="<?= $deleteType ?>">
        <input type="hidden" name="id" value="<?= $deleteData['id'] ?? $deleteData['id_turno'] ?? $deleteData['id_grado'] ?? $deleteData['id_sexo'] ?? $deleteData['id_dependencia'] ?>">
        <p>¿Estás seguro de que quieres eliminar <strong><?= htmlspecialchars($deleteData['nombre'] ?? $deleteData['descripcion_turno'] ?? $deleteData['nombre_grado'] ?? $deleteData['descripcion_sexo'] ?? $deleteData['nombre_dependencia']) ?></strong>?</p>
        <button type="submit" class="btn btn-danger">Sí, Eliminar</button>
    </form>
</div></div>
<?php endif; ?>
<?php if ($deleteType == 'usuario' && $deleteData): ?>
<div class="modal-overlay"><div class="modal-box">
    <h3>Confirmar Eliminación</h3><a href="index.php?action=configuraciones" class="modal-close">&times;</a>
    <form method="POST" action="index.php?action=config_delete_usuario">
        <input type="hidden" name="id" value="<?= $deleteData['id_usuario'] ?>">
        <p>¿Estás seguro de que quieres eliminar al usuario <strong><?= htmlspecialchars($deleteData['nombre_usuario']) ?></strong>?</p>
        <button type="submit" class="btn btn-danger">Sí, Eliminar</button>
    </form>
</div></div>
<?php endif; ?>

<?php $content = ob_get_clean(); require_once __DIR__ . '/../layouts/main.php'; ?>
