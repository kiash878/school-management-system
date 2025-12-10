<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'student') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';

$page_title = "My Results";
require_once '../includes/header.php';
require_once 'sidebar.php';

$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT student_id FROM students WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$student_id = $stmt->fetchColumn();

// Get Results
$query = "SELECT g.*, s.subject_name, e.exam_name 
          FROM grades g 
          JOIN subjects s ON g.subject_id = s.subject_id 
          JOIN exams e ON g.exam_id = e.exam_id 
          WHERE g.student_id = ? 
          ORDER BY e.start_date DESC, s.subject_name";
$stmt = $db->prepare($query);
$stmt->execute([$student_id]);
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">My Exam Results</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Exam</th>
                            <th>Subject</th>
                            <th>Marks Obtained</th>
                            <th>Max Marks</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <?php $pct = ($row['max_marks'] > 0) ? round(($row['marks_obtained'] / $row['max_marks']) * 100, 2) : 0; ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['exam_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                            <td><?php echo $row['marks_obtained']; ?></td>
                            <td><?php echo $row['max_marks']; ?></td>
                            <td>
                                <?php echo $pct; ?>%
                                <?php if ($pct >= 50): ?>
                                    <span class="badge bg-success ms-2">Pass</span>
                                <?php else: ?>
                                    <span class="badge bg-danger ms-2">Fail</span>
                                <?php endif; ?>
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
