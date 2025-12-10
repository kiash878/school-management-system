<?php
class Fee {
    private $conn;
    private $table = 'fees';

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    // Assign fee to student
    public function assignFee($student_id, $title, $amount, $due_date) {
        $query = "INSERT INTO " . $this->table . " (student_id, title, amount, due_date, status) VALUES (:student_id, :title, :amount, :due_date, 'Unpaid')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':due_date', $due_date);
        return $stmt->execute();
    }

    // Record Payment
    public function markPaid($fee_id) {
        $query = "UPDATE " . $this->table . " SET status = 'Paid', paid_date = CURRENT_DATE WHERE fee_id = :fee_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fee_id', $fee_id);
        return $stmt->execute();
    }

    // Get Student Fees
    public function getStudentFees($student_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE student_id = :student_id ORDER BY due_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        return $stmt;
    }
}
?>
