<?php
require_once 'BaseModel.php';

class Estudiante extends BaseModel {
    protected $table_name = "estudiantes";

    public function getEstudiantesCompletos($search = '', $limit = 10, $offset = 0, $filters = []) {
        $conditions = [];
        $params = [];

        // Search condition
        if ($search !== '') {
            $conditions[] = "(e.nombre LIKE :search OR e.apellido LIKE :search OR e.cedula LIKE :search)";
            $params[':search'] = "%$search%";
        }

        // Dropdown filters
        if (!empty($filters['id_municipio'])) {
            $conditions[] = "e.id_municipio = :id_municipio";
            $params[':id_municipio'] = (int)$filters['id_municipio'];
        }
        if (!empty($filters['id_institucion'])) {
            $conditions[] = "e.id_institucion = :id_institucion";
            $params[':id_institucion'] = (int)$filters['id_institucion'];
        }
        if (!empty($filters['tipo_estudiante'])) {
            $conditions[] = "e.tipo_estudiante = :tipo_estudiante";
            $params[':tipo_estudiante'] = $filters['tipo_estudiante'];
        }

        $whereClause = "";
        if (!empty($conditions)) {
            $whereClause = "WHERE " . implode(" AND ", $conditions);
        }

        $query = "SELECT e.*, s.descripcion_sexo, m.nombre_municipio, i.nombre_institucion 
                  FROM " . $this->table_name . " e
                  LEFT JOIN sexo s ON e.id_sexo = s.id_sexo
                  LEFT JOIN municipios m ON e.id_municipio = m.id_municipio
                  LEFT JOIN instituciones i ON e.id_institucion = i.id_institucion
                  $whereClause
                  ORDER BY e.id_estudiante DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function countEstudiantes($search = '', $filters = []) {
        $conditions = [];
        $params = [];

        // Search condition
        if ($search !== '') {
            $conditions[] = "(nombre LIKE :search OR apellido LIKE :search OR cedula LIKE :search)";
            $params[':search'] = "%$search%";
        }

        // Dropdown filters
        if (!empty($filters['id_municipio'])) {
            $conditions[] = "id_municipio = :id_municipio";
            $params[':id_municipio'] = (int)$filters['id_municipio'];
        }
        if (!empty($filters['id_institucion'])) {
            $conditions[] = "id_institucion = :id_institucion";
            $params[':id_institucion'] = (int)$filters['id_institucion'];
        }
        if (!empty($filters['tipo_estudiante'])) {
            $conditions[] = "tipo_estudiante = :tipo_estudiante";
            $params[':tipo_estudiante'] = $filters['tipo_estudiante'];
        }

        $whereClause = "";
        if (!empty($conditions)) {
            $whereClause = "WHERE " . implode(" AND ", $conditions);
        }

        $query = "SELECT COUNT(*) FROM " . $this->table_name . " $whereClause";
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getStudentStats() {
        $stats = [
            'total' => 0,
            'universitario' => 0,
            'liceista' => 0,
            'foraneo' => 0
        ];
        
        try {
            $query = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN tipo_estudiante = 'universitario' THEN 1 ELSE 0 END) as universitario,
                        SUM(CASE WHEN tipo_estudiante = 'liceista' THEN 1 ELSE 0 END) as liceista,
                        SUM(CASE WHEN tipo_estudiante = 'foraneo' THEN 1 ELSE 0 END) as foraneo
                      FROM estudiantes";
            $stmt = $this->conn->query($query);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $stats['total'] = (int)$row['total'];
                $stats['universitario'] = (int)$row['universitario'];
                $stats['liceista'] = (int)$row['liceista'];
                $stats['foraneo'] = (int)$row['foraneo'];
            }
        } catch (PDOException $e) {
            error_log("Error in getStudentStats: " . $e->getMessage());
        }
        
        return $stats;
    }

