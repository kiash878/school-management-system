<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo BASE_URL; ?>assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- School Header -->
<div class="school-header">
    <a href="<?php echo BASE_URL; ?>index.php" class="school-logo">
        <i class="fas fa-graduation-cap"></i>
    </a>
    <span class="school-name">School Management System</span>
    <div class="ms-auto d-flex align-items-center">
        <div class="theme-toggle me-3" data-theme-toggle>
            <span class="theme-toggle-label d-none d-md-inline">Theme</span>
            <div class="theme-toggle-switch">
                <div class="theme-toggle-slider">
                    <i class="fas fa-sun icon-light"></i>
                    <i class="fas fa-moon icon-dark"></i>
                </div>
            </div>
        </div>
        <span class="text-dark me-3 d-none d-md-block fw-semibold">
            Welcome, <?php echo $_SESSION['username'] ?? 'User'; ?>
        </span>
        <a href="<?php echo BASE_URL; ?>logout.php" class="btn btn-sm" style="background: var(--danger-color); color: white; border: none;">
            <i class="fas fa-sign-out-alt me-1"></i>Logout
        </a>
    </div>
</div>

<nav class="navbar navbar-light bg-light fixed-top">
  <div class="container-fluid">
    <div class="d-flex align-items-center">
        <button class="btn btn-outline-secondary me-2" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <span class="navbar-text fw-semibold text-dark">
            Dashboard Navigation
        </span>
    </div>
  </div>
</nav>

<div class="container-fluid">
    <div class="row">
