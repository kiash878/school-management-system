<?php
require_once 'User.php';

class Library {
    private $conn;
    private $book_table = 'library_books';
    private $tx_table = 'library_transactions';

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    // --- Book Management ---
    public function addBook($data) {
        $query = "INSERT INTO " . $this->book_table . " (title, author, isbn, category, quantity, available_qty) VALUES (:title, :author, :isbn, :category, :quantity, :available_qty)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':title' => $data['title'],
            ':author' => $data['author'],
            ':isbn' => $data['isbn'],
            ':category' => $data['category'],
            ':quantity' => $data['quantity'],
            ':available_qty' => $data['quantity']
        ]);
    }

    public function getBooks() {
        $query = "SELECT * FROM " . $this->book_table . " ORDER BY title ASC";
        return $this->conn->query($query);
    }

    // --- Issue/Return ---
    public function issueBook($book_id, $student_id, $due_date) {
        $this->conn->beginTransaction();
        try {
            // Check availability
            $check = $this->conn->prepare("SELECT available_qty FROM " . $this->book_table . " WHERE book_id = ?");
            $check->execute([$book_id]);
            $qty = $check->fetchColumn();

            if ($qty <= 0) throw new Exception("Book not available.");

            // Issue
            $query = "INSERT INTO " . $this->tx_table . " (book_id, student_id, issue_date, due_date) VALUES (:book_id, :student_id, CURDATE(), :due_date)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':book_id' => $book_id, ':student_id' => $student_id, ':due_date' => $due_date]);

            // Create Notification
            $notifQuery = "INSERT INTO notifications (user_id, title, message) 
                          SELECT user_id, 'Library Book Issued', 'You have borrowed a book. Due date: $due_date' 
                          FROM students WHERE student_id = :sid";
            $notifStmt = $this->conn->prepare($notifQuery);
            $notifStmt->execute([':sid' => $student_id]);

            // Create Notification for Parent
            $parentNotifQuery = "INSERT INTO notifications (user_id, title, message) 
                                SELECT p.user_id, 'Library Book Issued', CONCAT(s.first_name, ' borrowed a book. Due: $due_date')
                                FROM students s JOIN parents p ON s.parent_id = p.parent_id 
                                WHERE s.student_id = :sid";
            $parentStmt = $this->conn->prepare($parentNotifQuery);
            $parentStmt->execute([':sid' => $student_id]);

            // Update Qty
            $update = $this->conn->prepare("UPDATE " . $this->book_table . " SET available_qty = available_qty - 1 WHERE book_id = ?");
            $update->execute([$book_id]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function returnBook($transaction_id, $fine = 0) {
        $this->conn->beginTransaction();
        try {
            // Get book id
            $get = $this->conn->prepare("SELECT book_id FROM " . $this->tx_table . " WHERE transaction_id = ?");
            $get->execute([$transaction_id]);
            $book_id = $get->fetchColumn();

            // Mark returned
            $query = "UPDATE " . $this->tx_table . " SET status = 'Returned', return_date = CURDATE(), fine_amount = :fine WHERE transaction_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':fine' => $fine, ':id' => $transaction_id]);

            // Return Qty
            $update = $this->conn->prepare("UPDATE " . $this->book_table . " SET available_qty = available_qty + 1 WHERE book_id = ?");
            $update->execute([$book_id]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getActiveIssues() {
        $query = "SELECT t.*, b.title, s.first_name, s.last_name, s.admission_no 
                  FROM " . $this->tx_table . " t
                  JOIN " . $this->book_table . " b ON t.book_id = b.book_id
                  JOIN students s ON t.student_id = s.student_id
                  WHERE t.status = 'Issued' OR t.status = 'Overdue'
                  ORDER BY t.due_date ASC";
        return $this->conn->query($query);
    }
}
?>
