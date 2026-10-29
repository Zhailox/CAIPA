<?php
require_once 'BaseModel.php';

class Personal extends BaseModel {
    protected $table_name = "personal";

    public function getPersonalCompleto($search = '', $limit = 10, $offset = 0, $filters = []) {
        $conditions = [];
        $params = [];

        if ($search !== '') {
            $conditions[] = "(p.nombres_apellidos LIKE :search OR p.cedula LIKE :search)";
            $params[':search'] = "%$search%";
        }

        if (!empty($filters['tipo_personal'])) {
            $conditions[] = "p.tipo_personal = :tipo_personal";
            $params[':tipo_personal'] = $filters['tipo_personal'];
        }

        if (!empty($filters['id_dependencia'])) {
            $conditions[] = "p.id_dependencia = :id_dependencia";
            $params[':id_dependencia'] = (int)$filters['id_dependencia'];
        }

        $whereClause = "";
        if (!empty($conditions)) {
            $whereClause = "WHERE " . implode(" AND ", $conditions);
        }

        $query = "SELECT p.*, s.descripcion_sexo, d.nombre_dependencia 
                  FROM " . $this->table_name . " p
                  LEFT JOIN sexo s ON p.id_sexo = s.id_sexo
                  LEFT JOIN dependencias d ON p.id_dependencia = d.id_dependencia
                  $whereClause
                  ORDER BY p.id_personal DESC 
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

    public function countPersonal($search = '', $filters = []) {
        $conditions = [];
        $params = [];

        if ($search !== '') {
            $conditions[] = "(nombres_apellidos LIKE :search OR cedula LIKE :search)";
            $params[':search'] = "%$search%";
        }

        if (!empty($filters['tipo_personal'])) {
            $conditions[] = "tipo_personal = :tipo_personal";
            $params[':tipo_personal'] = $filters['tipo_personal'];
        }

        if (!empty($filters['id_dependencia'])) {
            $conditions[] = "id_dependencia = :id_dependencia";
            $params[':id_dependencia'] = (int)$filters['id_dependencia'];
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

    public function getPersonalStats() {
        $stats = [
            'total' => 0,
            'docente' => 0,
            'administrativo' => 0,
            'obrero' => 0
        ];
        
        try {
            $query = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN tipo_personal = 'Docente' THEN 1 ELSE 0 END) as docente,
                        SUM(CASE WHEN tipo_personal = 'Administrativo' THEN 1 ELSE 0 END) as administrativo,
                        SUM(CASE WHEN tipo_personal = 'Obrero' THEN 1 ELSE 0 END) as obrero
                      FROM personal";
            $stmt = $this->conn->query($query);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $stats['total'] = (int)$row['total'];
                $stats['docente'] = (int)$row['docente'];
                $stats['administrativo'] = (int)$row['administrativo'];
                $stats['obrero'] = (int)$row['obrero'];
            }
        } catch (PDOException $e) {
            error_log("Error in getPersonalStats: " . $e->getMessage());
        }
        
        return $stats;
    }

    public function getPersonalById($id) {
        $query = "SELECT p.*, s.descripcion_sexo, d.nombre_dependencia 
                  FROM " . $this->table_name . " p
                  LEFT JOIN sexo s ON p.id_sexo = s.id_sexo
                  LEFT JOIN dependencias d ON p.id_dependencia = d.id_dependencia
                  WHERE p.id_personal = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSexos() {
        return $this->conn->query("SELECT * FROM sexo")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDependencias() {
        return $this->conn->query("SELECT * FROM dependencias")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTiposPersonal() {
        return ['Docente', 'Administrativo', 'Obrero'];
    }

    public function getInstituciones() {
        return $this->conn->query("SELECT * FROM instituciones")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function storePersonalCompleto($data) {
        try {
            $nacimiento = new DateTime($data['fecha_nacimiento']);
            $hoy = new DateTime();
            $edad = $hoy->diff($nacimiento)->y;

            $query = "INSERT INTO personal (
                nombres_apellidos, cedula, fecha_nacimiento, lugar_nacimiento, direccion, 
                telefono_habitacion, telefono_celular, telefono_familiar, correo, id_sexo, edad,
                titulo_pregrado, postgrado, estudia_actualmente, estudios_actuales,
                tipo_personal, fecha_ingreso_mppe, codigo_rac, cargo, carga_horaria, 
                id_dependencia, institucion_pertenece, tipo_cuenta_nomina, numero_cuenta_nomina, 
                matricula_docente, situacion_trabajador, otros_datos
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                $data['nombres_apellidos'], $data['cedula'], $data['fecha_nacimiento'], $data['lugar_nacimiento'], $data['direccion'],
                $data['telefono_habitacion'], $data['telefono_celular'], $data['telefono_familiar'], $data['correo'], $data['id_sexo'], $edad,
                $data['titulo_pregrado'], $data['postgrado'], isset($data['estudia_actualmente']) ? 1 : 0, $data['estudios_actuales'],
                $data['tipo_personal'], $data['fecha_ingreso_mppe'] ?: null, $data['codigo_rac'], $data['cargo'], $data['carga_horaria'] ?: 0,
                $data['id_dependencia'], $data['institucion_pertenece'], $data['tipo_cuenta_nomina'], $data['numero_cuenta_nomina'],
                !empty($data['matricula_docente']) ? $data['matricula_docente'] : null,
                $data['situacion_trabajador'], $data['otros_datos']
            ]);
        } catch(PDOException $e) {
            error_log("Error saving personal: " . $e->getMessage());
            return false;
        }
    }

    public function updatePersonalCompleto($id, $data) {
        try {
            $nacimiento = new DateTime($data['fecha_nacimiento']);
            $hoy = new DateTime();
            $edad = $hoy->diff($nacimiento)->y;

            $query = "UPDATE personal SET 
                nombres_apellidos = ?, cedula = ?, fecha_nacimiento = ?, lugar_nacimiento = ?, direccion = ?, 
                telefono_habitacion = ?, telefono_celular = ?, telefono_familiar = ?, correo = ?, id_sexo = ?, edad = ?,
                titulo_pregrado = ?, postgrado = ?, estudia_actualmente = ?, estudios_actuales = ?,
                tipo_personal = ?, fecha_ingreso_mppe = ?, codigo_rac = ?, cargo = ?, carga_horaria = ?, 
                id_dependencia = ?, institucion_pertenece = ?, tipo_cuenta_nomina = ?, numero_cuenta_nomina = ?, 
                matricula_docente = ?, situacion_trabajador = ?, otros_datos = ?
                WHERE id_personal = ?";
            
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                $data['nombres_apellidos'], $data['cedula'], $data['fecha_nacimiento'], $data['lugar_nacimiento'], $data['direccion'],
                $data['telefono_habitacion'], $data['telefono_celular'], $data['telefono_familiar'], $data['correo'], $data['id_sexo'], $edad,
                $data['titulo_pregrado'], $data['postgrado'], isset($data['estudia_actualmente']) ? 1 : 0, $data['estudios_actuales'],
                $data['tipo_personal'], $data['fecha_ingreso_mppe'] ?: null, $data['codigo_rac'], $data['cargo'], $data['carga_horaria'] ?: 0,
                $data['id_dependencia'], $data['institucion_pertenece'], $data['tipo_cuenta_nomina'], $data['numero_cuenta_nomina'],
                !empty($data['matricula_docente']) ? $data['matricula_docente'] : null,
                $data['situacion_trabajador'], $data['otros_datos'],
                $id
            ]);
        } catch(PDOException $e) {
            error_log("Error updating personal: " . $e->getMessage());
            return false;
        }
    }
}
?>
