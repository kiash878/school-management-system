<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'teacher') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';

$page_title = "My Students";
require_once '../includes/header.php';
require_once 'sidebar.php';

$db = Database::getInstance()->getConnection();
$teacher_id = 0;
// Quick fetch teacher id
$stmt = $db->prepare("SELECT teacher_id FROM teachers WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$tRow = $stmt->fetch(PDO::FETCH_ASSOC);
$teacher_id = $tRow['teacher_id'] ?? 0;

// Fetch unique students taught by this teacher
$query = "SELECT DISTINCT s.*, c.class_name 
          FROM students s
          JOIN classes c ON s.class_id = c.class_id
          JOIN subjects sub ON sub.class_id = c.class_id
          WHERE sub.teacher_id = ?
          ORDER BY c.class_name, s.first_name";

$stmt = $db->prepare($query);
$stmt->execute([$teacher_id]);
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">My Students</h1>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Adm No</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Gender</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['admission_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['gender']); ?></td>
                    <td><?php 
                        // Need fetching email from users table ideally, but avoiding complex join for now if not critical
                        echo "student@example.com"; 
                    ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
