<nav class="sidebar" id="sidebar">
    <div class="sidebar-body">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo ($page_title == 'Student Dashboard') ? 'active' : ''; ?>" href="dashboard.php">
                    <i class="fas fa-home"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($page_title, 'Attendance') !== false) ? 'active' : ''; ?>" href="attendance.php">
                    <i class="fas fa-calendar-alt"></i> <span>My Attendance</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($page_title, 'Marks') !== false) || (strpos($page_title, 'Results') !== false) ? 'active' : ''; ?>" href="marks.php">
                    <i class="fas fa-poll"></i> <span>Results / Marks</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($page_title, 'Fee') !== false) ? 'active' : ''; ?>" href="fees.php">
                    <i class="fas fa-file-invoice-dollar"></i> <span>Fees</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($page_title, 'Timetable') !== false) ? 'active' : ''; ?>" href="timetable.php">
                    <i class="fas fa-clock"></i> <span>Timetable</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($page_title, 'Profile') !== false) ? 'active' : ''; ?>" href="profile.php">
                    <i class="fas fa-user"></i> <span>My Profile</span>
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
