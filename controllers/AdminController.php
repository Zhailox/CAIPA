<?php
require_once __DIR__ . '/../config/Database.php';

class AdminController {
    public function index() {
        if(!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "Acceso denegado. Se requieren permisos de administrador.";
            header("Location: index.php?action=dashboard");
            exit();
        }
        
        require_once __DIR__ . '/../views/admin/index.php';
    }

    public function backup() {
        if(!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
            header("Location: index.php?action=dashboard");
            exit();
        }

        try {
            $db = (new Database())->getConnection();
            $tables = [];
            $query = $db->query("SHOW TABLES");
            while($row = $query->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }

            $sql = "-- CAIPA Database Backup\n-- Date: " . date("Y-m-d H:i:s") . "\n\n";

            foreach($tables as $table) {
                $result = $db->query("SELECT * FROM `$table`");
                $numFields = $result->columnCount();

                $sql .= "DROP TABLE IF EXISTS `$table`;\n";
                $row2 = $db->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_NUM);
                $sql .= $row2[1] . ";\n\n";

                while($row = $result->fetch(PDO::FETCH_NUM)) {
                    $sql .= "INSERT INTO `$table` VALUES(";
                    for($j=0; $j<$numFields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = str_replace("\n","\\n",$row[$j]);
                        if(isset($row[$j])) { $sql .= '"'.$row[$j].'"'; } else { $sql .= 'NULL'; }
                        if($j < ($numFields-1)) { $sql .= ','; }
                    }
                    $sql .= ");\n";
                }
                $sql .= "\n\n";
            }

            $filename = 'backup_caipa_' . date("Y-m-d_H-i-s") . '.sql';
            header('Content-Type: application/sql');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            echo $sql;
            exit();
        } catch(Exception $e) {
            $_SESSION['error'] = "Error al generar respaldo: " . $e->getMessage();
            header("Location: index.php?action=admin");
            exit();
        }
    }
}
?>
