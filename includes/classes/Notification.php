<?php
require_once __DIR__ . '/Database.php';

class Notification {
    private $conn;

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    // Send internal message
    public function sendMessage($sender_id, $receiver_id, $subject, $message) {
        $query = "INSERT INTO messages (sender_id, receiver_id, subject, message) VALUES (:sender, :receiver, :subject, :message)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':sender', $sender_id);
        $stmt->bindParam(':receiver', $receiver_id);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':message', $message);
        return $stmt->execute();
    }

    // Get user messages
    public function getMessages($user_id) {
        $query = "SELECT m.*, u.username as sender_name 
                  FROM messages m 
                  JOIN users u ON m.sender_id = u.id 
                  WHERE m.receiver_id = :user_id 
                  ORDER BY m.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt;
    }

    // Create Announcement
    public function createAnnouncement($title, $content, $target_role, $created_by) {
        $query = "INSERT INTO announcements (title, content, target_role, created_by) VALUES (:title, :content, :role, :creator)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':role', $target_role);
        $stmt->bindParam(':creator', $created_by);
        return $stmt->execute();
    }

    // Get Announcements for a role
    public function getAnnouncements($role) {
        $query = "SELECT * FROM announcements WHERE target_role = 'all' OR target_role = :role ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
        return $stmt;
    }

    // Get all announcements (for admin view)
    public function getAllAnnouncements() {
        $query = "SELECT a.*, u.username as creator_name 
                  FROM announcements a 
                  LEFT JOIN users u ON a.created_by = u.id 
                  ORDER BY a.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
