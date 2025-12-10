<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'parent') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';
require_once '../includes/classes/Fee.php';

$page_title = "Child Fees";
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

$feeObj = new Fee();
$fees = $feeObj->getStudentFees($student_id);
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Fees: <?php echo htmlspecialchars($studentName); ?></h1>
        <a href="dashboard.php" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                 <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $fees->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo number_format($row['amount'], 2); ?></td>
                            <td><?php echo $row['due_date']; ?></td>
                            <td>
                                <?php if ($row['status'] == 'Paid'): ?>
                                    <span class="badge bg-success">Paid</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Unpaid</span>
                                    <button class="btn btn-sm btn-primary ms-2 pay-now-btn" 
                                            data-amount="<?php echo $row['amount']; ?>" 
                                            data-title="<?php echo htmlspecialchars($row['title']); ?>">
                                        pay Now
                                    </button>
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

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pay Fee via M-Pesa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm">
                    <div class="mb-3">
                        <label class="form-label">Fee Title</label>
                        <input type="text" class="form-control" id="payTitle" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" class="form-control" id="payAmount" name="amount" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">M-Pesa Phone Number</label>
                        <input type="text" class="form-control" name="phone" placeholder="+254..." required value="+254">
                    </div>
                    <div id="paymentResult" class="mt-2"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="confirmPayment">Initiate Payment</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
    var confirmBtn = document.getElementById('confirmPayment');
    
    document.querySelectorAll('.pay-now-btn').forEach(button => {
        button.addEventListener('click', function() {
            var amount = this.dataset.amount;
            var title = this.dataset.title;
            
            document.getElementById('payAmount').value = amount;
            document.getElementById('payTitle').value = title;
            document.getElementById('paymentResult').innerHTML = ''; // Clear prev messages
            
            paymentModal.show();
        });
    });

    confirmBtn.addEventListener('click', function() {
        var form = document.getElementById('paymentForm');
        var formData = new FormData(form);
        var resultDiv = document.getElementById('paymentResult');

        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Processing...';

        fetch('initiate_payment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultDiv.innerHTML = '<div class="alert alert-success">STK Push Sent! Check your phone.</div>';
                // You might want to reload or update status, but for now just notify
            } else {
                resultDiv.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
            }
            confirmBtn.disabled = false;
            confirmBtn.textContent = 'Initiate Payment';
        })
        .catch(error => {
            resultDiv.innerHTML = '<div class="alert alert-danger">Error processing request.</div>';
            confirmBtn.disabled = false;
            confirmBtn.textContent = 'Initiate Payment';
        });
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>
