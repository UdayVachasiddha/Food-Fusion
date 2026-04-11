<?php
session_start();
require_once 'db_connect.php';
require_once 'config.php'; // SMTP Credentials

// PHPMailer Classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'libs/PHPMailer/src/Exception.php';
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';

$success_msg = '';
$error_msg = '';

// Process the contact form when it's submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject_type = $_POST['subject_type'];
    $message = trim($_POST['message']);

    // Basic Validation
    if (empty($name) || empty($email) || empty($subject_type) || empty($message)) {
        $error_msg = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Please enter a valid email address.";
    } else {
        // Insert into database using prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject_type, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject_type, $message);

        if ($stmt->execute()) {
            // --- Automated Acknowledgement via Gmail SMTP (PHPMailer) ---
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = SMTP_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = SMTP_USER;
                $mail->Password   = SMTP_PASS;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = SMTP_PORT;

                // Recipients
                $mail->setFrom(SMTP_USER, SMTP_FROM_NAME);
                $mail->addAddress($email, $name); 

                // Content
                $mail->isHTML(true);
                $mail->Subject = "Acknowledgement: Your Enquiry to FoodFusion";
                
                $mail->Body = "
                <html>
                <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                    <div style='max-width: 600px; margin: 0 auto; border: 1px solid #eee; padding: 20px; border-radius: 10px;'>
                        <h2 style='color: #E63946;'>Hello $name,</h2>
                        <p>Thank you for reaching out to <strong>FoodFusion</strong>!</p>
                        <p>We have successfully received your <strong>$subject_type</strong>. Our team is already reviewing your request and will get back to you within 24-48 business hours.</p>
                        <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
                        <p style='font-size: 0.9em; color: #777;'>
                            This is an automated acknowledgement from your Gmail-powered SMTP service. There is no need to reply to this email.
                        </p>
                        <p>Happy Cooking,<br><strong>Team FoodFusion</strong></p>
                    </div>
                </body>
                </html>
                ";

                $mail->send();
                $success_msg = "Thank you! Your message was sent, and an acknowledgement has been sent to your email via Gmail SMTP.";
            } catch (Exception $e) {
                // If mail fails, we still show success for the message submission but log the error
                $success_msg = "Thank you! Your message was saved, but we couldn't send the automated email. Error: " . $mail->ErrorInfo;
            }
        } else {
            $error_msg = "Oops! Something went wrong. Please try again later.";
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
    <title>Contact Us | FoodFusion</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="logo" style="display: flex; align-items: center; text-decoration: none;"><img src="assets/logo.png" alt="FoodFusion Logo"></a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="recipes.php">Recipes</a></li>
            <li><a href="community.php">Community</a></li>
            <li><a href="resources.php">Resources</a></li>
        </ul>
        
        <div style="display: flex; align-items: center; gap: 10px;">
            <button id="theme-toggle" class="theme-switch" aria-label="Toggle Dark Mode">
                <svg class="sun-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                <svg class="moon-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
            </button>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="btn outline-btn">Logout</a>
            <?php else: ?>
                <button id="openModalBtn" class="btn primary-btn">Join Us</button>
            <?php endif; ?>
        </div>
    </nav>

    <header class="hero contact-hero">
        <div class="hero-content">
            <h1>Get In Touch</h1>
            <p>Have a question, feedback, or a recipe request? We would love to hear from you!</p>
        </div>
    </header>

    <section class="contact-section">
        <div class="contact-container">
            
            <div class="contact-info">
                <h2>Contact Information</h2>
                <p>Fill out the form, and our culinary team will get back to you within 24 hours.</p>
                
                <div class="info-item">
                    <strong>📧 Email:</strong> support@foodfusion.com
                </div>
                <div class="info-item">
                    <strong>📍 Location:</strong> 123 Culinary Lane, Flavor Town
                </div>
                <div class="info-item">
                    <strong>⏰ Hours:</strong> Mon - Fri, 9:00 AM - 6:00 PM
                </div>
            </div>

            <div class="contact-form-wrapper">
                
                <?php if (!empty($success_msg)): ?>
                    <div class="alert alert-success"><?php echo $success_msg; ?></div>
                <?php endif; ?>
                
                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-error"><?php echo $error_msg; ?></div>
                <?php endif; ?>

                <form action="contact.php" method="POST" class="custom-form">
                    <input type="text" name="name" placeholder="Your Full Name" required>
                    <input type="email" name="email" placeholder="Your Email Address" required>
                    
                    <select name="subject_type" required>
                        <option value="" disabled selected>Select a Subject</option>
                        <option value="Enquiry">General Enquiry</option>
                        <option value="Recipe Request">Recipe Request</option>
                        <option value="Feedback">Website Feedback</option>
                    </select>

                    <textarea name="message" placeholder="How can we help you today?" rows="6" required></textarea>
                    
                    <button type="submit" class="btn primary-btn full-width">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <?php if(!isset($_SESSION['user_id'])): ?>
    <div id="joinModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Join FoodFusion</h2>
            <p>Create your account to start sharing and saving recipes.</p>
            <form id="registerForm" action="register_process.php" method="POST">
                <div class="form-group">
                    <input type="text" name="first_name" placeholder="First Name" required>
                    <input type="text" name="last_name" placeholder="Last Name" required>
                </div>
                <input type="email" name="email" placeholder="Email Address" required>
                <div class="password-wrapper">
                    <input type="password" name="password" id="regPassword" placeholder="Password" required>
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('regPassword', this)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>
                <button type="submit" class="btn primary-btn full-width">Sign Up</button>
                <p style="margin-top: 15px; font-size: 0.9em; text-align: center;">
                    Already have an account? <a href="login.php" style="color: var(--primary-color);">Log in here</a>.
                </p>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <script src="main.js?v=<?php echo time(); ?>"></script>
</body>
</html>