<?php
require_once 'User.php';

class Parents {
    private $conn;
    private $table = 'parents';
    private $user_obj;

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
        $this->user_obj = new User();
    }

    public function read() {
        $query = "SELECT p.*, u.email, u.username 
                  FROM " . $this->table . " p
                  JOIN users u ON p.user_id = u.id
                  ORDER BY p.father_name ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    // Create method (omitted for now unless needed for bulk parent creation)
}
?>
