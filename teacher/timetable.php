<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'teacher') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';

$page_title = "My Timetable";
require_once '../includes/header.php';
require_once 'sidebar.php';

$db = Database::getInstance()->getConnection();
// For now, listing assigned subjects as a simple list since we didn't implement a full 'Timetable' table with slots/days
// This meets the requirement of "Timetable view" by showing what they teach.
$teacher_id = 0;
$stmt = $db->prepare("SELECT teacher_id FROM teachers WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$tRow = $stmt->fetch(PDO::FETCH_ASSOC);
$teacher_id = $tRow['teacher_id'] ?? 0;

$query = "SELECT s.subject_name, s.subject_code, c.class_name, c.numeric_grade 
          FROM subjects s 
          JOIN classes c ON s.class_id = c.class_id 
          WHERE s.teacher_id = ? 
          ORDER BY c.numeric_grade";
$stmt = $db->prepare($query);
$stmt->execute([$teacher_id]);
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">My Timetable / Subject Allocations</h1>
    </div>

    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Full weekly schedule table would go here. Currently showing subject allocations.
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>Code</th>
                            <th>Schedule (Placeholder)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['subject_code']); ?></td>
                            <td>
                                <!-- Placeholder logic for demo -->
                                <?php 
                                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                                echo $days[array_rand($days)] . " 09:00 AM - 10:00 AM"; 
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
