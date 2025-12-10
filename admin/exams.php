<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';
require_once '../includes/classes/Exam.php';

$page_title = "Manage Exams";
require_once '../includes/header.php';
require_once 'sidebar.php';

$examObj = new Exam();
$msg = '';

// Handle Create Exam
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['exam_name'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    
    if ($examObj->createExam($name, $start, $end)) {
        $msg = "Exam created successfully!";
    } else {
        $msg = "Failed to create exam.";
    }
}

$exams = $examObj->getExams();
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <div>
            <h1 class="h2">Exams</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Exams</li>
                </ol>
            </nav>
        </div>
        <div>
            <button type="button" class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addExamModal">
                <i class="fas fa-plus"></i> <span>Create Exam</span>
            </button>
        </div>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo $msg; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="fas fa-file-signature me-2"></i> All Scheduled Exams</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Exam Name</th>
                            <th class="border-0">Start Date</th>
                            <th class="border-0">End Date</th>
                            <th class="border-0">Status</th>
                            <th class="border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $exams->fetch(PDO::FETCH_ASSOC)): ?>
                        <?php 
                            $today = date('Y-m-d');
                            $status = 'Upcoming';
                            if ($today >= $row['start_date'] && $today <= $row['end_date']) {
                                $status = 'Ongoing';
                            } elseif ($today > $row['end_date']) {
                                $status = 'Completed';
                            }
                        ?>
                        <tr>
                            <td class="fw-medium text-dark"><?php echo htmlspecialchars($row['exam_name']); ?></td>
                            <td class="text-muted"><i class="far fa-calendar-alt me-1"></i> <?php echo $row['start_date']; ?></td>
                            <td class="text-muted"><i class="far fa-calendar-check me-1"></i> <?php echo $row['end_date']; ?></td>
                            <td>
                                <span class="badge rounded-pill bg-light-<?php echo ($status=='Ongoing')?'success':(($status=='Completed')?'secondary':'primary'); ?> text-<?php echo ($status=='Ongoing')?'success':(($status=='Completed')?'secondary':'primary'); ?>">
                                    <?php echo $status; ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <button class="btn-icon text-secondary" title="Edit">
                                    <i class="far fa-edit"></i>
                                </button>
                                <button class="btn-icon text-danger" title="Delete">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Add Exam Modal -->
<div class="modal fade" id="addExamModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
      <div class="modal-header">
        <h5 class="modal-title">Create New Exam</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label>Exam Name</label>
            <input type="text" name="exam_name" class="form-control" placeholder="e.g. Term 1 Finals" required>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Start Date</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>End Date</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save Exam</button>
      </div>
      </form>
    </div>
  </div>
</div>

<?php require_once '../includes/footer.php'; ?>
