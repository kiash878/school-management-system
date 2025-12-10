<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';
require_once '../includes/classes/Fee.php';
require_once '../includes/classes/Student.php';

$page_title = "Manage Fees";
require_once '../includes/header.php';
require_once 'sidebar.php';

$feeObj = new Fee();
$db = Database::getInstance()->getConnection();

$msg = '';

// Handle Fee Assignment (Single Student for now)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_fee'])) {
    $student_id = $_POST['student_id'];
    $title = $_POST['title'];
    $amount = $_POST['amount'];
    $due_date = $_POST['due_date'];

    if ($feeObj->assignFee($student_id, $title, $amount, $due_date)) {
        $msg = "Fee assigned successfully!";
    } else {
        $msg = "Failed to assign fee.";
    }
}

// Handle Mark Paid
if (isset($_GET['action']) && $_GET['action'] == 'pay' && isset($_GET['id'])) {
    if ($feeObj->markPaid($_GET['id'])) {
        $msg = "Fee marked as paid!";
    }
}

// Fetch all fees (latest first)
$query = "SELECT f.*, s.first_name, s.last_name, s.admission_no 
          FROM fees f 
          JOIN students s ON f.student_id = s.student_id 
          ORDER BY f.due_date DESC LIMIT 50";
$fees = $db->query($query);

// Fetch Students for dropdown
$studentObj = new Student();
$students = $studentObj->read(); // All students
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <div>
            <h1 class="h2">Fee Management</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Fees</li>
                </ol>
            </nav>
        </div>
        <div>
            <button type="button" class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#assignFeeModal">
                <i class="fas fa-plus"></i> <span class="d-none d-md-inline">Assign Fee</span>
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
            <h5 class="card-title mb-0"><i class="fas fa-money-bill-wave me-2"></i> Recent Fee Records</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Stud. ID</th>
                            <th class="border-0">Name</th>
                            <th class="border-0">Title</th>
                            <th class="border-0">Amount</th>
                            <th class="border-0">Due Date</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Paid Date</th>
                            <th class="border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $fees->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td class="fw-bold text-primary small"><?php echo htmlspecialchars($row['admission_no']); ?></td>
                            <td class="fw-medium"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td class="text-muted"><?php echo htmlspecialchars($row['title']); ?></td>
                            <td class="fw-bold text-dark">$<?php echo number_format($row['amount'], 2); ?></td>
                            <td class="text-muted small"><?php echo $row['due_date']; ?></td>
                            <td>
                                <?php if ($row['status'] == 'Paid'): ?>
                                    <span class="badge bg-light-success text-success">Paid</span>
                                <?php else: ?>
                                    <span class="badge bg-light-danger text-danger">Unpaid</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted small"><?php echo $row['paid_date'] ? $row['paid_date'] : '-'; ?></td>
                            <td class="text-end">
                                <?php if ($row['status'] != 'Paid'): ?>
                                <a href="fees.php?action=pay&id=<?php echo $row['fee_id']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Mark as Paid?')">
                                    <i class="fas fa-check me-1"></i> Pay
                                </a>
                                <?php else: ?>
                                <a href="#" class="btn btn-sm btn-icon text-secondary"><i class="fas fa-print"></i></a>
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

<!-- Assign Fee Modal -->
<div class="modal fade" id="assignFeeModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
      <input type="hidden" name="assign_fee" value="1">
      <div class="modal-header">
        <h5 class="modal-title">Assign Fee to Student</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label>Student</label>
            <select name="student_id" class="form-select" required>
                <option value="">Select Student</option>
                <?php while ($s = $students->fetch(PDO::FETCH_ASSOC)): ?>
                    <option value="<?php echo $s['student_id']; ?>">
                        <?php echo $s['admission_no'] . ' - ' . $s['first_name'] . ' ' . $s['last_name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Fee Title</label>
            <input type="text" name="title" class="form-control" placeholder="e.g. Tuition Term 1" required>
        </div>
        <div class="mb-3">
            <label>Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Due Date</label>
            <input type="date" name="due_date" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Assign</button>
      </div>
      </form>
    </div>
  </div>
</div>

<?php require_once '../includes/footer.php'; ?>
