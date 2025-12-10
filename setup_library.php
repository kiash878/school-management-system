<?php
require_once 'includes/config/config.php';
require_once 'includes/classes/Database.php';

$database = Database::getInstance();
$conn = $database->getConnection();

$sql = "
-- Library Books
CREATE TABLE IF NOT EXISTS library_books (
    book_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255),
    isbn VARCHAR(50),
    category VARCHAR(100),
    quantity INT DEFAULT 1,
    available_qty INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Library Transactions
CREATE TABLE IF NOT EXISTS library_transactions (
    transaction_id INT PRIMARY KEY AUTO_INCREMENT,
    book_id INT NOT NULL,
    student_id INT NOT NULL,
    issue_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE,
    fine_amount DECIMAL(10,2) DEFAULT 0.00,
    status ENUM('Issued', 'Returned', 'Overdue') DEFAULT 'Issued',
    FOREIGN KEY (book_id) REFERENCES library_books(book_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);
";

try {
    $conn->exec($sql);
    echo "Library tables created successfully.";
} catch (PDOException $e) {
    echo "Error creating tables: " . $e->getMessage();
}
?>
