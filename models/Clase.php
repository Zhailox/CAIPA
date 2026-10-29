<?php
require_once __DIR__ . '/../config/Database.php';

class Clase {
    private $conn;
    private $table = 'clases';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /**
     * Obtener clases paginadas con JOINs a profesores y grados
     */
    public function getPaginated($page = 1, $perPage = 10) {
        try {
            $offset = ($page - 1) * $perPage;

            // Contar total
            $countStmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM {$this->table}");
            $countStmt->execute();
            $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Obtener datos paginados
            $query = "SELECT c.id_clase, c.clase_nombre, 
                             per.nombres_apellidos as nombre_profesor, p.id_profesor, 
                             g.nombre_grado, g.id_grado 
                      FROM {$this->table} c 
                      LEFT JOIN profesores p ON c.id_profesor = p.id_profesor 
                      LEFT JOIN personal per ON p.id_personal = per.id_personal
                      LEFT JOIN grados g ON c.id_grado = g.id_grado 
                      ORDER BY c.id_clase DESC
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
            error_log("Error en Clase::getPaginated: " . $e->getMessage());
            return ['data' => [], 'total' => 0, 'pages' => 0, 'current' => 1];
        }
    }

    /**
     * Obtener una clase por ID con JOINs completos
     */
    public function getById($id) {
        try {
            $query = "SELECT c.id_clase, c.clase_nombre, 
                             per.nombres_apellidos as nombre_profesor, p.id_profesor, 
                             g.nombre_grado, g.id_grado,
                             t.descripcion_turno
                      FROM {$this->table} c 
                      LEFT JOIN profesores p ON c.id_profesor = p.id_profesor 
                      LEFT JOIN personal per ON p.id_personal = per.id_personal
                      LEFT JOIN grados g ON c.id_grado = g.id_grado 
                      LEFT JOIN turno t ON p.id_turno = t.id_turno
                      WHERE c.id_clase = :id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en Clase::getById: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener solo datos crudos de la clase (sin JOINs)
     */
    public function getRawById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id_clase = :id LIMIT 1");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en Clase::getRawById: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crear nueva clase
     */
    public function create($data) {
        try {
            $query = "INSERT INTO {$this->table} (clase_nombre, id_profesor, id_grado) VALUES (:nombre, :profesor, :grado)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $data['clase_nombre']);
            $stmt->bindParam(':profesor', $data['id_profesor'], PDO::PARAM_INT);
            $stmt->bindParam(':grado', $data['id_grado'], PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en Clase::create: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar clase
     */
    public function update($id, $data) {
        try {
            $query = "UPDATE {$this->table} SET clase_nombre = :nombre, id_profesor = :profesor, id_grado = :grado WHERE id_clase = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $data['clase_nombre']);
            $stmt->bindParam(':profesor', $data['id_profesor'], PDO::PARAM_INT);
            $stmt->bindParam(':grado', $data['id_grado'], PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en Clase::update: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar clase (primero elimina estudiantes_clases relacionados)
     */
    public function delete($id) {
        try {
            $this->conn->beginTransaction();
            
            // Eliminar relaciones estudiantes_clases
            $stmt1 = $this->conn->prepare("DELETE FROM estudiantes_clases WHERE id_clase = :id");
            $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt1->execute();

            // Eliminar la clase
            $stmt2 = $this->conn->prepare("DELETE FROM {$this->table} WHERE id_clase = :id");
            $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt2->execute();

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error en Clase::delete: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener estudiantes de una clase
     */
    public function getStudents($id_clase) {
        try {
            $query = "SELECT ec.id_estudiante_clase, e.id_estudiante, e.nombre, e.apellido, e.cedula 
                      FROM estudiantes_clases ec 
                      LEFT JOIN estudiantes e ON e.id_estudiante = ec.id_estudiante 
                      WHERE ec.id_clase = :id_clase";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_clase', $id_clase, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en Clase::getStudents: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Añadir estudiante a una clase por cédula
     */
    public function addStudentByCedula($id_clase, $cedula) {
        try {
            // Buscar estudiante por cédula
            $stmt = $this->conn->prepare("SELECT id_estudiante FROM estudiantes WHERE cedula = :cedula LIMIT 1");
            $stmt->bindParam(':cedula', $cedula);
            $stmt->execute();
            $student = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$student) {
                return ['success' => false, 'message' => 'No se encontró un alumno con esa cédula'];
            }

            $id_estudiante = $student['id_estudiante'];

            // Verificar si ya está en la clase
            $checkStmt = $this->conn->prepare(
                "SELECT id_estudiante_clase FROM estudiantes_clases WHERE id_estudiante = :est AND id_clase = :cls"
            );
            $checkStmt->bindParam(':est', $id_estudiante, PDO::PARAM_INT);
            $checkStmt->bindParam(':cls', $id_clase, PDO::PARAM_INT);
            $checkStmt->execute();

            if ($checkStmt->fetch()) {
                return ['success' => false, 'message' => 'El alumno ya está registrado en esta clase'];
            }

            // Insertar
            $insertStmt = $this->conn->prepare(
                "INSERT INTO estudiantes_clases (id_estudiante, id_clase) VALUES (:est, :cls)"
            );
            $insertStmt->bindParam(':est', $id_estudiante, PDO::PARAM_INT);
            $insertStmt->bindParam(':cls', $id_clase, PDO::PARAM_INT);
            $insertStmt->execute();

            return ['success' => true, 'message' => 'Alumno registrado exitosamente en la clase'];
        } catch (PDOException $e) {
            error_log("Error en Clase::addStudentByCedula: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al registrar alumno'];
        }
    }

    /**
     * Remover estudiante de una clase
     */
    public function removeStudent($id_estudiante_clase) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM estudiantes_clases WHERE id_estudiante_clase = :id");
            $stmt->bindParam(':id', $id_estudiante_clase, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en Clase::removeStudent: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener todas las clases (para dropdowns)
     */
    public function getAll() {
        try {
            $stmt = $this->conn->prepare("SELECT c.id_clase, c.clase_nombre, g.nombre_grado 
                                           FROM {$this->table} c 
                                           LEFT JOIN grados g ON c.id_grado = g.id_grado 
                                           ORDER BY c.clase_nombre");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en Clase::getAll: " . $e->getMessage());
            return [];
        }
    }
}
?>
