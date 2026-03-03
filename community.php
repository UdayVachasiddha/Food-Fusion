<?php
session_start();
require_once 'db_connect.php';

// Fetch COMMUNITY recipes (where user_id IS NOT NULL), joined with the users table to get their names
$query = "
    SELECT recipes.*, users.first_name, users.last_name 
    FROM recipes 
    JOIN users ON recipes.user_id = users.user_id 
    ORDER BY recipes.created_at DESC
";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Cookbook | FoodFusion</title>
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
            <li><a href="community.php" style="color: var(--primary-color);">Community</a></li>
            <li><a href="resources.php">Resources</a></li>
        </ul>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="btn outline-btn">Logout</a>
        <?php else: ?>
            <button id="openModalBtn" class="btn primary-btn">Join Us</button>
        <?php endif; ?>
    </nav>

    <header class="hero recipe-hero" style="background-image: linear-gradient(rgba(29, 53, 87, 0.7), rgba(29, 53, 87, 0.7)), url('https://images.unsplash.com/photo-1547592180-85f173990554?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');">
        <div class="hero-content">
            <h1>Community Cookbook</h1>
            <p>Share your kitchen triumphs and discover recipes from food enthusiasts around the world.</p>
        </div>
    </header>

    <section class="submission-section">
        <div class="form-container">
            <h2 class="section-title">Share Your Recipe</h2>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <form id="recipeForm" class="custom-form">
                    <div id="formMessage" class="alert" style="display: none;"></div>
                    
                    <input type="text" name="title" placeholder="Recipe Title" required>
                    <textarea name="description" placeholder="Brief Description..." rows="2" required></textarea>
                    
                    <div class="form-group-3">
                        <input type="text" name="cuisine_type" placeholder="Cuisine (e.g., Italian, Mexican)" required>
                        <input type="text" name="dietary_preference" placeholder="Dietary (e.g., Vegan, None)" required>
                        <select name="difficulty_level" required>
                            <option value="" disabled selected>Select Difficulty</option>
                            <option value="Easy">Easy</option>
                            <option value="Medium">Medium</option>
                            <option value="Hard">Hard</option>
                        </select>
                    </div>

                    <textarea name="instructions" placeholder="Step-by-step instructions..." rows="6" required></textarea>
                    <button type="submit" class="btn primary-btn full-width" id="submitRecipeBtn">Publish Recipe</button>
                </form>
            <?php else: ?>
                <div class="login-prompt">
                    <p>You must be logged in to share your culinary creations!</p>
                    <button id="triggerLoginModal" class="btn outline-btn" onclick="document.getElementById('joinModal').style.display='flex'">Log In / Sign Up</button>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="recipe-collection" style="background: var(--bg-color);">
        <h2 class="section-title">Latest Community Submissions</h2>
        <div class="card-grid">
            
            <?php 
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) { 
            ?>
                <div class="card recipe-card">
                    <div class="card-content" style="border-top: 4px solid var(--primary-color);">
                        <p style="font-size: 0.8rem; color: #888; text-transform: uppercase;">By <?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></p>
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        
                        <div class="recipe-badges" style="margin: 10px 0;">
                            <span class="badge badge-cuisine"><?php echo htmlspecialchars($row['cuisine_type']); ?></span>
                            <span class="badge badge-difficulty <?php echo strtolower($row['difficulty_level']); ?>"><?php echo htmlspecialchars($row['difficulty_level']); ?></span>
                        </div>
                        
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <a href="view_recipe.php?id=<?php echo $row['recipe_id']; ?>" class="btn outline-btn full-width" style="margin-top: 15px; text-align: center; display: block; text-decoration: none;">Read More</a>
                    </div>
                </div>
            <?php 
                } 
            } else {
                echo "<p style='text-align: center; width: 100%;'>No community recipes yet. Be the first to submit!</p>";
            }
            ?>
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