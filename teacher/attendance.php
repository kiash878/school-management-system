<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'teacher') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';
require_once '../includes/classes/Attendance.php';

$page_title = "Mark Attendance";
require_once '../includes/header.php';
require_once 'sidebar.php';

$teacher_id = 0; // In real app, fetch from session mapping
$db = Database::getInstance()->getConnection();
// Quick fetch teacher id
$stmt = $db->prepare("SELECT teacher_id FROM teachers WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$tRow = $stmt->fetch(PDO::FETCH_ASSOC);
$teacher_id = $tRow['teacher_id'] ?? 0;

// Fetch Assigned Classes/Subjects 
// (Simplified: Teachers see classes they teach ANY subject in, for Homeroom logic we'd need 'is_class_teacher' field)
$classesQuery = "SELECT DISTINCT c.class_id, c.class_name, c.numeric_grade 
                 FROM subjects s 
                 JOIN classes c ON s.class_id = c.class_id 
                 WHERE s.teacher_id = ?";
$classesStmt = $db->prepare($classesQuery);
$classesStmt->execute([$teacher_id]);

$selected_class = $_POST['class_id'] ?? ($_GET['class_id'] ?? '');
$selected_section = $_POST['section_id'] ?? ($_GET['section_id'] ?? 1); // Defaulting for demo
$selected_date = $_POST['date'] ?? date('Y-m-d');

$msg = '';

$attObj = new Attendance();

// Handle Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_attendance'])) {
    $students = $_POST['status']; // array of student_id => status
    $remarks = $_POST['remarks'] ?? [];
    
    foreach ($students as $sid => $status) {
        $rem = $remarks[$sid] ?? '';
        $attObj->mark($sid, $selected_class, $selected_section, $selected_date, $status, $rem);
    }
    $msg = "Attendance saved successfully!";
}

// Get Students for selected class
$studentsList = [];
if ($selected_class) {
    // We reuse getClassAttendance to get the list, effectively "viewing" allowing "editing"
    $stmt = $attObj->getClassAttendance($selected_class, $selected_section, $selected_date);
    $studentsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Mark Attendance</h1>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-success"><?php echo $msg; ?></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Class</label>
                    <select name="class_id" class="form-select" onchange="this.form.submit()" required>
                        <option value="">Select Class</option>
                        <?php while ($row = $classesStmt->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?php echo $row['class_id']; ?>" <?php echo ($selected_class == $row['class_id']) ? 'selected' : ''; ?>>
                                <?php echo $row['class_name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Section</label>
                    <select name="section_id" class="form-select">
                        <option value="1">A</option>
                        <option value="2">B</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary mt-4">Load Students</button>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($studentsList)): ?>
    <form method="POST">
        <input type="hidden" name="class_id" value="<?php echo $selected_class; ?>">
        <input type="hidden" name="section_id" value="<?php echo $selected_section; ?>">
        
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="<?php echo $selected_date; ?>" readonly>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Adm No</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($studentsList as $row): ?>
                    <tr>
                        <td><?php echo $row['admission_no']; ?></td>
                        <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                        <td>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status[<?php echo $row['student_id']; ?>]" value="Present" <?php echo ($row['status'] == 'Present' || !$row['status']) ? 'checked' : ''; ?>>
                                <label class="form-check-label">Present</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status[<?php echo $row['student_id']; ?>]" value="Absent" <?php echo ($row['status'] == 'Absent') ? 'checked' : ''; ?>>
                                <label class="form-check-label">Absent</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status[<?php echo $row['student_id']; ?>]" value="Late" <?php echo ($row['status'] == 'Late') ? 'checked' : ''; ?>>
                                <label class="form-check-label">Late</label>
                            </div>
                        </td>
                        <td>
                            <input type="text" name="remarks[<?php echo $row['student_id']; ?>]" class="form-control form-control-sm" value="<?php echo $row['remarks']; ?>">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <button type="submit" name="save_attendance" class="btn btn-success btn-lg">Save Attendance</button>
    </form>
    <?php endif; ?>
</main>

<?php require_once '../includes/footer.php'; ?>
