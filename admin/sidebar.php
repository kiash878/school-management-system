<nav class="sidebar" id="sidebar">
    <div class="sidebar-body">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo ($page_title == 'Admin Dashboard') ? 'active' : ''; ?>" href="dashboard.php">
                    <i class="fas fa-th-large"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($page_title, 'Student') !== false) ? 'active' : ''; ?>" href="students.php">
                    <i class="fas fa-user-graduate"></i> <span>Students</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($page_title, 'Teacher') !== false) ? 'active' : ''; ?>" href="teachers.php">
                    <i class="fas fa-chalkboard-teacher"></i> <span>Teachers</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($page_title, 'Class') !== false) ? 'active' : ''; ?>" href="classes.php">
                    <i class="fas fa-school"></i> <span>Classes</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($page_title, 'Subject') !== false) ? 'active' : ''; ?>" href="subjects.php">
                    <i class="fas fa-book"></i> <span>Subjects</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($page_title, 'Attendance') !== false) ? 'active' : ''; ?>" href="attendance.php">
                    <i class="fas fa-calendar-check"></i> <span>Attendance</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($page_title, 'Timetable') !== false) ? 'active' : ''; ?>" href="timetable.php">
                    <i class="fas fa-calendar-alt"></i> <span>Timetable</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($page_title, 'Exam') !== false) ? 'active' : ''; ?>" href="exams.php">
                    <i class="fas fa-file-alt"></i> <span>Exams</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($page_title, 'Library') !== false) ? 'active' : ''; ?>" href="library.php">
                    <i class="fas fa-book"></i> <span>Library</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($page_title, 'Fee') !== false) ? 'active' : ''; ?>" href="fees.php">
                    <i class="fas fa-money-bill-wave"></i> <span>Fees</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($page_title, 'Announcement') !== false) ? 'active' : ''; ?>" href="announcements.php">
                    <i class="fas fa-bullhorn"></i> <span>Announcements</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($page_title, 'Report') !== false) ? 'active' : ''; ?>" href="reports.php">
                    <i class="fas fa-chart-bar"></i> <span>Reports</span>
                </a>
            </li>
             <li class="nav-item">
                <a class="nav-link <?php echo (strpos($page_title, 'Setting') !== false) ? 'active' : ''; ?>" href="settings.php">
                    <i class="fas fa-cog"></i> <span>Settings</span>
                </a>
            </li>

            <li class="nav-item mt-4">
                 <a href="<?php echo BASE_URL; ?>logout.php" class="nav-link text-danger">
                    <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
