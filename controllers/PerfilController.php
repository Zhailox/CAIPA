<?php
require_once __DIR__ . '/../models/Estudiante.php';

class PerfilController {
    private $est;

    public function __construct() {
        $this->est = new Estudiante();
    }

    public function index() {
        $es_admin = (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1);
        $id = (int)($_GET['id_estudiante'] ?? 0);
        if (!$id) { header("Location: index.php?action=estudiantes"); exit(); }

        $estudiante = $this->est->getEstudianteById($id);
        if (!$estudiante) { header("Location: index.php?action=estudiantes"); exit(); }

        $representantes = $this->est->getAllRepresentantes($id);
        $medicamentos = $this->est->getAllMedicamentos($id);
        $pmas = $this->est->getAllPma($id);
        $clases = $this->est->getClasesDelEstudiante($id);
        
        $madre = $this->est->getMadreById($id);
        $padre = $this->est->getPadreById($id);

        // Data for edit modals
        $sexos = $this->est->getSexos();
        $municipios = $this->est->getMunicipios();
        $instituciones = $this->est->getInstituciones();
        $allClases = $this->est->getAllClases();

        // Edit student modal data
        $editEstudiante = null;
        $rawEstudiante = null;
        $deleteEstudiante = false;
        $editRep = null;
        $editMed = null;
        $editPma = null;
        $editClase = null;
        $registroType = null;

        if ($es_admin) {
            if (isset($_GET['editar'])) {
                $editEstudiante = true;
                $rawEstudiante = $estudiante;
            }
            // Delete student modal
            $deleteEstudiante = isset($_GET['eliminar']);

            // Rep edit
            if (isset($_GET['editar_representante'])) {
                $editRep = $this->est->getRepresentante((int)$_GET['id_representante']);
            }
            // Med edit
            if (isset($_GET['editar_medicamento'])) {
                $editMed = $this->est->getMedicamento((int)$_GET['id_medicamento']);
            }
            // PMA edit
            if (isset($_GET['editar_pma'])) {
                $editPma = $this->est->getPma((int)$_GET['id_pma_tp']);
            }
            // Clase edit
            if (isset($_GET['editar_clase'])) {
                $ecId = (int)$_GET['id_estudiante_clase'];
                $editClase = ['id_estudiante_clase' => $ecId];
                // find current clase
                foreach ($clases as $c) {
                    if ($c['id_estudiante_clase'] == $ecId) { $editClase['id_clase'] = $c['id_clase']; break; }
                }
            }
            // Register modal type
            $registroType = $_GET['registro'] ?? null;
        }

        require_once __DIR__ . '/../views/perfil/index.php';
    }

    // ==================== PROCESS POST ACTIONS ====================

