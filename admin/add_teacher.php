<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';
require_once '../includes/classes/Teacher.php';

$page_title = "Add Teacher";
require_once '../includes/header.php';
require_once 'sidebar.php';

$msg = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'email' => $_POST['email'],
        'username' => $_POST['email'],
        'password' => 'password123',
        'qualification' => $_POST['qualification'],
        'phone' => $_POST['phone'],
        'address' => $_POST['address'],
        'hire_date' => $_POST['hire_date']
    ];

    $teacher = new Teacher();
    try {
        if ($teacher->create($data)) {
            $msg = "Teacher added successfully!";
        } else {
            $error = "Failed to add teacher. Email might be in use.";
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
                        <i class="fas fa-chalkboard-teacher me-3"></i>Add Teacher
                    </h1>
                    <p class="form-subtitle">Add a new teacher to your school faculty</p>
                    <nav class="form-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="teachers.php">Teachers</a></li>
                            <li class="breadcrumb-item active">Add New</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <a href="teachers.php" class="btn btn-outline-secondary btn-lg">
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
                    <i class="fas fa-user-tie me-3"></i>Teacher Information
                </h2>
                <p class="form-card-subtitle">Please fill in all required fields marked with *</p>
            </div>
            
            <form method="POST" action="" class="modern-form" id="teacherForm">
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
                                           placeholder="Enter first name">
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
                                           placeholder="Enter last name">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="form-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-address-card me-2"></i>Contact Information
                        </h3>
                        <div class="section-divider"></div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email Address *</label>
                                <div class="input-group">
                                    <span class="input-icon">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" name="email" class="form-control" required 
                                           placeholder="Enter email address">
                                </div>
                                <div class="form-help">This will be used for login credentials</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-icon">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input type="text" name="phone" class="form-control" 
                                           placeholder="Enter phone number">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Address</label>
                        <div class="textarea-group">
                            <span class="textarea-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                            <textarea name="address" class="form-control" rows="3" 
                                      placeholder="Enter complete address"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Professional Information Section -->
                <div class="form-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-graduation-cap me-2"></i>Professional Information
                        </h3>
                        <div class="section-divider"></div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Qualification</label>
                                <div class="input-group">
                                    <span class="input-icon">
                                        <i class="fas fa-certificate"></i>
                                    </span>
                                    <input type="text" name="qualification" class="form-control" 
                                           placeholder="e.g., B.Ed, M.A. Mathematics">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Hire Date *</label>
                                <div class="input-group">
                                    <span class="input-icon">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <input type="date" name="hire_date" class="form-control" required 
                                           value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-note">
                                <i class="fas fa-info-circle me-2"></i>
                                Default password will be set to "password123". Teacher can change it after first login.
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="reset" class="btn btn-outline-secondary me-3">
                                <i class="fas fa-undo me-2"></i>Reset Form
                            </button>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Add Teacher
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
