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
$recipes_result = $conn->query($query);

// 1. Fetch ONLY the 3 most recent community posts
$feed_query = "SELECT cp.*, u.first_name, u.last_name 
               FROM community_posts cp 
               JOIN users u ON cp.user_id = u.user_id 
               ORDER BY cp.created_at DESC 
               LIMIT 3";
$feed_result = $conn->query($feed_query);

// 2. Count the total number of posts to see if we need a "Read More" button
$count_query = "SELECT COUNT(*) as total FROM community_posts";
$count_result = $conn->query($count_query);
$total_posts = $count_result->fetch_assoc()['total'];
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
        <a href="index.php" class="logo" style="display: flex; align-items: center; text-decoration: none;"><img src="assets/logo.png" alt="FoodFusion Logo"></a>
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
                    <input type="url" name="image_url" placeholder="Recipe Image URL (optional)" class="form-control" style="width: 100%; padding: 12px; margin-bottom: 20px; border-radius: 8px; border: 1px solid #ddd; box-sizing: border-box; font-family: var(--font-body); font-size: 1rem;">
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

        <div class="community-feed" style="max-width: 1200px; margin: 4rem auto; padding: 0 20px;">
            <h2 style="text-align: center; margin-bottom: 2rem; font-family: var(--font-heading); color: var(--primary-color);">Latest from the Community</h2>

            <?php if ($feed_result && $feed_result->num_rows > 0): ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
                    
                    <?php while($post = $feed_result->fetch_assoc()): ?>
                        <div class="post-card" style="background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 5px solid var(--primary-color); display: flex; flex-direction: column;">
                            
                            <div style="margin-bottom: 15px;">
                                <span style="background: #fef2f2; color: #dc2626; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; display: inline-block; margin-bottom: 10px;">
                                    <?php echo htmlspecialchars($post['post_type']); ?>
                                </span>
                                <div style="font-size: 0.85rem; color: #666; font-weight: 600;">
                                    By <?php echo htmlspecialchars($post['first_name'] . ' ' . $post['last_name']); ?>
                                </div>
                                <div style="font-size: 0.8rem; color: #999; margin-top: 3px;">
                                    <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                                </div>
                            </div>

                            <h3 style="margin-bottom: 15px; font-size: 1.25rem; color: var(--text-dark); font-family: var(--font-heading);">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </h3>
                            
                            <p style="color: #444; line-height: 1.6; font-size: 0.95rem; margin-bottom: 20px; flex-grow: 1; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                                <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                            </p>
                            
                        </div>
                    <?php endwhile; ?>
                    
                </div>

                <?php if ($total_posts > 3): ?>
                    <div style="text-align: center; margin-top: 3rem;">
                        <a href="all_community_posts.php" class="btn primary-btn" style="padding: 12px 35px; border-radius: 50px; text-decoration: none; font-weight: 600; letter-spacing: 1px; text-transform: uppercase;">
                            View All <?php echo $total_posts; ?> Posts &rarr;
                        </a>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div style="text-align: center; padding: 3rem; background: #fff; border-radius: 12px;">
                    <p style="font-size: 1.1rem; color: #666;">No posts yet. Be the first to share your culinary wisdom!</p>
                </div>
            <?php endif; ?>
        </div>

        <form action="submit_post.php" method="POST" class="community-form">
            <div class="form-group">
                <label for="post_type">What would you like to share?</label>
                <select name="post_type" id="post_type" class="form-control" required>
                    <option value="Recipe">Favourite Recipe</option>
                    <option value="Tip">Cooking Tip</option>
                    <option value="Experience">Culinary Experience</option>
                </select>
            </div>

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="e.g., My Grandma's Lasagna OR How to dice an onion..." required>
            </div>

            <div class="form-group">
                <label for="content">The Details (Ingredients, Instructions, or Story)</label>
                <textarea name="content" id="content" rows="6" class="form-control" placeholder="Share your recipe steps, your best kitchen hack, or a fun cooking story..." required></textarea>
            </div>

            <button type="submit" class="btn primary-btn full-width" style="margin-top: 10px;">Publish Post</button>
        </form>
    </section>

    <section class="recipe-collection" style="background: var(--bg-color);">
        <h2 class="section-title">Latest Community Submissions</h2>
        <div class="card-grid">
            
            <?php 
            if ($recipes_result && $recipes_result->num_rows > 0) {
                while($row = $recipes_result->fetch_assoc()): 
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
                endwhile;
            } else {
                echo "<p style='text-align: center; width: 100%;'>No community recipes yet. Be the first to submit!</p>";
            }
            ?>
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

        <!-- --- Success Notification Modal --- -->
        <div id="successModal" class="modal success-modal">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <div class="success-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <h2 id="successModalTitle">Success!</h2>
                <p id="successModalMessage">Your content has been published successfully.</p>
                <button class="btn primary-btn close-modal-btn" style="width: 100%; border-radius: 50px; padding: 12px;">Great!</button>
            </div>
        </div>

    <script src="main.js?v=<?php echo time(); ?>"></script>
</body>
</html>