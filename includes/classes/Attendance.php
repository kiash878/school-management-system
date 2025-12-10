<?php
class Attendance {
    private $conn;
    private $table = 'attendance';

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    // Mark attendance for a single student
    public function mark($student_id, $class_id, $section_id, $date, $status, $remarks = '') {
        // Check if already marked
        if ($this->isMarked($student_id, $date)) {
            return $this->update($student_id, $date, $status, $remarks);
        }

        $query = "INSERT INTO " . $this->table . " 
                (student_id, class_id, section_id, date, status, remarks)
                VALUES 
                (:student_id, :class_id, :section_id, :date, :status, :remarks)";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':class_id', $class_id);
        $stmt->bindParam(':section_id', $section_id);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':remarks', $remarks);

        return $stmt->execute();
    }

    // Check if attendance already marked
    public function isMarked($student_id, $date) {
        $query = "SELECT attendance_id FROM " . $this->table . " WHERE student_id = :student_id AND date = :date LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Update existing attendance
    public function update($student_id, $date, $status, $remarks) {
        $query = "UPDATE " . $this->table . " 
                  SET status = :status, remarks = :remarks 
                  WHERE student_id = :student_id AND date = :date";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':remarks', $remarks);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':date', $date);

        return $stmt->execute();
    }

    // Get attendance for a class on a specific date
    public function getClassAttendance($class_id, $section_id, $date) {
        $query = "SELECT a.*, s.first_name, s.last_name, s.admission_no 
                  FROM students s
                  LEFT JOIN " . $this->table . " a ON s.student_id = a.student_id AND a.date = :date
                  WHERE s.class_id = :class_id AND s.section_id = :section_id
                  ORDER BY s.admission_no ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':class_id', $class_id);
        $stmt->bindParam(':section_id', $section_id);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        return $stmt;
    }

    // Get individual student report
    public function getStudentReport($student_id, $month = null, $year = null) {
        $query = "SELECT * FROM " . $this->table . " WHERE student_id = :student_id";
        
        if ($month && $year) {
            $query .= " AND MONTH(date) = :month AND YEAR(date) = :year";
        }
        
        $query .= " ORDER BY date DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        
        if ($month && $year) {
            $stmt->bindParam(':month', $month);
            $stmt->bindParam(':year', $year);
        }

        $stmt->execute();
        return $stmt;
    }
}
?>
