<?php
require_once __DIR__ . '/../config/Database.php';

class Configuracion {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // ==================== MUNICIPIOS ====================

    public function getMunicipios() {
        try {
            $stmt = $this->conn->prepare("SELECT id_municipio, nombre_municipio FROM municipios ORDER BY nombre_municipio");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en Configuracion::getMunicipios: " . $e->getMessage());
            return [];
        }
    }

    public function getMunicipioById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM municipios WHERE id_municipio = :id LIMIT 1");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    public function createMunicipio($nombre) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO municipios (nombre_municipio) VALUES (:nombre)");
            $stmt->bindParam(':nombre', $nombre);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            if ($e->getCode() == 23000) return 'duplicate';
            return false;
        }
    }

    public function updateMunicipio($id, $nombre) {
        try {
            $stmt = $this->conn->prepare("UPDATE municipios SET nombre_municipio = :nombre WHERE id_municipio = :id");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteMunicipio($id) {
        try {
            $this->conn->beginTransaction();
            
            $stmt1 = $this->conn->prepare("UPDATE estudiantes SET id_municipio = NULL WHERE id_municipio = :id");
            $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt1->execute();

            $stmt2 = $this->conn->prepare("DELETE FROM municipios WHERE id_municipio = :id");
            $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt2->execute();

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    // ==================== INSTITUCIONES ====================

    public function getInstituciones() {
        try {
            $stmt = $this->conn->prepare("SELECT id_institucion, nombre_institucion FROM instituciones ORDER BY nombre_institucion");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return [];
        }
    }

    public function getInstitucionById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM instituciones WHERE id_institucion = :id LIMIT 1");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    public function createInstitucion($nombre) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO instituciones (nombre_institucion) VALUES (:nombre)");
            $stmt->bindParam(':nombre', $nombre);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            if ($e->getCode() == 23000) return 'duplicate';
            return false;
        }
    }

    public function updateInstitucion($id, $nombre) {
        try {
            $stmt = $this->conn->prepare("UPDATE instituciones SET nombre_institucion = :nombre WHERE id_institucion = :id");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteInstitucion($id) {
        try {
            $this->conn->beginTransaction();

            $stmt1 = $this->conn->prepare("UPDATE estudiantes SET id_institucion = NULL WHERE id_institucion = :id");
            $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt1->execute();

            $stmt2 = $this->conn->prepare("DELETE FROM instituciones WHERE id_institucion = :id");
            $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt2->execute();

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    // ==================== USUARIOS ====================

    public function getUsuarios() {
        try {
            $stmt = $this->conn->prepare(
                "SELECT u.id_usuario, u.nombre_usuario, r.descripcion_rol, u.id_rol
                 FROM usuarios u 
                 JOIN roles r ON u.id_rol = r.id_rol 
                 ORDER BY u.nombre_usuario ASC"
            );
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return [];
        }
    }

    public function getUsuarioById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT id_usuario, nombre_usuario, id_rol FROM usuarios WHERE id_usuario = :id LIMIT 1");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    public function getRoles() {
        try {
            $stmt = $this->conn->prepare("SELECT id_rol, descripcion_rol FROM roles ORDER BY descripcion_rol ASC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return [];
        }
    }

    public function updateUsuario($id, $data) {
        try {
            $setParts = [];
            $params = [];

            if (!empty($data['clave_nueva'])) {
                $setParts[] = "clave = :clave";
                $params[':clave'] = password_hash($data['clave_nueva'], PASSWORD_DEFAULT);
            }

            $setParts[] = "nombre_usuario = :nombre";
            $params[':nombre'] = $data['nombre_usuario'];

            $setParts[] = "id_rol = :rol";
            $params[':rol'] = $data['id_rol'];

            $params[':id'] = $id;

            $query = "UPDATE usuarios SET " . implode(", ", $setParts) . " WHERE id_usuario = :id";
            $stmt = $this->conn->prepare($query);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            if ($e->getCode() == 23000) return 'duplicate';
            return false;
        }
    }

    public function deleteUsuario($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM usuarios WHERE id_usuario = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    // ==================== GENERICS CATALOG ====================
    public function getCatalog($table, $id_col, $name_col) {
        try {
            $stmt = $this->conn->prepare("SELECT $id_col as id, $name_col as nombre FROM $table ORDER BY $name_col");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }

    public function getCatalogById($table, $id_col, $id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM $table WHERE $id_col = :id LIMIT 1");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return false; }
    }

    public function createCatalog($table, $name_col, $nombre) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO $table ($name_col) VALUES (:nombre)");
            $stmt->bindParam(':nombre', $nombre);
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) return 'duplicate';
            return false;
        }
    }

    public function updateCatalog($table, $id_col, $name_col, $id, $nombre) {
        try {
            $stmt = $this->conn->prepare("UPDATE $table SET $name_col = :nombre WHERE $id_col = :id");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    public function deleteCatalog($table, $id_col, $id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM $table WHERE $id_col = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) return 'in_use';
            return false;
        }
    }
    
    // ==================== TEMAS ====================
    public function updateTema($id_usuario, $tema) {
        try {
            $stmt = $this->conn->prepare("UPDATE usuarios SET tema_preferido = :tema WHERE id_usuario = :id");
            $stmt->bindParam(':tema', $tema);
            $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }
    // ==================== CONFIGURACION SISTEMA ====================
    public function getSistemaConfig($clave) {
        try {
            $stmt = $this->conn->prepare("SELECT valor FROM configuracion_sistema WHERE clave = :clave LIMIT 1");
            $stmt->bindParam(':clave', $clave);
            $stmt->execute();
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return $row['valor'];
            }
            return null;
        } catch (PDOException $e) { return null; }
    }

    public function updateSistemaConfig($clave, $valor) {
        try {
            $stmt = $this->conn->prepare("UPDATE configuracion_sistema SET valor = :valor WHERE clave = :clave");
            $stmt->bindParam(':valor', $valor);
            $stmt->bindParam(':clave', $clave);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }
}
?>
