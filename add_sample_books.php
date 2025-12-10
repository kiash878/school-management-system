<?php
require_once 'includes/config/config.php';
require_once 'includes/classes/Database.php';

$database = Database::getInstance();
$conn = $database->getConnection();

$sql = "
INSERT INTO library_books (title, author, isbn, category, quantity, available_qty) VALUES 
('Mathematics Form 1', 'KLB', '9789966441234', 'Textbook', 20, 20),
('Physics Form 2', 'KLB', '9789966441235', 'Textbook', 15, 15),
('A Doll''s House', 'Henrik Ibsen', '9780486270623', 'Literature', 30, 30),
('Blossoms of the Savannah', 'Henry ole Kulet', '9789966255763', 'Literature', 25, 25),
('Oxford Advanced Learners Dictionary', 'Oxford', '9780194799003', 'Reference', 10, 10),
('Biology Form 3', 'KLB', '9789966441236', 'Textbook', 18, 18),
('The River and the Source', 'Margaret Ogola', '9789966882051', 'Literature', 22, 22),
('Chemistry Form 4', 'KLB', '9789966441237', 'Textbook', 12, 12),
('Betrayal in the City', 'Francis Imbuga', '9789966463564', 'Drama', 20, 20),
('Kamusi Ya Kiswahili Sanifu', 'TUKI', '9780195738223', 'Reference', 15, 15);
";

try {
    $conn->exec($sql);
    echo "10 Sample Books added to Library successfully.";
} catch (PDOException $e) {
    echo "Error adding books: " . $e->getMessage();
}
?>
