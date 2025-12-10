<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';

$page_title = "Admin Dashboard";
require_once '../includes/header.php';
require_once 'sidebar.php';

$db = Database::getInstance()->getConnection();

// Get Stats
$students_count = $db->query("SELECT COUNT(*) FROM students")->fetchColumn();
$teachers_count = $db->query("SELECT COUNT(*) FROM teachers")->fetchColumn();
// distinct subjects or just classes as 'Notes' placeholder
$classes_count = $db->query("SELECT COUNT(*) FROM classes")->fetchColumn(); 
$notices_count = $db->query("SELECT COUNT(*) FROM announcements")->fetchColumn();

// Get Latest Notices
$notices_stmt = $db->query("SELECT * FROM announcements ORDER BY created_at DESC LIMIT 5");
?>

<main class="main-content">
    <!-- Enhanced Header -->
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="dashboard-title">
                    <i class="fas fa-chart-line me-3"></i>Admin Dashboard
                </h1>
                <p class="dashboard-subtitle">
                    Welcome back! Here's what's happening at your school today.
                </p>
            </div>
            <div class="dashboard-actions">
                <button class="btn btn-primary me-2">
                    <i class="fas fa-plus me-2"></i>Quick Add
                </button>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-calendar me-2"></i>This Week
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Today</a></li>
                        <li><a class="dropdown-item" href="#">This Week</a></li>
                        <li><a class="dropdown-item" href="#">This Month</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Stats Cards -->
    <div class="row stats-row">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-teachers">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number counter" data-count="<?php echo $teachers_count; ?>">0</div>
                        <div class="stat-label">Expert Teachers</div>
                        <div class="stat-trend">
                            <i class="fas fa-arrow-up"></i> 5% from last month
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-students">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number counter" data-count="<?php echo $students_count; ?>">0</div>
                        <div class="stat-label">Active Students</div>
                        <div class="stat-trend">
                            <i class="fas fa-arrow-up"></i> 12% from last month
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-classes">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="fas fa-school"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number counter" data-count="<?php echo $classes_count; ?>">0</div>
                        <div class="stat-label">Total Classes</div>
                        <div class="stat-trend">
                            <i class="fas fa-minus"></i> No change
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-notices">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number counter" data-count="<?php echo $notices_count; ?>">0</div>
                        <div class="stat-label">Active Notices</div>
                        <div class="stat-trend">
                            <i class="fas fa-arrow-up"></i> 3 new today
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Content Section -->
    <div class="row content-row">
        <!-- Latest Notices -->
        <div class="col-lg-8">
            <div class="modern-card notices-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-bullhorn me-2"></i>Latest Notices
                    </h5>
                    <div class="card-actions">
                        <button class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>Add Notice
                        </button>
                        <button class="btn btn-sm btn-outline-secondary ms-2">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="notices-list">
                        <?php if ($notices_count > 0): ?>
                            <?php while ($notice = $notices_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                            <div class="notice-item">
                                <div class="notice-indicator notice-active"></div>
                                <div class="notice-content">
                                    <h6 class="notice-title"><?php echo htmlspecialchars($notice['title']); ?></h6>
                                    <div class="notice-meta">
                                        <span class="notice-date">
                                            <i class="fas fa-calendar me-1"></i>
                                            <?php echo date('d M, Y', strtotime($notice['created_at'])); ?>
                                        </span>
                                        <span class="notice-author">By Admin</span>
                                    </div>
                                </div>
                                <div class="notice-actions">
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <!-- Enhanced Mock Data -->
                            <div class="notice-item">
                                <div class="notice-indicator notice-active"></div>
                                <div class="notice-content">
                                    <h6 class="notice-title">School Fees Payment Deadline</h6>
                                    <div class="notice-meta">
                                        <span class="notice-date">
                                            <i class="fas fa-calendar me-1"></i>15 Dec, 2024
                                        </span>
                                        <span class="notice-author">By Admin</span>
                                    </div>
                                </div>
                                <div class="notice-actions">
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                </div>
                            </div>
                            
                            <div class="notice-item">
                                <div class="notice-indicator notice-urgent"></div>
                                <div class="notice-content">
                                    <h6 class="notice-title">Mid-term Examination Schedule</h6>
                                    <div class="notice-meta">
                                        <span class="notice-date">
                                            <i class="fas fa-calendar me-1"></i>12 Dec, 2024
                                        </span>
                                        <span class="notice-author">By Academic Office</span>
                                    </div>
                                </div>
                                <div class="notice-actions">
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                </div>
                            </div>
                            
                            <div class="notice-item">
                                <div class="notice-indicator notice-info"></div>
                                <div class="notice-content">
                                    <h6 class="notice-title">Winter Holiday Notice</h6>
                                    <div class="notice-meta">
                                        <span class="notice-date">
                                            <i class="fas fa-calendar me-1"></i>10 Dec, 2024
                                        </span>
                                        <span class="notice-author">By Admin</span>
                                    </div>
                                </div>
                                <div class="notice-actions">
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Reminders & Quick Actions -->
        <div class="col-lg-4">
            <!-- Reminders Card -->
            <div class="modern-card reminders-card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-tasks me-2"></i>Today's Tasks
                    </h5>
                    <button class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="reminder-item priority-high">
                        <div class="reminder-checkbox">
                            <input type="checkbox" id="task1" class="form-check-input">
                        </div>
                        <div class="reminder-content">
                            <label for="task1" class="reminder-title">Review Teacher Applications</label>
                            <div class="reminder-time">
                                <i class="fas fa-clock me-1"></i>Due in 2 hours
                            </div>
                        </div>
                    </div>
                    
                    <div class="reminder-item priority-medium">
                        <div class="reminder-checkbox">
                            <input type="checkbox" id="task2" class="form-check-input">
                        </div>
                        <div class="reminder-content">
                            <label for="task2" class="reminder-title">Prepare Monthly Report</label>
                            <div class="reminder-time">
                                <i class="fas fa-clock me-1"></i>Due today
                            </div>
                        </div>
                    </div>
                    
                    <div class="reminder-item priority-low">
                        <div class="reminder-checkbox">
                            <input type="checkbox" id="task3" class="form-check-input" checked>
                        </div>
                        <div class="reminder-content">
                            <label for="task3" class="reminder-title completed">Staff Meeting Preparation</label>
                            <div class="reminder-time">
                                <i class="fas fa-check me-1"></i>Completed
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions Card -->
            <div class="modern-card quick-actions-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="quick-action-grid">
                        <a href="add_student.php" class="quick-action-item">
                            <i class="fas fa-user-plus"></i>
                            <span>Add Student</span>
                        </a>
                        <a href="add_teacher.php" class="quick-action-item">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <span>Add Teacher</span>
                        </a>
                        <a href="attendance.php" class="quick-action-item">
                            <i class="fas fa-calendar-check"></i>
                            <span>Mark Attendance</span>
                        </a>
                        <a href="reports.php" class="quick-action-item">
                            <i class="fas fa-chart-bar"></i>
                            <span>Generate Report</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Enhanced Dashboard Interactions
document.addEventListener('DOMContentLoaded', function() {
    // Animated Counter for Stats
    function animateCounter(element) {
        const target = parseInt(element.dataset.count);
        const increment = target / 100;
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current);
        }, 20);
    }

    // Trigger counter animations when stats cards are visible
    const counters = document.querySelectorAll('.counter');
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -50px 0px'
    };

    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                statsObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    counters.forEach(counter => {
        statsObserver.observe(counter);
    });

    // Animate cards on load
    const cards = document.querySelectorAll('.stat-card, .modern-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Task checkbox interactions
    const taskCheckboxes = document.querySelectorAll('.reminder-item input[type="checkbox"]');
    taskCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const reminderItem = this.closest('.reminder-item');
            const label = reminderItem.querySelector('.reminder-title');
            
            if (this.checked) {
                label.classList.add('completed');
                reminderItem.style.opacity = '0.7';
            } else {
                label.classList.remove('completed');
                reminderItem.style.opacity = '1';
            }
        });
    });

    // Quick action hover effects
    const quickActions = document.querySelectorAll('.quick-action-item');
    quickActions.forEach(action => {
        action.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.05)';
        });
        
        action.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Notice item hover effects
    const noticeItems = document.querySelectorAll('.notice-item');
    noticeItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(10px)';
            this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
            this.style.boxShadow = '0 2px 10px rgba(0,0,0,0.08)';
        });
    });

    // Real-time clock
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString();
        const dateString = now.toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        
        // Add clock to dashboard if element exists
        const clockElement = document.getElementById('dashboard-clock');
        if (clockElement) {
            clockElement.textContent = timeString;
        }
    }

    updateClock();
    setInterval(updateClock, 1000);
});
</script>

<?php require_once '../includes/footer.php'; ?>
