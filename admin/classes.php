<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';
require_once '../includes/classes/SchoolClass.php';

$page_title = "Manage Classes";
require_once '../includes/header.php';
require_once 'sidebar.php';

$schoolClass = new SchoolClass();
$classes = $schoolClass->getClasses();

// Handle form submissions
$message = '';
$error = '';

// Add new class
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_class'])) {
    $class_name = trim($_POST['class_name']);
    $numeric_grade = intval($_POST['numeric_grade']);
    
    if (empty($class_name) || empty($numeric_grade)) {
        $error = "Please fill in all required fields.";
    } else {
        if ($schoolClass->addClass($class_name, $numeric_grade)) {
            $message = "Class '$class_name' added successfully!";
            // Refresh the classes data
            $classes = $schoolClass->getClasses();
        } else {
            $error = "Failed to add class. Class might already exist.";
        }
    }
}

// Add new section
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_section'])) {
    $class_id = intval($_POST['class_id']);
    $section_name = trim($_POST['section_name']);
    
    if (empty($class_id) || empty($section_name)) {
        $error = "Please provide valid class and section information.";
    } else {
        if ($schoolClass->addSection($class_id, $section_name)) {
            $message = "Section '$section_name' added successfully!";
        } else {
            $error = "Failed to add section. Section might already exist.";
        }
    }
}

// Delete section
if (isset($_GET['delete_section'])) {
    $section_id = intval($_GET['delete_section']);
    if ($schoolClass->deleteSection($section_id)) {
        $message = "Section deleted successfully!";
    } else {
        $error = "Failed to delete section.";
    }
}

// Update class
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_class'])) {
    $class_id = intval($_POST['class_id']);
    $class_name = trim($_POST['class_name']);
    $numeric_grade = intval($_POST['numeric_grade']);
    
    if (empty($class_name) || empty($numeric_grade)) {
        $error = "Please fill in all required fields.";
    } else {
        if ($schoolClass->updateClass($class_id, $class_name, $numeric_grade)) {
            $message = "Class '$class_name' updated successfully!";
            $classes = $schoolClass->getClasses(); // Refresh data
        } else {
            $error = "Failed to update class.";
        }
    }
}

// Delete class
if (isset($_GET['delete_class'])) {
    $class_id = intval($_GET['delete_class']);
    if ($schoolClass->deleteClass($class_id)) {
        $message = "Class deleted successfully!";
        $classes = $schoolClass->getClasses(); // Refresh data
    } else {
        $error = "Failed to delete class. Make sure no students are enrolled.";
    }
}
?>

<main class="main-content">
    <!-- Enhanced Classes Header -->
    <div class="classes-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="page-title-section">
                    <h1 class="classes-title">
                        <i class="fas fa-school me-3"></i>Classes & Sections
                    </h1>
                    <p class="classes-subtitle">Manage academic classes and their sections</p>
                    <nav class="classes-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Classes</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#addClassModal">
                    <i class="fas fa-plus me-2"></i>Add Class
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

    <!-- Enhanced Classes Grid -->
    <div class="classes-grid">
        <?php while ($row = $classes->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="class-card">
            <div class="class-card-header">
                <div class="class-info">
                    <h3 class="class-name"><?php echo htmlspecialchars($row['class_name']); ?></h3>
                    <div class="class-details">
                        <span class="grade-badge">Grade <?php echo $row['numeric_grade']; ?></span>
                        <span class="academic-year">Academic Year 2024-2025</span>
                    </div>
                </div>
                <div class="class-actions">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-cog"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="editClass(<?php echo $row['class_id']; ?>, '<?php echo htmlspecialchars($row['class_name']); ?>', <?php echo $row['numeric_grade']; ?>)"><i class="fas fa-edit me-2"></i>Edit Class</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteClass(<?php echo $row['class_id']; ?>, '<?php echo htmlspecialchars($row['class_name']); ?>')"><i class="fas fa-trash me-2"></i>Delete</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="class-card-body">
                <div class="sections-area">
                    <div class="sections-header">
                        <h4 class="sections-title">
                            <i class="fas fa-layer-group me-2"></i>Sections
                        </h4>
                        <button class="btn btn-sm btn-warning" onclick="addSection(<?php echo $row['class_id']; ?>)">
                            <i class="fas fa-plus me-1"></i>Add Section
                        </button>
                    </div>
                    
                    <div class="sections-list">
                        <?php 
                        $sections = $schoolClass->getSections($row['class_id']);
                        if ($sections->rowCount() > 0) {
                            while ($sec = $sections->fetch(PDO::FETCH_ASSOC)) {
                                echo '<div class="section-badge">
                                        <span class="section-name">' . htmlspecialchars($sec['section_name']) . '</span>
                                        <button class="section-delete" onclick="deleteSection(' . $sec['section_id'] . ', \'' . htmlspecialchars($sec['section_name']) . '\')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                      </div>';
                            }
                        } else {
                            echo '<div class="no-sections">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No sections created yet
                                  </div>';
                        }
                        ?>
                    </div>
                </div>

                <div class="class-stats">
                    <div class="stat-item">
                        <i class="fas fa-users"></i>
                        <span class="stat-label">Students</span>
                        <span class="stat-value">
                            <?php 
                            $studentCount = $schoolClass->getStudentCount($row['class_id']);
                            echo $studentCount ?: '0';
                            ?>
                        </span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-book"></i>
                        <span class="stat-label">Subjects</span>
                        <span class="stat-value">8</span>
                    </div>
                </div>
            </div>

            <div class="class-card-footer">
                <a href="students.php?class_id=<?php echo $row['class_id']; ?>" class="btn btn-outline-primary">
                    <i class="fas fa-users me-2"></i>View Students
                </a>
                <a href="subjects.php?class_id=<?php echo $row['class_id']; ?>" class="btn btn-primary">
                    <i class="fas fa-book me-2"></i>Manage Subjects
                </a>
            </div>
        </div>
        <?php endwhile; ?>
        
        <!-- Add Class Card -->
        <div class="add-class-card" data-bs-toggle="modal" data-bs-target="#addClassModal">
            <div class="add-class-content">
                <div class="add-class-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <h4 class="add-class-title">Add New Class</h4>
                <p class="add-class-description">Create a new academic class</p>
            </div>
        </div>
    </div>
</main>

<!-- Add Class Modal -->
<div class="modal fade" id="addClassModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-school me-2"></i>Add New Class
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Class Name *</label>
                        <input type="text" name="class_name" class="form-control" placeholder="e.g., Grade 6, Class 7A" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Numeric Grade *</label>
                        <select name="numeric_grade" class="form-control" required>
                            <option value="">Select Grade</option>
                            <?php for($i = 1; $i <= 12; $i++): ?>
                                <option value="<?php echo $i; ?>">Grade <?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_class" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Add Class
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Section Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-layer-group me-2"></i>Add Section
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="class_id" id="section_class_id">
                    <div class="form-group mb-3">
                        <label class="form-label">Section Name *</label>
                        <input type="text" name="section_name" class="form-control" placeholder="e.g., A, B, Blue, Red" required>
                        <div class="form-help">
                            <i class="fas fa-info-circle me-1"></i>
                            Common section names: A, B, C or descriptive names like Blue, Red, etc.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_section" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Add Section
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Class Modal -->
<div class="modal fade" id="editClassModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Edit Class
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="edit_class" value="1">
                    <input type="hidden" name="class_id" id="edit_class_id">
                    <div class="form-group mb-3">
                        <label class="form-label">Class Name *</label>
                        <input type="text" name="class_name" id="edit_class_name" class="form-control" placeholder="e.g., Grade 6, Class 7A" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Numeric Grade *</label>
                        <select name="numeric_grade" id="edit_numeric_grade" class="form-control" required>
                            <option value="">Select Grade</option>
                            <?php for($i = 1; $i <= 12; $i++): ?>
                                <option value="<?php echo $i; ?>">Grade <?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_class" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Class
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Theme toggle and visual enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Initialize theme toggle if not already done
    if (typeof window.ThemeToggle === 'undefined') {
        const themeScript = document.createElement('script');
        themeScript.src = '../assets/js/theme-toggle.js';
        document.head.appendChild(themeScript);
    }
});

