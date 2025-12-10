<?php
require_once '../includes/config/config.php';
require_once '../includes/classes/Notification.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$page_title = "Announcements";

$notification = new Notification();

// Handle form submissions
$message = '';
$error = '';

// Create new announcement
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_announcement'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $target_role = $_POST['target_role'];
    
    if (empty($title) || empty($content)) {
        $error = "Please fill in all required fields.";
    } else {
        if ($notification->createAnnouncement($title, $content, $target_role, $_SESSION['user_id'])) {
            $message = "Announcement '$title' created successfully!";
        } else {
            $error = "Failed to create announcement.";
        }
    }
}

// Delete announcement
if (isset($_GET['delete_announcement'])) {
    $announcement_id = intval($_GET['delete_announcement']);
    if ($notification->deleteAnnouncement($announcement_id)) {
        $message = "Announcement deleted successfully!";
    } else {
        $error = "Failed to delete announcement.";
    }
}

// Toggle announcement status
if (isset($_GET['toggle_status'])) {
    $announcement_id = intval($_GET['toggle_status']);
    if ($notification->toggleAnnouncementStatus($announcement_id)) {
        $message = "Announcement status updated successfully!";
    } else {
        $error = "Failed to update announcement status.";
    }
}

// Get all announcements
$announcements = $notification->getAllAnnouncements();
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
    <?php require_once '../includes/header.php'; ?>
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <!-- Enhanced Announcements Header -->
        <div class="announcements-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-title-section">
                        <h1 class="announcements-title">
                            <i class="fas fa-bullhorn me-3"></i>Announcements
                        </h1>
                        <p class="announcements-subtitle">Create and manage school-wide announcements</p>
                        <nav class="announcements-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Announcements</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#createAnnouncementModal">
                        <i class="fas fa-plus me-2"></i>New Announcement
                    </button>
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

        <!-- Announcements List -->
        <div class="announcements-grid">
            <?php while ($announcement = $announcements->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="announcement-card">
                <div class="announcement-header">
                    <div class="announcement-priority priority-normal">
                        <i class="fas fa-bullhorn"></i>
                        <span>Announcement</span>
                    </div>
                    <div class="announcement-status">
                        <span class="status-badge status-active">Active</span>
                    </div>
                </div>

                <div class="announcement-body">
                    <h3 class="announcement-title"><?php echo htmlspecialchars($announcement['title']); ?></h3>
                    <p class="announcement-message"><?php echo nl2br(htmlspecialchars($announcement['content'])); ?></p>
                    
                    <div class="announcement-meta">
                        <div class="announcement-audience">
                            <i class="fas fa-users me-1"></i>
                            <span><?php echo ucfirst($announcement['target_role']); ?></span>
                        </div>
                        <div class="announcement-date">
                            <i class="fas fa-calendar me-1"></i>
                            <span><?php echo date('M j, Y g:i A', strtotime($announcement['created_at'])); ?></span>
                        </div>
                        <?php if (!empty($announcement['creator_name'])): ?>
                        <div class="announcement-creator">
                            <i class="fas fa-user me-1"></i>
                            <span>By: <?php echo htmlspecialchars($announcement['creator_name']); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="announcement-actions">
                    <button class="btn btn-sm btn-outline-primary" onclick="editAnnouncement(<?php echo $announcement['announcement_id']; ?>)">
                        <i class="fas fa-edit me-1"></i>Edit
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteAnnouncement(<?php echo $announcement['announcement_id']; ?>, '<?php echo htmlspecialchars($announcement['title']); ?>')">
                        <i class="fas fa-trash me-1"></i>Delete
                    </button>
                </div>
            </div>
            <?php endwhile; ?>
            
            <?php if ($announcements->rowCount() === 0): ?>
            <div class="empty-announcements">
                <div class="empty-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <h3 class="empty-title">No Announcements Yet</h3>
                <p class="empty-text">Create your first announcement to communicate with your school community.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAnnouncementModal">
                    <i class="fas fa-plus me-2"></i>Create First Announcement
                </button>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Create Announcement Modal -->
    <div class="modal fade" id="createAnnouncementModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-bullhorn me-2"></i>Create New Announcement
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label class="form-label">Title *</label>
                            <input type="text" name="title" class="form-control" placeholder="Enter announcement title" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label class="form-label">Content *</label>
                            <textarea name="content" class="form-control" rows="5" placeholder="Enter your announcement content" required></textarea>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label class="form-label">Target Audience *</label>
                            <select name="target_role" class="form-select" required>
                                <option value="all">Everyone</option>
                                <option value="student">Students Only</option>
                                <option value="teacher">Teachers Only</option>
                                <option value="parent">Parents Only</option>
                            </select>
                        </div>
                        
                        <div class="form-help">
                            <i class="fas fa-info-circle me-2"></i>
                            Announcements will be visible to the selected audience immediately after creation.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="create_announcement" class="btn btn-primary">
                            <i class="fas fa-bullhorn me-2"></i>Create Announcement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/theme-toggle.js"></script>
    <script src="../assets/js/visual-enhancements.js"></script>
    
    <script>
        function editAnnouncement(id) {
            // TODO: Implement edit functionality
            alert('Edit functionality coming soon!');
        }

        function toggleStatus(id, action) {
            if (confirm(`Are you sure you want to ${action} this announcement?`)) {
                window.location.href = `announcements.php?toggle_status=${id}`;
            }
        }

        function deleteAnnouncement(id, title) {
            if (confirm(`Are you sure you want to delete the announcement "${title}"?\n\nThis action cannot be undone.`)) {
                if (window.VE) {
                    window.VE.showLoading('Deleting announcement...');
                }
                window.location.href = `announcements.php?delete_announcement=${id}`;
            }
        }

        // Auto-hide alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                if (alert.querySelector('.btn-close')) {
                    alert.querySelector('.btn-close').click();
                }
            });
        }, 5000);

        // Form validation
        document.querySelector('#createAnnouncementModal form').addEventListener('submit', function(e) {
            const title = this.querySelector('[name="title"]').value.trim();
            const content = this.querySelector('[name="content"]').value.trim();
            
            if (!title || !content) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return false;
            }
            
            if (window.VE) {
                window.VE.formLoading(this);
            }
        });
    </script>
</body>
</html>