<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | FoodFusion</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="logo" style="display: flex; align-items: center; text-decoration: none;"><img src="assets/logo.png" alt="FoodFusion Logo" style="height: 55px; width: auto;"></a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php" style="color: var(--primary-color);">About Us</a></li>
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

    <header class="hero about-hero">
        <div class="hero-content">
            <h1>Our Story</h1>
            <p>Bringing people together through the joy of home cooking and shared culinary experiences.</p>
        </div>
    </header>

    <section class="about-section">
        <h2 class="section-title">Our Culinary Philosophy</h2>
        <div class="philosophy-content">
            <p class="lead-text">At FoodFusion, we believe that cooking is more than just a daily chore, it is an art form, a way to connect with loved ones, and a celebration of global cultures.</p>
            <p>We are dedicated to demystifying the kitchen. Whether you are boiling water for the first time or mastering complex French sauces, our mission is to make culinary creativity accessible, enjoyable, and rewarding for everyone.</p>
        </div>
    </section>

    <section class="values-section">
        <h2 class="section-title">Our Core Values</h2>
        <div class="card-grid values-grid">
            <div class="card value-card">
                <h3>🤝 Community</h3>
                <p>We foster a supportive environment where food enthusiasts can share, learn, and grow together without judgment.</p>
            </div>
            <div class="card value-card">
                <h3>💡 Creativity</h3>
                <p>We encourage stepping outside the recipe box. Substitute, experiment, and make every dish your own.</p>
            </div>
            <div class="card value-card">
                <h3>🌍 Sustainability</h3>
                <p>We promote mindful consumption, reducing food waste, and embracing eco-friendly kitchen practices.</p>
            </div>
        </div>
    </section>

    <section class="about-section team-section">
        <h2 class="section-title">The Team Behind FoodFusion</h2>
        <div class="team-grid">
            <div class="team-member">
                <img src="Ansh.png" alt="Ansh Maurya">
                <h3>Ansh Maurya</h3>
                <p>Founder & Head Chef</p>
            </div>
            <div class="team-member">
                <img src="Aryan.jpg" alt="Aryan Nagaonkar">
                <h3>Aryan Nagaonkar</h3>
                <p>Community Manager</p>
            </div>
            <div class="team-member">
                <img src="Nikyaa.jpeg" alt="Nekeel Das">
                <h3>Nekeel Das</h3>
                <p>Recipe Curator</p>
            </div>
        </div>
    </section>

    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-logo" style="display: flex; align-items: center; margin-bottom: 15px;"><img src="assets/logo.png" alt="FoodFusion Logo" style="height: 65px; width: auto; filter: drop-shadow(0px 2px 4px rgba(0,0,0,0.2));"></div>
            <div class="footer-links">
                <a href="privacy.php">Privacy Policy</a>
                <a href="terms.php">Terms of Service</a>
                <a href="contact.php">Contact Us</a>
            </div>
            
            <div class="social-links">
                <a href="https://facebook.com" target="_blank" class="social-item" aria-label="Facebook">
                    <span class="social-name">Facebook</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                </a>
                <a href="https://instagram.com" target="_blank" class="social-item" aria-label="Instagram">
                    <span class="social-name">Instagram</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                </a>
                <a href="https://twitter.com" target="_blank" class="social-item" aria-label="Twitter">
                    <span class="social-name">Twitter</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
                </a>
            </div>
        </div>
        <div class="copyright">
            &copy; <?php echo date("Y"); ?> FoodFusion. All rights reserved.
        </div>
    </footer>

<?php if(!isset($_SESSION['user_id'])): // Only render the modal if they aren't logged in ?>
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