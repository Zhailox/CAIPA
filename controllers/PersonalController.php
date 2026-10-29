<?php
require_once __DIR__ . '/../models/Personal.php';

class PersonalController {
    private $personal;

    public function __construct() {
        $this->personal = new Personal();
    }

    public function index() {
        $search = $_GET['search'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $filters = [
            'tipo_personal' => $_GET['tipo_personal'] ?? '',
            'id_dependencia' => $_GET['id_dependencia'] ?? ''
        ];

        $stmt = $this->personal->getPersonalCompleto($search, $limit, $offset, $filters);
        $personal_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $total_records = $this->personal->countPersonal($search, $filters);
        $total_pages = ceil($total_records / $limit);

        // Catalogs for filters
        $dependencias = $this->personal->getDependencias();
        $tipos_personal = $this->personal->getTiposPersonal();

        // Stats for top cards
        $stats = $this->personal->getPersonalStats();

        if(empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        require_once __DIR__ . '/../views/personal/index.php';
    }

    public function create() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=personal");
            exit();
        }
        if(empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $sexos = $this->personal->getSexos();
        $dependencias = $this->personal->getDependencias();
        $tipos = $this->personal->getTiposPersonal();
        $instituciones = $this->personal->getInstituciones();
        require_once __DIR__ . '/../views/personal/create.php';
    }

    public function store() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=personal");
            exit();
        }
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['error'] = "Error de validación CSRF.";
                header("Location: index.php?action=personal_create");
                exit();
            }
            
            $data = [];
            foreach($_POST as $key => $val) {
                $data[$key] = htmlspecialchars(strip_tags($val));
            }

            if($this->personal->storePersonalCompleto($data)) {
                $_SESSION['success'] = "Personal registrado con éxito.";
            } else {
                $_SESSION['error'] = "Hubo un error al guardar el registro. Verifica que la cédula no esté duplicada.";
            }

            header("Location: index.php?action=personal");
            exit();
        }
    }

    public function show() {
        $id = $_GET['id'] ?? 0;
        $personal = $this->personal->getPersonalById($id);
        if(!$personal) {
            $_SESSION['error'] = "Personal no encontrado.";
            header("Location: index.php?action=personal");
            exit;
        }
        require_once __DIR__ . '/../views/personal/show.php';
    }

    public function edit() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=personal");
            exit();
        }
        $id = $_GET['id'] ?? 0;
        $personal = $this->personal->getPersonalById($id);
        if(!$personal) {
            $_SESSION['error'] = "Personal no encontrado.";
            header("Location: index.php?action=personal");
            exit;
        }
        if(empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $sexos = $this->personal->getSexos();
        $dependencias = $this->personal->getDependencias();
        $tipos = $this->personal->getTiposPersonal();
        $instituciones = $this->personal->getInstituciones();
        require_once __DIR__ . '/../views/personal/edit.php';
    }

    public function update() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=personal");
            exit();
        }
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['error'] = "Error de validación CSRF.";
                header("Location: index.php?action=personal");
                exit();
            }
            $id = $_POST['id_personal'] ?? 0;
            $data = [];
            foreach($_POST as $key => $val) {
                $data[$key] = htmlspecialchars(strip_tags($val));
            }
            if($this->personal->updatePersonalCompleto($id, $data)) {
                $_SESSION['success'] = "Personal actualizado con éxito.";
            } else {
                $_SESSION['error'] = "Error al actualizar el registro.";
            }
            header("Location: index.php?action=personal_show&id=$id");
            exit();
        }
    }

    public function delete() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=personal");
            exit();
        }
        $id = $_GET['id'] ?? 0;
        if($id && $_SERVER['REQUEST_METHOD'] == 'POST') {
            if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['error'] = "Error de validación CSRF.";
                header("Location: index.php?action=personal");
                exit();
            }
            try {
                $reflection = new ReflectionClass($this->personal);
                $property = $reflection->getProperty('conn');
                $property->setAccessible(true);
                $conn = $property->getValue($this->personal);

                $stmt = $conn->prepare("DELETE FROM personal WHERE id_personal = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = "Personal eliminado exitosamente.";
            } catch(PDOException $e) {
                $_SESSION['error'] = "Error al eliminar: " . $e->getMessage();
            }
        }
        header("Location: index.php?action=personal");
        exit();
    }
}
?>