    public function getEstudianteById($id) {
        $query = "SELECT e.*, s.descripcion_sexo, m.nombre_municipio, i.nombre_institucion 
                  FROM " . $this->table_name . " e
                  LEFT JOIN sexo s ON e.id_sexo = s.id_sexo
                  LEFT JOIN municipios m ON e.id_municipio = m.id_municipio
                  LEFT JOIN instituciones i ON e.id_institucion = i.id_institucion
                  WHERE e.id_estudiante = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getMedicamentosById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM medicamentos WHERE id_estudiante = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPmaById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM pma_tp WHERE id_estudiante = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRepresentanteById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM representantes WHERE id_estudiante = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getMadreById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM madres WHERE id_estudiante = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPadreById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM padres WHERE id_estudiante = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSexos() {
        return $this->conn->query("SELECT * FROM sexo")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMunicipios() {
        return $this->conn->query("SELECT * FROM municipios")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getInstituciones() {
        return $this->conn->query("SELECT * FROM instituciones")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function storeEstudianteCompleto($data) {
        try {
            $this->conn->beginTransaction();

            // 1. Estudiante
            $nacimiento = new DateTime($data['fecha_nacimiento']);
            $hoy = new DateTime();
            $edad = $hoy->diff($nacimiento)->y;

            $query = "INSERT INTO estudiantes (apellido, nombre, cedula, fecha_nacimiento, edad, id_sexo, tz, tc, tp, id_municipio, id_institucion, tipo_estudiante) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                $data['apellido'], $data['nombre'], (isset($data['cedula']) && $data['cedula'] !== '') ? $data['cedula'] : null, $data['fecha_nacimiento'], $edad, 
                $data['id_sexo'], $data['talla_zapatos'] ?: 0, $data['talla_camisa'] ?: 0, $data['talla_pantalon'] ?: 0, 
                $data['id_municipio'], $data['id_institucion'], !empty($data['tipo_estudiante']) ? $data['tipo_estudiante'] : null
            ]);
            $id_estudiante = $this->conn->lastInsertId();

            // 2. Medicamentos
            if(!empty($data['medicamentos'])) {
                $q_med = "INSERT INTO medicamentos (nombre_medicamento, id_estudiante) VALUES (?, ?)";
                $stmt_med = $this->conn->prepare($q_med);
                $stmt_med->execute([$data['medicamentos'], $id_estudiante]);
            }

            // 3. PMA
            if(!empty($data['pma_principal']) || !empty($data['pma_primaria'])) {
                $q_pma = "INSERT INTO pma_tp (pma_principal, pma_primaria, id_estudiante) VALUES (?, ?, ?)";
                $stmt_pma = $this->conn->prepare($q_pma);
                $stmt_pma->execute([$data['pma_principal'] ?: '', $data['pma_primaria'] ?: '', $id_estudiante]);
            }

            // 4. Representante Principal
            $q_rep = "INSERT INTO representantes (apellidos_nombres, cedula, lugar_nacimiento, fecha_nacimiento, estado_civil, id_sexo, nacionalidad, telefono, id_estudiante) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_rep = $this->conn->prepare($q_rep);
            $stmt_rep->execute([
                $data['rep_apellidos_nombres'], $data['rep_cedula'], $data['rep_lugar_nacimiento'], 
                $data['rep_fecha_nacimiento'], $data['rep_estado_civil'], $data['rep_sexo'], 
                $data['rep_nacionalidad'], $data['rep_telefono'], $id_estudiante
            ]);

            // 5. Madre
            if(!empty($data['madre_apellidos_nombres'])) {
                $q_madre = "INSERT INTO madres (apellidos_nombres, cedula, edad, estado_civil, direccion, telefono, ocupacion, id_estudiante) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_madre = $this->conn->prepare($q_madre);
                $stmt_madre->execute([
                    $data['madre_apellidos_nombres'], $data['madre_cedula'] ?: '', $data['madre_edad'] ?: 0, 
                    $data['madre_estado_civil'] ?: '', $data['madre_direccion'] ?: '', $data['madre_telefono'] ?: '', 
                    $data['madre_ocupacion'] ?: '', $id_estudiante
                ]);
            }

            // 6. Padre
            if(!empty($data['padre_apellidos_nombres'])) {
                $q_padre = "INSERT INTO padres (apellidos_nombres, cedula, edad, estado_civil, direccion, telefono, ocupacion, id_estudiante) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_padre = $this->conn->prepare($q_padre);
                $stmt_padre->execute([
                    $data['padre_apellidos_nombres'], $data['padre_cedula'] ?: '', $data['padre_edad'] ?: 0, 
                    $data['padre_estado_civil'] ?: '', $data['padre_direccion'] ?: '', $data['padre_telefono'] ?: '', 
                    $data['padre_ocupacion'] ?: '', $id_estudiante
                ]);
            }

            $this->conn->commit();
            return true;
        } catch(Exception $e) {
            $this->conn->rollBack();
            error_log("Error saving full student: " . $e->getMessage());
            return false;
        }
    }

    public function updateEstudianteCompleto($id, $data) {
        try {
            $this->conn->beginTransaction();

            $nacimiento = new DateTime($data['fecha_nacimiento']);
            $hoy = new DateTime();
            $edad = $hoy->diff($nacimiento)->y;

            $query = "UPDATE estudiantes SET apellido = ?, nombre = ?, cedula = ?, fecha_nacimiento = ?, edad = ?, id_sexo = ?, tz = ?, tc = ?, tp = ?, id_municipio = ?, id_institucion = ?, tipo_estudiante = ? WHERE id_estudiante = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                $data['apellido'], $data['nombre'], (isset($data['cedula']) && $data['cedula'] !== '') ? $data['cedula'] : null, $data['fecha_nacimiento'], $edad, 
                $data['id_sexo'], $data['talla_zapatos'] ?: 0, $data['talla_camisa'] ?: 0, $data['talla_pantalon'] ?: 0, 
                $data['id_municipio'], $data['id_institucion'], !empty($data['tipo_estudiante']) ? $data['tipo_estudiante'] : null, $id
            ]);

            // Medicamentos (Delete and Re-insert to handle optionality)
            $this->conn->prepare("DELETE FROM medicamentos WHERE id_estudiante = ?")->execute([$id]);
            if(!empty($data['medicamentos'])) {
                $q_med = "INSERT INTO medicamentos (nombre_medicamento, id_estudiante) VALUES (?, ?)";
                $stmt_med = $this->conn->prepare($q_med);
                $stmt_med->execute([$data['medicamentos'], $id]);
            }

            // PMA
            $this->conn->prepare("DELETE FROM pma_tp WHERE id_estudiante = ?")->execute([$id]);
            if(!empty($data['pma_principal']) || !empty($data['pma_primaria'])) {
                $q_pma = "INSERT INTO pma_tp (pma_principal, pma_primaria, id_estudiante) VALUES (?, ?, ?)";
                $stmt_pma = $this->conn->prepare($q_pma);
                $stmt_pma->execute([$data['pma_principal'] ?: '', $data['pma_primaria'] ?: '', $id]);
            }

            // Representante Principal
            $this->conn->prepare("DELETE FROM representantes WHERE id_estudiante = ?")->execute([$id]);
            $q_rep = "INSERT INTO representantes (apellidos_nombres, cedula, lugar_nacimiento, fecha_nacimiento, estado_civil, id_sexo, nacionalidad, telefono, id_estudiante) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_rep = $this->conn->prepare($q_rep);
            $stmt_rep->execute([
                $data['rep_apellidos_nombres'], $data['rep_cedula'], $data['rep_lugar_nacimiento'], 
                $data['rep_fecha_nacimiento'], $data['rep_estado_civil'], $data['rep_sexo'], 
                $data['rep_nacionalidad'], $data['rep_telefono'], $id
            ]);

            // Madre
            $this->conn->prepare("DELETE FROM madres WHERE id_estudiante = ?")->execute([$id]);
            if(!empty($data['madre_apellidos_nombres'])) {
                $q_madre = "INSERT INTO madres (apellidos_nombres, cedula, edad, estado_civil, direccion, telefono, ocupacion, id_estudiante) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_madre = $this->conn->prepare($q_madre);
                $stmt_madre->execute([
                    $data['madre_apellidos_nombres'], $data['madre_cedula'] ?: '', $data['madre_edad'] ?: 0, 
                    $data['madre_estado_civil'] ?: '', $data['madre_direccion'] ?: '', $data['madre_telefono'] ?: '', 
                    $data['madre_ocupacion'] ?: '', $id
                ]);
            }

            // Padre
            $this->conn->prepare("DELETE FROM padres WHERE id_estudiante = ?")->execute([$id]);
            if(!empty($data['padre_apellidos_nombres'])) {
                $q_padre = "INSERT INTO padres (apellidos_nombres, cedula, edad, estado_civil, direccion, telefono, ocupacion, id_estudiante) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_padre = $this->conn->prepare($q_padre);
                $stmt_padre->execute([
                    $data['padre_apellidos_nombres'], $data['padre_cedula'] ?: '', $data['padre_edad'] ?: 0, 
                    $data['padre_estado_civil'] ?: '', $data['padre_direccion'] ?: '', $data['padre_telefono'] ?: '', 
                    $data['padre_ocupacion'] ?: '', $id
                ]);
            }

            $this->conn->commit();
            return true;
        } catch(Exception $e) {
            $this->conn->rollBack();
            error_log("Error updating full student: " . $e->getMessage());
            return false;
        }
    }

