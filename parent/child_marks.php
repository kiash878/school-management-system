<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'parent') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';

$page_title = "Child Results";
require_once '../includes/header.php';
require_once 'sidebar.php';

if (!isset($_GET['student_id'])) {
    echo "<div class='main-content alert alert-danger'>No student selected.</div>";
    exit;
}

$student_id = $_GET['student_id'];
$db = Database::getInstance()->getConnection();
$checkStmt = $db->prepare("SELECT s.first_name FROM students s JOIN parents p ON s.parent_id = p.parent_id WHERE s.student_id = ? AND p.user_id = ?");
$checkStmt->execute([$student_id, $_SESSION['user_id']]);

if ($checkStmt->rowCount() == 0) {
    echo "<div class='main-content alert alert-danger'>Access Denied.</div>";
    exit;
}

$studentName = $checkStmt->fetchColumn();

// Get Results
$query = "SELECT g.*, s.subject_name, e.exam_name 
          FROM grades g 
          JOIN subjects s ON g.subject_id = s.subject_id 
          JOIN exams e ON g.exam_id = e.exam_id 
          WHERE g.student_id = ? 
          ORDER BY e.start_date DESC";
$stmt = $db->prepare($query);
$stmt->execute([$student_id]);
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Results: <?php echo htmlspecialchars($studentName); ?></h1>
        <a href="dashboard.php" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Exam</th>
                            <th>Subject</th>
                            <th>Marks</th>
                            <th>Max Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['exam_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                            <td><?php echo $row['marks_obtained']; ?></td>
                            <td><?php echo $row['max_marks']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
