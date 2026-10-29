<?php
require_once __DIR__ . '/../config/Database.php';

if(file_exists(__DIR__ . '/../fpdf/fpdf.php')) {
    require_once __DIR__ . '/../fpdf/fpdf.php';
}

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(80);
        $this->Cell(30, 10, 'CAIPA - Reporte Oficial', 0, 0, 'C');
        $this->Ln(20);
    }
    
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina '.$this->PageNo().'/{nb}', 0, 0, 'C');
    }
}

class ReporteController {
    public function index() {
        if(empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        require_once __DIR__ . '/../views/reportes/index.php';
    }

    public function generate() {
        if($_SERVER["REQUEST_METHOD"] != "POST") {
            header("Location: index.php?action=reportes"); exit();
        }

        if(!class_exists('FPDF')) {
            $_SESSION['error'] = "La librería FPDF no se encuentra instalada correctamente en /fpdf/fpdf.php.";
            header("Location: index.php?action=reportes"); exit();
        }

        $modulo = $_POST['modulo'] ?? '';
        $desde = $_POST['fecha_desde'] ?? '';
        $hasta = $_POST['fecha_hasta'] ?? '';
        $limite = $_POST['limite'] ?? 'all';
        $cedula = $_POST['cedula'] ?? '';

        $db = (new Database())->getConnection();

        // Build base query based on module
        $where = "WHERE 1=1";
        $params = [];

        if($modulo != 'ficha_estudiante' && !empty($desde)) {
            if($modulo == 'profesores') {
                $where .= " AND DATE(p.created_at) >= :desde";
            } elseif ($modulo == 'clases') {
                $where .= " AND DATE(c.created_at) >= :desde";
            } else {
                $where .= " AND DATE(created_at) >= :desde";
            }
            $params[':desde'] = $desde;
        }
        if($modulo != 'ficha_estudiante' && !empty($hasta)) {
            if($modulo == 'profesores') {
                $where .= " AND DATE(p.created_at) <= :hasta";
            } elseif ($modulo == 'clases') {
                $where .= " AND DATE(c.created_at) <= :hasta";
            } else {
                $where .= " AND DATE(created_at) <= :hasta";
            }
            $params[':hasta'] = $hasta;
        }

        $limit_sql = "";
        if($modulo != 'ficha_estudiante' && $limite != 'all') {
            $limit_sql = " LIMIT " . (int)$limite;
        }

        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);

        switch($modulo) {
            case 'estudiantes':
                $pdf->Cell(0, 10, 'Listado General de Estudiantes', 0, 1, 'C');
                $pdf->Ln(5);
                $query = "SELECT cedula, nombre, apellido, edad, DATE(created_at) as registro FROM estudiantes $where ORDER BY created_at DESC $limit_sql";
                $stmt = $db->prepare($query);
                $stmt->execute($params);

                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(30, 8, 'Cedula', 1);
                $pdf->Cell(80, 8, 'Nombres y Apellidos', 1);
                $pdf->Cell(20, 8, 'Edad', 1);
                $pdf->Cell(30, 8, 'Registro', 1);
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 10);
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $pdf->Cell(30, 8, $row['cedula'], 1);
                    $pdf->Cell(80, 8, mb_convert_encoding($row['nombre'].' '.$row['apellido'], 'ISO-8859-1', 'UTF-8'), 1);
                    $pdf->Cell(20, 8, $row['edad'], 1);
                    $pdf->Cell(30, 8, $row['registro'], 1);
                    $pdf->Ln();
                }
                break;

