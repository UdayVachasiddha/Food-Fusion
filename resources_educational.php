<?php
session_start();
require_once 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Educational Resources | FoodFusion</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

    <nav class="navbar">
        <div class="logo">FoodFusion</div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="recipes.php">Recipes</a></li>
            <li><a href="community.php">Community</a></li>
            <li><a href="resources.php" style="color: var(--primary-color);">Resources</a></li>
        </ul>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="btn outline-btn">Logout</a>
        <?php else: ?>
            <button id="openModalBtn" class="btn primary-btn">Join Us</button>
        <?php endif; ?>
    </nav>

    <header class="hero resource-hero" style="background-image: linear-gradient(rgba(38, 70, 83, 0.7), rgba(38, 70, 83, 0.7)), url('https://images.unsplash.com/photo-1466611653911-95081537e5b7?auto=format&fit=crop&w=1950&q=80');">
        <div class="hero-content">
            <h1>Educational Resources</h1>
            <p>Learn more about renewable energy, sustainability, and how we can power a greener future together.</p>
        </div>
    </header>

    <!-- Educational Resources Section -->
    <section class="resource-collection" style="background: var(--bg-color); padding: 4rem 20px;">
        <h2 class="section-title" style="margin-bottom: 10px;">Educational Resources</h2>
        <p style="text-align: center; color: #666; max-width: 800px; margin: 0 auto 3rem auto; line-height: 1.6; font-size: 1.1rem;">
            Providing downloadable resources, infographics, and videos on renewable energy topics.
        </p>
        <div class="card-grid">
            
            <div class="card resource-card">
                <div class="resource-icon-wrapper" style="background: rgba(38, 70, 83, 0.1); color: #264653;">
                    <span class="resource-icon">📊</span>
                </div>
                <div class="card-content" style="text-align: center;">
                    <span class="badge badge-info" style="margin-bottom: 10px; display: inline-block; background: #264653; color: white;">Infographic</span>
                    <p style="font-size: 0.8rem; color: #888; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">Renewable Energy</p>
                    <h3 style="margin-bottom: 15px; font-size: 1.3rem;">Solar Energy: How it Powers Homes</h3>
                    <a href="https://images.unsplash.com/photo-1509391366360-2e959784a276?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80" target="_blank" class="btn outline-btn full-width" style="text-align: center; display: inline-block; text-decoration: none; border-color: #264653; color: #264653; box-sizing: border-box;">View Infographic</a>
                </div>
            </div>

            <div class="card resource-card">
                <div class="resource-icon-wrapper" style="background: rgba(42, 157, 143, 0.1); color: #2a9d8f;">
                    <span class="resource-icon">📄</span>
                </div>
                <div class="card-content" style="text-align: center;">
                    <span class="badge badge-pdf" style="margin-bottom: 10px; display: inline-block; background: #2a9d8f; color: white;">PDF Guide</span>
                    <p style="font-size: 0.8rem; color: #888; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">Sustainability</p>
                    <h3 style="margin-bottom: 15px; font-size: 1.3rem;">Wind Power in the Modern Grid</h3>
                    <a href="downloads/wind_power_guide.pdf" download="Wind_Power_Guide.pdf" class="btn outline-btn full-width" style="text-align: center; display: inline-block; text-decoration: none; border-color: #2a9d8f; color: #2a9d8f; box-sizing: border-box;">Download Guide</a>
                </div>
            </div>

            <div class="card resource-card">
                <div class="resource-icon-wrapper" style="background: rgba(233, 196, 106, 0.2); color: #e9c46a;">
                    <span class="resource-icon">▶️</span>
                </div>
                <div class="card-content" style="text-align: center;">
                    <span class="badge badge-video" style="margin-bottom: 10px; display: inline-block; background: #e9c46a; color: #333;">Video Series</span>
                    <p style="font-size: 0.8rem; color: #888; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">Green Tech</p>
                    <h3 style="margin-bottom: 15px; font-size: 1.3rem;">The Future of Hydropower</h3>
                    <a href="https://www.youtube.com/watch?v=LqUuG1n9Xv4" target="_blank" class="btn outline-btn full-width" style="text-align: center; display: inline-block; text-decoration: none; border-color: #e9c46a; color: #e9c46a; box-sizing: border-box;">Watch Video</a>
                </div>
            </div>

        </div>
    </section>

    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-logo">FoodFusion</div>
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
