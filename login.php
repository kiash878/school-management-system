<?php
require_once 'includes/config/config.php';
require_once 'includes/classes/User.php';

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    $role = $_SESSION['user_role'];
    switch ($role) {
        case 'admin':
            header("Location: admin/dashboard.php");
            break;
        case 'teacher':
            header("Location: teacher/dashboard.php");
            break;
        case 'student':
            header("Location: student/dashboard.php");
            break;
        case 'parent':
            header("Location: parent/dashboard.php");
            break;
        default:
            header("Location: logout.php");
    }
    exit;
}

// Handle Login Submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new User();
    if ($user->login($email, $password)) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['user_role'] = $user->role;

        // Redirect based on role
        switch ($user->role) {
            case 'admin':
                header("Location: admin/dashboard.php");
                break;
            case 'teacher':
                header("Location: teacher/dashboard.php");
                break;
            case 'student':
                header("Location: student/dashboard.php");
                break;
            case 'parent':
                header("Location: parent/dashboard.php");
                break;
        }
        exit;
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #010221;
            --secondary-color: #0a7373;
            --accent-color: #b7bf99;
            --warning-color: #edaa25;
            --success-color: #0a7373;
            --danger-color: #c43302;
            --text-dark: #010221;
            --text-light: #6b7280;
            --navy: #010221;
            --teal: #0a7373;
            --sage: #b7bf99;
            --amber: #edaa25;
            --rust: #c43302;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--navy);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        .login-wrapper {
            max-width: 1000px;
            width: 100%;
            display: flex;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
            border-radius: 30px;
            overflow: hidden;
            background: white;
            position: relative;
            z-index: 10;
            backdrop-filter: blur(10px);
        }

        .login-sidebar {
            width: 50%;
            background: var(--amber);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 4rem 3rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(rgba(237, 170, 37, 0.8), rgba(237, 170, 37, 0.8)), url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80');
            background-size: cover;
            background-position: center;
        }

        .login-sidebar-content {
            position: relative;
            z-index: 2;
        }

        .login-sidebar h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            font-family: 'Playfair Display', serif;
        }

        .login-sidebar p {
            font-size: 1.2rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .login-form-container {
            width: 50%;
            padding: 4rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
            position: relative;
        }

        .back-link {
            position: absolute;
            top: 2rem;
            left: 2rem;
            color: var(--text-light);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: var(--amber);
            transform: translateX(-5px);
        }

        .brand-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-logo i {
            font-size: 4rem;
            color: var(--navy);
        }

        .login-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-dark);
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: var(--text-light);
            text-align: center;
            margin-bottom: 3rem;
            font-size: 1.1rem;
        }

        .form-group {
            margin-bottom: 2rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            z-index: 5;
        }

        .form-control {
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            border-color: var(--navy);
            box-shadow: 0 0 0 0.2rem rgba(1, 2, 33, 0.25);
            background: white;
            transform: translateY(-2px);
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-check-input:checked {
            background-color: var(--navy);
            border-color: var(--navy);
        }

        .forgot-link {
            color: var(--amber);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .forgot-link:hover {
            color: var(--rust);
        }

        .btn-login {
            background: var(--amber);
            border: none;
            color: white;
            font-weight: 600;
            padding: 1rem 2rem;
            border-radius: 15px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(237, 170, 37, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(237, 170, 37, 0.4);
            color: white;
        }

        .contact-admin {
            color: var(--amber);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .contact-admin:hover {
            color: var(--rust);
        }

        .alert {
            border: none;
            border-radius: 15px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border-left: 4px solid #dc3545;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
                margin: 20px;
                border-radius: 20px;
            }
            
            .login-sidebar {
                width: 100%;
                padding: 3rem 2rem;
                order: 2;
            }
            
            .login-sidebar h1 {
                font-size: 2rem;
            }
            
            .login-form-container {
                width: 100%;
                padding: 3rem 2rem;
                order: 1;
            }
            
            .back-link {
                position: relative;
                top: 0;
                left: 0;
                margin-bottom: 2rem;
                display: inline-block;
            }
            
            .login-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .login-form-container {
                padding: 2rem 1.5rem;
            }
            
            .login-sidebar {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-sidebar">
        <div class="login-sidebar-content">
            <h1>Welcome!</h1>
            <p>Manage your school efficiently with our all-in-one comprehensive ERP solution</p>
        </div>
    </div>
    
    <div class="login-form-container">
        <a href="index.php" class="back-link">
            <i class="fas fa-arrow-left me-2"></i>Back to Home
        </a>
        
        <div class="brand-logo">
            <i class="fas fa-graduation-cap"></i>
        </div>
        
        <h2 class="login-title">Sign In</h2>
        <p class="login-subtitle">Please sign in to continue to your dashboard</p>

        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                </div>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label text-muted" for="remember">Remember me</label>
                </div>
                <a href="forgot_password.php" class="forgot-link">Forgot Password?</a>
            </div>

            <div class="d-grid">
                <button type="submit" name="login" class="btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                </button>
            </div>
        </form>
        
        <div class="mt-4 text-center">
            <p class="text-muted">Don't have an account? <a href="#" class="contact-admin">Contact Admin</a></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
