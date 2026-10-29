<?php
session_start();

// Load components
require_once __DIR__ . '/views/components/icons.php';

$action = $_GET['action'] ?? 'login';

// Initialize default theme if not in session
if (!isset($_SESSION['tema'])) {
    require_once __DIR__ . '/models/Configuracion.php';
    $config_model = new Configuracion();
    $_SESSION['tema'] = $config_model->getSistemaConfig('tema_default') ?? 'nord-light';
}

// Public actions (no session required)
$public_actions = ['login', 'register'];

// Check auth
if (!in_array($action, $public_actions) && !isset($_SESSION['user_id'])) {
    header("Location: index.php?action=login");
    exit();
}

// Route to controller
switch ($action) {
    // ==================== AUTH ====================
    case 'login':
        require_once 'controllers/AuthController.php';
        (new AuthController())->login();
        break;
    case 'register':
        require_once 'controllers/AuthController.php';
        (new AuthController())->register();
        break;
    case 'logout':
        require_once 'controllers/AuthController.php';
        (new AuthController())->logout();
        break;

    // ==================== DASHBOARD ====================
    case 'dashboard':
        require_once 'controllers/DashboardController.php';
        (new DashboardController())->index();
        break;

    // ==================== ESTUDIANTES ====================
    case 'estudiantes':
        require_once 'controllers/EstudianteController.php';
        (new EstudianteController())->index();
        break;
    case 'estudiantes_create':
        require_once 'controllers/EstudianteController.php';
        (new EstudianteController())->create();
        break;
    case 'estudiantes_store':
        require_once 'controllers/EstudianteController.php';
        (new EstudianteController())->store();
        break;

    case 'estudiantes_edit':
        require_once 'controllers/EstudianteController.php';
        (new EstudianteController())->edit();
        break;
    case 'estudiantes_update':
        require_once 'controllers/EstudianteController.php';
        (new EstudianteController())->update();
        break;
    case 'estudiantes_delete':
        require_once 'controllers/EstudianteController.php';
        (new EstudianteController())->delete();
        break;

    // ==================== PERFIL (LEGACY PROFILE) ====================
    case 'perfil':
        require_once 'controllers/PerfilController.php';
        (new PerfilController())->index();
        break;
    case 'perfil_process':
        require_once 'controllers/PerfilController.php';
        (new PerfilController())->process();
        break;
    case 'perfil_del_rep':
        require_once 'controllers/PerfilController.php';
        (new PerfilController())->deleteRepresentante();
        break;
    case 'perfil_del_med':
        require_once 'controllers/PerfilController.php';
        (new PerfilController())->deleteMedicamento();
        break;
    case 'perfil_del_pma':
        require_once 'controllers/PerfilController.php';
        (new PerfilController())->deletePma();
        break;
    case 'perfil_del_cls':
        require_once 'controllers/PerfilController.php';
        (new PerfilController())->deleteClase();
        break;

    // ==================== PERSONAL ====================
    case 'personal':
        require_once 'controllers/PersonalController.php';
        (new PersonalController())->index();
        break;
    case 'personal_create':
        require_once 'controllers/PersonalController.php';
        (new PersonalController())->create();
        break;
    case 'personal_store':
        require_once 'controllers/PersonalController.php';
        (new PersonalController())->store();
        break;
    case 'personal_show':
        require_once 'controllers/PersonalController.php';
        (new PersonalController())->show();
        break;
    case 'personal_edit':
        require_once 'controllers/PersonalController.php';
        (new PersonalController())->edit();
        break;
    case 'personal_update':
        require_once 'controllers/PersonalController.php';
        (new PersonalController())->update();
        break;
    case 'personal_delete':
        require_once 'controllers/PersonalController.php';
        (new PersonalController())->delete();
        break;

    // ==================== CLASES ====================
    case 'clases':
        require_once 'controllers/ClaseController.php';
        (new ClaseController())->index();
        break;
    case 'clases_create':
        require_once 'controllers/ClaseController.php';
        (new ClaseController())->create();
        break;
    case 'clases_store':
        require_once 'controllers/ClaseController.php';
        (new ClaseController())->store();
        break;
    case 'clases_edit':
        require_once 'controllers/ClaseController.php';
        (new ClaseController())->edit();
        break;
    case 'clases_update':
        require_once 'controllers/ClaseController.php';
        (new ClaseController())->update();
        break;
    case 'clases_delete':
        require_once 'controllers/ClaseController.php';
        (new ClaseController())->delete();
        break;
    case 'clases_manage':
        require_once 'controllers/ClaseController.php';
        (new ClaseController())->manage();
        break;

    // ==================== PROFESORES ====================
    case 'profesores':
        require_once 'controllers/ProfesorController.php';
        (new ProfesorController())->index();
        break;
    case 'profesores_store':
        require_once 'controllers/ProfesorController.php';
        (new ProfesorController())->store();
        break;
    case 'profesores_update':
        require_once 'controllers/ProfesorController.php';
        (new ProfesorController())->update();
        break;
    case 'profesores_delete':
        require_once 'controllers/ProfesorController.php';
        (new ProfesorController())->delete();
        break;

    // ==================== GRADOS ====================
    case 'grados':
        require_once 'controllers/GradoController.php';
        (new GradoController())->index();
        break;
    case 'grados_store':
        require_once 'controllers/GradoController.php';
        (new GradoController())->store();
        break;
    case 'grados_update':
        require_once 'controllers/GradoController.php';
        (new GradoController())->update();
        break;
    case 'grados_delete':
        require_once 'controllers/GradoController.php';
        (new GradoController())->delete();
        break;

    // ==================== REPORTES ====================
    case 'reportes':
        require_once 'controllers/ReporteController.php';
        (new ReporteController())->index();
        break;
    case 'reportes_generate':
        require_once 'controllers/ReporteController.php';
        (new ReporteController())->generate();
        break;

    // ==================== CONFIGURACIONES ====================
    case 'configuraciones':
        require_once 'controllers/ConfiguracionController.php';
        (new ConfiguracionController())->index();
        break;
    case 'config_store_municipio':
        require_once 'controllers/ConfiguracionController.php';
        (new ConfiguracionController())->storeMunicipio();
        break;
    case 'config_store_institucion':
        require_once 'controllers/ConfiguracionController.php';
        (new ConfiguracionController())->storeInstitucion();
        break;
    case 'config_update_municipio':
        require_once 'controllers/ConfiguracionController.php';
        (new ConfiguracionController())->updateMunicipio();
        break;
    case 'config_update_institucion':
        require_once 'controllers/ConfiguracionController.php';
        (new ConfiguracionController())->updateInstitucion();
        break;
    case 'config_update_usuario':
        require_once 'controllers/ConfiguracionController.php';
        (new ConfiguracionController())->updateUsuario();
        break;
    case 'config_delete_municipio':
        require_once 'controllers/ConfiguracionController.php';
        (new ConfiguracionController())->deleteMunicipio();
        break;
    case 'config_delete_institucion':
        require_once 'controllers/ConfiguracionController.php';
        (new ConfiguracionController())->deleteInstitucion();
        break;
    case 'config_delete_usuario':
        require_once 'controllers/ConfiguracionController.php';
        (new ConfiguracionController())->deleteUsuario();
        break;
        
    case 'config_catalog_store':
        require_once 'controllers/ConfiguracionController.php';
        (new ConfiguracionController())->storeCatalog();
        break;
    case 'config_catalog_update':
        require_once 'controllers/ConfiguracionController.php';
        (new ConfiguracionController())->updateCatalog();
        break;
    case 'config_catalog_delete':
        require_once 'controllers/ConfiguracionController.php';
        (new ConfiguracionController())->deleteCatalog();
        break;
    case 'config_update_tema':
        require_once 'controllers/ConfiguracionController.php';
        (new ConfiguracionController())->updateTema();
        break;
    case 'config_update_tema_default':
        require_once 'controllers/ConfiguracionController.php';
        (new ConfiguracionController())->updateTemaDefault();
        break;

    // ==================== ADMIN ====================
    case 'admin':
        require_once 'controllers/AdminController.php';
        (new AdminController())->index();
        break;

    // ==================== DEFAULT ====================
    default:
        header("Location: index.php?action=dashboard");
        exit();
        break;
}
?>
