<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'parent') {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';

$page_title = "Parent Dashboard";
require_once '../includes/header.php';
require_once 'sidebar.php';

$db = Database::getInstance()->getConnection();
$parent_id = 0;

// Fetch Parent ID
$stmt = $db->prepare("SELECT parent_id, father_name, mother_name FROM parents WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$parentData = $stmt->fetch(PDO::FETCH_ASSOC);

if ($parentData) {
    $parent_id = $parentData['parent_id'];
    $name = $parentData['father_name']; // Display name
} else {
    echo "Parent Profile Not Found";
    exit;
}

// Fetch Children
$childStmt = $db->prepare("SELECT s.*, c.class_name, sc.section_name 
                           FROM students s 
                           JOIN classes c ON s.class_id = c.class_id 
                           LEFT JOIN sections sc ON sc.section_id = s.section_id 
                           WHERE s.parent_id = ?");
$childStmt->execute([$parent_id]);
?>

<main class="main-content">
    <!-- Enhanced Parent Header -->
    <div class="parent-dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="welcome-message">
                    <h1 class="dashboard-title">
                        <i class="fas fa-home me-3"></i>Parent Portal
                    </h1>
                    <p class="dashboard-subtitle">
                        Welcome back, <strong><?php echo htmlspecialchars($name); ?></strong>! Stay connected with your children's educational journey.
                    </p>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div class="family-stats">
                    <div class="stat-mini">
                        <span class="stat-mini-number"><?php echo $childStmt->rowCount(); ?></span>
                        <span class="stat-mini-label">Children</span>
                    </div>
                    <div class="stat-mini">
                        <span class="stat-mini-number">92%</span>
                        <span class="stat-mini-label">Avg Attendance</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Children Cards Section -->
    <div class="children-section">
        <div class="section-header">
            <h3 class="section-title">
                <i class="fas fa-users me-2"></i>My Children
            </h3>
            <p class="section-subtitle">Monitor your children's academic progress and school activities</p>
        </div>

        <div class="children-grid">
            <?php while ($child = $childStmt->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="child-card">
                <div class="child-card-header">
                    <div class="child-avatar">
                        <div class="avatar-circle">
                            <?php echo strtoupper(substr($child['first_name'], 0, 1)); ?>
                        </div>
                        <div class="status-indicator active"></div>
                    </div>
                    <div class="child-info">
                        <h4 class="child-name"><?php echo htmlspecialchars($child['first_name'] . ' ' . $child['last_name']); ?></h4>
                        <div class="child-details">
                            <span class="class-badge">
                                <i class="fas fa-graduation-cap me-1"></i>
                                <?php echo $child['class_name'] . ($child['section_name'] ? ' - ' . $child['section_name'] : ''); ?>
                            </span>
                            <span class="admission-number">
                                <i class="fas fa-id-card me-1"></i>
                                <?php echo $child['admission_no']; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats for Child -->
                <div class="child-quick-stats">
                    <div class="quick-stat">
                        <div class="stat-icon attendance">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-value">95%</span>
                            <span class="stat-label">Attendance</span>
                        </div>
                    </div>
                    <div class="quick-stat">
                        <div class="stat-icon performance">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-value">B+</span>
                            <span class="stat-label">Grade</span>
                        </div>
                    </div>
                    <div class="quick-stat">
                        <div class="stat-icon fees">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-value">Paid</span>
                            <span class="stat-label">Fees</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="child-actions">
                    <a href="child_attendance.php?student_id=<?php echo $child['student_id']; ?>" class="action-btn attendance">
                        <i class="fas fa-calendar-check"></i>
                        <span>Attendance</span>
                    </a>
                    <a href="child_marks.php?student_id=<?php echo $child['student_id']; ?>" class="action-btn results">
                        <i class="fas fa-chart-bar"></i>
                        <span>Results</span>
                    </a>
                    <a href="child_fees.php?student_id=<?php echo $child['student_id']; ?>" class="action-btn fees">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Fees</span>
                    </a>
                </div>

                <!-- Recent Activity -->
                <div class="child-activity">
                    <h6 class="activity-title">Recent Updates</h6>
                    <div class="activity-items">
                        <div class="activity-item">
                            <span class="activity-dot success"></span>
                            <span class="activity-text">Mathematics test: 85/100</span>
                        </div>
                        <div class="activity-item">
                            <span class="activity-dot info"></span>
                            <span class="activity-text">Fee payment confirmed</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Parent Dashboard Additional Sections -->
    <div class="row parent-content-row">
        <!-- Family Overview -->
        <div class="col-lg-8">
            <div class="modern-card family-overview">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-chart-pie me-2"></i>Family Academic Overview
                    </h5>
                    <div class="card-actions">
                        <button class="btn btn-sm btn-outline-primary">Download Report</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="overview-stats">
                        <div class="overview-stat">
                            <div class="stat-circle attendance-circle">
                                <span class="circle-percentage">92%</span>
                            </div>
                            <div class="stat-details">
                                <h6>Overall Attendance</h6>
                                <p>Excellent attendance record across all children</p>
                            </div>
                        </div>
                        
                        <div class="overview-stat">
                            <div class="stat-circle performance-circle">
                                <span class="circle-grade">B+</span>
                            </div>
                            <div class="stat-details">
                                <h6>Average Performance</h6>
                                <p>Strong academic performance in most subjects</p>
                            </div>
                        </div>
                        
                        <div class="overview-stat">
                            <div class="stat-circle fees-circle">
                                <span class="circle-status">✓</span>
                            </div>
                            <div class="stat-details">
                                <h6>Fee Status</h6>
                                <p>All payments up to date</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Notifications -->
        <div class="col-lg-4">
            <div class="modern-card notifications-card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-bell me-2"></i>School Updates
                    </h5>
                </div>
                <div class="card-body">
                    <div class="notification-item important">
                        <div class="notification-icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="notification-content">
                            <h6>Parent-Teacher Meeting</h6>
                            <p>Schedule: December 20, 2024</p>
                            <span class="notification-time">3 days ago</span>
                        </div>
                    </div>
                    
                    <div class="notification-item info">
                        <div class="notification-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="notification-content">
                            <h6>Winter Break Schedule</h6>
                            <p>Holidays from Dec 25 - Jan 5</p>
                            <span class="notification-time">1 week ago</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modern-card parent-actions">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-tools me-2"></i>Parent Tools
                    </h5>
                </div>
                <div class="card-body">
                    <div class="parent-tool-grid">
                        <a href="#" class="tool-item">
                            <i class="fas fa-comments"></i>
                            <span>Message Teachers</span>
                        </a>
                        <a href="#" class="tool-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>School Calendar</span>
                        </a>
                        <a href="#" class="tool-item">
                            <i class="fas fa-download"></i>
                            <span>Download Reports</span>
                        </a>
                        <a href="#" class="tool-item">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
