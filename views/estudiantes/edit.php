<?php
ob_start();
?>
<div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2>Editar Estudiante: <?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?></h2>
        <a href="index.php?action=estudiantes_show&id=<?= $estudiante['id_estudiante'] ?>" class="btn btn-outline">Cancelar</a>
    </div>
    
    <div class="panel">
        <form class="stepper-form" action="index.php?action=estudiantes_update" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="id_estudiante" value="<?= $estudiante['id_estudiante'] ?>">
            
            <div class="stepper-header">
                <div class="step-indicator">1</div>
                <div class="step-indicator">2</div>
                <div class="step-indicator">3</div>
                <div class="step-indicator">4</div>
                <div class="step-indicator">5</div>
            </div>

            <!-- Paso 1: Datos Básicos -->
            <div class="step-content">
                <h3 style="margin-bottom: 1.5rem;">Datos del Estudiante</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" value="<?= htmlspecialchars($estudiante['nombre']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Apellido</label>
                        <input type="text" name="apellido" value="<?= htmlspecialchars($estudiante['apellido']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Cédula (Opcional)</label>
                        <input type="text" name="cedula" value="<?= htmlspecialchars($estudiante['cedula'] ?? '') ?>" pattern="[0-9]*" inputmode="numeric" placeholder="Ej: 12345678">
                    </div>
                    <div class="form-group">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" value="<?= htmlspecialchars($estudiante['fecha_nacimiento']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Sexo</label>
                        <select name="id_sexo" required>
                            <option value="">Seleccione un sexo</option>
                            <?php foreach($sexos as $s): ?>
                                <option value="<?= $s['id_sexo'] ?>" <?= $s['id_sexo'] == $estudiante['id_sexo'] ? 'selected' : '' ?>><?= htmlspecialchars($s['descripcion_sexo']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Municipio</label>
                        <select name="id_municipio" required>
                            <option value="">Seleccione un municipio</option>
                            <?php foreach($municipios as $m): ?>
                                <option value="<?= $m['id_municipio'] ?>" <?= $m['id_municipio'] == $estudiante['id_municipio'] ? 'selected' : '' ?>><?= htmlspecialchars($m['nombre_municipio']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Institución</label>
                        <select name="id_institucion" required>
                            <option value="">Seleccione una institución</option>
                            <?php foreach($instituciones as $i): ?>
                                <option value="<?= $i['id_institucion'] ?>" <?= $i['id_institucion'] == $estudiante['id_institucion'] ? 'selected' : '' ?>><?= htmlspecialchars($i['nombre_institucion']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tipo de Estudiante</label>
                        <select name="tipo_estudiante" required>
                            <option value="">Seleccione un tipo</option>
                            <option value="universitario" <?= ($estudiante['tipo_estudiante'] ?? '') == 'universitario' ? 'selected' : '' ?>>Universitario</option>
                            <option value="liceista" <?= ($estudiante['tipo_estudiante'] ?? '') == 'liceista' ? 'selected' : '' ?>>Liceista</option>
                            <option value="foraneo" <?= ($estudiante['tipo_estudiante'] ?? '') == 'foraneo' ? 'selected' : '' ?>>Foráneo</option>
                        </select>
                    </div>
                </div>
                <div style="text-align: right; margin-top: 1rem;">
                    <button type="button" class="btn btn-primary next-btn">Siguiente <?= Icons::check() ?></button>
                </div>
            </div>

            <!-- Paso 2: Detalles Físicos y Clínicos -->
            <div class="step-content">
                <h3 style="margin-bottom: 1.5rem;">Detalles Físicos y Clínicos</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Talla de Zapatos</label>
                        <input type="number" name="talla_zapatos" value="<?= htmlspecialchars($estudiante['tz'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Talla de Camisa</label>
                        <input type="number" name="talla_camisa" value="<?= htmlspecialchars($estudiante['tc'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Talla de Pantalón</label>
                        <input type="number" name="talla_pantalon" value="<?= htmlspecialchars($estudiante['tp'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Medicamentos Asignados</label>
                        <input type="text" name="medicamentos" value="<?= $medicamentos ? htmlspecialchars($medicamentos['nombre_medicamento']) : '' ?>" placeholder="Ej: Paracetamol, Ibuprofeno...">
                    </div>
                    <div class="form-group">
                        <label>PMA Principal</label>
                        <input type="text" name="pma_principal" value="<?= $pma ? htmlspecialchars($pma['pma_principal']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label>PMA Primaria</label>
                        <input type="text" name="pma_primaria" value="<?= $pma ? htmlspecialchars($pma['pma_primaria']) : '' ?>">
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 1rem;">
                    <button type="button" class="btn btn-outline prev-btn">Atrás</button>
                    <button type="button" class="btn btn-primary next-btn">Siguiente <?= Icons::check() ?></button>
                </div>
            </div>

            <!-- Paso 3: Representante -->
            <div class="step-content">
                <h3 style="margin-bottom: 1.5rem;">Datos del Representante Principal</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Apellidos y Nombres</label>
                        <input type="text" name="rep_apellidos_nombres" value="<?= $representante ? htmlspecialchars($representante['apellidos_nombres']) : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Cédula</label>
                        <input type="text" name="rep_cedula" value="<?= $representante ? htmlspecialchars($representante['cedula']) : '' ?>" pattern="[0-9]+" inputmode="numeric" required>
                    </div>
                    <div class="form-group">
                        <label>Lugar de Nacimiento</label>
                        <input type="text" name="rep_lugar_nacimiento" value="<?= $representante ? htmlspecialchars($representante['lugar_nacimiento']) : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" name="rep_fecha_nacimiento" value="<?= $representante ? htmlspecialchars($representante['fecha_nacimiento']) : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Estado Civil</label>
                        <input type="text" name="rep_estado_civil" value="<?= $representante ? htmlspecialchars($representante['estado_civil']) : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Sexo</label>
                        <select name="rep_sexo" required>
                            <option value="">Seleccione un sexo</option>
                            <?php foreach($sexos as $s): ?>
                                <option value="<?= $s['id_sexo'] ?>" <?= ($representante && $representante['id_sexo'] == $s['id_sexo']) ? 'selected' : '' ?>><?= htmlspecialchars($s['descripcion_sexo']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nacionalidad</label>
                        <input type="text" name="rep_nacionalidad" value="<?= $representante ? htmlspecialchars($representante['nacionalidad']) : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="rep_telefono" value="<?= $representante ? htmlspecialchars($representante['telefono']) : '' ?>" pattern="[0-9\-\+\s]+" inputmode="tel" required>
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 1rem;">
                    <button type="button" class="btn btn-outline prev-btn">Atrás</button>
                    <button type="button" class="btn btn-primary next-btn">Siguiente <?= Icons::check() ?></button>
                </div>
            </div>

            <!-- Paso 4: Madre -->
            <div class="step-content">
                <h3 style="margin-bottom: 1.5rem;">Datos de la Madre</h3>
                <p style="color: var(--text-muted); margin-bottom: 1rem;">Puede dejar estos campos vacíos si no aplica.</p>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Apellidos y Nombres</label>
                        <input type="text" name="madre_apellidos_nombres" value="<?= $madre ? htmlspecialchars($madre['apellidos_nombres']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Cédula</label>
                        <input type="text" name="madre_cedula" value="<?= $madre ? htmlspecialchars($madre['cedula']) : '' ?>" pattern="[0-9]*" inputmode="numeric">
                    </div>
                    <div class="form-group">
                        <label>Edad</label>
                        <input type="number" name="madre_edad" value="<?= $madre ? htmlspecialchars($madre['edad']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Estado Civil</label>
                        <input type="text" name="madre_estado_civil" value="<?= $madre ? htmlspecialchars($madre['estado_civil']) : '' ?>">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Dirección</label>
                        <textarea name="madre_direccion"><?= $madre ? htmlspecialchars($madre['direccion']) : '' ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="madre_telefono" value="<?= $madre ? htmlspecialchars($madre['telefono']) : '' ?>" pattern="[0-9\-\+\s]*" inputmode="tel">
                    </div>
                    <div class="form-group">
                        <label>Ocupación</label>
                        <input type="text" name="madre_ocupacion" value="<?= $madre ? htmlspecialchars($madre['ocupacion']) : '' ?>">
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 1rem;">
                    <button type="button" class="btn btn-outline prev-btn">Atrás</button>
                    <button type="button" class="btn btn-primary next-btn">Siguiente <?= Icons::check() ?></button>
                </div>
            </div>

            <!-- Paso 5: Padre -->
            <div class="step-content">
                <h3 style="margin-bottom: 1.5rem;">Datos del Padre</h3>
                <p style="color: var(--text-muted); margin-bottom: 1rem;">Puede dejar estos campos vacíos si no aplica.</p>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Apellidos y Nombres</label>
                        <input type="text" name="padre_apellidos_nombres" value="<?= $padre ? htmlspecialchars($padre['apellidos_nombres']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Cédula</label>
                        <input type="text" name="padre_cedula" value="<?= $padre ? htmlspecialchars($padre['cedula']) : '' ?>" pattern="[0-9]*" inputmode="numeric">
                    </div>
                    <div class="form-group">
                        <label>Edad</label>
                        <input type="number" name="padre_edad" value="<?= $padre ? htmlspecialchars($padre['edad']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Estado Civil</label>
                        <input type="text" name="padre_estado_civil" value="<?= $padre ? htmlspecialchars($padre['estado_civil']) : '' ?>">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Dirección</label>
                        <textarea name="padre_direccion"><?= $padre ? htmlspecialchars($padre['direccion']) : '' ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="padre_telefono" value="<?= $padre ? htmlspecialchars($padre['telefono']) : '' ?>" pattern="[0-9\-\+\s]*" inputmode="tel">
                    </div>
                    <div class="form-group">
                        <label>Ocupación</label>
                        <input type="text" name="padre_ocupacion" value="<?= $padre ? htmlspecialchars($padre['ocupacion']) : '' ?>">
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 1rem;">
                    <button type="button" class="btn btn-outline prev-btn">Atrás</button>
                    <button type="submit" class="btn btn-primary next-btn">Actualizar Registro</button>
                </div>
            </div>
            
        </form>
    </div>
</div>

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
    soloNumeros(document.querySelector('input[name="cedula"]'));
    soloNumeros(document.querySelector('input[name="rep_cedula"]'));
    soloNumeros(document.querySelector('input[name="madre_cedula"]'));
    soloNumeros(document.querySelector('input[name="padre_cedula"]'));

    // Teléfonos: dígitos, guiones, +, espacios
    soloTelefono(document.querySelector('input[name="rep_telefono"]'));
    soloTelefono(document.querySelector('input[name="madre_telefono"]'));
    soloTelefono(document.querySelector('input[name="padre_telefono"]'));
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
