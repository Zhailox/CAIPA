<?php
require_once __DIR__ . '/../models/Configuracion.php';

class ConfiguracionController {
    private $config;

    public function __construct() {
        $this->config = new Configuracion();
    }

    public function index() {
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            header("Location: index.php?action=dashboard"); exit();
        }
        $es_administrador = true;
        $municipios = $this->config->getMunicipios();
        $instituciones = $this->config->getInstituciones();
        $usuarios = $this->config->getUsuarios();
        $roles = $this->config->getRoles();
        
        $turnos = $this->config->getCatalog('turno', 'id_turno', 'descripcion_turno');
        $grados = $this->config->getCatalog('grados', 'id_grado', 'nombre_grado');
        $sexos = $this->config->getCatalog('sexo', 'id_sexo', 'descripcion_sexo');
        $dependencias = $this->config->getCatalog('dependencias', 'id_dependencia', 'nombre_dependencia');
        
        $editData = null; $editType = $_GET['editar'] ?? null;
        $deleteData = null; $deleteType = $_GET['eliminar'] ?? null;
        $registrarType = $_GET['registrar'] ?? null;

        if ($editType == 'municipio' && isset($_GET['id_municipio']))
            $editData = $this->config->getMunicipioById((int)$_GET['id_municipio']);
        elseif ($editType == 'institucion' && isset($_GET['id_institucion']))
            $editData = $this->config->getInstitucionById((int)$_GET['id_institucion']);
        elseif ($editType == 'usuario' && isset($_GET['id_usuario']))
            $editData = $this->config->getUsuarioById((int)$_GET['id_usuario']);
        elseif ($editType && isset($_GET['id_catalog'])) {
            // handle generic edits
            $table = $editType;
            $id_col = 'id_' . ($table == 'grados' ? 'grado' : ($table == 'dependencias' ? 'dependencia' : $table));
            $editData = $this->config->getCatalogById($table, $id_col, (int)$_GET['id_catalog']);
        }

        if ($deleteType == 'municipio' && isset($_GET['id_municipio']))
            $deleteData = $this->config->getMunicipioById((int)$_GET['id_municipio']);
        elseif ($deleteType == 'institucion' && isset($_GET['id_institucion']))
            $deleteData = $this->config->getInstitucionById((int)$_GET['id_institucion']);
        elseif ($deleteType == 'usuario' && isset($_GET['id_usuario'])) {
            if ((int)$_GET['id_usuario'] === $_SESSION['user_id']) {
                $_SESSION['error'] = 'No puedes eliminar tu propia cuenta.';
                header("Location: index.php?action=configuraciones"); exit();
            }
            $deleteData = $this->config->getUsuarioById((int)$_GET['id_usuario']);
        } elseif ($deleteType && isset($_GET['id_catalog'])) {
            $table = $deleteType;
            $id_col = 'id_' . ($table == 'grados' ? 'grado' : ($table == 'dependencias' ? 'dependencia' : $table));
            $deleteData = $this->config->getCatalogById($table, $id_col, (int)$_GET['id_catalog']);
        }
        
