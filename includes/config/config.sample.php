<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'school_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Site Configuration
define('SITE_NAME', 'School Management System');
define('BASE_URL', 'http://localhost/school-management-system/');

// Email Configuration (for notifications)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');

// File Upload Configuration
define('MAX_FILE_SIZE', 5242880); // 5MB in bytes
define('UPLOAD_PATH', 'assets/uploads/');

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
session_start();

// Timezone
date_default_timezone_set('Asia/Kolkata'); // Change as per your location
?>