<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';

require_once '../includes/classes/Settings.php';
require_once '../includes/classes/User.php';

$page_title = "Settings";
require_once '../includes/header.php';
require_once 'sidebar.php';

$settingsObj = new Settings();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_general'])) {
        $settingsObj->update('school_name', $_POST['school_name']);
        $settingsObj->update('school_email', $_POST['school_email']);
        $settingsObj->update('school_phone', $_POST['school_phone']);
        $settingsObj->update('school_address', $_POST['school_address']);
        $msg = "General settings updated successfully!";
    }
    
    if (isset($_POST['save_branding'])) {
        if (isset($_FILES['school_logo']) && $_FILES['school_logo']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['school_logo']['name'];
            $filetype = $_FILES['school_logo']['type'];
            $filesize = $_FILES['school_logo']['size'];
            
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (!in_array(strtolower($ext), $allowed)) {
                $msg = "Error: Invalid file format. Please upload JPG, PNG, or GIF.";
            } else {
                $newFilename = "logo." . $ext;
                $uploadDir = "../assets/uploads/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $destination = $uploadDir . $newFilename;
                if (move_uploaded_file($_FILES['school_logo']['tmp_name'], $destination)) {
                    // Save path to DB (relative path)
                    $settingsObj->update('school_logo', 'assets/uploads/' . $newFilename);
                    $msg = "Logo uploaded successfully!";
                } else {
                    $msg = "Error moving uploaded file.";
                }
            }
        }
    }
}

$currentSettings = $settingsObj->getAll();
// Fallbacks
$sName = $currentSettings['school_name'] ?? SITE_NAME;
$sEmail = $currentSettings['school_email'] ?? 'admin@school.com';
$sPhone = $currentSettings['school_phone'] ?? '+1 234 567 890';
$sAddr = $currentSettings['school_address'] ?? '123 School Lane';
$sLogo = $currentSettings['school_logo'] ?? '';
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <div>
            <h1 class="h2">System Settings</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Settings</li>
                </ol>
            </nav>
        </div>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $msg; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0 text-primary"><i class="fas fa-university me-2"></i> General Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <input type="hidden" name="save_general" value="1">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">School Name</label>
                            <input type="text" name="school_name" class="form-control" value="<?php echo htmlspecialchars($sName); ?>">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Email</label>
                                <input type="email" name="school_email" class="form-control" value="<?php echo htmlspecialchars($sEmail); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Phone</label>
                                <input type="text" name="school_phone" class="form-control" value="<?php echo htmlspecialchars($sPhone); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Address</label>
                            <textarea name="school_address" class="form-control" rows="3"><?php echo htmlspecialchars($sAddr); ?></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i> Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                 <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0 text-danger"><i class="fas fa-lock me-2"></i> Security Settings</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Current Password</label>
                                <input type="password" name="current_password" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                             <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">New Password</label>
                                <input type="password" name="new_password" class="form-control">
                            </div>
                             <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-danger px-4"><i class="fas fa-key me-2"></i> Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                     <h5 class="card-title mb-0 text-info"><i class="fas fa-image me-2"></i> Branding</h5>
                </div>
                <div class="card-body text-center">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="save_branding" value="1">
                        <div class="mb-3">
                            <div class="bg-light p-4 rounded mb-3 d-flex justify-content-center align-items-center" style="height: 150px; overflow: hidden;">
                                <?php if ($sLogo && file_exists('../' . $sLogo)): ?>
                                    <img src="../<?php echo $sLogo; ?>?v=<?php echo time(); ?>" alt="Logo" class="img-fluid" style="max-height: 100%;">
                                <?php else: ?>
                                    <i class="fas fa-school fa-3x text-muted"></i>
                                <?php endif; ?>
                            </div>
                            <label class="form-label fw-bold small d-block text-start">Logo</label>
                            <input type="file" name="school_logo" class="form-control" accept="image/*" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-info text-white"><i class="fas fa-upload me-2"></i> Upload Logo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
