<?php
require_once 'User.php';

class Teacher {
    private $conn;
    private $table = 'teachers';
    private $user_obj;

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
        $this->user_obj = new User();
    }

    public function create($data) {
        $this->conn->beginTransaction();

        try {
            $user_id = $this->user_obj->register($data['username'], $data['email'], $data['password'], 'teacher');
            
            if (!$user_id) {
                throw new Exception("Failed to create user account.");
            }

            $query = "INSERT INTO " . $this->table . " 
                    (user_id, first_name, last_name, qualification, phone, address, hire_date)
                    VALUES 
                    (:user_id, :first_name, :last_name, :qualification, :phone, :address, :hire_date)";
            
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':first_name', $data['first_name']);
            $stmt->bindParam(':last_name', $data['last_name']);
            $stmt->bindParam(':qualification', $data['qualification']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':address', $data['address']);
            $stmt->bindParam(':hire_date', $data['hire_date']);

            if (!$stmt->execute()) {
                throw new Exception("Failed to create teacher profile.");
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function read() {
        $query = "SELECT t.*, u.email, u.username 
                  FROM " . $this->table . " t
                  JOIN users u ON t.user_id = u.id
                  ORDER BY t.last_name ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getTeacherById($teacher_id) {
        $query = "SELECT t.*, u.email, u.username 
                  FROM " . $this->table . " t
                  JOIN users u ON t.user_id = u.id
                  WHERE t.teacher_id = :teacher_id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':teacher_id', $teacher_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Update teacher details
    public function update($teacher_id, $data) {
        $this->conn->beginTransaction();

        try {
            // 1. Update User Email
            if (isset($data['email'])) {
                $checkQuery = "SELECT user_id FROM " . $this->table . " WHERE teacher_id = ?";
                $checkStmt = $this->conn->prepare($checkQuery);
                $checkStmt->execute([$teacher_id]);
                $userId = $checkStmt->fetchColumn();

                if ($userId) {
                    $userUpdate = "UPDATE users SET email = :email, username = :username WHERE id = :id";
                    $userStmt = $this->conn->prepare($userUpdate);
                    $userStmt->execute([
                        ':email' => $data['email'], 
                        ':username' => $data['email'], 
                        ':id' => $userId
                    ]);
                }
            }

            // 2. Update Teacher Record
            $query = "UPDATE " . $this->table . " 
                    SET first_name = :first_name,
                        last_name = :last_name,
                        qualification = :qualification,
                        phone = :phone,
                        address = :address,
                        hire_date = :hire_date
                    WHERE teacher_id = :teacher_id";
            
            $stmt = $this->conn->prepare($query);

            if (!$stmt->execute([
                ':first_name' => $data['first_name'],
                ':last_name' => $data['last_name'],
                ':qualification' => $data['qualification'],
                ':phone' => $data['phone'],
                ':address' => $data['address'],
                ':hire_date' => $data['hire_date'],
                ':teacher_id' => $teacher_id
            ])) {
                throw new Exception("Failed to update teacher profile.");
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            // Re-throw exception to be caught by the calling script
            throw $e;
        }
    }
}
?>
