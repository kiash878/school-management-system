<?php
require_once '../includes/config/config.php';
require_once '../includes/classes/Student.php';

// Check if user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

$page_title = "My Profile";

// Get student information
$student = new Student();
$student_data = $student->getStudentByUserId($_SESSION['user_id']);

if (!$student_data) {
    header("Location: ../login.php");
    exit;
}

// Handle profile update
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    
    if ($student->updateProfile($_SESSION['user_id'], $first_name, $last_name, $email, $address)) {
        $message = "Profile updated successfully!";
        // Refresh data
        $student_data = $student->getStudentByUserId($_SESSION['user_id']);
    } else {
        $error = "Failed to update profile. Please try again.";
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password !== $confirm_password) {
        $error = "New passwords do not match.";
    } elseif (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($student->changePassword($_SESSION['user_id'], $current_password, $new_password)) {
        $message = "Password changed successfully!";
    } else {
        $error = "Current password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title . ' - ' . SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- School Header -->
    <div class="school-header">
        <a href="../index.php" class="school-logo">
            <i class="fas fa-graduation-cap"></i>
        </a>
        <span class="school-name">School Management System</span>
        <div class="ms-auto d-flex align-items-center">
            <span class="text-dark me-3 d-none d-md-block fw-semibold">
                Welcome, <?php echo htmlspecialchars($student_data['first_name']); ?>
            </span>
            <a href="../logout.php" class="btn btn-sm" style="background: var(--danger-color); color: white; border: none;">
                <i class="fas fa-sign-out-alt me-1"></i>Logout
            </a>
        </div>
    </div>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <!-- Enhanced Profile Header -->
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-title-section">
                        <h1 class="profile-title">
                            <i class="fas fa-user-circle me-3"></i>My Profile
                        </h1>
                        <p class="profile-subtitle">Manage your personal information and account settings</p>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="student-avatar-large">
                        <div class="avatar-circle-large">
                            <?php echo strtoupper(substr($student_data['first_name'], 0, 2)); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if ($message): ?>
            <div class="alert alert-success modern-alert" role="alert">
                <div class="alert-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="alert-content">
                    <h6 class="alert-title">Success!</h6>
                    <p class="alert-message"><?php echo $message; ?></p>
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

        <!-- Profile Content -->
        <div class="row">
            <!-- Student Information Card -->
            <div class="col-lg-8">
                <div class="modern-card profile-info-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-id-card me-2"></i>Personal Information
                        </h5>
                        <button class="btn btn-sm btn-primary" onclick="toggleEditMode()">
                            <i class="fas fa-edit me-1"></i>Edit Profile
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Display Mode -->
                        <div id="display-mode">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label class="info-label">First Name</label>
                                        <div class="info-value"><?php echo htmlspecialchars($student_data['first_name']); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label class="info-label">Last Name</label>
                                        <div class="info-value"><?php echo htmlspecialchars($student_data['last_name']); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label class="info-label">Email Address</label>
                                        <div class="info-value"><?php echo htmlspecialchars($student_data['email']); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label class="info-label">Admission Number</label>
                                        <div class="info-value"><?php echo htmlspecialchars($student_data['admission_no']); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Address</label>
                                <div class="info-value"><?php echo htmlspecialchars($student_data['address'] ?: 'Not provided'); ?></div>
                            </div>
                        </div>

                        <!-- Edit Mode -->
                        <div id="edit-mode" style="display: none;">
                            <form method="POST" action="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">First Name *</label>
                                            <input type="text" name="first_name" class="form-control" 
                                                   value="<?php echo htmlspecialchars($student_data['first_name']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Last Name *</label>
                                            <input type="text" name="last_name" class="form-control" 
                                                   value="<?php echo htmlspecialchars($student_data['last_name']); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email Address *</label>
                                    <input type="email" name="email" class="form-control" 
                                           value="<?php echo htmlspecialchars($student_data['email']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($student_data['address'] ?: ''); ?></textarea>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" name="update_profile" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Save Changes
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary ms-2" onclick="cancelEdit()">
                                        <i class="fas fa-times me-1"></i>Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Information & Security -->
            <div class="col-lg-4">
                <!-- Academic Info -->
                <div class="modern-card academic-info-card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-graduation-cap me-2"></i>Academic Info
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="academic-item">
                            <div class="academic-icon">
                                <i class="fas fa-school"></i>
                            </div>
                            <div class="academic-details">
                                <span class="academic-label">Class</span>
                                <span class="academic-value"><?php echo htmlspecialchars($student_data['class_name'] ?? 'Not Assigned'); ?></span>
                            </div>
                        </div>
                        <div class="academic-item">
                            <div class="academic-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="academic-details">
                                <span class="academic-label">Date of Birth</span>
                                <span class="academic-value"><?php echo htmlspecialchars($student_data['dob'] ?? 'Not provided'); ?></span>
                            </div>
                        </div>
                        <div class="academic-item">
                            <div class="academic-icon">
                                <i class="fas fa-venus-mars"></i>
                            </div>
                            <div class="academic-details">
                                <span class="academic-label">Gender</span>
                                <span class="academic-value"><?php echo htmlspecialchars($student_data['gender'] ?? 'Not provided'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="modern-card security-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-lock me-2"></i>Security Settings
                        </h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-outline-primary w-100 mb-3" onclick="togglePasswordForm()">
                            <i class="fas fa-key me-2"></i>Change Password
                        </button>
                        
                        <!-- Password Change Form -->
                        <div id="password-form" style="display: none;">
                            <form method="POST" action="">
                                <div class="form-group">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="new_password" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" name="confirm_password" class="form-control" required>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" name="change_password" class="btn btn-primary btn-sm">
                                        <i class="fas fa-save me-1"></i>Update Password
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="togglePasswordForm()">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="security-info">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                For security, use a strong password with at least 6 characters.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleEditMode() {
            const displayMode = document.getElementById('display-mode');
            const editMode = document.getElementById('edit-mode');
            
            displayMode.style.display = 'none';
            editMode.style.display = 'block';
        }

        function cancelEdit() {
            const displayMode = document.getElementById('display-mode');
            const editMode = document.getElementById('edit-mode');
            
            displayMode.style.display = 'block';
            editMode.style.display = 'none';
        }

        function togglePasswordForm() {
            const passwordForm = document.getElementById('password-form');
            passwordForm.style.display = passwordForm.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>