<?php
require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';

header('Content-Type: application/json');

if (isset($_GET['class_id'])) {
    $class_id = $_GET['class_id'];
    
    $database = Database::getInstance();
    $conn = $database->getConnection();
    
    $query = "SELECT * FROM subjects WHERE class_id = :class_id ORDER BY subject_name";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':class_id', $class_id);
    $stmt->execute();
    
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($subjects);
} else {
    echo json_encode([]);
}
?>
