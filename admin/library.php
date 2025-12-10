<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/config/config.php';
require_once '../includes/classes/Database.php';
require_once '../includes/classes/Library.php';
require_once '../includes/classes/Student.php';

$page_title = "Library Management";
require_once '../includes/header.php';
require_once 'sidebar.php';

$library = new Library();
$studentObj = new Student();
$msg = '';
$error = '';

// Handle Add Book
if (isset($_POST['add_book'])) {
    if ($library->addBook($_POST)) {
        $msg = "Book added successfully.";
        // Refresh to show new book
        echo "<meta http-equiv='refresh' content='0'>";
        exit;
    } else {
        $error = "Failed to add book.";
    }
}

// Handle Issue Book
if (isset($_POST['issue_book'])) {
    if ($library->issueBook($_POST['book_id'], $_POST['student_id'], $_POST['due_date'])) {
        $msg = "Book issued successfully.";
    } else {
        $error = "Failed to issue book (Check availability).";
    }
}

// Handle Return
if (isset($_GET['return_id'])) {
    if ($library->returnBook($_GET['return_id'])) {
        $msg = "Book returned successfully.";
    }
}

$books = $library->getBooks();
$activeIssues = $library->getActiveIssues();
?>

<main class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h2">Library</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addBookModal">
                <i class="fas fa-plus"></i> Add Book
            </button>
            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#issueBookModal">
                <i class="fas fa-book-reader"></i> Issue Book
            </button>
        </div>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-success alert-dismissible fade show"><?php echo $msg; ?> <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show"><?php echo $error; ?> <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <!-- Active Issues Section -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-transparent border-0">
            <h5 class="card-title mb-0 text-primary">Currently Issued Books</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Student</th>
                            <th>Book Title</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $activeIssues->fetch(PDO::FETCH_ASSOC)): 
                            $isOverdue = strtotime($row['due_date']) < time();
                        ?>
                        <tr class="<?php echo $isOverdue ? 'table-danger' : ''; ?>">
                            <td class="ps-4">
                                <span class="fw-bold"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></span><br>
                                <small class="text-muted"><?php echo $row['admission_no']; ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo $row['issue_date']; ?></td>
                            <td class="fw-bold"><?php echo $row['due_date']; ?> 
                                <?php if ($isOverdue) echo '<span class="badge bg-danger ms-1">Overdue</span>'; ?>
                            </td>
                            <td class="text-end pe-4">
                                <a href="?return_id=<?php echo $row['transaction_id']; ?>" class="btn btn-sm btn-outline-success" onclick="return confirm('Confirm return?')">
                                    Mark Returned
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Books Inventory -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0">
            <h5 class="card-title mb-0">Book Inventory</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Status (Available/Total)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Reset pointer if needed or re-query. PDOStatement is forward-only usually.
                        $books = $library->getBooks(); 
                        while ($row = $books->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td class="ps-4 fw-bold"><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['author']); ?></td>
                            <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($row['category']); ?></span></td>
                            <td>
                                <?php echo $row['available_qty'] . ' / ' . $row['quantity']; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</main>

<!-- Add Book Modal -->
<div class="modal fade" id="addBookModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="add_book" value="1">
                <div class="mb-3">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Author</label>
                    <input type="text" name="author" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>ISBN</label>
                    <input type="text" name="isbn" class="form-control">
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label>Category</label>
                        <select name="category" class="form-select">
                            <option>Textbook</option>
                            <option>Fiction</option>
                            <option>Science</option>
                            <option>History</option>
                        </select>
                    </div>
                    <div class="col-6 mb-3">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="form-control" value="1" min="1">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Add Book</button>
            </div>
        </form>
    </div>
</div>

<!-- Issue Book Modal -->
<div class="modal fade" id="issueBookModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Issue Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="issue_book" value="1">
                <div class="mb-3">
                    <label>Student Admission No</label>
                    <!-- Simplification: In real app use Select2 or AJAX search -->
                    <input type="text" id="adm_search" class="form-control" placeholder="Enter Admission No to search ID..." onblur="findStudent(this.value)">
                    <input type="hidden" name="student_id" id="student_id" required>
                    <small id="student_name_display" class="text-success fw-bold"></small>
                </div>
                <div class="mb-3">
                    <label>Book</label>
                    <select name="book_id" class="form-select" required>
                        <option value="">Select Book</option>
                        <?php 
                        $books = $library->getBooks();
                        while ($row = $books->fetch(PDO::FETCH_ASSOC)): 
                            if ($row['available_qty'] > 0):
                        ?>
                            <option value="<?php echo $row['book_id']; ?>"><?php echo $row['title']; ?></option>
                        <?php endif; endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Due Date</label>
                    <input type="date" name="due_date" class="form-control" required value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="issueBtn" disabled>Issue Book</button>
            </div>
        </form>
    </div>
</div>

<script>
function findStudent(admNo) {
    if (!admNo) return;
    // Mocking a simple check or implementing a simple API call
    // Ideally create api/get_student_by_adm.php
    fetch('../api/get_student_by_adm.php?adm=' + admNo)
    .then(res => res.json())
    .then(data => {
        if (data.status == 'found') {
            document.getElementById('student_id').value = data.id;
            document.getElementById('student_name_display').innerText = data.name;
            document.getElementById('issueBtn').disabled = false;
        } else {
            document.getElementById('student_name_display').innerText = 'Student not found';
            document.getElementById('student_name_display').className = 'text-danger fw-bold';
            document.getElementById('issueBtn').disabled = true;
        }
    });
}
</script>

<?php require_once '../includes/footer.php'; ?>
