<nav class="sidebar" id="sidebar">
    <div class="sidebar-body">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo ($page_title == 'Teacher Dashboard') ? 'active' : ''; ?>" href="dashboard.php">
                    <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($page_title, 'Attendance') !== false) ? 'active' : ''; ?>" href="attendance.php">
                    <i class="fas fa-calendar-check"></i> <span>Mark Attendance</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($page_title, 'Marks') !== false) ? 'active' : ''; ?>" href="marks.php">
                    <i class="fas fa-marker"></i> <span>Enter Marks</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($page_title, 'Students') !== false) ? 'active' : ''; ?>" href="students.php">
                    <i class="fas fa-user-graduate"></i> <span>My Students</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($page_title, 'Timetable') !== false) ? 'active' : ''; ?>" href="timetable.php">
                    <i class="fas fa-clock"></i> <span>Timetable</span>
                </a>
            </li>
            
            <li class="nav-item mt-5">
                 <a href="../logout.php" class="nav-link text-danger">
                    <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