        require_once __DIR__ . '/../views/configuraciones/index.php';
    }

    public function storeMunicipio() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $r = $this->config->createMunicipio(htmlspecialchars(strip_tags($_POST['nombre'] ?? '')));
            $_SESSION[$r === 'duplicate' ? 'error' : ($r ? 'success' : 'error')] = $r === 'duplicate' ? 'Ya existe.' : ($r ? 'Municipio registrado.' : 'Error.');
        }
        header("Location: index.php?action=configuraciones"); exit();
    }

    public function storeInstitucion() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $r = $this->config->createInstitucion(htmlspecialchars(strip_tags($_POST['nombre'] ?? '')));
            $_SESSION[$r === 'duplicate' ? 'error' : ($r ? 'success' : 'error')] = $r === 'duplicate' ? 'Ya existe.' : ($r ? 'Institución registrada.' : 'Error.');
        }
        header("Location: index.php?action=configuraciones"); exit();
    }

    public function updateMunicipio() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->config->updateMunicipio((int)$_POST['id'], htmlspecialchars(strip_tags($_POST['nombre'])));
            $_SESSION['success'] = 'Municipio actualizado.';
        }
        header("Location: index.php?action=configuraciones"); exit();
    }

    public function updateInstitucion() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->config->updateInstitucion((int)$_POST['id'], htmlspecialchars(strip_tags($_POST['nombre'])));
            $_SESSION['success'] = 'Institución actualizada.';
        }
        header("Location: index.php?action=configuraciones"); exit();
    }

    public function updateUsuario() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'nombre_usuario' => htmlspecialchars(strip_tags($_POST['nombre_usuario'] ?? '')),
                'clave_nueva' => $_POST['clave_nueva'] ?? '',
                'id_rol' => (int)($_POST['id_rol'] ?? 0),
            ];
            $r = $this->config->updateUsuario((int)$_POST['id_usuario_edit'], $data);
            $_SESSION[$r === 'duplicate' ? 'error' : ($r ? 'success' : 'error')] = $r === 'duplicate' ? 'Nombre ya existe.' : ($r ? 'Usuario actualizado.' : 'Error.');
        }
        header("Location: index.php?action=configuraciones"); exit();
    }

    public function deleteMunicipio() {
        $this->config->deleteMunicipio((int)($_POST['id'] ?? 0));
        $_SESSION['success'] = 'Municipio eliminado.';
        header("Location: index.php?action=configuraciones"); exit();
    }

    public function deleteInstitucion() {
        $this->config->deleteInstitucion((int)($_POST['id'] ?? 0));
        $_SESSION['success'] = 'Institución eliminada.';
        header("Location: index.php?action=configuraciones"); exit();
    }

    public function deleteUsuario() {
        $id = (int)($_POST['id'] ?? 0);
        if ($id === $_SESSION['user_id']) { $_SESSION['error'] = 'No puedes eliminar tu propia cuenta.'; }
        else { $this->config->deleteUsuario($id); $_SESSION['success'] = 'Usuario eliminado.'; }
        header("Location: index.php?action=configuraciones"); exit();
    }

    public function storeCatalog() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $table = $_POST['type'];
            $name_col = 'nombre_' . ($table == 'grados' ? 'grado' : ($table == 'dependencias' ? 'dependencia' : $table));
            if($table == 'sexo' || $table == 'turno') $name_col = 'descripcion_' . $table;
            $r = $this->config->createCatalog($table, $name_col, htmlspecialchars(strip_tags($_POST['nombre'] ?? '')));
            $_SESSION[$r === 'duplicate' ? 'error' : ($r ? 'success' : 'error')] = $r === 'duplicate' ? 'Ya existe.' : ($r ? 'Registro guardado.' : 'Error.');
        }
        header("Location: index.php?action=configuraciones"); exit();
    }

    public function updateCatalog() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $table = $_POST['type'];
            $id_col = 'id_' . ($table == 'grados' ? 'grado' : ($table == 'dependencias' ? 'dependencia' : $table));
            $name_col = 'nombre_' . ($table == 'grados' ? 'grado' : ($table == 'dependencias' ? 'dependencia' : $table));
            if($table == 'sexo' || $table == 'turno') $name_col = 'descripcion_' . $table;
            $this->config->updateCatalog($table, $id_col, $name_col, (int)$_POST['id'], htmlspecialchars(strip_tags($_POST['nombre'])));
            $_SESSION['success'] = 'Registro actualizado.';
        }
        header("Location: index.php?action=configuraciones"); exit();
    }

    public function deleteCatalog() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $table = $_POST['type'];
            $id_col = 'id_' . ($table == 'grados' ? 'grado' : ($table == 'dependencias' ? 'dependencia' : $table));
            $r = $this->config->deleteCatalog($table, $id_col, (int)$_POST['id']);
            $_SESSION[$r === 'in_use' ? 'error' : ($r ? 'success' : 'error')] = $r === 'in_use' ? 'No se puede eliminar porque está en uso.' : ($r ? 'Registro eliminado.' : 'Error.');
        }
        header("Location: index.php?action=configuraciones"); exit();
    }

    public function updateTema() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tema = $_POST['tema'];
            if (isset($_SESSION['user_id'])) {
                $this->config->updateTema($_SESSION['user_id'], $tema);
                $_SESSION['success'] = 'Tema visual actualizado para tu cuenta.';
            } else {
                $_SESSION['success'] = 'Tema visual actualizado temporalmente.';
            }
            $_SESSION['tema'] = $tema;
        }
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?action=dashboard';
        header("Location: " . $referer); exit();
    }

    public function updateTemaDefault() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tema = $_POST['tema_default'];
            $this->config->updateSistemaConfig('tema_default', $tema);
            $_SESSION['success'] = 'Tema global por defecto actualizado a ' . htmlspecialchars($tema) . '.';
        }
        header("Location: index.php?action=configuraciones"); exit();
    }
}
?>
