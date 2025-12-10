<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'student') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';
require_once '../includes/classes/Attendance.php';

$page_title = "My Attendance";
require_once '../includes/header.php';
require_once 'sidebar.php';

$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT student_id FROM students WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$student_id = $stmt->fetchColumn();

// Get Attendance
$attObj = new Attendance();
$attStmt = $attObj->getStudentReport($student_id);
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">My Attendance History</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $attStmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo date('d M Y', strtotime($row['date'])); ?></td>
                            <td>
                                <?php if ($row['status'] == 'Present'): ?>
                                    <span class="badge bg-success">Present</span>
                                <?php elseif ($row['status'] == 'Absent'): ?>
                                    <span class="badge bg-danger">Absent</span>
                                <?php elseif ($row['status'] == 'Late'): ?>
                                    <span class="badge bg-warning text-dark">Late</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo $row['status']; ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['remarks'] ?? '-'); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
