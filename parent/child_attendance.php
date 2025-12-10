<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'parent') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';
require_once '../includes/classes/Attendance.php';

$page_title = "Child Attendance";
require_once '../includes/header.php';
require_once 'sidebar.php';

if (!isset($_GET['student_id'])) {
    echo "<div class='main-content alert alert-danger'>No student selected.</div>";
    exit;
}

$student_id = $_GET['student_id'];
// Security check: Ensure this student belongs to this parent
$db = Database::getInstance()->getConnection();
$checkStmt = $db->prepare("SELECT s.first_name FROM students s JOIN parents p ON s.parent_id = p.parent_id WHERE s.student_id = ? AND p.user_id = ?");
$checkStmt->execute([$student_id, $_SESSION['user_id']]);

if ($checkStmt->rowCount() == 0) {
    echo "<div class='main-content alert alert-danger'>Access Denied.</div>";
    exit;
}

$studentName = $checkStmt->fetchColumn();

$attObj = new Attendance();
$attStmt = $attObj->getStudentReport($student_id);
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Attendance: <?php echo htmlspecialchars($studentName); ?></h1>
        <a href="dashboard.php" class="btn btn-outline-secondary">Back to Dashboard</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
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
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['remarks']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
