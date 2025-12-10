<?php
class SchoolClass {
    private $conn;

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public function getClasses() {
        $query = "SELECT * FROM classes ORDER BY numeric_grade ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getSections($class_id) {
        $query = "SELECT * FROM sections WHERE class_id = :class_id ORDER BY section_name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':class_id', $class_id);
        $stmt->execute();
        return $stmt;
    }

    // Add new class
    public function addClass($class_name, $numeric_grade) {
        try {
            $query = "INSERT INTO classes (class_name, numeric_grade) VALUES (:class_name, :numeric_grade)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':class_name', $class_name);
            $stmt->bindParam(':numeric_grade', $numeric_grade);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    // Add new section to a class
    public function addSection($class_id, $section_name) {
        try {
            $query = "INSERT INTO sections (class_id, section_name) VALUES (:class_id, :section_name)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':class_id', $class_id);
            $stmt->bindParam(':section_name', $section_name);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    // Get student count for a class
    public function getStudentCount($class_id) {
        try {
            $query = "SELECT COUNT(*) as count FROM students WHERE class_id = :class_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':class_id', $class_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'];
        } catch (Exception $e) {
            return 0;
        }
    }

    // Get class details by ID
    public function getClassById($class_id) {
        try {
            $query = "SELECT * FROM classes WHERE class_id = :class_id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':class_id', $class_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    // Delete class
    public function deleteClass($class_id) {
        try {
            $this->conn->beginTransaction();
            
            // First delete all sections for this class
            $deleteSections = "DELETE FROM sections WHERE class_id = :class_id";
            $stmt = $this->conn->prepare($deleteSections);
            $stmt->bindParam(':class_id', $class_id);
            $stmt->execute();
            
            // Then delete the class
            $deleteClass = "DELETE FROM classes WHERE class_id = :class_id";
            $stmt = $this->conn->prepare($deleteClass);
            $stmt->bindParam(':class_id', $class_id);
            $result = $stmt->execute();
            
            $this->conn->commit();
            return $result;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Delete section
    public function deleteSection($section_id) {
        try {
            $query = "DELETE FROM sections WHERE section_id = :section_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':section_id', $section_id);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    // Update class
    public function updateClass($class_id, $class_name, $numeric_grade) {
        try {
            $query = "UPDATE classes SET class_name = :class_name, numeric_grade = :numeric_grade WHERE class_id = :class_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':class_name', $class_name);
            $stmt->bindParam(':numeric_grade', $numeric_grade);
            $stmt->bindParam(':class_id', $class_id);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
