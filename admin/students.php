<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';
require_once '../includes/classes/Student.php';

$page_title = "Manage Students";
require_once '../includes/header.php';
require_once 'sidebar.php';

$student = new Student();
$stmt = $student->read();
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <div>
            <h1 class="h2">Students</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Students</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="fas fa-user-graduate me-2"></i> All Students</h5>
            <a href="add_student.php" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="fas fa-plus"></i> <span>Add New Student</span>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Admission No</th>
                            <th class="border-0">Name</th>
                            <th class="border-0">Class</th>
                            <th class="border-0">Section</th>
                            <th class="border-0">Parent</th>
                            <th class="border-0">Contact</th>
                            <th class="border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td class="fw-bold text-primary"><?php echo htmlspecialchars($row['admission_no']); ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px; font-weight: bold; color: var(--info);">
                                        <?php echo strtoupper(substr($row['first_name'], 0, 1)); ?>
                                    </div>
                                    <span class="fw-medium"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></span>
                                </div>
                            </td>
                            <td><span class="badge bg-light-success text-success"><?php echo htmlspecialchars($row['class_name'] ?? '-'); ?></span></td>
                            <td><?php echo htmlspecialchars($row['section_name'] ?? '-'); ?></td>
                            <td class="text-muted"><?php echo htmlspecialchars($row['parent_name'] ?? '-'); ?></td>
                            <td class="text-muted"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td class="text-end">
                                <a href="edit_student.php?id=<?php echo $row['student_id']; ?>" class="btn-icon text-secondary" title="Edit">
                                    <i class="far fa-edit"></i>
                                </a>
                                <a href="delete_student.php?id=<?php echo $row['student_id']; ?>" class="btn-icon text-danger" title="Delete" onclick="return confirm('Are you sure?')">
                                    <i class="far fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
