<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';
require_once '../includes/classes/SchoolClass.php';
require_once '../includes/classes/Teacher.php';
require_once '../includes/classes/Timetable.php'; // Make sure to implement this class or use raw DB query for now

$page_title = "Add Timetable Entry";
require_once '../includes/header.php';
require_once 'sidebar.php';

$msg = '';
$error = '';

$classObj = new SchoolClass();
$teacherObj = new Teacher();
$timetableObj = new Timetable();

$classes = $classObj->getClasses();
$teachers = $teacherObj->read();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'class_id' => $_POST['class_id'],
        'section_id' => $_POST['section_id'],
        'subject_id' => $_POST['subject_id'],
        'teacher_id' => $_POST['teacher_id'],
        'day_of_week' => $_POST['day_of_week'],
        'start_time' => $_POST['start_time'],
        'end_time' => $_POST['end_time']
    ];

    try {
        if ($timetableObj->create($data)) {
            $msg = "Schedule added successfully!";
        } else {
            $error = "Failed to add schedule.";
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <div>
            <h1 class="h2">Add Class Routine</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="timetable.php">Timetable</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Entry</li>
                </ol>
            </nav>
        </div>
        <a href="timetable.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Go Back
        </a>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-success alert-dismissible fade show"><?php echo $msg; ?> <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show"><?php echo $error; ?> <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 d-flex align-items-center">
             <h5 class="card-title mb-0 text-primary"><i class="fas fa-calendar-plus me-2"></i> Schedule Details</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3">Target Class</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Class</label>
                        <select name="class_id" id="class_id" class="form-select" required>
                            <option value="">Select Class</option>
                            <?php while ($row = $classes->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?php echo $row['class_id']; ?>"><?php echo $row['class_name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Section</label>
                        <select name="section_id" id="section_id" class="form-select" required>
                            <option value="">Select Class First</option>
                        </select>
                    </div>
                </div>

                <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3 mt-4">Time & Subject</h6>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold small">Day</label>
                        <select name="day_of_week" class="form-select" required>
                            <option value="">Select Day</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold small">Start Time</label>
                        <input type="time" name="start_time" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold small">End Time</label>
                        <input type="time" name="end_time" class="form-control" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Subject</label>
                        <select name="subject_id" id="subject_id" class="form-select" required>
                            <option value="">Select Class First</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Teacher</label>
                        <select name="teacher_id" class="form-select">
                            <option value="">Select Teacher (Optional)</option>
                            <?php while ($row = $teachers->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?php echo $row['teacher_id']; ?>"><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                        <div class="form-text">Leave blank to use Subject's default teacher</div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i> Save to Routine</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var classSelect = document.getElementById('class_id');
    var sectionSelect = document.getElementById('section_id');

    classSelect.addEventListener('change', function() {
        var classId = this.value;
        sectionSelect.innerHTML = '<option value="">Loading...</option>';
        document.getElementById('subject_id').innerHTML = '<option value="">Loading...</option>';

        if (classId) {
            // Load Sections
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

            // Load Subjects (Need a new API for this ideally, or just reuse checking subjects per class)
            // For now, let's assuming we might need to fetch subjects dynamically or if not, just simple text? 
            // Better to fetch:
             fetch('../api/get_subjects.php?class_id=' + classId)
                .then(response => response.json())
                .then(data => {
                    var subjectSelect = document.getElementById('subject_id');
                    subjectSelect.innerHTML = '<option value="">Select Subject</option>';
                    data.forEach(function(subject) {
                        var option = document.createElement('option');
                        option.value = subject.subject_id;
                        option.text = subject.subject_name + ' (' + subject.subject_code + ')';
                        subjectSelect.add(option);
                    });
                })
                .catch(err => {
                    // fall back if api missing
                     document.getElementById('subject_id').innerHTML = '<option value="">Error loading subjects</option>';
                });

        } else {
            sectionSelect.innerHTML = '<option value="">Select Class First</option>';
            document.getElementById('subject_id').innerHTML = '<option value="">Select Class First</option>';
        }
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>
