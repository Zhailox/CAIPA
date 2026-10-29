<?php
require_once __DIR__ . '/../models/Estudiante.php';

class EstudianteController {
    private $estudiante;

    public function __construct() {
        $this->estudiante = new Estudiante();
    }

    public function index() {
        $search = $_GET['search'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Retrieve filter parameters
        $filters = [
            'id_municipio' => $_GET['id_municipio'] ?? '',
            'id_institucion' => $_GET['id_institucion'] ?? '',
            'tipo_estudiante' => $_GET['tipo_estudiante'] ?? ''
        ];
        
        $stmt = $this->estudiante->getEstudiantesCompletos($search, $limit, $offset, $filters);
        $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $total_records = $this->estudiante->countEstudiantes($search, $filters);
        $total_pages = ceil($total_records / $limit);

        // Fetch catalogs for filters in the sidebar
        $municipios = $this->estudiante->getMunicipios();
        $instituciones = $this->estudiante->getInstituciones();

        // Fetch stats for top cards
        $stats = $this->estudiante->getStudentStats();
        
        if(empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        require_once __DIR__ . '/../views/estudiantes/index.php';
    }

    public function create() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=estudiantes");
            exit();
        }
        if(empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $sexos = $this->estudiante->getSexos();
        $municipios = $this->estudiante->getMunicipios();
        $instituciones = $this->estudiante->getInstituciones();
        require_once __DIR__ . '/../views/estudiantes/create.php';
    }

    public function store() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=estudiantes");
            exit();
        }
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['error'] = "Error de validación CSRF.";
                header("Location: index.php?action=estudiantes_create");
                exit();
            }

            $data = [];
            foreach($_POST as $key => $val) {
                $data[$key] = htmlspecialchars(strip_tags($val));
            }

            if($this->estudiante->storeEstudianteCompleto($data)) {
                $_SESSION['success'] = "Estudiante y familia registrados con éxito.";
            } else {
                $_SESSION['error'] = "Hubo un error al guardar el registro. Verifica que la cédula no esté duplicada.";
            }

            header("Location: index.php?action=estudiantes");
            exit();
        }
    }

    public function edit() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=estudiantes");
            exit();
        }
        $id = $_GET['id'] ?? 0;
        $estudiante = $this->estudiante->getEstudianteById($id);
        if(!$estudiante) {
            $_SESSION['error'] = "Estudiante no encontrado.";
            header("Location: index.php?action=estudiantes");
            exit;
        }
        $medicamentos = $this->estudiante->getMedicamentosById($id);
        $pma = $this->estudiante->getPmaById($id);
        $representante = $this->estudiante->getRepresentanteById($id);
        $madre = $this->estudiante->getMadreById($id);
        $padre = $this->estudiante->getPadreById($id);

        if(empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $sexos = $this->estudiante->getSexos();
        $municipios = $this->estudiante->getMunicipios();
        $instituciones = $this->estudiante->getInstituciones();
        require_once __DIR__ . '/../views/estudiantes/edit.php';
    }

    public function update() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=estudiantes");
            exit();
        }
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['error'] = "Error de validación CSRF.";
                header("Location: index.php?action=estudiantes");
                exit();
            }

            $id = $_POST['id_estudiante'] ?? 0;
            $data = [];
            foreach($_POST as $key => $val) {
                $data[$key] = htmlspecialchars(strip_tags($val));
            }

            if($this->estudiante->updateEstudianteCompleto($id, $data)) {
                $_SESSION['success'] = "Estudiante y familia actualizados con éxito.";
            } else {
                $_SESSION['error'] = "Hubo un error al actualizar el registro.";
            }

            header("Location: index.php?action=perfil&id_estudiante=$id");
            exit();
        }
    }

    public function delete() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=estudiantes");
            exit();
        }
        $id = $_GET['id'] ?? 0;
        if($id && $_SERVER['REQUEST_METHOD'] == 'POST') {
            if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['error'] = "Error de validación CSRF.";
                header("Location: index.php?action=estudiantes");
                exit();
            }
            try {
                // Get PDO connection from BaseModel
                $reflection = new ReflectionClass($this->estudiante);
                $property = $reflection->getProperty('conn');
                $property->setAccessible(true);
                $conn = $property->getValue($this->estudiante);

                $stmt = $conn->prepare("DELETE FROM estudiantes WHERE id_estudiante = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = "Estudiante eliminado exitosamente.";
            } catch(PDOException $e) {
                $_SESSION['error'] = "Error al eliminar: " . $e->getMessage();
            }
        }
        header("Location: index.php?action=estudiantes");
        exit();
    }
}
?>
