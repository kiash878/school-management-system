<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'teacher') {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';
require_once '../includes/classes/Exam.php';
require_once '../includes/classes/Student.php';

$page_title = "Enter Marks";
require_once '../includes/header.php';
require_once 'sidebar.php';

$db = Database::getInstance()->getConnection();
$msg = '';

$teacher_id = 0;
// Quick fetch teacher id
$stmt = $db->prepare("SELECT teacher_id FROM teachers WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$tRow = $stmt->fetch(PDO::FETCH_ASSOC);
$teacher_id = $tRow['teacher_id'] ?? 0;


// Fetch Exams
$examObj = new Exam();
$exams = $examObj->getExams();

// Fetch Assigned Subjects
$subjectsQuery = "SELECT s.subject_id, s.subject_name, c.class_name, c.class_id, sc.section_name 
                 FROM subjects s 
                 JOIN classes c ON s.class_id = c.class_id 
                 LEFT JOIN sections sc ON sc.class_id = c.class_id 
                 WHERE s.teacher_id = ? 
                 GROUP BY s.subject_id, s.subject_name, c.class_name, c.class_id"; 
                 // Note: Section handling logic simplified for demo, usually specific assignment
$subjectsStmt = $db->prepare($subjectsQuery);
$subjectsStmt->execute([$teacher_id]);
$teacherSubjects = $subjectsStmt->fetchAll(PDO::FETCH_ASSOC);

$selected_exam = $_POST['exam_id'] ?? '';
$selected_subject_compound = $_POST['subject_class'] ?? ''; // Format: subjectId_classId

$studentList = [];

// Handle Save
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_marks'])) {
    $marksData = $_POST['marks']; // student_id => marks
    $subjectId = $_POST['subject_id'];
    $examId = $_POST['exam_id'];
    
    foreach ($marksData as $sid => $mark) {
        $examObj->enterMarks($sid, $examId, $subjectId, $mark, 100);
    }
    $msg = "Marks saved successfully!";
}

if ($selected_subject_compound && $selected_exam) {
    list($subId, $clsId) = explode('_', $selected_subject_compound);
    
    // Fetch students for this class
    $stuObj = new Student();
    $stmt = $stuObj->read($clsId);
    $studentList = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Enter Marks</h1>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-success"><?php echo $msg; ?></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Exam</label>
                    <select name="exam_id" class="form-select" required>
                        <option value="">Select Exam</option>
                        <?php while ($row = $exams->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?php echo $row['exam_id']; ?>" <?php echo ($selected_exam == $row['exam_id']) ? 'selected' : ''; ?>>
                                <?php echo $row['exam_name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Subject & Class</label>
                    <select name="subject_class" class="form-select" required>
                        <option value="">Select Subject</option>
                        <?php foreach ($teacherSubjects as $row): ?>
                            <?php $val = $row['subject_id'] . '_' . $row['class_id']; ?>
                            <option value="<?php echo $val; ?>" <?php echo ($selected_subject_compound == $val) ? 'selected' : ''; ?>>
                                <?php echo $row['subject_name'] . ' - ' . $row['class_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Load Students</button>
                    <!-- Small hack to keep POST state without JS -->
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($studentList)): ?>
    <form method="POST">
        <input type="hidden" name="exam_id" value="<?php echo $selected_exam; ?>">
        <?php list($subId, $clsId) = explode('_', $selected_subject_compound); ?>
        <input type="hidden" name="subject_id" value="<?php echo $subId; ?>">
        <input type="hidden" name="subject_class" value="<?php echo $selected_subject_compound; ?>">
        <input type="hidden" name="save_marks" value="1">

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Adm No</th>
                        <th>Name</th>
                        <th>Marks Obtained (Out of 100)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($studentList as $student): ?>
                        <!-- Ideally fetch existing marks if any to pre-fill -->
                    <tr>
                        <td><?php echo $student['admission_no']; ?></td>
                        <td><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></td>
                        <td>
                            <input type="number" step="0.01" name="marks[<?php echo $student['student_id']; ?>]" class="form-control" max="100" placeholder="0.00">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-success btn-lg mt-3">Save Marks</button>
    </form>
    <?php endif; ?>
</main>

<?php require_once '../includes/footer.php'; ?>
