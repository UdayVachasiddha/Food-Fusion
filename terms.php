<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service | FoodFusion</title>
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

        <div class="hamburger">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
        </div>
    </nav>

    <header class="hero" style="height: 25vh; background: linear-gradient(rgba(29, 53, 87, 0.9), rgba(29, 53, 87, 0.9));">
        <div class="hero-content">
            <h1 style="font-size: 2.5rem;">Terms of Service</h1>
        </div>
    </header>

    <main class="policy-container">
        <p class="last-updated"><strong>Last Updated:</strong> <?php echo date("F j, Y"); ?></p>

        <section class="policy-section">
            <h2>1. Acceptance of Terms</h2>
            <p>By accessing and using FoodFusion (the "Service"), you accept and agree to be bound by the terms and provision of this agreement. In addition, when using these particular services, you shall be subject to any posted guidelines or rules applicable to such services.</p>
        </section>

        <section class="policy-section">
            <h2>2. User Accounts & Security</h2>
            <p>To access certain features of the Service, such as the Community Cookbook, you must register for an account. You are responsible for maintaining the confidentiality of your account credentials. FoodFusion utilizes secure cryptographic hashing for passwords, but you agree to accept responsibility for all activities that occur under your account.</p>
        </section>

        <section class="policy-section">
            <h2>3. Community Submissions</h2>
            <p>By submitting recipes, comments, or other content to FoodFusion, you grant us a non-exclusive, royalty-free, perpetual, and worldwide license to use, modify, publicly perform, publicly display, reproduce, and distribute such content on and through the Service.</p>
            <ul>
                <li>You must own the rights to the content you post.</li>
                <li>Content must not be illegal, offensive, or violate intellectual property rights.</li>
            </ul>
        </section>

        <section class="policy-section">
            <h2>4. Prohibited Conduct</h2>
            <p>You agree not to engage in any of the following prohibited activities:</p>
            <ul>
                <li>Attempting to interfere with, compromise the system integrity, or decipher any transmissions to or from the servers running FoodFusion (e.g., SQL Injection, XSS attacks).</li>
                <li>Impersonating another person or otherwise misrepresenting your affiliation with a person or entity.</li>
                <li>Using the Service for any commercial solicitation purposes.</li>
            </ul>
        </section>

        <section class="policy-section">
            <h2>5. Termination</h2>
            <p>We may terminate or suspend your account and bar access to the Service immediately, without prior notice or liability, under our sole discretion, for any reason whatsoever and without limitation, including but not limited to a breach of the Terms.</p>
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