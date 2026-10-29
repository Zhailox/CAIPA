<?php
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    private $usuario;

    public function __construct() {
        $this->usuario = new Usuario();
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login() {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['error'] = "Error de validación CSRF.";
                header("Location: index.php?action=login");
                exit();
            }

            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if(empty($username) || empty($password)) {
                $_SESSION['error'] = "Todos los campos son obligatorios.";
                header("Location: index.php?action=login");
                exit();
            }

            $userData = $this->usuario->login($username, $password);

            if($userData) {
                $_SESSION['user_id'] = $userData['id_usuario'];
                $_SESSION['username'] = $userData['nombre_usuario'];
                $_SESSION['role_id'] = $userData['id_rol'];
                $_SESSION['tema'] = $userData['tema_preferido'] ?? 'dark';
                
                session_regenerate_id(true);

                header("Location: index.php?action=dashboard");
                exit();
            } else {
                $_SESSION['error'] = "Usuario o contraseña incorrectos.";
                header("Location: index.php?action=login");
                exit();
            }
        }
        
        if(empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function register() {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['error'] = "Error de validación CSRF.";
                header("Location: index.php?action=register");
                exit();
            }

            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $id_rol = 2; // Default to 'Invitado' (Guest) role

            if(empty($username) || empty($password)) {
                $_SESSION['error'] = "Todos los campos son obligatorios.";
                header("Location: index.php?action=register");
                exit();
            }

            if($password !== $confirm_password) {
                $_SESSION['error'] = "Las contraseñas no coinciden.";
                header("Location: index.php?action=register");
                exit();
            }

            $result = $this->usuario->create_user($username, $password, $id_rol);

            if($result === "exists") {
                $_SESSION['error'] = "El nombre de usuario ya existe.";
                header("Location: index.php?action=register");
                exit();
            } else if($result) {
                $_SESSION['success'] = "Usuario creado exitosamente. Puede iniciar sesión.";
                header("Location: index.php?action=login");
                exit();
            } else {
                $_SESSION['error'] = "Error al crear el usuario.";
                header("Location: index.php?action=register");
                exit();
            }
        }
        
        if(empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function logout() {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = array();
        session_destroy();
        header("Location: index.php?action=login");
        exit();
    }
}
?>
