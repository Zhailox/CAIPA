<?php
require_once __DIR__ . '/../models/Grado.php';

class GradoController {
    private $grado;

    public function __construct() {
        $this->grado = new Grado();
    }

    public function index() {
        $es_administrador = (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1);
        $grados = $this->grado->getAll();

        // Para modal de edición
        $editData = null;
        if (isset($_GET['editar_grado'])) {
            $editData = $this->grado->getById((int)$_GET['id_grado']);
        }

        // Para modal de eliminación
        $deleteData = null;
        if (isset($_GET['eliminar_grado'])) {
            $deleteData = $this->grado->getById((int)$_GET['id_grado']);
        }

        require_once __DIR__ . '/../views/grados/index.php';
    }

    public function store() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=grados");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = htmlspecialchars(strip_tags($_POST['nombre_grado'] ?? ''));
            $result = $this->grado->create($nombre);
            if ($result === 'duplicate') {
                $_SESSION['error'] = 'El grado ya existe.';
            } elseif ($result) {
                $_SESSION['success'] = 'Grado registrado correctamente.';
            } else {
                $_SESSION['error'] = 'Error al registrar el grado.';
            }
        }
        header("Location: index.php?action=grados");
        exit();
    }

    public function update() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=grados");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = (int)($_POST['id_grado'] ?? 0);
            $nombre = htmlspecialchars(strip_tags($_POST['nombre_grado'] ?? ''));
            if ($this->grado->update($id, $nombre)) {
                $_SESSION['success'] = 'Grado actualizado correctamente.';
            } else {
                $_SESSION['error'] = 'Error al actualizar el grado.';
            }
        }
        header("Location: index.php?action=grados");
        exit();
    }

    public function delete() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "No tiene permisos para realizar esta acción.";
            header("Location: index.php?action=grados");
            exit();
        }
        $id = (int)($_POST['id_grado'] ?? ($_GET['id_grado'] ?? 0));
        if ($id > 0 && $this->grado->delete($id)) {
            $_SESSION['success'] = 'Grado eliminado correctamente.';
        } else {
            $_SESSION['error'] = 'Error al eliminar el grado.';
        }
        header("Location: index.php?action=grados");
        exit();
    }
}
?>
