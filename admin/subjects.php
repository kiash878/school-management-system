<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';
require_once '../includes/classes/SchoolClass.php';

$page_title = "Manage Subjects";
require_once '../includes/header.php';
require_once 'sidebar.php';

$db = Database::getInstance()->getConnection();

// Add Subject Handler
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['subject_name'];
    $code = $_POST['subject_code'];
    $class_id = $_POST['class_id'];
    $teacher_id = $_POST['teacher_id'];

    $q = "INSERT INTO subjects (subject_name, subject_code, class_id, teacher_id) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($q);
    $stmt->execute([$name, $code, $class_id, $teacher_id ?: null]);
    $msg = "Subject Added!";
}

// Fetch Subjects
$query = "SELECT s.*, c.class_name, t.first_name, t.last_name 
          FROM subjects s 
          JOIN classes c ON s.class_id = c.class_id 
          LEFT JOIN teachers t ON s.teacher_id = t.teacher_id
          ORDER BY c.numeric_grade, s.subject_name";
$subjects = $db->query($query);

// Fetch Classes & Teachers for Modal
$classObj = new SchoolClass();
$classes = $classObj->getClasses();
$teachers = $db->query("SELECT * FROM teachers ORDER BY first_name");
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <div>
            <h1 class="h2">Subjects</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Subjects</li>
                </ol>
            </nav>
        </div>
        <div>
            <button type="button" class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                <i class="fas fa-plus"></i> <span>Add Subject</span>
            </button>
        </div>
    </div>

    <?php if (isset($msg)) echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>$msg <button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>"; ?>

    <div class="card">
        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="fas fa-book-open me-2"></i> All Subjects</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Subject Name</th>
                            <th class="border-0">Code</th>
                            <th class="border-0">Class</th>
                            <th class="border-0">Assigned Teacher</th>
                            <th class="border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $subjects->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td class="fw-medium text-dark"><?php echo htmlspecialchars($row['subject_name']); ?></td>
                            <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($row['subject_code']); ?></span></td>
                            <td><span class="badge bg-light-info text-info"><?php echo htmlspecialchars($row['class_name'] ?? '-'); ?></span></td>
                            <td>
                                <?php if ($row['first_name']): ?>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light-secondary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px; font-size: 0.8rem;">
                                            <?php echo strtoupper(substr($row['first_name'], 0, 1)); ?>
                                        </div>
                                        <span><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">Not Assigned</span>
                                <?php endif; ?>
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

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
      <div class="modal-header">
        <h5 class="modal-title">Add New Subject</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label>Subject Name</label>
            <input type="text" name="subject_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Subject Code</label>
            <input type="text" name="subject_code" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Class</label>
            <select name="class_id" class="form-select" required>
                <?php while ($c = $classes->fetch(PDO::FETCH_ASSOC)): ?>
                    <option value="<?php echo $c['class_id']; ?>"><?php echo $c['class_name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Teacher (Optional)</label>
            <select name="teacher_id" class="form-select">
                <option value="">-- Select Teacher --</option>
                <?php while ($t = $teachers->fetch(PDO::FETCH_ASSOC)): ?>
                    <option value="<?php echo $t['teacher_id']; ?>"><?php echo $t['first_name'] . ' ' . $t['last_name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save Subject</button>
      </div>
      </form>
    </div>
  </div>
</div>

<?php require_once '../includes/footer.php'; ?>
