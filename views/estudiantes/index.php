<?php
// views/estudiantes/index.php
ob_start();
?>
<style>
    .student-table th, .student-table td {
        font-size: 0.825rem;
        padding: 0.6rem 0.8rem;
    }
    .badge-tipo {
        display: inline-block;
        padding: 0.2rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.725rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    .badge-uni {
        background-color: rgba(59, 130, 246, 0.12);
        color: #3b82f6;
        border: 1px solid rgba(59, 130, 246, 0.25);
    }
    .badge-liceo {
        background-color: rgba(16, 185, 129, 0.12);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.25);
    }
    .badge-foraneo {
        background-color: rgba(245, 158, 11, 0.12);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.25);
    }
    .sidebar-title {
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text);
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding-bottom: 0.5rem;
    }
    .filter-group {
        margin-bottom: 1rem;
    }
    .filter-group label {
        display: block;
        font-size: 0.725rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 0.3rem;
    }
    .filter-group select, .filter-group input {
        width: 100%;
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
        border-radius: 6px;
        border: 1px solid rgba(0,0,0,0.15);
        background: rgba(255,255,255,0.7);
    }
    .stat-card {
        padding: 1rem;
        text-align: center;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
    }
    .liquid-glass {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.25) 0%, rgba(255, 255, 255, 0.08) 100%);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.45);
        border-radius: var(--radius);
        box-shadow: 
            0 8px 32px 0 rgba(31, 38, 135, 0.04), 
            inset 0 1px 0 0 rgba(255, 255, 255, 0.5), 
            inset 0 -1px 0 0 rgba(0, 0, 0, 0.02);
        position: relative;
        overflow: hidden;
    }
    .liquid-glass::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 50%;
        background: linear-gradient(to bottom, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 100%);
        pointer-events: none;
    }
    [data-theme="dark"] .liquid-glass,
    [data-theme="tokyo-night"] .liquid-glass,
    [data-theme="dracula"] .liquid-glass {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.06) 0%, rgba(255, 255, 255, 0.01) 100%);
        border: 1px solid rgba(255, 255, 255, 0.12);
        box-shadow: 
            0 8px 32px 0 rgba(0, 0, 0, 0.2),
            inset 0 1px 0 0 rgba(255, 255, 255, 0.12);
    }
    [data-theme="dark"] .liquid-glass::after,
    [data-theme="tokyo-night"] .liquid-glass::after,
    [data-theme="dracula"] .liquid-glass::after {
        background: linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0) 100%);
    }
</style>

