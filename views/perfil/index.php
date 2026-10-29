<?php ob_start(); ?>
<div class="page-header">
    <h1><?= Icons::user() ?> Perfil de <span class="text-gradient"><?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?></span></h1>
    <a href="index.php?action=estudiantes" class="btn btn-outline"><?= Icons::back() ?> Volver</a>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success"><?= Icons::check() ?> <?= htmlspecialchars($_SESSION['success']) ?></div>
<?php unset($_SESSION['success']); endif; ?>
<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger"><?= Icons::warning() ?> <?= htmlspecialchars($_SESSION['error']) ?></div>
<?php unset($_SESSION['error']); endif; ?>

<!-- FICHA HEADER -->
<div class="panel ficha-panel">
    <div class="ficha-header-row">
        <div class="ficha-avatar"><?= strtoupper(mb_substr($estudiante['nombre'], 0, 1)) ?><?= strtoupper(mb_substr($estudiante['apellido'], 0, 1)) ?></div>
        <div class="ficha-title">
            <h2><?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?></h2>
            <span class="text-muted">C.I: <?= htmlspecialchars($estudiante['cedula'] ?: 'N/A') ?> · Nacimiento: <?= htmlspecialchars($estudiante['fecha_nacimiento']) ?> (<?= $estudiante['edad'] ?> años)</span>
        </div>
        <div class="ficha-actions">
            <?php if ($es_admin): ?>
            <a href="index.php?action=perfil&id_estudiante=<?= $estudiante['id_estudiante'] ?>&editar=1" class="btn btn-sm btn-outline"><?= Icons::edit() ?> Editar</a>
            <a href="index.php?action=perfil&id_estudiante=<?= $estudiante['id_estudiante'] ?>&eliminar=1" class="btn btn-sm btn-danger"><?= Icons::trash() ?> Eliminar</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- DATOS PERSONALES -->
<div class="panel" style="margin-top:1rem">
    <h3><?= Icons::user() ?> Datos Personales</h3>
    <div class="datos-grid">
        <div class="dato"><strong>Cédula</strong><span><?= htmlspecialchars($estudiante['cedula'] ?: 'N/A') ?></span></div>
        <div class="dato"><strong>Sexo</strong><span><?= htmlspecialchars($estudiante['descripcion_sexo'] ?? 'N/A') ?></span></div>
        <div class="dato"><strong>Talla Zapatos</strong><span><?= htmlspecialchars($estudiante['tz']) ?></span></div>
        <div class="dato"><strong>Talla Camisas</strong><span><?= htmlspecialchars($estudiante['tc']) ?></span></div>
        <div class="dato"><strong>Talla Pantalón</strong><span><?= htmlspecialchars($estudiante['tp']) ?></span></div>
        <div class="dato"><strong>Municipio</strong><span><?= htmlspecialchars($estudiante['nombre_municipio'] ?? 'N/A') ?></span></div>
        <div class="dato"><strong>Institución anterior</strong><span><?= htmlspecialchars($estudiante['nombre_institucion'] ?? 'N/A') ?></span></div>
        <div class="dato"><strong>Tipo Estudiante</strong><span><?= htmlspecialchars(ucfirst($estudiante['tipo_estudiante'] ?? 'N/A')) ?></span></div>
    </div>
</div>

<!-- REPRESENTANTES -->
<div class="panel" style="margin-top:1rem">
    <div class="panel-header"><h3><?= Icons::staff() ?> Representantes</h3>
        <?php if ($es_admin): ?><a href="index.php?action=perfil&id_estudiante=<?= $estudiante['id_estudiante'] ?>&registro=representante" class="btn btn-sm btn-primary"><?= Icons::plus() ?></a><?php endif; ?>
    </div>
    <?php if (!empty($representantes)): foreach ($representantes as $r): ?>
    <div class="mini-ficha">
        <h4><?= htmlspecialchars($r['apellidos_nombres']) ?></h4>
        <div class="mini-ficha-datos">
            <span><strong>Cédula:</strong> <?= htmlspecialchars($r['cedula']) ?></span>
            <span><strong>Teléfono:</strong> <?= htmlspecialchars($r['telefono']) ?></span>
            <span><strong>Nacionalidad:</strong> <?= htmlspecialchars($r['nacionalidad'] ?? 'N/A') ?></span>
            <span><strong>F. Nacimiento:</strong> <?= htmlspecialchars($r['fecha_nacimiento'] ?? 'N/A') ?></span>
            <span><strong>Lugar:</strong> <?= htmlspecialchars($r['lugar_nacimiento'] ?? 'N/A') ?></span>
            <span><strong>Edo. Civil:</strong> <?= htmlspecialchars($r['estado_civil'] ?? 'N/A') ?></span>
            <?php if ($es_admin): ?>
            <a href="index.php?action=perfil&id_estudiante=<?= $estudiante['id_estudiante'] ?>&editar_representante=1&id_representante=<?= $r['id_representante'] ?>" class="btn btn-sm btn-outline"><?= Icons::edit() ?></a>
            <a href="index.php?action=perfil_del_rep&id_representante=<?= $r['id_representante'] ?>&id_estudiante=<?= $estudiante['id_estudiante'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')"><?= Icons::trash() ?></a>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; else: ?>
    <div class="mini-ficha empty">No tiene representantes registrados</div>
    <?php endif; ?>
