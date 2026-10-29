<?php
ob_start();
?>
<div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2>Perfil de Personal: <?= htmlspecialchars($personal['nombres_apellidos']) ?></h2>
        <div style="display: flex; gap: 0.5rem;">
            <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
            <a href="index.php?action=personal_edit&id=<?= $personal['id_personal'] ?>" class="btn btn-primary">Editar Datos</a>
            <form action="index.php?action=personal_delete&id=<?= $personal['id_personal'] ?>" method="POST" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar este registro?');">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <button type="submit" class="btn btn-outline" style="color: var(--danger); border-color: var(--danger);">Eliminar</button>
            </form>
            <?php endif; ?>
            <a href="index.php?action=personal" class="btn btn-outline">Volver</a>
        </div>
    </div>

    <div class="panel">
        <h3 style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Información Básica</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">Cédula</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['cedula']) ?></div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">Nombres y Apellidos</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['nombres_apellidos']) ?></div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">Sexo</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['descripcion_sexo']) ?></div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">Fecha de Nacimiento</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['fecha_nacimiento']) ?> (<?= $personal['edad'] ?> años)</div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">Lugar de Nacimiento</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['lugar_nacimiento']) ?></div>
            </div>
        </div>

        <h3 style="margin-bottom: 1.5rem; margin-top: 2.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Contacto y Dirección</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            <div style="grid-column: 1 / -1;">
                <span style="color: var(--text-muted); font-size: 0.875rem;">Dirección Exacta</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['direccion']) ?></div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">Teléfono de Habitación</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['telefono_habitacion'] ?: 'N/A') ?></div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">Teléfono Celular</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['telefono_celular']) ?></div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">Teléfono Familiar</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['telefono_familiar'] ?: 'N/A') ?></div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">Correo Electrónico</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['correo'] ?: 'N/A') ?></div>
            </div>
        </div>

        <h3 style="margin-bottom: 1.5rem; margin-top: 2.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Información Laboral</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">Tipo de Personal</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['tipo_personal']) ?></div>
            </div>
            <?php if (!empty($personal['matricula_docente'])): ?>
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">Matrícula del Docente</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['matricula_docente']) ?></div>
            </div>
            <?php endif; ?>
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">Dependencia</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['nombre_dependencia'] ?? 'N/A') ?></div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">Código de Dependencia</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['codigo_dependencia'] ?? 'N/A') ?></div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">Institución Perteneciente</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['institucion_pertenece'] ?: 'N/A') ?></div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">Cargo</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['cargo'] ?: 'N/A') ?></div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">Código RAC</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['codigo_rac'] ?: 'N/A') ?></div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">Carga Horaria</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['carga_horaria'] ?: '0') ?> horas</div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">Fecha de Ingreso MPPE</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['fecha_ingreso_mppe'] ?: 'N/A') ?></div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">Situación</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['situacion_trabajador'] ?: 'N/A') ?></div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">Cuenta Nómina (<?= htmlspecialchars($personal['tipo_cuenta_nomina'] ?: 'N/A') ?>)</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['numero_cuenta_nomina'] ?: 'N/A') ?></div>
            </div>
        </div>

        <h3 style="margin-bottom: 1.5rem; margin-top: 2.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Formación Académica y Otros</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">Título de Pregrado</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['titulo_pregrado'] ?: 'N/A') ?></div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">Postgrado</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['postgrado'] ?: 'N/A') ?></div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.875rem;">¿Estudia Actualmente?</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= $personal['estudia_actualmente'] ? 'Sí' : 'No' ?></div>
            </div>
            <div style="grid-column: 1 / -1;">
                <span style="color: var(--text-muted); font-size: 0.875rem;">Estudios Actuales</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= htmlspecialchars($personal['estudios_actuales'] ?: 'N/A') ?></div>
            </div>
            <div style="grid-column: 1 / -1;">
                <span style="color: var(--text-muted); font-size: 0.875rem;">Otros Datos</span>
                <div style="font-weight: 600; font-size: 1.1rem;"><?= nl2br(htmlspecialchars($personal['otros_datos'] ?: 'Ninguno')) ?></div>
            </div>
        </div>

    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