// Add section functionality
function addSection(classId) {
    document.getElementById('section_class_id').value = classId;
    const modal = new bootstrap.Modal(document.getElementById('addSectionModal'));
    modal.show();
}

// Delete section with confirmation
function deleteSection(sectionId, sectionName) {
    if (confirm(`Are you sure you want to delete section "${sectionName}"?\n\nThis action cannot be undone.`)) {
        // Show loading
        if (window.VE) {
            window.VE.showLoading('Deleting section...');
        }
        
        // Redirect to delete
        window.location.href = `classes.php?delete_section=${sectionId}`;
    }
}

// Edit class functionality
function editClass(classId, className, numericGrade) {
    // Populate edit modal
    document.getElementById('edit_class_id').value = classId;
    document.getElementById('edit_class_name').value = className;
    document.getElementById('edit_numeric_grade').value = numericGrade;
    
    const modal = new bootstrap.Modal(document.getElementById('editClassModal'));
    modal.show();
}

// Delete class with confirmation
function deleteClass(classId, className) {
    if (confirm(`Are you sure you want to delete class "${className}"?\n\nThis will also delete all sections in this class.\nThis action cannot be undone.`)) {
        // Show loading
        if (window.VE) {
            window.VE.showLoading('Deleting class...');
        }
        
        // Redirect to delete
        window.location.href = `classes.php?delete_class=${classId}`;
    }
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    // Add class form validation
    const addClassForm = document.querySelector('#addClassModal form');
    if (addClassForm) {
        addClassForm.addEventListener('submit', function(e) {
            const className = this.querySelector('input[name="class_name"]').value.trim();
            const numericGrade = this.querySelector('select[name="numeric_grade"]').value;
            
            if (!className || !numericGrade) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return false;
            }
            
            // Show loading state
            if (window.VE) {
                window.VE.formLoading(this);
            }
        });
    }
    
    // Add section form validation
    const addSectionForm = document.querySelector('#addSectionModal form');
    if (addSectionForm) {
        addSectionForm.addEventListener('submit', function(e) {
            const sectionName = this.querySelector('input[name="section_name"]').value.trim();
            
            if (!sectionName) {
                e.preventDefault();
                alert('Please enter a section name.');
                return false;
            }
            
            // Show loading state
            if (window.VE) {
                window.VE.formLoading(this);
            }
        });
    }
});

// Auto-hide alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        if (alert.querySelector('.btn-close')) {
            alert.querySelector('.btn-close').click();
        }
    });
}, 5000);
</script>

<?php require_once '../includes/footer.php'; ?>
