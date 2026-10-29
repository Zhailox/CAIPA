<?php
require_once 'BaseModel.php';

class Usuario extends BaseModel {
    protected $table_name = "usuarios";

    public function login($username, $password) {
        try {
            $query = "SELECT id_usuario, nombre_usuario, clave, id_rol, tema_preferido FROM " . $this->table_name . " WHERE nombre_usuario = ? LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(1, htmlspecialchars(strip_tags($username)));
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if(password_verify($password, $row['clave'])) {
                    return $row;
                }
            }
            return false;
        } catch(PDOException $e) {
            error_log("Login Error: " . $e->getMessage());
            return false;
        }
    }

    public function getRoles() {
        $query = "SELECT id_rol, descripcion_rol FROM roles ORDER BY descripcion_rol ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create_user($username, $password, $id_rol) {
        try {
            $check = "SELECT id_usuario FROM " . $this->table_name . " WHERE nombre_usuario = ?";
            $stmt_check = $this->conn->prepare($check);
            $stmt_check->bindValue(1, htmlspecialchars(strip_tags($username)));
            $stmt_check->execute();
            if($stmt_check->rowCount() > 0) return "exists";

            $query = "INSERT INTO " . $this->table_name . " (nombre_usuario, clave, id_rol) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(1, htmlspecialchars(strip_tags($username)));
            $stmt->bindValue(2, password_hash($password, PASSWORD_DEFAULT));
            $stmt->bindValue(3, $id_rol);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Register Error: " . $e->getMessage());
            return false;
        }
    }
}
?>
