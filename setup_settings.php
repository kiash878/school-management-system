<?php
require_once 'includes/config/config.php';
require_once 'includes/classes/Database.php';

$database = Database::getInstance();
$conn = $database->getConnection();

$sql = "
CREATE TABLE IF NOT EXISTS settings (
    setting_id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(50) UNIQUE NOT NULL,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO settings (setting_key, setting_value) VALUES 
('school_name', 'School Management System'),
('school_email', 'admin@school.com'),
('school_phone', '+1 234 567 890'),
('school_address', '123 School Lane, Education City')
ON DUPLICATE KEY UPDATE setting_key=setting_key;
";

try {
    $conn->exec($sql);
    echo "Settings table created successfully.";
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>
