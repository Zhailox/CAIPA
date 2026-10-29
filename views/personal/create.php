<?php
ob_start();
?>
<div>
    <h2 style="margin-bottom: 2rem;">Registro de Personal</h2>
    
    <div class="panel">
        <form class="stepper-form" action="index.php?action=personal_store" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <div class="stepper-header">
                <div class="step-indicator">1</div>
                <div class="step-indicator">2</div>
                <div class="step-indicator">3</div>
            </div>

            <!-- Paso 1: Datos Personales -->
            <div class="step-content">
                <h3 style="margin-bottom: 1.5rem;">Datos Personales</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Nombres y Apellidos</label>
                        <input type="text" name="nombres_apellidos" required>
                    </div>
                    <div class="form-group">
                        <label>Cédula de Identidad</label>
                        <input type="text" name="cedula" pattern="[0-9]+" inputmode="numeric" title="Solo números" required>
                    </div>
                    <div class="form-group">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" required>
                    </div>
                    <div class="form-group">
                        <label>Lugar de Nacimiento</label>
                        <input type="text" name="lugar_nacimiento" required>
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
                        <label>Correo Electrónico</label>
                        <input type="email" name="correo" required>
                    </div>
                    <div class="form-group">
                        <label>Teléfono Celular</label>
                        <input type="tel" name="telefono_celular" pattern="[0-9\-\+\s]+" inputmode="tel" title="Solo números, guiones y espacios" required>
                    </div>
                    <div class="form-group">
                        <label>Teléfono Habitación</label>
                        <input type="tel" name="telefono_habitacion" pattern="[0-9\-\+\s]*" inputmode="tel" title="Solo números, guiones y espacios">
                    </div>
                    <div class="form-group">
                        <label>Teléfono Familiar (Opcional)</label>
                        <input type="tel" name="telefono_familiar" pattern="[0-9\-\+\s]*" inputmode="tel" title="Solo números, guiones y espacios">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Dirección de Domicilio</label>
                        <textarea name="direccion" required></textarea>
                    </div>
                </div>
                <div style="text-align: right; margin-top: 1rem;">
                    <button type="button" class="btn btn-primary next-btn">Siguiente <?= Icons::check() ?></button>
                </div>
            </div>

            <!-- Paso 2: Profesionalización -->
            <div class="step-content">
                <h3 style="margin-bottom: 1.5rem;">Profesionalización</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Título obtenido de Pregrado</label>
                        <input type="text" name="titulo_pregrado">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Postgrado (Especialidad o Maestría obtenida)</label>
                        <input type="text" name="postgrado">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="checkbox" name="estudia_actualmente" id="estudia_actualmente" style="width:auto; transform: scale(1.5);">
                            Estudia Actualmente
                        </label>
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Especifique los estudios que realiza actualmente</label>
                        <input type="text" name="estudios_actuales" id="estudios_actuales_input" disabled>
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 1rem;">
                    <button type="button" class="btn btn-outline prev-btn">Atrás</button>
                    <button type="button" class="btn btn-primary next-btn">Siguiente <?= Icons::check() ?></button>
                </div>
            </div>

            <!-- Paso 3: Información Laboral -->
            <div class="step-content">
                <h3 style="margin-bottom: 1.5rem;">Información Laboral</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Tipo de Personal</label>
                        <select name="tipo_personal" required>
                            <option value="">Seleccione el tipo</option>
                            <?php foreach($tipos as $t): ?>
                                <option value="<?= $t ?>"><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Dependencia (Funcionario)</label>
                        <select name="id_dependencia" required>
                            <option value="">Seleccione Dependencia</option>
                            <?php foreach($dependencias as $d): ?>
                                <option value="<?= $d['id_dependencia'] ?>"><?= htmlspecialchars($d['nombre_dependencia']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Fecha de Ingreso al MPPE (s/recibo)</label>
                        <input type="date" name="fecha_ingreso_mppe">
                    </div>
                    <div class="form-group">
                        <label>Código RAC (s/recibo)</label>
                        <input type="text" name="codigo_rac">
                    </div>
                    <div class="form-group">
                        <label>Cargo (s/recibo)</label>
                        <input type="text" name="cargo">
                    </div>
                    <div class="form-group">
                        <label>Carga Horaria</label>
                        <input type="number" name="carga_horaria" min="0" max="80" step="1">
                    </div>
                    <div class="form-group">
                        <label>Institución a la que pertenece</label>
                        <select name="institucion_pertenece">
                            <option value="">Seleccione Institución</option>
                            <?php foreach($instituciones as $i): ?>
                                <option value="<?= htmlspecialchars($i['nombre_institucion']) ?>"><?= htmlspecialchars($i['nombre_institucion']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Situación del Trabajador</label>
                        <input type="text" name="situacion_trabajador">
                    </div>
                    <div class="form-group">
                        <label>Tipo de Cuenta Nómina</label>
                        <input type="text" name="tipo_cuenta_nomina">
                    </div>
                    <div class="form-group">
                        <label>Número de Cuenta Nómina</label>
                        <input type="text" name="numero_cuenta_nomina" pattern="[0-9]*" inputmode="numeric" title="Solo números">
                    </div>
                    <div class="form-group">
                        <label>Matrícula del Docente (Opcional)</label>
                        <input type="text" name="matricula_docente" placeholder="Solo si es Docente">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Otros Datos de Interés</label>
                        <textarea name="otros_datos"></textarea>
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 1rem;">
                    <button type="button" class="btn btn-outline prev-btn">Atrás</button>
                    <button type="submit" class="btn btn-primary">Guardar Registro</button>
                </div>
            </div>
            
        </form>
    </div>
</div>

<script>
    document.getElementById('estudia_actualmente').addEventListener('change', function() {
        document.getElementById('estudios_actuales_input').disabled = !this.checked;
    });

    // === Validación en tiempo real: bloquear letras en campos numéricos ===
    function soloNumeros(input) {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    function soloTelefono(input) {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9\-\+\s]/g, '');
        });
    }

    // Cédula y Cuenta Nómina: solo dígitos
    soloNumeros(document.querySelector('input[name="cedula"]'));
    var cuentaInput = document.querySelector('input[name="numero_cuenta_nomina"]');
    if (cuentaInput) soloNumeros(cuentaInput);

    // Teléfonos: dígitos, guiones, +, espacios
    soloTelefono(document.querySelector('input[name="telefono_celular"]'));
    var telHab = document.querySelector('input[name="telefono_habitacion"]');
    if (telHab) soloTelefono(telHab);
    var telFam = document.querySelector('input[name="telefono_familiar"]');
    if (telFam) soloTelefono(telFam);
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
