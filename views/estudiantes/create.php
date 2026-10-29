<?php
// views/estudiantes/create.php
ob_start();
?>
<div>
    <h2 style="margin-bottom: 2rem;">Registro de Estudiante y Familia</h2>
    
    <div class="panel">
        <form class="stepper-form" action="index.php?action=estudiantes_store" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
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
                        <input type="text" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label>Apellido</label>
                        <input type="text" name="apellido" required>
                    </div>
                    <div class="form-group">
                        <label>Cédula (Opcional)</label>
                        <input type="text" name="cedula" pattern="[0-9]*" inputmode="numeric" placeholder="Ej: 12345678">
                    </div>
                    <div class="form-group">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" required>
                    </div>
                    <div class="form-group">
                        <label>Sexo</label>
                        <select name="id_sexo" required>
                            <option value="">Seleccione un sexo</option>
                            <?php foreach($sexos as $s): ?>
                                <option value="<?= $s['id_sexo'] ?>"><?= htmlspecialchars($s['descripcion_sexo']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Municipio</label>
                        <select name="id_municipio" required>
                            <option value="">Seleccione un municipio</option>
                            <?php foreach($municipios as $m): ?>
                                <option value="<?= $m['id_municipio'] ?>"><?= htmlspecialchars($m['nombre_municipio']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Institución</label>
                        <select name="id_institucion" required>
                            <option value="">Seleccione una institución</option>
                            <?php foreach($instituciones as $i): ?>
                                <option value="<?= $i['id_institucion'] ?>"><?= htmlspecialchars($i['nombre_institucion']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tipo de Estudiante</label>
                        <select name="tipo_estudiante" required>
                            <option value="">Seleccione un tipo</option>
                            <option value="universitario">Universitario</option>
                            <option value="liceista">Liceista</option>
                            <option value="foraneo">Foráneo</option>
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
                        <input type="number" name="talla_zapatos">
                    </div>
                    <div class="form-group">
                        <label>Talla de Camisa</label>
                        <input type="number" name="talla_camisa">
                    </div>
                    <div class="form-group">
                        <label>Talla de Pantalón</label>
                        <input type="number" name="talla_pantalon">
                    </div>
                    <div class="form-group">
                        <label>Medicamentos Asignados</label>
                        <input type="text" name="medicamentos" placeholder="Ej: Paracetamol, Ibuprofeno...">
                    </div>
                    <div class="form-group">
                        <label>PMA Principal</label>
                        <input type="text" name="pma_principal">
                    </div>
                    <div class="form-group">
                        <label>PMA Primaria</label>
                        <input type="text" name="pma_primaria">
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
                        <input type="text" name="rep_apellidos_nombres" required>
                    </div>
                    <div class="form-group">
                        <label>Cédula</label>
                        <input type="text" name="rep_cedula" pattern="[0-9]+" inputmode="numeric" required>
                    </div>
                    <div class="form-group">
                        <label>Lugar de Nacimiento</label>
                        <input type="text" name="rep_lugar_nacimiento" required>
                    </div>
                    <div class="form-group">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" name="rep_fecha_nacimiento" required>
                    </div>
                    <div class="form-group">
                        <label>Estado Civil</label>
                        <input type="text" name="rep_estado_civil" required>
                    </div>
                    <div class="form-group">
                        <label>Sexo</label>
                        <select name="rep_sexo" required>
                            <option value="">Seleccione un sexo</option>
                            <?php foreach($sexos as $s): ?>
                                <option value="<?= $s['id_sexo'] ?>"><?= htmlspecialchars($s['descripcion_sexo']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nacionalidad</label>
                        <input type="text" name="rep_nacionalidad" required>
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="rep_telefono" pattern="[0-9\-\+\s]+" inputmode="tel" required>
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
                        <input type="text" name="madre_apellidos_nombres">
                    </div>
                    <div class="form-group">
                        <label>Cédula</label>
                        <input type="text" name="madre_cedula" pattern="[0-9]*" inputmode="numeric">
                    </div>
                    <div class="form-group">
                        <label>Edad</label>
                        <input type="number" name="madre_edad">
                    </div>
                    <div class="form-group">
                        <label>Estado Civil</label>
                        <input type="text" name="madre_estado_civil">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Dirección</label>
                        <textarea name="madre_direccion"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="madre_telefono" pattern="[0-9\-\+\s]*" inputmode="tel">
                    </div>
                    <div class="form-group">
                        <label>Ocupación</label>
                        <input type="text" name="madre_ocupacion">
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
                        <input type="text" name="padre_apellidos_nombres">
                    </div>
                    <div class="form-group">
                        <label>Cédula</label>
                        <input type="text" name="padre_cedula" pattern="[0-9]*" inputmode="numeric">
                    </div>
                    <div class="form-group">
                        <label>Edad</label>
                        <input type="number" name="padre_edad">
                    </div>
                    <div class="form-group">
                        <label>Estado Civil</label>
                        <input type="text" name="padre_estado_civil">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Dirección</label>
                        <textarea name="padre_direccion"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="padre_telefono" pattern="[0-9\-\+\s]*" inputmode="tel">
                    </div>
                    <div class="form-group">
                        <label>Ocupación</label>
                        <input type="text" name="padre_ocupacion">
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 1rem;">
                    <button type="button" class="btn btn-outline prev-btn">Atrás</button>
                    <button type="submit" class="btn btn-primary next-btn">Guardar Todo el Registro</button>
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
