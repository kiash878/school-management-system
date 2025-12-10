<?php
require_once 'User.php';

class Student {
    private $conn;
    private $table = 'students';
    private $user_obj;

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
        $this->user_obj = new User();
    }

    // Create new student (Admission)
    public function create($data) {
        $this->conn->beginTransaction();

        try {
            // 1. Create User Account
            $user_id = $this->user_obj->register($data['username'], $data['email'], $data['password'], 'student');
            
            if (!$user_id) {
                throw new Exception("Failed to create user account.");
            }

            // 2. Create Student Record
            $query = "INSERT INTO " . $this->table . " 
                    (user_id, parent_id, first_name, last_name, admission_no, dob, gender, address, class_id, section_id, admission_date)
                    VALUES 
                    (:user_id, :parent_id, :first_name, :last_name, :admission_no, :dob, :gender, :address, :class_id, :section_id, :admission_date)";
            
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':parent_id', $data['parent_id']);
            $stmt->bindParam(':first_name', $data['first_name']);
            $stmt->bindParam(':last_name', $data['last_name']);
            $stmt->bindParam(':admission_no', $data['admission_no']);
            $stmt->bindParam(':dob', $data['dob']);
            $stmt->bindParam(':gender', $data['gender']);
            $stmt->bindParam(':address', $data['address']);
            $stmt->bindParam(':class_id', $data['class_id']);
            $stmt->bindParam(':section_id', $data['section_id']);
            $stmt->bindParam(':admission_date', $data['admission_date']);

            if (!$stmt->execute()) {
                throw new Exception("Failed to create student profile.");
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            // Log error or handle it
            return false;
        }
    }

    // Read all students (with optional filtering by class)
    public function read($class_id = null) {
        $query = "SELECT s.*, u.email, u.username, c.class_name, sc.section_name,
                  CONCAT(p.father_name, ' & ', p.mother_name) as parent_name
                  FROM " . $this->table . " s
                  JOIN users u ON s.user_id = u.id
                  LEFT JOIN classes c ON s.class_id = c.class_id
                  LEFT JOIN sections sc ON s.section_id = sc.section_id
                  LEFT JOIN parents p ON s.parent_id = p.parent_id";
        
        if ($class_id) {
            $query .= " WHERE s.class_id = :class_id";
        }
        
        $query .= " ORDER BY s.admission_no ASC";

        $stmt = $this->conn->prepare($query);
        
        if ($class_id) {
            $stmt->bindParam(':class_id', $class_id);
        }
        
        $stmt->execute();
        return $stmt;
    }

    // Get single student details
    public function getStudentById($student_id) {
        $query = "SELECT s.*, u.email, u.username, c.class_name, sc.section_name 
                  FROM " . $this->table . " s
                  JOIN users u ON s.user_id = u.id
                  LEFT JOIN classes c ON s.class_id = c.class_id
                  LEFT JOIN sections sc ON s.section_id = sc.section_id
                  WHERE s.student_id = :student_id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get student details by user ID (for profile page)
    public function getStudentByUserId($user_id) {
        $query = "SELECT s.*, u.email, u.username, c.class_name, sc.section_name 
                  FROM " . $this->table . " s
                  JOIN users u ON s.user_id = u.id
                  LEFT JOIN classes c ON s.class_id = c.class_id
                  LEFT JOIN sections sc ON s.section_id = sc.section_id
                  WHERE s.user_id = :user_id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update student profile information
    public function updateProfile($user_id, $first_name, $last_name, $email, $address) {
        $this->conn->beginTransaction();

        try {
            // 1. Update user email
            $userQuery = "UPDATE users SET email = :email, username = :username WHERE id = :user_id";
            $userStmt = $this->conn->prepare($userQuery);
            $userStmt->execute([
                ':email' => $email,
                ':username' => $email,
                ':user_id' => $user_id
            ]);

            // 2. Update student information
            $studentQuery = "UPDATE " . $this->table . " 
                           SET first_name = :first_name, 
                               last_name = :last_name, 
                               address = :address 
                           WHERE user_id = :user_id";
            
            $studentStmt = $this->conn->prepare($studentQuery);
            $result = $studentStmt->execute([
                ':first_name' => $first_name,
                ':last_name' => $last_name,
                ':address' => $address,
                ':user_id' => $user_id
            ]);

            if (!$result) {
                throw new Exception("Failed to update student profile.");
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Change student password
    public function changePassword($user_id, $current_password, $new_password) {
        try {
            // 1. Verify current password
            $query = "SELECT password FROM users WHERE id = :user_id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !password_verify($current_password, $user['password'])) {
                return false;
            }

            // 2. Update with new password
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $updateQuery = "UPDATE users SET password = :password WHERE id = :user_id";
            $updateStmt = $this->conn->prepare($updateQuery);
            
            return $updateStmt->execute([
                ':password' => $new_password_hash,
                ':user_id' => $user_id
            ]);

        } catch (Exception $e) {
            return false;
        }
    }
    // Update student details
    public function update($student_id, $data) {
        $this->conn->beginTransaction();

        try {
            // 1. Update User Email (Optional: only if changed)
            if (isset($data['email'])) {
                // Fetch user_id first
                $checkQuery = "SELECT user_id FROM " . $this->table . " WHERE student_id = ?";
                $checkStmt = $this->conn->prepare($checkQuery);
                $checkStmt->execute([$student_id]);
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

            // 2. Update Student Record
            $query = "UPDATE " . $this->table . " 
                    SET parent_id = :parent_id,
                        first_name = :first_name,
                        last_name = :last_name,
                        admission_no = :admission_no,
                        dob = :dob,
                        gender = :gender,
                        address = :address,
                        class_id = :class_id,
                        section_id = :section_id
                    WHERE student_id = :student_id";
            
            $stmt = $this->conn->prepare($query);

            if (!$stmt->execute([
                ':parent_id' => $data['parent_id'],
                ':first_name' => $data['first_name'],
                ':last_name' => $data['last_name'],
                ':admission_no' => $data['admission_no'],
                ':dob' => $data['dob'],
                ':gender' => $data['gender'],
                ':address' => $data['address'],
                ':class_id' => $data['class_id'],
                ':section_id' => $data['section_id'],
                ':student_id' => $student_id
            ])) {
                throw new Exception("Failed to update student profile.");
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
