<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';
require_once '../includes/classes/SchoolClass.php';

$page_title = "Generate Reports";
require_once '../includes/header.php';
require_once 'sidebar.php';

$classObj = new SchoolClass();
$classes = $classObj->getClasses();

$report_type = $_GET['type'] ?? '';
$class_id = $_GET['class_id'] ?? '';
$data = [];

if ($report_type == 'student_list' && $class_id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT s.*, c.class_name 
                          FROM students s 
                          JOIN classes c ON s.class_id = c.class_id 
                          WHERE s.class_id = ? 
                          ORDER BY s.last_name");
    $stmt->execute([$class_id]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 d-print-none">
        <div>
            <h1 class="h2">Generate Reports</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Reports</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card d-print-none mb-4">
        <div class="card-header bg-transparent border-0">
             <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i> Report Criteria</h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <input type="hidden" name="type" value="student_list">
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-uppercase">Report Type</label>
                    <select class="form-select" disabled>
                        <option selected>Class Student List</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-uppercase">Class</label>
                    <select name="class_id" class="form-select" required>
                        <option value="">Select Class</option>
                        <?php while ($row = $classes->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?php echo $row['class_id']; ?>" <?php echo ($class_id == $row['class_id']) ? 'selected' : ''; ?>>
                                <?php echo $row['class_name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2"><i class="fas fa-file-alt me-1"></i> Generate Report</button>
                    <?php if (!empty($data)): ?>
                        <button type="button" onclick="window.print()" class="btn btn-secondary">
                            <i class="fas fa-print me-1"></i> Print
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <?php if ($report_type == 'student_list' && !empty($data)): ?>
    <div class="card report-container">
        <div class="card-body">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-primary">School Management System</h2>
                <h4 class="text-dark">Student List Report - <?php echo htmlspecialchars($data[0]['class_name']); ?></h4>
                <p class="text-muted small">Generated on: <?php echo date('F j, Y, g:i a'); ?></p>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="fw-bold">#</th>
                            <th class="fw-bold">Admission No</th>
                            <th class="fw-bold">Name</th>
                            <th class="fw-bold">Gender</th>
                            <th class="fw-bold">Date of Birth</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; foreach ($data as $row): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td class="fw-bold"><?php echo $row['admission_no']; ?></td>
                            <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                            <td><?php echo ucfirst($row['gender']); ?></td>
                            <td><?php echo $row['dob']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php elseif ($report_type && empty($data) && $class_id): ?>
        <div class="alert alert-warning text-center">
            <i class="fas fa-exclamation-triangle me-2"></i> No records found for the selected criteria.
        </div>
    <?php endif; ?>
</main>

<style>
@media print {
    .sidebar, .navbar, .d-print-none {
        display: none !important;
    }
    .main-content {
        margin: 0;
        padding: 0;
    }
}
</style>

<?php require_once '../includes/footer.php'; ?>
