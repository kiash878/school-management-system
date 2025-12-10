<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'teacher') {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';

$page_title = "Teacher Dashboard";
require_once '../includes/header.php';
require_once 'sidebar.php';

$teacher_id = 0;
// Need to get teacher_id from user_id
$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT teacher_id, first_name FROM teachers WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$teacherData = $stmt->fetch(PDO::FETCH_ASSOC);

if ($teacherData) {
    $teacher_id = $teacherData['teacher_id'];
    $teacher_name = $teacherData['first_name'];
} else {
    // Should not happen if data integrity is good
    echo "Teacher profile not found.";
    exit;
}

// Get Assigned Subjects/Classes
$classesStmt = $db->prepare("SELECT s.subject_name, c.class_name, c.numeric_grade 
                             FROM subjects s 
                             JOIN classes c ON s.class_id = c.class_id 
                             WHERE s.teacher_id = ?");
$classesStmt->execute([$teacher_id]);
?>

<main class="main-content">
    <!-- Enhanced Teacher Header -->
    <div class="teacher-dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="welcome-message">
                    <h1 class="dashboard-title">
                        <i class="fas fa-chalkboard-teacher me-3"></i>Teacher Portal
                    </h1>
                    <p class="dashboard-subtitle">
                        Welcome back, <strong><?php echo htmlspecialchars($teacher_name); ?></strong>! Ready to inspire minds today?
                    </p>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div class="teacher-stats-mini">
                    <div class="stat-mini">
                        <span class="stat-mini-number"><?php echo $classesStmt->rowCount(); ?></span>
                        <span class="stat-mini-label">Subjects</span>
                    </div>
                    <div class="stat-mini">
                        <span class="stat-mini-number">156</span>
                        <span class="stat-mini-label">Students</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Teacher Statistics Cards -->
    <div class="row teacher-stats-row">
        <div class="col-lg-4 col-md-6">
            <div class="teacher-stat-card subjects-card">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo $classesStmt->rowCount(); ?></div>
                        <div class="stat-label">Subjects Assigned</div>
                        <div class="stat-description">Active teaching assignments</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6">
            <div class="teacher-stat-card attendance-card">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">85%</div>
                        <div class="stat-label">Class Attendance</div>
                        <div class="stat-description">Average attendance rate</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6">
            <div class="teacher-stat-card performance-card">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">B+</div>
                        <div class="stat-label">Average Grade</div>
                        <div class="stat-description">Student performance</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Content Section -->
    <div class="row teacher-content-row">
        <!-- Assigned Subjects -->
        <div class="col-lg-6">
            <div class="modern-card subjects-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-books me-2"></i>My Subjects
                    </h5>
                    <div class="card-actions">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus me-1"></i>Request Subject
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if ($classesStmt->rowCount() > 0): ?>
                        <div class="subjects-list">
                            <?php while ($row = $classesStmt->fetch(PDO::FETCH_ASSOC)): ?>
                            <div class="subject-item">
                                <div class="subject-icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="subject-info">
                                    <h6 class="subject-name"><?php echo htmlspecialchars($row['subject_name']); ?></h6>
                                    <div class="subject-meta">
                                        <span class="class-info">
                                            <i class="fas fa-school me-1"></i>
                                            <?php echo htmlspecialchars($row['class_name']); ?>
                                        </span>
                                        <span class="grade-info">
                                            Grade <?php echo $row['numeric_grade']; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="subject-actions">
                                    <button class="btn btn-sm btn-primary">Manage</button>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <h6 class="empty-title">No Subjects Assigned</h6>
                            <p class="empty-text">Contact administration to get subject assignments.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Tools -->
        <div class="col-lg-6">
            <!-- Quick Actions Card -->
            <div class="modern-card quick-actions-card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="teacher-actions-grid">
                        <a href="attendance.php" class="teacher-action-btn attendance">
                            <div class="action-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="action-content">
                                <h6 class="action-title">Mark Attendance</h6>
                                <p class="action-desc">Record student attendance</p>
                            </div>
                            <i class="fas fa-arrow-right action-arrow"></i>
                        </a>

                        <a href="marks.php" class="teacher-action-btn marks">
                            <div class="action-icon">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="action-content">
                                <h6 class="action-title">Enter Marks</h6>
                                <p class="action-desc">Update student grades</p>
                            </div>
                            <i class="fas fa-arrow-right action-arrow"></i>
                        </a>

                        <a href="students.php" class="teacher-action-btn students">
                            <div class="action-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="action-content">
                                <h6 class="action-title">View Students</h6>
                                <p class="action-desc">Manage class roster</p>
                            </div>
                            <i class="fas fa-arrow-right action-arrow"></i>
                        </a>

                        <a href="timetable.php" class="teacher-action-btn schedule">
                            <div class="action-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="action-content">
                                <h6 class="action-title">My Schedule</h6>
                                <p class="action-desc">View teaching timetable</p>
                            </div>
                            <i class="fas fa-arrow-right action-arrow"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Today's Schedule -->
            <div class="modern-card schedule-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-calendar-day me-2"></i>Today's Classes
                    </h5>
                </div>
                <div class="card-body">
                    <div class="schedule-timeline">
                        <div class="schedule-item current">
                            <div class="schedule-time">
                                <span class="time">09:00</span>
                                <span class="duration">1hr</span>
                            </div>
                            <div class="schedule-info">
                                <h6 class="class-subject">Mathematics</h6>
                                <p class="class-details">Grade 10 - Room 205</p>
                            </div>
                            <div class="schedule-status current-class">Now</div>
                        </div>
                        
                        <div class="schedule-item upcoming">
                            <div class="schedule-time">
                                <span class="time">11:00</span>
                                <span class="duration">1hr</span>
                            </div>
                            <div class="schedule-info">
                                <h6 class="class-subject">Physics</h6>
                                <p class="class-details">Grade 11 - Lab 3</p>
                            </div>
                            <div class="schedule-status upcoming-class">Next</div>
                        </div>
                        
                        <div class="schedule-item">
                            <div class="schedule-time">
                                <span class="time">14:00</span>
                                <span class="duration">1hr</span>
                            </div>
                            <div class="schedule-info">
                                <h6 class="class-subject">Mathematics</h6>
                                <p class="class-details">Grade 9 - Room 201</p>
                            </div>
                            <div class="schedule-status">Later</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