            case 'personal':
                $pdf->Cell(0, 10, 'Listado General de Personal', 0, 1, 'C');
                $pdf->Ln(5);
                $query = "SELECT cedula, nombres_apellidos, tipo_personal, DATE(created_at) as registro FROM personal $where ORDER BY created_at DESC $limit_sql";
                $stmt = $db->prepare($query);
                $stmt->execute($params);

                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(30, 8, 'Cedula', 1);
                $pdf->Cell(80, 8, 'Nombres y Apellidos', 1);
                $pdf->Cell(50, 8, 'Tipo Personal', 1);
                $pdf->Cell(30, 8, 'Registro', 1);
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 10);
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $pdf->Cell(30, 8, $row['cedula'], 1);
                    $pdf->Cell(80, 8, mb_convert_encoding($row['nombres_apellidos'], 'ISO-8859-1', 'UTF-8'), 1);
                    $pdf->Cell(50, 8, mb_convert_encoding($row['tipo_personal'], 'ISO-8859-1', 'UTF-8'), 1);
                    $pdf->Cell(30, 8, $row['registro'], 1);
                    $pdf->Ln();
                }
                break;

            case 'profesores':
                $pdf->Cell(0, 10, 'Listado General de Profesores', 0, 1, 'C');
                $pdf->Ln(5);
                $query = "SELECT p.id_profesor, per.nombres_apellidos, per.cedula, DATE(p.created_at) as registro FROM profesores p INNER JOIN personal per ON p.id_personal = per.id_personal $where ORDER BY p.created_at DESC $limit_sql";
                $stmt = $db->prepare($query);
                $stmt->execute($params);

                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(30, 8, 'Cedula', 1);
                $pdf->Cell(100, 8, 'Nombres y Apellidos', 1);
                $pdf->Cell(30, 8, 'Registro', 1);
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 10);
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $pdf->Cell(30, 8, $row['cedula'], 1);
                    $pdf->Cell(100, 8, mb_convert_encoding($row['nombres_apellidos'], 'ISO-8859-1', 'UTF-8'), 1);
                    $pdf->Cell(30, 8, $row['registro'], 1);
                    $pdf->Ln();
                }
                break;

            case 'clases':
                $pdf->Cell(0, 10, 'Listado de Clases Activas', 0, 1, 'C');
                $pdf->Ln(5);
                $query = "SELECT c.clase_nombre, g.nombre_grado, DATE(c.created_at) as registro FROM clases c LEFT JOIN grados g ON c.id_grado = g.id_grado $where ORDER BY c.created_at DESC $limit_sql";
                $stmt = $db->prepare($query);
                $stmt->execute($params);

                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(70, 8, 'Nombre de la Clase', 1);
                $pdf->Cell(60, 8, 'Grado', 1);
                $pdf->Cell(30, 8, 'Registro', 1);
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 10);
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $pdf->Cell(70, 8, mb_convert_encoding($row['clase_nombre'], 'ISO-8859-1', 'UTF-8'), 1);
                    $pdf->Cell(60, 8, mb_convert_encoding($row['nombre_grado'] ?? 'N/A', 'ISO-8859-1', 'UTF-8'), 1);
                    $pdf->Cell(30, 8, $row['registro'], 1);
                    $pdf->Ln();
                }
                break;

            case 'ficha_estudiante':
                if(empty($cedula)) {
                    $_SESSION['error'] = "Debes ingresar la cédula del estudiante.";
                    header("Location: index.php?action=reportes"); exit();
                }
                $query = "SELECT * FROM estudiantes WHERE cedula = :cedula LIMIT 1";
                $stmt = $db->prepare($query);
                $stmt->execute([':cedula' => $cedula]);
                $est = $stmt->fetch(PDO::FETCH_ASSOC);

                if(!$est) {
                    $_SESSION['error'] = "Estudiante no encontrado.";
                    header("Location: index.php?action=reportes"); exit();
                }

                $pdf->Cell(0, 10, 'Ficha Oficial del Estudiante', 0, 1, 'C');
                $pdf->Ln(5);
                
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(40, 8, 'Cedula:', 1);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(0, 8, $est['cedula'], 1);
                $pdf->Ln();
                
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(40, 8, 'Nombres y Apellidos:', 1);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(0, 8, mb_convert_encoding($est['nombre'] . ' ' . $est['apellido'], 'ISO-8859-1', 'UTF-8'), 1);
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(40, 8, 'F. Nacimiento:', 1);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(0, 8, $est['fecha_nacimiento'] . ' (' . $est['edad'] . ' anos)', 1);
                $pdf->Ln();

                // Representantes
                $queryRep = "SELECT * FROM representantes WHERE id_estudiante = ?";
                $stmtRep = $db->prepare($queryRep);
                $stmtRep->execute([$est['id_estudiante']]);
                
                $pdf->Ln(5);
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->Cell(0, 8, 'Representantes Legales', 0, 1, 'L');
                
                while($rep = $stmtRep->fetch(PDO::FETCH_ASSOC)) {
                    $pdf->SetFont('Arial', 'B', 10);
                    $pdf->Cell(40, 8, 'Nombre/Cedula:', 1);
                    $pdf->SetFont('Arial', '', 10);
                    $pdf->Cell(0, 8, mb_convert_encoding($rep['apellidos_nombres'] . ' - ' . $rep['cedula'], 'ISO-8859-1', 'UTF-8'), 1);
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', 10);
                    $pdf->Cell(40, 8, 'Telefono:', 1);
                    $pdf->SetFont('Arial', '', 10);
                    $pdf->Cell(0, 8, $rep['telefono'], 1);
                    $pdf->Ln();
                }

                break;
        }

        ob_clean();
        $pdf->Output('I', 'Reporte_CAIPA_' . $modulo . '.pdf');
        exit();
    }
}
?>
