<?php
require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';
require_once '../includes/classes/SchoolClass.php';

header('Content-Type: application/json');

if (isset($_GET['class_id'])) {
    $schoolClass = new SchoolClass();
    $stmt = $schoolClass->getSections($_GET['class_id']);
    $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($sections);
} else {
    echo json_encode([]);
}
?>
