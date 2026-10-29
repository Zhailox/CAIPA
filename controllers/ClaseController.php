<?php
require_once __DIR__ . '/../models/Clase.php';
require_once __DIR__ . '/../models/Profesor.php';
require_once __DIR__ . '/../models/Grado.php';

class ClaseController {
    private $clase;

    public function __construct() {
        $this->clase = new Clase();
    }

    public function index() {
        $es_administrador = (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1);
        $page = max(1, (int)($_GET['page'] ?? 1));
        $result = $this->clase->getPaginated($page, 10);
        require_once __DIR__ . '/../views/clases/index.php';
    }

    public function create() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=clases");
            exit();
        }
        $es_administrador = true;
        $profesor = new Profesor();
        $grado = new Grado();
        $profesores = $profesor->getAll();
        $grados = $grado->getAll();
        require_once __DIR__ . '/../views/clases/create.php';
    }

    public function store() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=clases");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'clase_nombre' => htmlspecialchars(strip_tags($_POST['clase_nombre'] ?? '')),
                'id_profesor' => (int)($_POST['id_profesor'] ?? 0),
                'id_grado' => (int)($_POST['id_grado'] ?? 0),
            ];

            if ($this->clase->create($data)) {
                $_SESSION['success'] = 'Clase registrada correctamente.';
            } else {
                $_SESSION['error'] = 'Error al registrar la clase.';
            }
        }
        header("Location: index.php?action=clases");
        exit();
    }

    public function edit() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=clases");
            exit();
        }
        $es_administrador = true;
        $id = (int)($_GET['id_clase'] ?? 0);
        $claseData = $this->clase->getRawById($id);
        
        if (!$claseData) {
            $_SESSION['error'] = 'Clase no encontrada.';
            header("Location: index.php?action=clases");
            exit();
        }

        $profesor = new Profesor();
        $grado = new Grado();
        $profesores = $profesor->getAll();
        $grados = $grado->getAll();
        require_once __DIR__ . '/../views/clases/edit.php';
    }

    public function update() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=clases");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = (int)($_POST['id_clase'] ?? 0);
            $data = [
                'clase_nombre' => htmlspecialchars(strip_tags($_POST['clase_nombre'] ?? '')),
                'id_profesor' => (int)($_POST['id_profesor'] ?? 0),
                'id_grado' => (int)($_POST['id_grado'] ?? 0),
            ];

            if ($this->clase->update($id, $data)) {
                $_SESSION['success'] = 'Clase actualizada correctamente.';
            } else {
                $_SESSION['error'] = 'Error al actualizar la clase.';
            }
        }
        header("Location: index.php?action=clases");
        exit();
    }

    public function delete() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=clases");
            exit();
        }
        $id = (int)($_GET['id_clase'] ?? ($_POST['id_clase'] ?? 0));
        if ($id > 0 && $this->clase->delete($id)) {
            $_SESSION['success'] = 'Clase eliminada correctamente.';
        } else {
            $_SESSION['error'] = 'Error al eliminar la clase.';
        }
        header("Location: index.php?action=clases");
        exit();
    }

    public function manage() {
        $es_administrador = (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1);
        $id_clase = (int)($_GET['id_clase'] ?? 0);
        $claseData = $this->clase->getById($id_clase);
        $students = $this->clase->getStudents($id_clase);
        $error = null;
        $success = null;

        if (!$claseData) {
            $_SESSION['error'] = 'Clase no encontrada.';
            header("Location: index.php?action=clases");
            exit();
        }

        // Registrar alumno
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrar_alumno'])) {
            if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
                $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
                header("Location: index.php?action=clases_manage&id_clase=" . $id_clase);
                exit();
            }
            $cedula = htmlspecialchars(strip_tags($_POST['cedula'] ?? ''));
            $result = $this->clase->addStudentByCedula($id_clase, $cedula);
            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
            header("Location: index.php?action=clases_manage&id_clase=" . $id_clase);
            exit();
        }

        // Eliminar alumno de clase
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['borrar_de_clase'])) {
            if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
                $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
                header("Location: index.php?action=clases_manage&id_clase=" . $id_clase);
                exit();
            }
            $id_ec = (int)($_POST['id_estudiante_clase'] ?? 0);
            if ($this->clase->removeStudent($id_ec)) {
                $_SESSION['success'] = 'Alumno eliminado de la clase correctamente.';
            } else {
                $_SESSION['error'] = 'Error al eliminar alumno de la clase.';
            }
            header("Location: index.php?action=clases_manage&id_clase=" . $id_clase);
            exit();
        }

        require_once __DIR__ . '/../views/clases/manage.php';
    }
}
?>