</div>

<!-- MADRE -->
<div class="panel" style="margin-top:1rem">
    <div class="panel-header"><h3><?= Icons::user() ?> Madre</h3></div>
    <?php if ($madre): ?>
    <div class="mini-ficha">
        <h4><?= htmlspecialchars($madre['apellidos_nombres']) ?></h4>
        <div class="mini-ficha-datos">
            <span><strong>Cédula:</strong> <?= htmlspecialchars($madre['cedula'] ?: 'N/A') ?></span>
            <span><strong>Edad:</strong> <?= htmlspecialchars($madre['edad'] ?: 'N/A') ?></span>
            <span><strong>Teléfono:</strong> <?= htmlspecialchars($madre['telefono'] ?: 'N/A') ?></span>
            <span><strong>Ocupación:</strong> <?= htmlspecialchars($madre['ocupacion'] ?: 'N/A') ?></span>
            <span style="flex-basis: 100%;"><strong>Dirección:</strong> <?= htmlspecialchars($madre['direccion'] ?: 'N/A') ?></span>
        </div>
    </div>
    <?php else: ?>
    <div class="mini-ficha empty">No hay información de la madre registrada</div>
    <?php endif; ?>
</div>

<!-- PADRE -->
<div class="panel" style="margin-top:1rem">
    <div class="panel-header"><h3><?= Icons::user() ?> Padre</h3></div>
    <?php if ($padre): ?>
    <div class="mini-ficha">
        <h4><?= htmlspecialchars($padre['apellidos_nombres']) ?></h4>
        <div class="mini-ficha-datos">
            <span><strong>Cédula:</strong> <?= htmlspecialchars($padre['cedula'] ?: 'N/A') ?></span>
            <span><strong>Edad:</strong> <?= htmlspecialchars($padre['edad'] ?: 'N/A') ?></span>
            <span><strong>Teléfono:</strong> <?= htmlspecialchars($padre['telefono'] ?: 'N/A') ?></span>
            <span><strong>Ocupación:</strong> <?= htmlspecialchars($padre['ocupacion'] ?: 'N/A') ?></span>
            <span style="flex-basis: 100%;"><strong>Dirección:</strong> <?= htmlspecialchars($padre['direccion'] ?: 'N/A') ?></span>
        </div>
    </div>
    <?php else: ?>
    <div class="mini-ficha empty">No hay información del padre registrada</div>
    <?php endif; ?>
</div>

<!-- MEDICAMENTOS -->
<div class="panel" style="margin-top:1rem">
    <div class="panel-header"><h3><?= Icons::pill() ?> Medicamentos</h3>
        <?php if ($es_admin): ?><a href="index.php?action=perfil&id_estudiante=<?= $estudiante['id_estudiante'] ?>&registro=medicamentos" class="btn btn-sm btn-primary"><?= Icons::plus() ?></a><?php endif; ?>
    </div>
    <?php if (!empty($medicamentos)): foreach ($medicamentos as $m): ?>
    <div class="mini-ficha inline">
        <h4><?= htmlspecialchars($m['nombre_medicamento']) ?></h4>
        <?php if ($es_admin): ?>
        <div>
            <a href="index.php?action=perfil&id_estudiante=<?= $estudiante['id_estudiante'] ?>&editar_medicamento=1&id_medicamento=<?= $m['id_medicamento'] ?>" class="btn btn-sm btn-outline"><?= Icons::edit() ?></a>
            <a href="index.php?action=perfil_del_med&id_medicamento=<?= $m['id_medicamento'] ?>&id_estudiante=<?= $estudiante['id_estudiante'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')"><?= Icons::trash() ?></a>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; else: ?>
    <div class="mini-ficha empty">No tiene medicamentos registrados</div>
    <?php endif; ?>
