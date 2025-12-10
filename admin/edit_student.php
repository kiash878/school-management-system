<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';
require_once '../includes/classes/Student.php';
require_once '../includes/classes/Parents.php';
require_once '../includes/classes/SchoolClass.php';

$page_title = "Edit Student";
require_once '../includes/header.php';
require_once 'sidebar.php';

$msg = '';
$error = '';
$student_id = isset($_GET['id']) ? $_GET['id'] : 0;

$studentObj = new Student();
$parentObj = new Parents();
$classObj = new SchoolClass();

// Fetch Data for Dropdowns
$parents = $parentObj->read();
$classes = $classObj->getClasses();

// Fetch Student Data
$studentData = $studentObj->getStudentById($student_id);

if (!$studentData) {
    echo "<div class='main-content'><div class='alert alert-danger'>Student not found.</div></div>";
    require_once '../includes/footer.php';
    exit;
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'admission_no' => $_POST['admission_no'],
        'dob' => $_POST['dob'],
        'gender' => $_POST['gender'],
        'email' => $_POST['email'],
        'parent_id' => $_POST['parent_id'],
        'class_id' => $_POST['class_id'],
        'section_id' => $_POST['section_id'],
        'address' => $_POST['address']
    ];

    try {
        if ($studentObj->update($student_id, $data)) {
            $msg = "Student details updated successfully!";
            // Refresh data
            $studentData = $studentObj->getStudentById($student_id);
        } else {
            $error = "Failed to update student.";
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <div>
            <h1 class="h2">Edit Student</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="students.php">Students</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Student</li>
                </ol>
            </nav>
        </div>
        <a href="students.php" class="btn btn-outline-secondary">
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
             <h5 class="card-title mb-0 text-primary"><i class="far fa-edit me-2"></i> Edit Student Details</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3">Personal Information</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">First Name</label>
                        <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($studentData['first_name']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($studentData['last_name']); ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Admission No</label>
                        <input type="text" name="admission_no" class="form-control" value="<?php echo htmlspecialchars($studentData['admission_no']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Date of Birth</label>
                        <input type="date" name="dob" class="form-control" value="<?php echo $studentData['dob']; ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Gender</label>
                        <select name="gender" class="form-select" required>
                            <option value="Male" <?php echo ($studentData['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo ($studentData['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($studentData['email']); ?>" required>
                    </div>
                </div>

                <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3 mt-4">Academic Details</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Class</label>
                        <select name="class_id" id="class_id" class="form-select" required>
                            <option value="">Select Class</option>
                            <?php while ($row = $classes->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?php echo $row['class_id']; ?>" <?php echo ($studentData['class_id'] == $row['class_id']) ? 'selected' : ''; ?>>
                                    <?php echo $row['class_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Section</label>
                        <select name="section_id" id="section_id" class="form-select" required>
                            <option value="">Select Class First</option>
                            <!-- Sections will be populated by JS -->
                        </select>
                    </div>
                </div>

                <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3 mt-4">Parent & Contact</h6>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Parent</label>
                    <select name="parent_id" class="form-select" required>
                        <option value="">Select Parent</option>
                        <?php while ($row = $parents->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?php echo $row['parent_id']; ?>" <?php echo ($studentData['parent_id'] == $row['parent_id']) ? 'selected' : ''; ?>>
                                <?php echo $row['father_name'] . " & " . $row['mother_name'] . " (" . $row['email'] . ")"; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small">Address</label>
                    <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($studentData['address']); ?></textarea>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i> Update Student</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var classSelect = document.getElementById('class_id');
    var sectionSelect = document.getElementById('section_id');
    var selectedSection = "<?php echo $studentData['section_id']; ?>";

    function loadSections(classId, selectedId = null) {
        sectionSelect.innerHTML = '<option value="">Loading...</option>';
        if (classId) {
            fetch('../api/get_sections.php?class_id=' + classId)
                .then(response => response.json())
                .then(data => {
                    sectionSelect.innerHTML = '<option value="">Select Section</option>';
                    data.forEach(function(section) {
                        var option = document.createElement('option');
                        option.value = section.section_id;
                        option.text = section.section_name;
                        if (selectedId && section.section_id == selectedId) {
                            option.selected = true;
                        }
                        sectionSelect.add(option);
                    });
                });
        } else {
            sectionSelect.innerHTML = '<option value="">Select Class First</option>';
        }
    }

    // Load initial sections
    if (classSelect.value) {
        loadSections(classSelect.value, selectedSection);
    }

    // Handle change
    classSelect.addEventListener('change', function() {
        loadSections(this.value);
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>
