<nav class="sidebar" id="sidebar">
    <div class="sidebar-body">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo ($page_title == 'Parent Dashboard') ? 'active' : ''; ?>" href="dashboard.php">
                    <i class="fas fa-home"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-bullhorn"></i> <span>Announcements</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-envelope"></i> <span>Messages</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-user-circle"></i> <span>Profile</span>
                </a>
            </li>
            
             <li class="nav-item mt-5 pt-3 border-top">
                <div class="px-3 mb-2">
                    <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Quick Note</small>
                    <p class="small text-muted mb-0">Select a child from the dashboard to view details.</p>
                </div>
            </li>

            <li class="nav-item mt-4">
                 <a href="../logout.php" class="nav-link text-danger">
                    <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
