<?php
require_once 'Database.php';

class Settings {
    private $conn;
    private $table = 'settings';

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public function get($key) {
        $query = "SELECT setting_value FROM " . $this->table . " WHERE setting_key = :key";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':key' => $key]);
        return $stmt->fetchColumn();
    }

    public function update($key, $value) {
        $query = "INSERT INTO " . $this->table . " (setting_key, setting_value) VALUES (:key, :val1)
                  ON DUPLICATE KEY UPDATE setting_value = :val2";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':key' => $key, ':val1' => $value, ':val2' => $value]);
    }
    
    public function getAll() {
        $query = "SELECT setting_key, setting_value FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
}
?>