<div>
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success" style="margin-bottom: 1rem;"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger" style="margin-bottom: 1rem;"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Encabezado de Página -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2>Gestión de Estudiantes</h2>
        <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
        <a href="index.php?action=estudiantes_create" class="btn btn-primary">
            <?= Icons::plus() ?> Nuevo Estudiante
        </a>
        <?php endif; ?>
    </div>

    <!-- Panel de Estadísticas (Esteroides Funcionales) -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
        <div class="liquid-glass stat-card">
            <small style="color: var(--text-muted); font-weight: 600; font-size: 0.75rem;">Total Estudiantes</small>
            <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-top: 0.25rem; position: relative; z-index: 1;"><?= $stats['total'] ?></div>
        </div>
        <div class="liquid-glass stat-card">
            <small style="color: var(--text-muted); font-weight: 600; font-size: 0.75rem;">Universitarios</small>
            <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-top: 0.25rem; position: relative; z-index: 1;"><?= $stats['universitario'] ?></div>
        </div>
        <div class="liquid-glass stat-card">
            <small style="color: var(--text-muted); font-weight: 600; font-size: 0.75rem;">Liceístas</small>
            <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-top: 0.25rem; position: relative; z-index: 1;"><?= $stats['liceista'] ?></div>
        </div>
        <div class="liquid-glass stat-card">
            <small style="color: var(--text-muted); font-weight: 600; font-size: 0.75rem;">Foráneos</small>
            <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-top: 0.25rem; position: relative; z-index: 1;"><?= $stats['foraneo'] ?></div>
        </div>
    </div>

    <!-- Layout Dividido: Tabla (Izquierda) + Panel de Filtros (Derecha) -->
    <div style="display: flex; gap: 1.5rem; align-items: flex-start;">
        
        <!-- Sección Izquierda: Tabla de Estudiantes -->
        <div style="flex: 1; min-width: 0;">
            <div class="panel" style="padding: 0;">
                <div class="table-container">
                    <table class="student-table">
                        <thead>
                            <tr>
                                <th>Cédula</th>
                                <th>Nombres y Apellidos</th>
                                <th>Edad</th>
                                <th>Sexo</th>
                                <th>Institución</th>
                                <th>Tipo Estudiante</th>
                                <th style="text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($estudiantes)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                                    No se encontraron estudiantes que coincidan con la búsqueda o filtros.
                                </td>
                            </tr>
                            <?php else: ?>
                                <?php foreach($estudiantes as $est): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($est['cedula'] ?: 'S/C') ?></strong></td>
                                    <td><?= htmlspecialchars($est['nombre'] . ' ' . $est['apellido']) ?></td>
                                    <td><?= htmlspecialchars($est['edad']) ?> años</td>
                                    <td><?= htmlspecialchars($est['descripcion_sexo'] ?? 'N/A') ?></td>
                                    <td><span style="font-size: 0.75rem; color: var(--text-muted);"><?= htmlspecialchars($est['nombre_institucion'] ?? 'N/A') ?></span></td>
                                    <td>
                                        <?php if(!empty($est['tipo_estudiante'])): ?>
                                            <?php 
                                                $badge_class = 'badge-foraneo';
                                                if($est['tipo_estudiante'] == 'universitario') $badge_class = 'badge-uni';
                                                elseif($est['tipo_estudiante'] == 'liceista') $badge_class = 'badge-liceo';
                                            ?>
                                            <span class="badge-tipo <?= $badge_class ?>"><?= htmlspecialchars($est['tipo_estudiante']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted" style="font-size: 0.75rem;">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <div style="display: flex; gap: 0.25rem; justify-content: center;">
                                            <a href="index.php?action=perfil&id_estudiante=<?= $est['id_estudiante'] ?>" class="btn btn-outline" style="padding: 0.2rem 0.4rem; font-size: 0.75rem;">Ver Ficha</a>
                                            <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                                            <form action="index.php?action=estudiantes_delete&id=<?= $est['id_estudiante'] ?>" method="POST" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar este registro de estudiante por completo?');">
                                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                                <button type="submit" class="btn" style="background:var(--danger); color:white; padding: 0.2rem 0.4rem; font-size: 0.75rem; border:none; border-radius:4px;">Eliminar</button>
                                            </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Paginador con Filtros Persistidos (Corrección de Error de Paginación) -->
            <?php if(isset($total_pages) && $total_pages > 1): ?>
                <?php 
                    // Construir query string base para mantener los filtros y búsqueda
                    $page_params = [
                        'action' => 'estudiantes',
                        'search' => $search
                    ];
                    if(!empty($filters['id_municipio'])) $page_params['id_municipio'] = $filters['id_municipio'];
                    if(!empty($filters['id_institucion'])) $page_params['id_institucion'] = $filters['id_institucion'];
                    if(!empty($filters['tipo_estudiante'])) $page_params['tipo_estudiante'] = $filters['tipo_estudiante'];
                ?>
                <div style="display: flex; justify-content: center; gap: 0.25rem; margin-top: 1.5rem; margin-bottom: 2rem;">
                    <?php for($i=1; $i<=$total_pages; $i++): ?>
                        <?php 
                            $page_params['page'] = $i;
                            $url = "index.php?" . http_build_query($page_params);
                        ?>
                        <a href="<?= $url ?>" 
                           class="btn <?= ($page == $i) ? 'btn-primary' : 'btn-outline' ?>"
                           style="padding: 0.4rem 0.8rem; font-size: 0.8rem; border-radius: 4px;">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sección Derecha: Panel de Filtros (Mini Panel) -->
        <div style="width: 260px; flex-shrink: 0;" class="panel">
            <div class="sidebar-title">
                <?= Icons::filter() ?> Filtros y Búsqueda
            </div>
            
            <form method="GET" action="index.php">
                <input type="hidden" name="action" value="estudiantes">
                
                <!-- Búsqueda por Texto -->
                <div class="filter-group">
                    <label for="search">Buscar</label>
                    <input type="text" name="search" id="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Nombre, apellido o cédula...">
                </div>

                <!-- Filtro por Municipio -->
                <div class="filter-group">
                    <label for="id_municipio">Municipio</label>
                    <select name="id_municipio" id="id_municipio">
                        <option value="">Todos</option>
                        <?php foreach($municipios as $m): ?>
                            <option value="<?= $m['id_municipio'] ?>" <?= ($filters['id_municipio'] ?? '') == $m['id_municipio'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['nombre_municipio']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Filtro por Institución -->
                <div class="filter-group">
                    <label for="id_institucion">Institución</label>
                    <select name="id_institucion" id="id_institucion">
                        <option value="">Todas</option>
                        <?php foreach($instituciones as $inst): ?>
                            <option value="<?= $inst['id_institucion'] ?>" <?= ($filters['id_institucion'] ?? '') == $inst['id_institucion'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($inst['nombre_institucion']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Filtro por Tipo de Estudiante -->
                <div class="filter-group">
                    <label for="tipo_estudiante">Tipo de Estudiante</label>
                    <select name="tipo_estudiante" id="tipo_estudiante">
                        <option value="">Todos</option>
                        <option value="universitario" <?= ($filters['tipo_estudiante'] ?? '') == 'universitario' ? 'selected' : '' ?>>Universitario</option>
                        <option value="liceista" <?= ($filters['tipo_estudiante'] ?? '') == 'liceista' ? 'selected' : '' ?>>Liceísta</option>
                        <option value="foraneo" <?= ($filters['tipo_estudiante'] ?? '') == 'foraneo' ? 'selected' : '' ?>>Foráneo</option>
                    </select>
                </div>

                <!-- Acciones del Filtro -->
                <div style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 0.8rem; padding: 0.5rem;">
                        Aplicar Filtros
                    </button>
                    
                    <?php if(!empty($search) || !empty($filters['id_municipio']) || !empty($filters['id_institucion']) || !empty($filters['tipo_estudiante'])): ?>
                        <a href="index.php?action=estudiantes" class="btn btn-outline" style="width: 100%; text-align: center; font-size: 0.8rem; padding: 0.5rem;">
                            Limpiar Filtros
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