</div>

<!-- PMA -->
<div class="panel" style="margin-top:1rem">
    <div class="panel-header"><h3><?= Icons::food() ?> Plan de Alimentos</h3>
        <?php if ($es_admin): ?><a href="index.php?action=perfil&id_estudiante=<?= $estudiante['id_estudiante'] ?>&registro=pma" class="btn btn-sm btn-primary"><?= Icons::plus() ?></a><?php endif; ?>
    </div>
    <?php if (!empty($pmas)): foreach ($pmas as $p): ?>
    <div class="mini-ficha">
        <h4>Encargados</h4>
        <div class="mini-ficha-datos">
            <span><strong>Primario:</strong> <?= htmlspecialchars($p['pma_primaria']) ?></span>
            <span><strong>Principal:</strong> <?= htmlspecialchars($p['pma_principal']) ?></span>
            <?php if ($es_admin): ?>
            <a href="index.php?action=perfil&id_estudiante=<?= $estudiante['id_estudiante'] ?>&editar_pma=1&id_pma_tp=<?= $p['id_pma_tp'] ?>" class="btn btn-sm btn-outline"><?= Icons::edit() ?></a>
            <a href="index.php?action=perfil_del_pma&id_pma_tp=<?= $p['id_pma_tp'] ?>&id_estudiante=<?= $estudiante['id_estudiante'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')"><?= Icons::trash() ?></a>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; else: ?>
    <div class="mini-ficha empty">No tiene PMA registrado</div>
    <?php endif; ?>
</div>

<!-- CLASES -->
<div class="panel" style="margin-top:1rem">
    <div class="panel-header"><h3><?= Icons::book() ?> Clases</h3>
        <?php if ($es_admin): ?><a href="index.php?action=perfil&id_estudiante=<?= $estudiante['id_estudiante'] ?>&registro=clase" class="btn btn-sm btn-primary"><?= Icons::plus() ?></a><?php endif; ?>
    </div>
    <?php if (!empty($clases)): foreach ($clases as $c): ?>
    <div class="mini-ficha">
        <h4><?= htmlspecialchars($c['clase_nombre']) ?></h4>
        <div class="mini-ficha-datos">
            <span><strong>Grado:</strong> <?= htmlspecialchars($c['nombre_grado'] ?? 'N/A') ?></span>
            <span><strong>Profesor:</strong> <?= htmlspecialchars($c['nombre_profesor'] ?? 'N/A') ?></span>
            <?php if ($es_admin): ?>
            <a href="index.php?action=perfil&id_estudiante=<?= $estudiante['id_estudiante'] ?>&editar_clase=1&id_estudiante_clase=<?= $c['id_estudiante_clase'] ?>" class="btn btn-sm btn-outline"><?= Icons::edit() ?></a>
            <a href="index.php?action=perfil_del_cls&id_estudiante_clase=<?= $c['id_estudiante_clase'] ?>&id_estudiante=<?= $estudiante['id_estudiante'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar de esta clase?')"><?= Icons::trash() ?></a>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; else: ?>
    <div class="mini-ficha empty">No tiene clases asignadas</div>
    <?php endif; ?>
</div>

<!-- ========== MODALS ========== -->