    // ==================== PERFIL: REPRESENTANTES (LEGACY) ====================

    /**
     * Obtener TODOS los representantes de un estudiante
     */
    public function getAllRepresentantes($id_estudiante) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM representantes WHERE id_estudiante = :id");
            $stmt->bindValue(':id', $id_estudiante, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return [];
        }
    }

    public function getRepresentante($id_representante) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM representantes WHERE id_representante = :id LIMIT 1");
            $stmt->bindValue(':id', $id_representante, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    public function createRepresentante($data) {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO representantes (apellidos_nombres, cedula, telefono, id_estudiante, lugar_nacimiento, fecha_nacimiento, estado_civil, id_sexo, nacionalidad) 
                 VALUES (:nombre, :cedula, :tel, :est, '', '2000-01-01', '', 1, '')"
            );
            $stmt->execute([
                ':nombre' => $data['apellidos_nombres'],
                ':cedula' => $data['cedula'],
                ':tel' => $data['telefono'],
                ':est' => $data['id_estudiante']
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    public function updateRepresentante($id, $data) {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE representantes SET apellidos_nombres = :nombre, cedula = :cedula, telefono = :tel 
                 WHERE id_representante = :id"
            );
            return $stmt->execute([
                ':nombre' => $data['apellidos_nombres'],
                ':cedula' => $data['cedula'],
                ':tel' => $data['telefono'],
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteRepresentante($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM representantes WHERE id_representante = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    // ==================== PERFIL: MEDICAMENTOS ====================

    public function getAllMedicamentos($id_estudiante) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM medicamentos WHERE id_estudiante = :id");
            $stmt->bindValue(':id', $id_estudiante, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return [];
        }
    }

    public function getMedicamento($id_medicamento) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM medicamentos WHERE id_medicamento = :id LIMIT 1");
            $stmt->bindValue(':id', $id_medicamento, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    public function createMedicamento($nombre, $id_estudiante) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO medicamentos (nombre_medicamento, id_estudiante) VALUES (:nombre, :est)");
            return $stmt->execute([':nombre' => $nombre, ':est' => $id_estudiante]);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    public function updateMedicamento($id, $nombre) {
        try {
            $stmt = $this->conn->prepare("UPDATE medicamentos SET nombre_medicamento = :nombre WHERE id_medicamento = :id");
            return $stmt->execute([':nombre' => $nombre, ':id' => $id]);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteMedicamento($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM medicamentos WHERE id_medicamento = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    // ==================== PERFIL: PMA ====================

    public function getAllPma($id_estudiante) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM pma_tp WHERE id_estudiante = :id");
            $stmt->bindValue(':id', $id_estudiante, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return [];
        }
    }

    public function getPma($id_pma) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM pma_tp WHERE id_pma_tp = :id LIMIT 1");
            $stmt->bindValue(':id', $id_pma, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    public function createPma($data) {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO pma_tp (pma_principal, pma_primaria, id_estudiante) VALUES (:principal, :primaria, :est)"
            );
            return $stmt->execute([
                ':principal' => $data['pma_principal'],
                ':primaria' => $data['pma_primaria'],
                ':est' => $data['id_estudiante']
            ]);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    public function updatePma($id, $data) {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE pma_tp SET pma_principal = :principal, pma_primaria = :primaria WHERE id_pma_tp = :id"
            );
            return $stmt->execute([
                ':principal' => $data['pma_principal'],
                ':primaria' => $data['pma_primaria'],
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    public function deletePma($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM pma_tp WHERE id_pma_tp = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    // ==================== PERFIL: CLASES DEL ESTUDIANTE ====================

    public function getClasesDelEstudiante($id_estudiante) {
        try {
            $query = "SELECT ec.id_estudiante_clase, c.id_clase, c.clase_nombre, 
                             p.nombre_profesor, g.nombre_grado 
                      FROM estudiantes_clases ec 
                      LEFT JOIN clases c ON c.id_clase = ec.id_clase 
                      LEFT JOIN profesores p ON c.id_profesor = p.id_profesor 
                      LEFT JOIN grados g ON g.id_grado = c.id_grado 
                      WHERE ec.id_estudiante = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id_estudiante, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return [];
        }
    }

    public function addClaseEstudiante($id_estudiante, $id_clase) {
        try {
            // Verificar si ya está
            $check = $this->conn->prepare(
                "SELECT id_estudiante_clase FROM estudiantes_clases WHERE id_estudiante = :est AND id_clase = :cls"
            );
            $check->execute([':est' => $id_estudiante, ':cls' => $id_clase]);
            if ($check->fetch()) {
                return 'already_enrolled';
            }

            $stmt = $this->conn->prepare(
                "INSERT INTO estudiantes_clases (id_estudiante, id_clase) VALUES (:est, :cls)"
            );
            return $stmt->execute([':est' => $id_estudiante, ':cls' => $id_clase]);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    public function updateClaseEstudiante($id_estudiante_clase, $id_estudiante, $id_clase) {
        try {
            // Verificar que no se duplique
            $check = $this->conn->prepare(
                "SELECT * FROM estudiantes_clases WHERE id_estudiante = :est AND id_clase = :cls AND id_estudiante_clase != :id"
            );
            $check->execute([':est' => $id_estudiante, ':cls' => $id_clase, ':id' => $id_estudiante_clase]);
            if ($check->fetch()) {
                return 'already_enrolled';
            }

            $stmt = $this->conn->prepare(
                "UPDATE estudiantes_clases SET id_clase = :cls WHERE id_estudiante_clase = :id"
            );
            return $stmt->execute([':cls' => $id_clase, ':id' => $id_estudiante_clase]);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    public function removeClaseEstudiante($id_estudiante_clase) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM estudiantes_clases WHERE id_estudiante_clase = :id");
            $stmt->bindValue(':id', $id_estudiante_clase, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    // ==================== UTILIDADES ====================

    /**
     * Buscar estudiante por cédula exacta
     */
    public function searchByCedula($cedula) {
        try {
            $stmt = $this->conn->prepare("SELECT id_estudiante FROM estudiantes WHERE cedula = :cedula LIMIT 1");
            $stmt->bindParam(':cedula', $cedula);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar solo los datos básicos del estudiante (desde perfil legacy)
     */
    public function updateBasico($id, $data) {
        try {
            $nacimiento = new DateTime($data['fecha_nacimiento']);
            $hoy = new DateTime();
            $edad = $hoy->diff($nacimiento)->y;

            $query = "UPDATE estudiantes SET apellido = :apellido, nombre = :nombre, cedula = :cedula, 
                      fecha_nacimiento = :fecha, edad = :edad, id_sexo = :sexo, 
                      tz = :tz, tc = :tc, tp = :tp, id_municipio = :mun, id_institucion = :inst,
                      tipo_estudiante = :tipo_estudiante 
                      WHERE id_estudiante = :id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':apellido' => $data['apellido'],
                ':nombre' => $data['nombre'],
                ':cedula' => (isset($data['cedula']) && $data['cedula'] !== '') ? $data['cedula'] : null,
                ':fecha' => $data['fecha_nacimiento'],
                ':edad' => $edad,
                ':sexo' => $data['id_sexo'],
                ':tz' => $data['tz'] ?: 0,
                ':tc' => $data['tc'] ?: 0,
                ':tp' => $data['tp'] ?: 0,
                ':mun' => $data['id_municipio'],
                ':inst' => $data['id_institucion'],
                ':tipo_estudiante' => !empty($data['tipo_estudiante']) ? $data['tipo_estudiante'] : null,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            if ($e->getCode() == 23000) return 'duplicate';
            return false;
        }
    }

    /**
     * Eliminar estudiante y TODOS sus datos relacionados (usando cascada de la DB)
     */
    public function deleteCompleto($id) {
        try {
            // La base de datos ya tiene ON DELETE CASCADE en las tablas relacionadas
            // por lo que solo necesitamos eliminar al estudiante.
            $stmt = $this->conn->prepare("DELETE FROM estudiantes WHERE id_estudiante = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en deleteCompleto: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener todas las clases disponibles (para dropdown)
     */
    public function getAllClases() {
        try {
            $stmt = $this->conn->prepare(
                "SELECT c.id_clase, c.clase_nombre, g.nombre_grado 
                 FROM clases c 
                 LEFT JOIN grados g ON c.id_grado = g.id_grado 
                 ORDER BY c.clase_nombre"
            );
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return [];
        }
    }
}
?>
