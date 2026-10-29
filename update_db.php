<?php
require_once 'c:/wamp64/www/CAIPA/config/Database.php';

$db = new Database();
$conn = $db->getConnection();

try {
    // Alter clases to allow NULL for id_profesor
    $conn->exec("ALTER TABLE clases MODIFY id_profesor INT NULL");
    
    // Clear id_profesor in clases
    $conn->exec("UPDATE clases SET id_profesor = NULL");
    
    // Delete existing profesores
    $conn->exec("DELETE FROM profesores");
    
    // Check if nombre_profesor exists before dropping
    $result = $conn->query("SHOW COLUMNS FROM profesores LIKE 'nombre_profesor'");
    if ($result->rowCount() > 0) {
        $conn->exec("ALTER TABLE profesores DROP COLUMN nombre_profesor");
    }
    
    // Check if id_personal exists before adding
    $result = $conn->query("SHOW COLUMNS FROM profesores LIKE 'id_personal'");
    if ($result->rowCount() == 0) {
        $conn->exec("ALTER TABLE profesores ADD COLUMN id_personal INT NOT NULL");
        // Add foreign key constraint
        $conn->exec("ALTER TABLE profesores ADD CONSTRAINT fk_prof_personal FOREIGN KEY (id_personal) REFERENCES personal(id_personal)");
    }
    
    echo "Database schema updated successfully.\n";
} catch (PDOException $e) {
    echo "Error updating schema: " . $e->getMessage() . "\n";
}
?>
