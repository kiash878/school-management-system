<?php
class Exam {
    private $conn;
    private $exam_table = 'exams';
    private $grade_table = 'grades';

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    // Create new exam
    public function createExam($name, $start_date, $end_date) {
        $query = "INSERT INTO " . $this->exam_table . " (exam_name, start_date, end_date) VALUES (:name, :start, :end)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':start', $start_date);
        $stmt->bindParam(':end', $end_date);
        return $stmt->execute();
    }

    // Get all exams
    public function getExams() {
        $query = "SELECT * FROM " . $this->exam_table . " ORDER BY start_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Enter/Update marks for a student
    public function enterMarks($student_id, $exam_id, $subject_id, $marks, $max_marks = 100) {
        // Check if exists
        if ($this->marksExist($student_id, $exam_id, $subject_id)) {
            $query = "UPDATE " . $this->grade_table . " 
                      SET marks_obtained = :marks, max_marks = :max_marks 
                      WHERE student_id = :student_id AND exam_id = :exam_id AND subject_id = :subject_id";
        } else {
            $query = "INSERT INTO " . $this->grade_table . " 
                      (student_id, exam_id, subject_id, marks_obtained, max_marks) 
                      VALUES (:student_id, :exam_id, :subject_id, :marks, :max_marks)";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':exam_id', $exam_id);
        $stmt->bindParam(':subject_id', $subject_id);
        $stmt->bindParam(':marks', $marks);
        $stmt->bindParam(':max_marks', $max_marks);

        return $stmt->execute();
    }

    private function marksExist($student_id, $exam_id, $subject_id) {
        $query = "SELECT grade_id FROM " . $this->grade_table . " WHERE student_id = :sid AND exam_id = :eid AND subject_id = :subid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':sid', $student_id);
        $stmt->bindParam(':eid', $exam_id);
        $stmt->bindParam(':subid', $subject_id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Get Report Card
    public function getReportCard($student_id, $exam_id) {
        $query = "SELECT g.*, s.subject_name, e.exam_name 
                  FROM " . $this->grade_table . " g
                  JOIN subjects s ON g.subject_id = s.subject_id
                  JOIN exams e ON g.exam_id = e.exam_id
                  WHERE g.student_id = :student_id AND g.exam_id = :exam_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':exam_id', $exam_id);
        $stmt->execute();
        return $stmt;
    }
}
?>
