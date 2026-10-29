<?php
require_once __DIR__ . '/../models/Profesor.php';

class ProfesorController {
    private $profesor;

    public function __construct() {
        $this->profesor = new Profesor();
    }

    public function index() {
        $es_administrador = (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1);
        $page = max(1, (int)($_GET['page'] ?? 1));
        $result = $this->profesor->getPaginated($page, 10);
        $turnos = $this->profesor->getTurnos();
        $docentes = $this->profesor->getDocentesDisponibles();
        
        // Para modal de edición
        $editData = null;
        if (isset($_GET['editar_profesor'])) {
            $editData = $this->profesor->getById((int)$_GET['id_profesor']);
        }
        
        // Para modal de eliminación
        $deleteData = null;
        if (isset($_GET['eliminar_profesor'])) {
            $deleteData = $this->profesor->getById((int)$_GET['id_profesor']);
        }
 
        require_once __DIR__ . '/../views/profesores/index.php';
    }
 
    public function store() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=profesores");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id_personal' => (int)($_POST['id_personal'] ?? 0),
                'id_turno' => (int)($_POST['id_turno'] ?? 0),
            ];
 
            $result = $this->profesor->create($data);
            if ($result === 'duplicate') {
                $_SESSION['error'] = 'El profesor ya existe.';
            } elseif ($result) {
                $_SESSION['success'] = 'Profesor registrado correctamente.';
            } else {
                $_SESSION['error'] = 'Error al registrar el profesor.';
            }
        }
        header("Location: index.php?action=profesores");
        exit();
    }
 
    public function update() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=profesores");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = (int)($_POST['id_profesor'] ?? 0);
            $data = [
                'id_personal' => (int)($_POST['id_personal'] ?? 0),
                'id_turno' => (int)($_POST['id_turno'] ?? 0),
            ];
 
            if ($this->profesor->update($id, $data)) {
                $_SESSION['success'] = 'Profesor actualizado correctamente.';
            } else {
                $_SESSION['error'] = 'Error al actualizar el profesor.';
            }
        }
        header("Location: index.php?action=profesores");
        exit();
    }
 
    public function delete() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=profesores");
            exit();
        }
        $id = (int)($_POST['id_profesor'] ?? ($_GET['id_profesor'] ?? 0));
        if ($id > 0 && $this->profesor->delete($id)) {
            $_SESSION['success'] = 'Profesor eliminado correctamente.';
        } else {
            $_SESSION['error'] = 'Error al eliminar el profesor.';
        }
        header("Location: index.php?action=profesores");
        exit();
    }
}
?>
