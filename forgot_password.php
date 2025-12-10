<?php
session_start();
require_once 'includes/config/config.php';
require_once 'includes/classes/Database.php';

$msg = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    // In a real application, you would check if email exists, generate a unique token, 
    // save it to DB, and email it to the user.
    // For this demo, we will simulate the process.
    
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        $msg = "If an account with that email exists, a password reset link has been sent.";
    } else {
        // Security best practice: Don't reveal if user exists or not, but for dev we might want to know.
        // We'll stick to the safe message.
        $msg = "If an account with that email exists, a password reset link has been sent.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .card {
            max-width: 400px;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <h3 class="text-center mb-3">Forgot Password</h3>
        <p class="text-muted text-center mb-4">Enter your email address to reset your password.</p>
        
        <?php if ($msg): ?>
            <div class="alert alert-success"><?php echo $msg; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Send Reset Link</button>
                <a href="index.php" class="btn btn-light">Back to Login</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
