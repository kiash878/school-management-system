<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';
require_once '../includes/classes/SchoolClass.php';
require_once '../includes/classes/Timetable.php';

$page_title = "Class Timetable";
require_once '../includes/header.php';
require_once 'sidebar.php';

$classObj = new SchoolClass();
$timetableObj = new Timetable();

$classes = $classObj->getClasses();

// Default selection
$selected_class = isset($_GET['class_id']) ? $_GET['class_id'] : '';
$selected_section = isset($_GET['section_id']) ? $_GET['section_id'] : '';

$timetable = [];
if ($selected_class && $selected_section) {
    try {
        $timetable = $timetableObj->getByClassAndSection($selected_class, $selected_section);
    } catch (PDOException $e) {
        // If table doesn't exist yet, catch error
        $error = "Error: Database table might be missing. " . $e->getMessage();
    }
}
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <div>
            <h1 class="h2">Class Timetable</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Timetable</li>
                </ol>
            </nav>
        </div>
        <a href="add_timetable.php" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="fas fa-plus"></i> <span>Add New Class</span>
        </a>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <form method="GET" action="" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold small">Class</label>
                    <select name="class_id" id="class_id" class="form-select" required>
                        <option value="">Select Class</option>
                        <?php 
                        // Reset pointer
                        $classes->execute(); 
                        while ($row = $classes->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?php echo $row['class_id']; ?>" <?php echo ($selected_class == $row['class_id']) ? 'selected' : ''; ?>>
                                <?php echo $row['class_name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small">Section</label>
                    <select name="section_id" id="section_id" class="form-select" required>
                        <option value="">Select Class First</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-search me-2"></i> View Timetable</button>
                </div>
            </form>
        </div>
    </div>

    <?php if ($selected_class && $selected_section): ?>
        <?php if (empty($timetable)): ?>
            <div class="alert alert-info border-0 shadow-sm">
                <i class="fas fa-info-circle me-2"></i> No timetable records found for this class and section.
            </div>
        <?php else: ?>
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-4">Day</th>
                                    <th class="border-0">Time</th>
                                    <th class="border-0">Subject</th>
                                    <th class="border-0">Teacher</th>
                                    <th class="border-0 text-end px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                $grouped = [];
                                foreach ($timetable as $t) {
                                    $grouped[$t['day_of_week']][] = $t;
                                }
                                
                                foreach ($days as $day):
                                    if (isset($grouped[$day])):
                                        foreach ($grouped[$day] as $row):
                                ?>
                                <tr>
                                    <td class="px-4 fw-bold text-primary"><?php echo $day; ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <?php echo date('h:i A', strtotime($row['start_time'])) . ' - ' . date('h:i A', strtotime($row['end_time'])); ?>
                                        </span>
                                    </td>
                                    <td class="fw-bold"><?php echo htmlspecialchars($row['subject_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                    <td class="text-end px-4">
                                        <a href="delete_timetable.php?id=<?php echo $row['timetable_id']; ?>" class="btn-icon text-danger" title="Delete" onclick="return confirm('Are you sure?')">
                                            <i class="far fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                        endforeach;
                                    endif;
                                endforeach; 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var classSelect = document.getElementById('class_id');
    var sectionSelect = document.getElementById('section_id');
    var selectedSection = "<?php echo $selected_section; ?>";

    function loadSections(classId, selectedId = null) {
        sectionSelect.innerHTML = '<option value="">Loading...</option>';
        if (classId) {
            fetch('../api/get_sections.php?class_id=' + classId)
                .then(response => response.json())
                .then(data => {
                    sectionSelect.innerHTML = '<option value="">Select Section</option>';
                    data.forEach(function(section) {
                        var option = document.createElement('option');
                        option.value = section.section_id;
                        option.text = section.section_name;
                        if (selectedId && section.section_id == selectedId) {
                            option.selected = true;
                        }
                        sectionSelect.add(option);
                    });
                });
        } else {
            sectionSelect.innerHTML = '<option value="">Select Class First</option>';
        }
    }

    if (classSelect.value) {
        loadSections(classSelect.value, selectedSection);
    }

    classSelect.addEventListener('change', function() {
        loadSections(this.value);
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>
