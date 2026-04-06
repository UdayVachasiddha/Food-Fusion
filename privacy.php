<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy | FoodFusion</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="logo"><img src="assets/logo.png" alt="FoodFusion Logo"></a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="recipes.php">Recipes</a></li>
            <li><a href="community.php">Community</a></li>
            <li><a href="resources.php">Resources</a></li>
        </ul>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="btn outline-btn">Logout</a>
        <?php else: ?>
            <button id="openModalBtn" class="btn primary-btn">Join Us</button>
        <?php endif; ?>
    </nav>

    <header class="hero" style="height: 25vh; background: linear-gradient(rgba(29, 53, 87, 0.9), rgba(29, 53, 87, 0.9));">
        <div class="hero-content">
            <h1 style="font-size: 2.5rem;">Privacy Policy</h1>
        </div>
    </header>

    <main class="policy-container">
        <p class="last-updated"><strong>Last Updated:</strong> <?php echo date("F j, Y"); ?></p>

        <section class="policy-section">
            <h2>1. Introduction</h2>
            <p>Welcome to FoodFusion. We are committed to protecting your personal information and your right to privacy. If you have any questions or concerns about this privacy notice or our practices with regard to your personal information, please contact us via our Contact page.</p>
        </section>

        <section class="policy-section">
            <h2>2. Information We Collect</h2>
            <p>We collect personal information that you voluntarily provide to us when you register on the website, express an interest in obtaining information about us or our products and services, or when you participate in activities on the website (such as posting recipes to the Community Cookbook).</p>
            <ul>
                <li><strong>Personal Data:</strong> Names, email addresses, and passwords.</li>
                <li><strong>Application Data:</strong> User-submitted recipes, comments, and dietary preferences.</li>
            </ul>
        </section>

        <section class="policy-section">
            <h2>3. How We Use Cookies</h2>
            <p>We use cookies and similar tracking technologies to access or store information. Cookies allow us to recognize your browser or device and tell us how and when pages and features in our Services are visited and by how many people. You can manage your cookie preferences through our consent banner.</p>
        </section>

        <section class="policy-section">
            <h2>4. Data Security & Forensics</h2>
            <p>Security is a core pillar of FoodFusion. We have implemented appropriate technical and organizational security measures designed to protect the security of any personal information we process. This includes utilizing strong cryptographic hashing for all user passwords and strictly enforcing parameterized queries (Prepared Statements) across our MySQL database architecture to mitigate SQL injection vulnerabilities.</p>
        </section>

        <section class="policy-section">
            <h2>5. Your Privacy Rights</h2>
            <p>Depending on your location, you may have the right to request access to the personal information we collect from you, change that information, or delete it in some circumstances. To request to review, update, or delete your personal information, please submit a request through our Contact form.</p>
        </section>
    </main>

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
            </form>
        </div>
    </div>
    <?php endif; ?>

    <script src="main.js?v=<?php echo time(); ?>"></script>
</body>
</html>