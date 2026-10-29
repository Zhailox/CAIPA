<?php
require_once __DIR__ . '/../models/Estudiante.php';
require_once __DIR__ . '/../models/Clase.php';

class DashboardController {
    private $estudiante;
    private $clase;

    public function __construct() {
        $this->estudiante = new Estudiante();
        $this->clase = new Clase();
    }

    public function index() {
        $es_administrador = (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1);
        $clases = $this->clase->getAll();
        $error = null;

        // Buscar estudiante por cédula
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buscar_cedula'])) {
            $cedula = htmlspecialchars(strip_tags($_POST['cedula'] ?? ''));
            if (!empty($cedula)) {
                $result = $this->estudiante->searchByCedula($cedula);
                if ($result) {
                    header("Location: index.php?action=perfil&id_estudiante=" . $result['id_estudiante']);
                    exit();
                } else {
                    $error = "Estudiante no encontrado.";
                }
            }
        }

        // Gestionar clase seleccionada
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['gestionar_clase'])) {
            $id_clase = (int)($_POST['id_clase'] ?? 0);
            if ($id_clase > 0) {
                header("Location: index.php?action=clases_manage&id_clase=" . $id_clase);
                exit();
            }
        }

        require_once __DIR__ . '/../views/dashboard/index.php';
    }
}
?>
