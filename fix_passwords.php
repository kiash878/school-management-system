<?php
require_once 'includes/config/config.php';
require_once 'includes/classes/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // The password we want everyone to have
    $new_password = "password123";
    
    // Hash it properly using the server's algorithm
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    
    // Update all users
    $query = "UPDATE users SET password = :password";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':password', $hashed_password);
    
    if ($stmt->execute()) {
        echo "<h1>Success!</h1>";
        echo "<p>All passwords have been reset to: <strong>password123</strong></p>";
        echo "<p>Rows updated: " . $stmt->rowCount() . "</p>";
        echo "<br><a href='index.php'>Go to Login Page</a>";
    } else {
        echo "<h1>Error</h1>";
        echo "<p>Failed to update passwords.</p>";
    }

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>