    public function process() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=estudiantes");
            exit();
        }
        $id_est = (int)($_POST['id_estudiante'] ?? $_POST['id_estudiante_rep'] ?? $_GET['id_estudiante'] ?? 0);

        // --- Student update ---
        if (isset($_POST['guardar_estudiante'])) {
            $data = [
                'apellido' => htmlspecialchars(strip_tags($_POST['Apellido'])),
                'nombre' => htmlspecialchars(strip_tags($_POST['Nombre'])),
                'cedula' => htmlspecialchars(strip_tags($_POST['Cedula'])),
                'fecha_nacimiento' => $_POST['FechaNac'],
                'id_sexo' => (int)$_POST['Sexo'],
                'tz' => $_POST['TallaZap'] ?? 0, 'tc' => $_POST['TallaCam'] ?? 0, 'tp' => $_POST['TallaPan'] ?? 0,
                'id_municipio' => (int)$_POST['Municipio'], 'id_institucion' => (int)$_POST['Institucion'],
                'tipo_estudiante' => htmlspecialchars(strip_tags($_POST['TipoEstudiante'] ?? '')),
            ];
            $r = $this->est->updateBasico((int)$_POST['id_estudiante'], $data);
            $_SESSION[$r === 'duplicate' ? 'error' : ($r ? 'success' : 'error')] = $r === 'duplicate' ? 'Cédula duplicada.' : ($r ? 'Estudiante actualizado.' : 'Error.');
            header("Location: index.php?action=perfil&id_estudiante=" . (int)$_POST['id_estudiante']); exit();
        }

        // --- Student delete ---
        if (isset($_POST['borrar_estudiante'])) {
            $this->est->deleteCompleto((int)$_POST['id_estudiante']);
            $_SESSION['success'] = 'Estudiante eliminado.';
            header("Location: index.php?action=estudiantes"); exit();
        }

        // --- Representante CRUD ---
        if (isset($_POST['registrar_representante'])) {
            $this->est->createRepresentante([
                'apellidos_nombres' => htmlspecialchars(strip_tags($_POST['apellidos_nombres'])),
                'cedula' => htmlspecialchars(strip_tags($_POST['cedula'])),
                'telefono' => htmlspecialchars(strip_tags($_POST['telefono'])),
                'id_estudiante' => $id_est
            ]);
            $_SESSION['success'] = 'Representante registrado.';
            header("Location: index.php?action=perfil&id_estudiante=$id_est"); exit();
        }
        if (isset($_POST['guardar_representante'])) {
            $this->est->updateRepresentante((int)$_POST['id_representante'], [
                'apellidos_nombres' => htmlspecialchars(strip_tags($_POST['apellidos_nombres'])),
                'cedula' => htmlspecialchars(strip_tags($_POST['cedula'])),
                'telefono' => htmlspecialchars(strip_tags($_POST['telefono'])),
            ]);
            $_SESSION['success'] = 'Representante actualizado.';
            header("Location: index.php?action=perfil&id_estudiante=$id_est"); exit();
        }

        // --- Medicamento CRUD ---
        if (isset($_POST['registrar_medicamento'])) {
            $this->est->createMedicamento(htmlspecialchars(strip_tags($_POST['nombre_medicamento'])), $id_est);
            $_SESSION['success'] = 'Medicamento registrado.';
            header("Location: index.php?action=perfil&id_estudiante=$id_est"); exit();
        }
        if (isset($_POST['guardar_medicamento'])) {
            $this->est->updateMedicamento((int)$_POST['id_medicamento'], htmlspecialchars(strip_tags($_POST['nombre_medicamento'])));
            $_SESSION['success'] = 'Medicamento actualizado.';
            $id_est = (int)$_POST['id_estudiante_med'];
            header("Location: index.php?action=perfil&id_estudiante=$id_est"); exit();
        }

        // --- PMA CRUD ---
        if (isset($_POST['registrar_pma'])) {
            $this->est->createPma([
                'pma_principal' => htmlspecialchars(strip_tags($_POST['nombre_principal'])),
                'pma_primaria' => htmlspecialchars(strip_tags($_POST['nombre_primario'])),
                'id_estudiante' => $id_est
            ]);
            $_SESSION['success'] = 'PMA registrado.';
            header("Location: index.php?action=perfil&id_estudiante=$id_est"); exit();
        }
        if (isset($_POST['guardar_pma'])) {
            $this->est->updatePma((int)$_POST['id_pma_tp'], [
                'pma_principal' => htmlspecialchars(strip_tags($_POST['pma_principal'])),
                'pma_primaria' => htmlspecialchars(strip_tags($_POST['pma_primaria'])),
            ]);
            $_SESSION['success'] = 'PMA actualizado.';
            $id_est = (int)$_POST['id_estudiante_pma'];
            header("Location: index.php?action=perfil&id_estudiante=$id_est"); exit();
        }

        // --- Clase CRUD ---
        if (isset($_POST['registrar_clase'])) {
            $r = $this->est->addClaseEstudiante($id_est, (int)$_POST['id_clase_selected']);
            $_SESSION[$r === 'already_enrolled' ? 'error' : ($r ? 'success' : 'error')] = $r === 'already_enrolled' ? 'Ya está en esa clase.' : ($r ? 'Clase asignada.' : 'Error.');
            header("Location: index.php?action=perfil&id_estudiante=$id_est"); exit();
        }
        if (isset($_POST['guardar_clase'])) {
            $r = $this->est->updateClaseEstudiante((int)$_POST['id_estudiante_clase'], (int)$_POST['id_estudiante_cls'], (int)$_POST['id_clase_selected']);
            $_SESSION[$r === 'already_enrolled' ? 'error' : ($r ? 'success' : 'error')] = $r === 'already_enrolled' ? 'Ya está en esa clase.' : ($r ? 'Clase actualizada.' : 'Error.');
            $id_est = (int)$_POST['id_estudiante_cls'];
            header("Location: index.php?action=perfil&id_estudiante=$id_est"); exit();
        }

        header("Location: index.php?action=estudiantes"); exit();
    }

    // --- GET-based deletes (with confirm) ---
    public function deleteRepresentante() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=estudiantes");
            exit();
        }
        $this->est->deleteRepresentante((int)$_GET['id_representante']);
        $_SESSION['success'] = 'Representante eliminado.';
        header("Location: index.php?action=perfil&id_estudiante=" . (int)$_GET['id_estudiante']); exit();
    }

    public function deleteMedicamento() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=estudiantes");
            exit();
        }
        $this->est->deleteMedicamento((int)$_GET['id_medicamento']);
        $_SESSION['success'] = 'Medicamento eliminado.';
        header("Location: index.php?action=perfil&id_estudiante=" . (int)$_GET['id_estudiante']); exit();
    }

    public function deletePma() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=estudiantes");
            exit();
        }
        $this->est->deletePma((int)$_GET['id_pma_tp']);
        $_SESSION['success'] = 'PMA eliminado.';
        header("Location: index.php?action=perfil&id_estudiante=" . (int)$_GET['id_estudiante']); exit();
    }

    public function deleteClase() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=estudiantes");
            exit();
        }
        $this->est->removeClaseEstudiante((int)$_GET['id_estudiante_clase']);
        $_SESSION['success'] = 'Clase eliminada.';
        header("Location: index.php?action=perfil&id_estudiante=" . (int)$_GET['id_estudiante']); exit();
    }
}
?>
