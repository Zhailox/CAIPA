<?php
require_once __DIR__ . '/../config/Database.php';

class Grado {
    private $conn;
    private $table = 'grados';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /**
     * Obtener todos los grados
     */
    public function getAll() {
        try {
            $stmt = $this->conn->prepare("SELECT id_grado, nombre_grado FROM {$this->table} ORDER BY id_grado");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en Grado::getAll: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener grado por ID
     */
    public function getById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id_grado = :id LIMIT 1");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en Grado::getById: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crear grado
     */
    public function create($nombre) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO {$this->table} (nombre_grado) VALUES (:nombre)");
            $stmt->bindParam(':nombre', $nombre);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en Grado::create: " . $e->getMessage());
            if ($e->getCode() == 23000) return 'duplicate';
            return false;
        }
    }

    /**
     * Actualizar grado
     */
    public function update($id, $nombre) {
        try {
            $stmt = $this->conn->prepare("UPDATE {$this->table} SET nombre_grado = :nombre WHERE id_grado = :id");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en Grado::update: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar grado (pone NULL en clases que lo referencian)
     */
    public function delete($id) {
        try {
            $this->conn->beginTransaction();

            $stmt1 = $this->conn->prepare("UPDATE clases SET id_grado = NULL WHERE id_grado = :id");
            $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt1->execute();

            $stmt2 = $this->conn->prepare("DELETE FROM {$this->table} WHERE id_grado = :id");
            $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt2->execute();

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error en Grado::delete: " . $e->getMessage());
            return false;
        }
    }
}
?>
