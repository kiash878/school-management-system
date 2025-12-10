<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';
require_once '../includes/classes/Teacher.php';

$page_title = "Manage Teachers";
require_once '../includes/header.php';
require_once 'sidebar.php';

$teacher = new Teacher();
$stmt = $teacher->read();
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <div>
            <h1 class="h2">Teachers</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Teachers</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="fas fa-chalkboard-teacher me-2"></i> All Teachers</h5>
             <a href="add_teacher.php" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="fas fa-plus"></i> <span>Add New Teacher</span>
            </a>
        </div>
        <div class="card-body p-0">
             <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Name</th>
                            <th class="border-0">Email</th>
                            <th class="border-0">Qualification</th>
                            <th class="border-0">Phone</th>
                            <th class="border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px; font-weight: bold; color: var(--warning);">
                                        <?php echo strtoupper(substr($row['first_name'], 0, 1)); ?>
                                    </div>
                                    <span class="fw-medium text-dark"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></span>
                                </div>
                            </td>
                            <td class="text-muted"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><span class="badge bg-light-primary text-primary"><?php echo htmlspecialchars($row['qualification']); ?></span></td>
                            <td class="text-muted"><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td class="text-end">
                                <a href="edit_teacher.php?id=<?php echo $row['teacher_id']; ?>" class="btn-icon text-secondary" title="Edit">
                                    <i class="far fa-edit"></i>
                                </a>
                                <a href="delete_teacher.php?id=<?php echo $row['teacher_id']; ?>" class="btn-icon text-danger" title="Delete" onclick="return confirm('Are you sure?')">
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
