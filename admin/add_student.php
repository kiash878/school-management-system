<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';
require_once '../includes/classes/Student.php';
require_once '../includes/classes/Parents.php';
require_once '../includes/classes/SchoolClass.php';

$page_title = "Add Student";
require_once '../includes/header.php';
require_once 'sidebar.php';

$msg = '';
$error = '';

// Fetch Data for Dropdowns
$parentObj = new Parents();
$parents = $parentObj->read();

$classObj = new SchoolClass();
$classes = $classObj->getClasses();

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'admission_no' => $_POST['admission_no'],
        'dob' => $_POST['dob'],
        'gender' => $_POST['gender'],
        'email' => $_POST['email'],
        'username' => $_POST['email'], // Using email as username for now
        'password' => 'password123', // Default password
        'parent_id' => $_POST['parent_id'],
        'class_id' => $_POST['class_id'],
        'section_id' => $_POST['section_id'],
        'address' => $_POST['address'],
        'admission_date' => date('Y-m-d')
    ];

    $student = new Student();
    try {
        if ($student->create($data)) {
            $msg = "Student admmitted successfully!";
        } else {
            $error = "Failed to add student. Check admission number or email uniqueness.";
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<main class="main-content">
    <!-- Enhanced Form Header -->
    <div class="form-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="page-title-section">
                    <h1 class="form-title">
                        <i class="fas fa-user-graduate me-3"></i>Add Student
                    </h1>
                    <p class="form-subtitle">Enroll a new student in your school</p>
                    <nav class="form-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="students.php">Students</a></li>
                            <li class="breadcrumb-item active">Add New</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <a href="students.php" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced Alert Messages -->
    <?php if ($msg): ?>
        <div class="alert alert-success modern-alert" role="alert">
            <div class="alert-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="alert-content">
                <h6 class="alert-title">Success!</h6>
                <p class="alert-message"><?php echo $msg; ?></p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger modern-alert" role="alert">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="alert-content">
                <h6 class="alert-title">Error!</h6>
                <p class="alert-message"><?php echo $error; ?></p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Enhanced Form Container -->
    <div class="modern-form-container">
        <div class="form-card">
            <div class="form-card-header">
                <h2 class="form-card-title">
                    <i class="fas fa-user-graduate me-3"></i>Student Admission
                </h2>
                <p class="form-card-subtitle">Complete student enrollment process with all required information</p>
            </div>
            
            <form method="POST" action="" class="modern-form" id="studentForm">
                <!-- Personal Information Section -->
                <div class="form-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-user me-2"></i>Personal Information
                        </h3>
                        <div class="section-divider"></div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">First Name *</label>
                                <div class="input-group">
                                    <span class="input-icon">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" name="first_name" class="form-control" required 
                                           placeholder="Enter student's first name">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Last Name *</label>
                                <div class="input-group">
                                    <span class="input-icon">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" name="last_name" class="form-control" required 
                                           placeholder="Enter student's last name">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Admission Number *</label>
                                <div class="input-group">
                                    <span class="input-icon">
                                        <i class="fas fa-id-badge"></i>
                                    </span>
                                    <input type="text" name="admission_no" class="form-control" required 
                                           placeholder="e.g., 2024/001">
                                </div>
                                <div class="form-help">Unique identification number for the student</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Date of Birth *</label>
                                <div class="input-group">
                                    <span class="input-icon">
                                        <i class="fas fa-birthday-cake"></i>
                                    </span>
                                    <input type="date" name="dob" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Gender *</label>
                                <select name="gender" class="form-select" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <div class="input-group">
                            <span class="input-icon">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" name="email" class="form-control" required 
                                   placeholder="student@example.com">
                        </div>
                        <div class="form-help">This will be used for student login credentials</div>
                    </div>
                </div>

                <!-- Academic Information Section -->
                <div class="form-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-graduation-cap me-2"></i>Academic Information
                        </h3>
                        <div class="section-divider"></div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Class *</label>
                                <select name="class_id" id="class_id" class="form-select" required>
                                    <option value="">Select Class</option>
                                    <?php while ($row = $classes->fetch(PDO::FETCH_ASSOC)): ?>
                                        <option value="<?php echo $row['class_id']; ?>">
                                            <?php echo $row['class_name']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Section *</label>
                                <select name="section_id" id="section_id" class="form-select" required>
                                    <option value="">Select Class First</option>
                                </select>
                                <div class="form-help">Available sections will load after selecting class</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parent & Contact Information Section -->
                <div class="form-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-users me-2"></i>Parent & Contact Information
                        </h3>
                        <div class="section-divider"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Parent/Guardian *</label>
                        <select name="parent_id" class="form-select" required>
                            <option value="">Select Parent/Guardian</option>
                            <?php while ($row = $parents->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?php echo $row['parent_id']; ?>">
                                    <?php echo $row['father_name'] . " & " . $row['mother_name'] . " (" . $row['email'] . ")"; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <div class="form-help">
                            <i class="fas fa-info-circle me-1"></i>
                            Parent not in list? <a href="#" class="text-decoration-none fw-bold">Add Parent First</a>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Home Address</label>
                        <div class="textarea-group">
                            <span class="textarea-icon">
                                <i class="fas fa-home"></i>
                            </span>
                            <textarea name="address" class="form-control" rows="3" 
                                      placeholder="Enter complete home address"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="form-note">
                                <i class="fas fa-shield-alt me-2"></i>
                                Student will receive default password "password123" for first login. Password can be changed after initial access.
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="reset" class="btn btn-outline-secondary me-3">
                                <i class="fas fa-undo me-2"></i>Clear Form
                            </button>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Admit Student
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
document.getElementById('class_id').addEventListener('change', function() {
    var classId = this.value;
    var sectionSelect = document.getElementById('section_id');
    
    // Clear options
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
                    sectionSelect.add(option);
                });
            });
    } else {
        sectionSelect.innerHTML = '<option value="">Select Class First</option>';
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>
