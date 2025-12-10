<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'student') {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';

$page_title = "Student Dashboard";
require_once '../includes/header.php';
require_once 'sidebar.php';

$db = Database::getInstance()->getConnection();
$student_id = 0;

// Fetch Student ID
$stmt = $db->prepare("SELECT student_id, first_name, last_name, class_id FROM students WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$studentReq = $stmt->fetch(PDO::FETCH_ASSOC);

if ($studentReq) {
    $student_id = $studentReq['student_id'];
    $name = $studentReq['first_name'];
    $class_id = $studentReq['class_id'];
} else {
    echo "Student Profile Not Found";
    exit;
}

// Calculate Attendance %
$attStmt = $db->prepare("SELECT 
    COUNT(*) as total_days,
    SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present_days
    FROM attendance WHERE student_id = ?");
$attStmt->execute([$student_id]);
$attData = $attStmt->fetch(PDO::FETCH_ASSOC);
$attendance_pct = ($attData['total_days'] > 0) ? round(($attData['present_days'] / $attData['total_days']) * 100, 1) : 0;

// Get Recent Fees
$feeStmt = $db->prepare("SELECT COUNT(*) as pending_fees FROM fees WHERE student_id = ? AND status != 'Paid'");
$feeStmt->execute([$student_id]);
$pending_fees = $feeStmt->fetchColumn();

// Recent Marks
$marksStmt = $db->prepare("SELECT s.subject_name, g.marks_obtained, e.exam_name 
    FROM grades g
    JOIN subjects s ON g.subject_id = s.subject_id 
    JOIN exams e ON g.exam_id = e.exam_id
    WHERE g.student_id = ? 
    ORDER BY e.start_date DESC LIMIT 5");
$marksStmt->execute([$student_id]);

?>

<main class="main-content">
    <!-- Enhanced Student Header -->
    <div class="student-dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="welcome-message">
                    <h1 class="dashboard-title">
                        <i class="fas fa-user-graduate me-3"></i>Student Portal
                    </h1>
                    <p class="dashboard-subtitle">
                        Welcome back, <strong><?php echo htmlspecialchars($name); ?></strong>! Ready for another great day of learning?
                    </p>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div class="student-avatar">
                    <div class="avatar-circle">
                        <?php echo strtoupper(substr($name, 0, 2)); ?>
                    </div>
                    <div class="status-indicator active"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="row student-stats-row">
        <div class="col-lg-3 col-md-6">
            <div class="student-stat-card attendance-card">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number counter" data-count="<?php echo $attendance_pct; ?>"><?php echo $attendance_pct; ?>%</div>
                        <div class="stat-label">Attendance Rate</div>
                        <div class="stat-progress">
                            <div class="progress-bar" style="width: <?php echo $attendance_pct; ?>%"></div>
                        </div>
                    </div>
                    <a href="attendance.php" class="stat-action">View Details</a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="student-stat-card fees-card">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number counter" data-count="<?php echo $pending_fees; ?>"><?php echo $pending_fees; ?></div>
                        <div class="stat-label">Pending Payments</div>
                        <div class="stat-status <?php echo $pending_fees > 0 ? 'urgent' : 'good'; ?>">
                            <?php echo $pending_fees > 0 ? 'Action Required' : 'All Clear'; ?>
                        </div>
                    </div>
                    <a href="fees.php" class="stat-action">View Fees</a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="student-stat-card performance-card">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">A-</div>
                        <div class="stat-label">Average Grade</div>
                        <div class="stat-trend up">
                            <i class="fas fa-arrow-up"></i> Improving
                        </div>
                    </div>
                    <a href="marks.php" class="stat-action">View Results</a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="student-stat-card schedule-card">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">6</div>
                        <div class="stat-label">Classes Today</div>
                        <div class="stat-next">Next: Mathematics</div>
                    </div>
                    <a href="timetable.php" class="stat-action">View Schedule</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Content Section -->
    <div class="row student-content-row">
        <!-- Recent Results -->
        <div class="col-lg-8">
            <div class="modern-card results-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-trophy me-2"></i>Recent Results
                    </h5>
                    <div class="card-actions">
                        <a href="marks.php" class="btn btn-sm btn-primary">View All Results</a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if ($marksStmt->rowCount() > 0): ?>
                        <div class="results-grid">
                            <?php while ($m = $marksStmt->fetch(PDO::FETCH_ASSOC)): ?>
                            <div class="result-item">
                                <div class="result-subject">
                                    <div class="subject-icon">
                                        <i class="fas fa-book-open"></i>
                                    </div>
                                    <div class="subject-info">
                                        <h6 class="subject-name"><?php echo htmlspecialchars($m['subject_name']); ?></h6>
                                        <span class="exam-name"><?php echo htmlspecialchars($m['exam_name']); ?></span>
                                    </div>
                                </div>
                                <div class="result-score">
                                    <div class="score-circle">
                                        <span class="score-number"><?php echo $m['marks_obtained']; ?></span>
                                        <span class="score-total">/100</span>
                                    </div>
                                    <div class="score-grade">
                                        <?php 
                                        $marks = $m['marks_obtained'];
                                        if ($marks >= 90) echo '<span class="grade grade-a">A+</span>';
                                        elseif ($marks >= 80) echo '<span class="grade grade-a">A</span>';
                                        elseif ($marks >= 70) echo '<span class="grade grade-b">B+</span>';
                                        elseif ($marks >= 60) echo '<span class="grade grade-b">B</span>';
                                        elseif ($marks >= 50) echo '<span class="grade grade-c">C</span>';
                                        else echo '<span class="grade grade-d">D</span>';
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h6 class="empty-title">No Results Yet</h6>
                            <p class="empty-text">Your exam results will appear here once they're published.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Notice Board & Quick Actions -->
        <div class="col-lg-4">
            <!-- Notice Board -->
            <div class="modern-card notice-card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-bell me-2"></i>Announcements
                    </h5>
                </div>
                <div class="card-body">
                    <div class="notice-item priority-high">
                        <div class="notice-dot"></div>
                        <div class="notice-content">
                            <h6 class="notice-title">Exam Schedule Released</h6>
                            <p class="notice-text">Mid-term examinations will begin next week. Check your schedule.</p>
                            <span class="notice-time">2 hours ago</span>
                        </div>
                    </div>
                    
                    <div class="notice-item priority-medium">
                        <div class="notice-dot"></div>
                        <div class="notice-content">
                            <h6 class="notice-title">Library Extended Hours</h6>
                            <p class="notice-text">Library will stay open until 8 PM during exam week.</p>
                            <span class="notice-time">1 day ago</span>
                        </div>
                    </div>
                    
                    <div class="notice-item priority-low">
                        <div class="notice-dot"></div>
                        <div class="notice-content">
                            <h6 class="notice-title">Sports Day Registration</h6>
                            <p class="notice-text">Register for upcoming sports events before Friday.</p>
                            <span class="notice-time">3 days ago</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="modern-card quick-actions-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-bolt me-2"></i>Quick Access
                    </h5>
                </div>
                <div class="card-body">
                    <div class="student-quick-actions">
                        <a href="attendance.php" class="quick-action-btn attendance">
                            <i class="fas fa-calendar-check"></i>
                            <span>View Attendance</span>
                        </a>
                        <a href="timetable.php" class="quick-action-btn schedule">
                            <i class="fas fa-clock"></i>
                            <span>Class Schedule</span>
                        </a>
                        <a href="marks.php" class="quick-action-btn results">
                            <i class="fas fa-chart-bar"></i>
                            <span>All Results</span>
                        </a>
                        <a href="fees.php" class="quick-action-btn fees">
                            <i class="fas fa-credit-card"></i>
                            <span>Pay Fees</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
