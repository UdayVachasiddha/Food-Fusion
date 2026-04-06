<?php
session_start();
require_once 'db_connect.php';

$error_msg = '';
$success_msg = '';

// Check if there's a success message from registration
if (isset($_SESSION['success'])) {
    $success_msg = $_SESSION['success'];
    unset($_SESSION['success']);
}

// Process the login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error_msg = "Please enter both email and password.";
    } else {
        // Fetch user data, including a check if they are currently locked out based on server time
        $stmt = $conn->prepare("SELECT user_id, password_hash, failed_attempts, lockout_until, (lockout_until > NOW()) AS is_locked FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // 1. Check if the user is currently locked out
            if ($user['is_locked']) {
                // Calculate how many minutes/seconds are left using PHP
                $lockout_time = strtotime($user['lockout_until']);
                $current_time = time();
                $diff_seconds = $lockout_time - $current_time;
                $minutes_left = ceil($diff_seconds / 60);
                
                $error_msg = "Account locked due to too many failed attempts. Please try again in {$minutes_left} minute(s).";
            } else {
                // 2. User is NOT locked out. Verify the password.
                if (password_verify($password, $user['password_hash'])) {
                    // Success! Reset failed attempts and lockout time
                    $reset_stmt = $conn->prepare("UPDATE users SET failed_attempts = 0, lockout_until = NULL WHERE user_id = ?");
                    $reset_stmt->bind_param("i", $user['user_id']);
                    $reset_stmt->execute();
                    $reset_stmt->close();

                    // Log them in by setting session variables
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['user_email'] = $email;

                    // Redirect to the homepage
                    header("Location: index.php");
                    exit();
                } else {
                    // 3. Password was incorrect. Increment failed attempts.
                    $attempts = $user['failed_attempts'] + 1;

                    if ($attempts >= 3) {
                        // Lock them out for 3 minutes
                        $lock_stmt = $conn->prepare("UPDATE users SET failed_attempts = ?, lockout_until = DATE_ADD(NOW(), INTERVAL 3 MINUTE) WHERE user_id = ?");
                        $lock_stmt->bind_param("ii", $attempts, $user['user_id']);
                        $lock_stmt->execute();
                        $lock_stmt->close();

                        $error_msg = "Incorrect password. You have failed 3 times. Account locked for 3 minutes.";
                    } else {
                        // Just increment the attempts counter
                        $inc_stmt = $conn->prepare("UPDATE users SET failed_attempts = ? WHERE user_id = ?");
                        $inc_stmt->bind_param("ii", $attempts, $user['user_id']);
                        $inc_stmt->execute();
                        $inc_stmt->close();

                        $remaining = 3 - $attempts;
                        $error_msg = "Incorrect password. You have {$remaining} attempt(s) left before lockout.";
                    }
                }
            }
        } else {
            // User not found. Use a generic error message for security.
            $error_msg = "Invalid email or password.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | FoodFusion</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Specific layout for the standalone login page */
        body { display: flex; flex-direction: column; min-height: 100vh; }
        .login-container { flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .login-card { background: #fff; padding: 3rem; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 400px; text-align: center; }
        .login-card h2 { margin-bottom: 1.5rem; color: var(--primary-color); }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 5px; font-size: 0.9rem; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="logo" style="display: flex; align-items: center; text-decoration: none;"><img src="assets/logo.png" alt="FoodFusion Logo"></a>
        <ul class="nav-links">
            <li><a href="index.php">Return to Home</a></li>
        </ul>
    </nav>

    <div class="login-container">
        <div class="login-card">
            <h2>Welcome Back</h2>
            
            <?php if (!empty($success_msg)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success_msg); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error_msg); ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <input type="email" name="email" placeholder="Email Address" required>
                <div class="password-wrapper">
                <input type="password" name="password" id="regPassword" placeholder="Password" required>
                <button type="button" class="toggle-password" onclick="togglePasswordVisibility('regPassword', this)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </button>
            </div>
                <button type="submit" class="btn primary-btn full-width">Log In</button>
            </form>
            
            <p style="margin-top: 20px; font-size: 0.9em;">
                Don't have an account? <a href="index.php" style="color: var(--primary-color);">Return home to Join Us</a>.
            </p>
        </div>
    </div>
    <script src="main.js?v=<?php echo time(); ?>"></script>
</body>
</html>