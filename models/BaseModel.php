<?php
require_once __DIR__ . '/../config/Database.php';

abstract class BaseModel {
    protected $conn;
    protected $table_name;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    public function readById($id, $primary_key) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE " . $primary_key . " = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        
        $query = "INSERT INTO " . $this->table_name . " ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($query);
        
        $i = 1;
        foreach($data as $key => $value) {
            $stmt->bindValue($i, htmlspecialchars(strip_tags($value)));
            $i++;
        }
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }
    
    public function update($id, $primary_key, $data) {
        $setQuery = "";
        foreach(array_keys($data) as $key) {
            $setQuery .= $key . " = ?, ";
        }
        $setQuery = rtrim($setQuery, ", ");
        
        $query = "UPDATE " . $this->table_name . " SET " . $setQuery . " WHERE " . $primary_key . " = ?";
        $stmt = $this->conn->prepare($query);
        
        $i = 1;
        foreach($data as $key => $value) {
            $stmt->bindValue($i, htmlspecialchars(strip_tags($value)));
            $i++;
        }
        $stmt->bindValue($i, $id);
        
        return $stmt->execute();
    }

    public function delete($id, $primary_key) {
        $query = "DELETE FROM " . $this->table_name . " WHERE " . $primary_key . " = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        return $stmt->execute();
    }
}
?>
