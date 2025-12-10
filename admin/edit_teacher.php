<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';
require_once '../includes/classes/Teacher.php';

$page_title = "Edit Teacher";
require_once '../includes/header.php';
require_once 'sidebar.php';

$msg = '';
$error = '';
$teacher_id = isset($_GET['id']) ? $_GET['id'] : 0;

$teacherObj = new Teacher();

// Fetch Teacher Data
$teacherData = $teacherObj->getTeacherById($teacher_id);

if (!$teacherData) {
    echo "<div class='main-content'><div class='alert alert-danger'>Teacher not found.</div></div>";
    require_once '../includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'email' => $_POST['email'],
        'qualification' => $_POST['qualification'],
        'phone' => $_POST['phone'],
        'address' => $_POST['address'],
        'hire_date' => $_POST['hire_date']
    ];

    try {
        if ($teacherObj->update($teacher_id, $data)) {
            $msg = "Teacher details updated successfully!";
            $teacherData = $teacherObj->getTeacherById($teacher_id); // Refresh
        } else {
            $error = "Failed to update teacher.";
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <div>
            <h1 class="h2">Edit Teacher</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="teachers.php">Teachers</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Teacher</li>
                </ol>
            </nav>
        </div>
        <a href="teachers.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Go Back
        </a>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $msg; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 d-flex align-items-center">
             <h5 class="card-title mb-0 text-primary"><i class="far fa-edit me-2"></i> Edit Teacher Details</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3">Personal & Professional Info</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">First Name</label>
                        <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($teacherData['first_name']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($teacherData['last_name']); ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($teacherData['email']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Phone</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($teacherData['phone']); ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Qualification</label>
                        <input type="text" name="qualification" class="form-control" value="<?php echo htmlspecialchars($teacherData['qualification']); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Hire Date</label>
                        <input type="date" name="hire_date" class="form-control" value="<?php echo htmlspecialchars($teacherData['hire_date']); ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small">Address</label>
                    <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($teacherData['address']); ?></textarea>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i> Update Teacher</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
