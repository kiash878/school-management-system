<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'student') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';

$page_title = "My Timetable";
require_once '../includes/header.php';
require_once 'sidebar.php';

$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT class_id FROM students WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$class_id = $stmt->fetchColumn();

// Fetch Subjects for Class
$query = "SELECT s.subject_name, s.subject_code, t.first_name, t.last_name 
          FROM subjects s 
          LEFT JOIN teachers t ON s.teacher_id = t.teacher_id 
          WHERE s.class_id = ? 
          ORDER BY s.subject_name";
$stmt = $db->prepare($query);
$stmt->execute([$class_id]);
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Class Timetable</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-4">Registered Subjects</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Code</th>
                            <th>Teacher</th>
                            <th>Schedule (Placeholder)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['subject_code']); ?></td>
                            <td><?php echo $row['first_name'] ? $row['first_name'] . ' ' . $row['last_name'] : 'TBA'; ?></td>
                            <td>
                                <?php 
                                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                                echo $days[array_rand($days)] . " 10:00 AM - 11:00 AM"; 
                                ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
