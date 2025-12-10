<?php
require_once 'User.php';

class Timetable {
    private $conn;
    private $table = 'timetable';

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    // Create a new timetable entry
    public function create($data) {
        // Simple conflict check
        if ($this->hasConflict($data)) {
            throw new Exception("Conflict detected! This time slot is already occupied for this Class or Teacher.");
        }

        $query = "INSERT INTO " . $this->table . " 
                  (class_id, section_id, subject_id, teacher_id, day_of_week, start_time, end_time) 
                  VALUES 
                  (:class_id, :section_id, :subject_id, :teacher_id, :day_of_week, :start_time, :end_time)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':class_id' => $data['class_id'],
            ':section_id' => $data['section_id'],
            ':subject_id' => $data['subject_id'],
            ':teacher_id' => $data['teacher_id'],
            ':day_of_week' => $data['day_of_week'],
            ':start_time' => $data['start_time'],
            ':end_time' => $data['end_time']
        ]);
    }

    // Check for schedule conflicts
    public function hasConflict($data) {
        $query = "SELECT COUNT(*) FROM " . $this->table . " 
                  WHERE day_of_week = :day_of_week 
                  AND (
                      (start_time < :end_time AND end_time > :start_time) -- Time overlap logic
                  ) 
                  AND (
                      (class_id = :class_id AND section_id = :section_id) -- Same class occupied
                      OR 
                      (teacher_id = :teacher_id) -- Same teacher occupied
                  )";
        
        // If updating, exclude self? (Not implemented here for Create)
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':day_of_week' => $data['day_of_week'],
            ':start_time' => $data['start_time'],
            ':end_time' => $data['end_time'],
            ':class_id' => $data['class_id'],
            ':section_id' => $data['section_id'],
            ':teacher_id' => $data['teacher_id']
        ]);

        return $stmt->fetchColumn() > 0;
    }

    // Get timetable for a specific class and section
    public function getByClassAndSection($class_id, $section_id) {
        $query = "SELECT t.*, s.subject_name, u.first_name, u.last_name 
                  FROM " . $this->table . " t
                  LEFT JOIN subjects s ON t.subject_id = s.subject_id
                  LEFT JOIN teachers te ON t.teacher_id = te.teacher_id
                  LEFT JOIN users u ON te.user_id = u.id
                  WHERE t.class_id = :class_id AND t.section_id = :section_id
                  ORDER BY 
                    FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
                    t.start_time";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':class_id' => $class_id, ':section_id' => $section_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete an entry
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE timetable_id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $id]);
    }
}
?>