<!-- Editar Estudiante -->
<?php if (isset($editEstudiante) && $editEstudiante): $raw = $rawEstudiante ?? $estudiante; ?>
<div class="modal-overlay"><div class="modal-box modal-lg">
    <h3>Editar Estudiante</h3><a href="index.php?action=perfil&id_estudiante=<?= $estudiante['id_estudiante'] ?>" class="modal-close">&times;</a>
    <form method="POST" action="index.php?action=perfil_process">
        <input type="hidden" name="id_estudiante" value="<?= $estudiante['id_estudiante'] ?>">
        <div class="form-row">
            <div class="form-group"><label>Nombre</label><input type="text" name="Nombre" value="<?= htmlspecialchars($raw['nombre'] ?? '') ?>" required></div>
            <div class="form-group"><label>Apellido</label><input type="text" name="Apellido" value="<?= htmlspecialchars($raw['apellido'] ?? '') ?>" required></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Cédula (Opcional)</label><input type="text" name="Cedula" value="<?= htmlspecialchars($raw['cedula'] ?? '') ?>" pattern="[0-9]*" inputmode="numeric" placeholder="Ej: 12345678"></div>
            <div class="form-group"><label>F. Nacimiento</label><input type="date" name="FechaNac" value="<?= htmlspecialchars($raw['fecha_nacimiento'] ?? '') ?>" required></div>
        </div>
        <div class="form-group"><label>Sexo</label>
            <select name="Sexo" required><option value="">Seleccione</option>
                <?php foreach ($sexos as $s): ?><option value="<?= $s['id_sexo'] ?>" <?= ($raw['id_sexo'] ?? '') == $s['id_sexo'] ? 'selected' : '' ?>><?= htmlspecialchars($s['descripcion_sexo']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="form-row">
            <div class="form-group"><label>T. Zapatos</label><input type="text" name="TallaZap" value="<?= htmlspecialchars($raw['tz'] ?? '') ?>"></div>
            <div class="form-group"><label>T. Camisas</label><input type="text" name="TallaCam" value="<?= htmlspecialchars($raw['tc'] ?? '') ?>"></div>
            <div class="form-group"><label>T. Pantalón</label><input type="text" name="TallaPan" value="<?= htmlspecialchars($raw['tp'] ?? '') ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Municipio</label>
                <select name="Municipio" required><option value="">Seleccione</option>
                    <?php foreach ($municipios as $mu): ?><option value="<?= $mu['id_municipio'] ?>" <?= ($raw['id_municipio'] ?? '') == $mu['id_municipio'] ? 'selected' : '' ?>><?= htmlspecialchars($mu['nombre_municipio']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label>Institución</label>
                <select name="Institucion" required><option value="">Seleccione</option>
                    <?php foreach ($instituciones as $ins): ?><option value="<?= $ins['id_institucion'] ?>" <?= ($raw['id_institucion'] ?? '') == $ins['id_institucion'] ? 'selected' : '' ?>><?= htmlspecialchars($ins['nombre_institucion']) ?></option><?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group" style="margin-bottom: 1.5rem;"><label>Tipo de Estudiante</label>
            <select name="TipoEstudiante" required><option value="">Seleccione</option>
                <option value="universitario" <?= ($raw['tipo_estudiante'] ?? '') == 'universitario' ? 'selected' : '' ?>>Universitario</option>
                <option value="liceista" <?= ($raw['tipo_estudiante'] ?? '') == 'liceista' ? 'selected' : '' ?>>Liceista</option>
                <option value="foraneo" <?= ($raw['tipo_estudiante'] ?? '') == 'foraneo' ? 'selected' : '' ?>>Foráneo</option>
            </select>
        </div>
        <button type="submit" name="guardar_estudiante" class="btn btn-primary"><?= Icons::check() ?> Guardar</button>
    </form>
</div></div>
<?php endif; ?>

<!-- Eliminar Estudiante -->
<?php if (isset($deleteEstudiante) && $deleteEstudiante): ?>
<div class="modal-overlay"><div class="modal-box">
    <h3>¿Eliminar Estudiante?</h3><a href="index.php?action=perfil&id_estudiante=<?= $estudiante['id_estudiante'] ?>" class="modal-close">&times;</a>
    <p>¿Eliminar a <strong><?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?></strong> (C.I: <?= htmlspecialchars($estudiante['cedula']) ?>)?<br>Se borrarán todos sus datos relacionados.</p>
    <form method="POST" action="index.php?action=perfil_process">
        <input type="hidden" name="id_estudiante" value="<?= $estudiante['id_estudiante'] ?>">
        <button type="submit" name="borrar_estudiante" class="btn btn-danger">Sí, Eliminar</button>
        <a href="index.php?action=perfil&id_estudiante=<?= $estudiante['id_estudiante'] ?>" class="btn btn-outline">Cancelar</a>
    </form>
</div></div>
<?php endif; ?>

<!-- Editar Representante -->
<?php if (isset($editRep) && $editRep): ?>
<div class="modal-overlay"><div class="modal-box">
    <h3>Editar Representante</h3><a href="index.php?action=perfil&id_estudiante=<?= $estudiante['id_estudiante'] ?>" class="modal-close">&times;</a>
    <form method="POST" action="index.php?action=perfil_process">
        <input type="hidden" name="id_representante" value="<?= $editRep['id_representante'] ?>">
        <input type="hidden" name="id_estudiante_rep" value="<?= $estudiante['id_estudiante'] ?>">
        <div class="form-group"><label>Nombre</label><input type="text" name="apellidos_nombres" value="<?= htmlspecialchars($editRep['apellidos_nombres']) ?>" required></div>
        <div class="form-group"><label>Cédula</label><input type="text" name="cedula" value="<?= htmlspecialchars($editRep['cedula']) ?>" pattern="[0-9]+" inputmode="numeric" required></div>
        <div class="form-group"><label>Teléfono</label><input type="text" name="telefono" value="<?= htmlspecialchars($editRep['telefono']) ?>" pattern="[0-9\-\+\s]+" inputmode="tel" required></div>
        <button type="submit" name="guardar_representante" class="btn btn-primary"><?= Icons::check() ?> Guardar</button>
    </form>
</div></div>
<?php endif; ?>

<!-- Editar Medicamento -->
<?php if (isset($editMed) && $editMed): ?>
<div class="modal-overlay"><div class="modal-box">
    <h3>Editar Medicamento</h3><a href="index.php?action=perfil&id_estudiante=<?= $estudiante['id_estudiante'] ?>" class="modal-close">&times;</a>
    <form method="POST" action="index.php?action=perfil_process">
        <input type="hidden" name="id_medicamento" value="<?= $editMed['id_medicamento'] ?>">
        <input type="hidden" name="id_estudiante_med" value="<?= $estudiante['id_estudiante'] ?>">
        <div class="form-group"><label>Nombre</label><input type="text" name="nombre_medicamento" value="<?= htmlspecialchars($editMed['nombre_medicamento']) ?>" required></div>
        <button type="submit" name="guardar_medicamento" class="btn btn-primary"><?= Icons::check() ?> Guardar</button>
    </form>
</div></div>
<?php endif; ?>

<!-- Editar PMA -->
<?php if (isset($editPma) && $editPma): ?>
<div class="modal-overlay"><div class="modal-box">
    <h3>Editar Plan de Alimentos</h3><a href="index.php?action=perfil&id_estudiante=<?= $estudiante['id_estudiante'] ?>" class="modal-close">&times;</a>
    <form method="POST" action="index.php?action=perfil_process">
        <input type="hidden" name="id_pma_tp" value="<?= $editPma['id_pma_tp'] ?>">
        <input type="hidden" name="id_estudiante_pma" value="<?= $estudiante['id_estudiante'] ?>">
        <div class="form-group"><label>Primario</label><input type="text" name="pma_primaria" value="<?= htmlspecialchars($editPma['pma_primaria']) ?>" required></div>
        <div class="form-group"><label>Principal</label><input type="text" name="pma_principal" value="<?= htmlspecialchars($editPma['pma_principal']) ?>" required></div>
        <button type="submit" name="guardar_pma" class="btn btn-primary"><?= Icons::check() ?> Guardar</button>
    </form>
</div></div>
<?php endif; ?>

<!-- Editar Clase -->
<?php if (isset($editClase) && $editClase): ?>
<div class="modal-overlay"><div class="modal-box">
    <h3>Editar Clase</h3><a href="index.php?action=perfil&id_estudiante=<?= $estudiante['id_estudiante'] ?>" class="modal-close">&times;</a>
    <form method="POST" action="index.php?action=perfil_process">
        <input type="hidden" name="id_estudiante_clase" value="<?= $editClase['id_estudiante_clase'] ?>">
        <input type="hidden" name="id_estudiante_cls" value="<?= $estudiante['id_estudiante'] ?>">
        <div class="form-group"><label>Clase</label>
            <select name="id_clase_selected" required>
                <?php foreach ($allClases as $ac): ?><option value="<?= $ac['id_clase'] ?>" <?= ($editClase['id_clase'] ?? 0) == $ac['id_clase'] ? 'selected' : '' ?>><?= htmlspecialchars($ac['clase_nombre'] . ' (' . ($ac['nombre_grado'] ?? 'Sin grado') . ')') ?></option><?php endforeach; ?>
            </select>
        </div>
        <button type="submit" name="guardar_clase" class="btn btn-primary"><?= Icons::check() ?> Guardar</button>
    </form>
</div></div>
<?php endif; ?>

<!-- REGISTER MODALS -->
<?php if ($registroType == 'representante'): ?>
<div class="modal-overlay"><div class="modal-box">
    <h3>Registrar Representante</h3><a href="index.php?action=perfil&id_estudiante=<?= $estudiante['id_estudiante'] ?>" class="modal-close">&times;</a>
    <form method="POST" action="index.php?action=perfil_process">
        <input type="hidden" name="id_estudiante_rep" value="<?= $estudiante['id_estudiante'] ?>">
        <div class="form-group"><label>Nombres y Apellidos</label><input type="text" name="apellidos_nombres" required></div>
        <div class="form-group"><label>Cédula</label><input type="text" name="cedula" pattern="[0-9]+" inputmode="numeric" required></div>
        <div class="form-group"><label>Teléfono</label><input type="text" name="telefono" pattern="[0-9\-\+\s]+" inputmode="tel" required></div>
        <button type="submit" name="registrar_representante" class="btn btn-primary"><?= Icons::check() ?> Registrar</button>
    </form>
</div></div>
<?php endif; ?>

<?php if ($registroType == 'medicamentos'): ?>
<div class="modal-overlay"><div class="modal-box">
    <h3>Registrar Medicamento</h3><a href="index.php?action=perfil&id_estudiante=<?= $estudiante['id_estudiante'] ?>" class="modal-close">&times;</a>
    <form method="POST" action="index.php?action=perfil_process">
        <input type="hidden" name="id_estudiante_rep" value="<?= $estudiante['id_estudiante'] ?>">
        <div class="form-group"><label>Nombre</label><input type="text" name="nombre_medicamento" required></div>
        <button type="submit" name="registrar_medicamento" class="btn btn-primary"><?= Icons::check() ?> Registrar</button>
    </form>
</div></div>
<?php endif; ?>

<?php if ($registroType == 'pma'): ?>
<div class="modal-overlay"><div class="modal-box">
    <h3>Registrar PMA</h3><a href="index.php?action=perfil&id_estudiante=<?= $estudiante['id_estudiante'] ?>" class="modal-close">&times;</a>
    <form method="POST" action="index.php?action=perfil_process">
        <input type="hidden" name="id_estudiante_rep" value="<?= $estudiante['id_estudiante'] ?>">
        <div class="form-group"><label>Primario</label><input type="text" name="nombre_primario" required></div>
        <div class="form-group"><label>Principal</label><input type="text" name="nombre_principal" required></div>
        <button type="submit" name="registrar_pma" class="btn btn-primary"><?= Icons::check() ?> Registrar</button>
    </form>
</div></div>
<?php endif; ?>

<?php if ($registroType == 'clase'): ?>
<div class="modal-overlay"><div class="modal-box">
    <h3>Asignar Clase</h3><a href="index.php?action=perfil&id_estudiante=<?= $estudiante['id_estudiante'] ?>" class="modal-close">&times;</a>
    <form method="POST" action="index.php?action=perfil_process">
        <input type="hidden" name="id_estudiante_rep" value="<?= $estudiante['id_estudiante'] ?>">
        <div class="form-group"><label>Clase</label>
            <select name="id_clase_selected" required>
                <option value="">Seleccione</option>
                <?php foreach ($allClases as $ac): ?><option value="<?= $ac['id_clase'] ?>"><?= htmlspecialchars($ac['clase_nombre'] . ' (' . ($ac['nombre_grado'] ?? 'Sin grado') . ')') ?></option><?php endforeach; ?>
            </select>
        </div>
        <button type="submit" name="registrar_clase" class="btn btn-primary"><?= Icons::check() ?> Asignar</button>
    </form>
</div></div>
<?php endif; ?>

<script>
    // === Validación en tiempo real: bloquear letras en campos numéricos ===
    function soloNumeros(input) {
        if (!input) return;
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    function soloTelefono(input) {
        if (!input) return;
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9\-\+\s]/g, '');
        });
    }

    // Cédulas: solo dígitos
    soloNumeros(document.querySelector('input[name="Cedula"]'));
    document.querySelectorAll('input[name="cedula"]').forEach(soloNumeros);

    // Teléfonos: dígitos, guiones, +, espacios
    document.querySelectorAll('input[name="telefono"]').forEach(soloTelefono);
</script>

<?php $content = ob_get_clean(); require_once __DIR__ . '/../layouts/main.php'; ?>
