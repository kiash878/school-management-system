<?php
require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';

header('Content-Type: application/json');

if (isset($_GET['adm'])) {
    $adm = $_GET['adm'];
    
    $database = Database::getInstance();
    $conn = $database->getConnection();
    
    $query = "SELECT student_id, first_name, last_name FROM students WHERE admission_no = :adm";
    $stmt = $conn->prepare($query);
    $stmt->execute([':adm' => $adm]);
    
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($student) {
        echo json_encode(['status' => 'found', 'id' => $student['student_id'], 'name' => $student['first_name'] . ' ' . $student['last_name']]);
    } else {
        echo json_encode(['status' => 'not_found']);
    }
} else {
    echo json_encode(['status' => 'error']);
}
?>
