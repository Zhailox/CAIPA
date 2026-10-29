<?php
require_once __DIR__ . '/../config/Database.php';

class Profesor {
    private $conn;
    private $table = 'profesores';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /**
     * Obtener profesores paginados con JOIN a turno
     */
    public function getPaginated($page = 1, $perPage = 10) {
        try {
            $offset = ($page - 1) * $perPage;

            $countStmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM {$this->table}");
            $countStmt->execute();
            $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            $query = "SELECT p.id_profesor, per.nombres_apellidos as nombre_profesor, t.descripcion_turno, p.id_turno, p.id_personal
                      FROM {$this->table} p 
                      INNER JOIN personal per ON p.id_personal = per.id_personal
                      LEFT JOIN turno t ON p.id_turno = t.id_turno 
                      ORDER BY p.id_profesor DESC
                      LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            return [
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                'total' => $total,
                'pages' => ceil($total / $perPage),
                'current' => $page
            ];
        } catch (PDOException $e) {
            error_log("Error en Profesor::getPaginated: " . $e->getMessage());
            return ['data' => [], 'total' => 0, 'pages' => 0, 'current' => 1];
        }
    }

    /**
     * Obtener profesor por ID
     */
    public function getById($id) {
        try {
            $query = "SELECT p.id_profesor, per.nombres_apellidos as nombre_profesor, t.descripcion_turno, p.id_turno, p.id_personal
                      FROM {$this->table} p 
                      INNER JOIN personal per ON p.id_personal = per.id_personal
                      LEFT JOIN turno t ON p.id_turno = t.id_turno 
                      WHERE p.id_profesor = :id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en Profesor::getById: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener todos los profesores (para dropdowns)
     */
    public function getAll() {
        try {
            $query = "SELECT p.id_profesor, per.nombres_apellidos as nombre_profesor 
                      FROM {$this->table} p
                      INNER JOIN personal per ON p.id_personal = per.id_personal 
                      ORDER BY per.nombres_apellidos";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en Profesor::getAll: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener todos los turnos
     */
    public function getTurnos() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM turno ORDER BY id_turno");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en Profesor::getTurnos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener lista de docentes disponibles del personal
     */
    public function getDocentesDisponibles() {
        try {
            $stmt = $this->conn->prepare("SELECT id_personal, nombres_apellidos FROM personal WHERE tipo_personal = 'Docente' ORDER BY nombres_apellidos");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en Profesor::getDocentesDisponibles: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Crear profesor
     */
    public function create($data) {
        try {
            $query = "INSERT INTO {$this->table} (id_personal, id_turno) VALUES (:personal, :turno)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':personal', $data['id_personal'], PDO::PARAM_INT);
            $stmt->bindParam(':turno', $data['id_turno'], PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en Profesor::create: " . $e->getMessage());
            if ($e->getCode() == 23000) { // Duplicate key
                return 'duplicate';
            }
            return false;
        }
    }

    /**
     * Actualizar profesor
     */
    public function update($id, $data) {
        try {
            $query = "UPDATE {$this->table} SET id_personal = :personal, id_turno = :turno WHERE id_profesor = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':personal', $data['id_personal'], PDO::PARAM_INT);
            $stmt->bindParam(':turno', $data['id_turno'], PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en Profesor::update: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar profesor (pone NULL en clases que lo referencian)
     */
    public function delete($id) {
        try {
            $this->conn->beginTransaction();

            // Poner NULL en clases que lo referencien
            $stmt1 = $this->conn->prepare("UPDATE clases SET id_profesor = NULL WHERE id_profesor = :id");
            $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt1->execute();

            // Eliminar profesor
            $stmt2 = $this->conn->prepare("DELETE FROM {$this->table} WHERE id_profesor = :id");
            $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt2->execute();

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error en Profesor::delete: " . $e->getMessage());
            return false;
        }
    }
}
?>
