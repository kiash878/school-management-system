<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';
require_once '../includes/classes/Attendance.php';
require_once '../includes/classes/SchoolClass.php';

$page_title = "Manage Attendance";
require_once '../includes/header.php';
require_once 'sidebar.php';

$schoolClass = new SchoolClass();
$classes = $schoolClass->getClasses();

$attendanceData = [];
$selected_class = $_GET['class_id'] ?? '';
$selected_section = $_GET['section_id'] ?? '';
$selected_date = $_GET['date'] ?? date('Y-m-d');

if ($selected_class && $selected_section) {
    $attObj = new Attendance();
    $stmt = $attObj->getClassAttendance($selected_class, $selected_section, $selected_date);
    $attendanceData = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <div>
            <h1 class="h2">Attendance</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Attendance</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-transparent border-0">
             <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i> Filter Attendance</h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-uppercase">Class</label>
                    <select name="class_id" id="class_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Select Class</option>
                        <?php 
                        $classes->execute(); 
                        while ($row = $classes->fetch(PDO::FETCH_ASSOC)): 
                        ?>
                            <option value="<?php echo $row['class_id']; ?>" <?php echo ($selected_class == $row['class_id']) ? 'selected' : ''; ?>>
                                <?php echo $row['class_name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <!-- Note: Section reload logic simplified for PHP-only approach, ideally AJAX -->
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-uppercase">Section</label>
                     <select name="section_id" class="form-select">
                        <option value="1" <?php echo ($selected_section == 1) ? 'selected' : ''; ?>>A</option>
                        <option value="2" <?php echo ($selected_section == 2) ? 'selected' : ''; ?>>B</option>
                        <!-- In real app, populate this dynamically based on class -->
                    </select>
                   
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-uppercase">Date</label>
                    <input type="date" name="date" class="form-control" value="<?php echo $selected_date; ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-2"></i> View Attendance</button>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($attendanceData)): ?>
    <div class="card">
        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="fas fa-users me-2"></i> Student List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Admission No</th>
                            <th class="border-0">Name</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attendanceData as $row): ?>
                        <tr>
                            <td class="fw-bold text-primary"><?php echo $row['admission_no']; ?></td>
                            <td class="fw-medium"><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                            <td>
                                <?php if ($row['status'] == 'Present'): ?>
                                    <span class="badge bg-light-success text-success">Present</span>
                                <?php elseif ($row['status'] == 'Absent'): ?>
                                    <span class="badge bg-light-danger text-danger">Absent</span>
                                <?php elseif ($row['status'] == 'Late'): ?>
                                    <span class="badge bg-light-warning text-warning">Late</span>
                                <?php else: ?>
                                    <span class="badge bg-light-secondary text-secondary">Not Marked</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted small"><?php echo $row['remarks'] ? $row['remarks'] : '-'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php elseif ($selected_class && $selected_section): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i> No attendance records found for this selection.
        </div>
    <?php endif; ?>
</main>

<?php require_once '../includes/footer.php'; ?>
