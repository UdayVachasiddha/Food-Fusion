<?php
session_start();
require_once 'db_connect.php';

// Fetch the 3 most recent recipes for the Culinary Trends section
$featured_query = "SELECT * FROM recipes ORDER BY created_at DESC LIMIT 3";
$featured_result = $conn->query($featured_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodFusion | Culinary Creativity</title>
    <link rel="stylesheet" href="style.css">
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
            <li><a href="resources.php">Resources</a></li>
        </ul>
        
        <?php 
        // Dynamic Button: Check if the user is already logged in
        if(isset($_SESSION['user_id'])): 
        ?>
            <a href="logout.php" class="btn outline-btn">Logout</a>
        <?php else: ?>
            <button id="openModalBtn" class="btn primary-btn">Join Us</button>
        <?php endif; ?>

    </nav>

    <header class="hero">
        <div class="hero-content">
            <h1>Ignite Your Culinary Creativity</h1>
            <p>Join a vibrant community of food enthusiasts. Discover recipes, share your kitchen triumphs, and elevate your home cooking.</p>
            <a href="recipes.php" class="hero-btn">Explore Recipes</a>
            <!-- <li><a href="recipes.php" class="btn secondary-btn">Explore Recipes</a></li> -->
        </div>
    </header>

    <?php if(!isset($_SESSION['user_id'])): // Only render the modal if they aren't logged in ?>
    <div id="joinModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Join FoodFusion</h2>
            <p>Create your account to start sharing and saving recipes.</p>
            <form id="registerForm" action="register_process.php" method="POST">
                
                <div id="modalAlert" class="alert" style="display: none; margin-bottom: 15px;"></div>
                
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

   

    <section class="news-feed">
        <h2 class="section-title">Culinary Trends & Featured Recipes</h2>
        <div class="card-grid">
        <?php 
        // 1. Create a gallery of high-quality default images
        $default_images = [
            'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80', // Hearty dish
            'https://images.unsplash.com/photo-1543353071-873f17a7a088?auto=format&fit=crop&w=800&q=80', // Plated meal
            'https://images.unsplash.com/photo-1476224203421-9ac39bcb3327?auto=format&fit=crop&w=800&q=80', // Gourmet
            'https://images.unsplash.com/photo-1495521821757-a1efb6729352?auto=format&fit=crop&w=800&q=80'  // Your original tablet image
        ];

        if ($featured_result && $featured_result->num_rows > 0) {
            while($row = $featured_result->fetch_assoc()) { 
                
                // 2. Clever trick: Use the recipe's unique ID to pick an image from the array!
                // This ensures the image stays consistent for that specific recipe on refresh.
                $fallback_index = $row['recipe_id'] % count($default_images);
                
                // 3. Check if they uploaded a real image; if not, use the rotating fallback
                $image = !empty($row['image_url']) ? htmlspecialchars($row['image_url']) : $default_images[$fallback_index];
                
                // Truncate instructions
                $snippet = htmlspecialchars(substr($row['instructions'], 0, 90)) . '...';
        ?>
            <div class="card" style="border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); background: #fff;">
                <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" style="width: 100%; height: 200px; object-fit: cover;">
                
                <div class="card-content" style="padding: 20px;">
                    <h3 style="color: var(--primary-color); margin-bottom: 10px; font-family: var(--font-heading); font-size: 1.25rem;">
                        <?php echo htmlspecialchars($row['title']); ?>
                    </h3>
                    <p style="color: var(--text-dark); font-size: 0.95rem; line-height: 1.6; margin-bottom: 15px;">
                        <?php echo $snippet; ?>
                    </p>
                    <a href="view_recipe.php?id=<?php echo $row['recipe_id']; ?>" style="color: var(--text-dark); font-weight: 600; text-decoration: none; font-size: 0.9rem;">
                        Read Recipe &rarr;
                    </a>
                </div>
            </div>
        <?php 
            } 
        } else {
            echo "<p style='text-align: center; width: 100%;'>No featured recipes to display at the moment. Be the first to add one!</p>";
        }
        ?>
    </div>
    </section>

    <section class="events-section">
        <h2 class="section-title">Upcoming Cooking Events</h2>
        <div class="carousel-container">
            <button class="carousel-btn prev-btn" onclick="moveCarousel(-1)">&#10094;</button>
            <div class="carousel-track">
                
                <div class="carousel-slide">
                    <div class="event-box">
                        <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80" alt="French Sauces" class="event-image">
                        <div class="event-details">
                            <h3>Mastering French Sauces</h3>
                            <p>Online Workshop</p>
                            <div class="countdown-timer" data-date="2026-06-15T10:00:00"></div>
                            <button class="btn outline-btn" style="margin-top: 15px; padding: 5px 15px; font-size: 0.9em;" onclick="registerForEvent('Mastering French Sauces')">Register Now</button>
                        </div>
                    </div>
                </div>

                <div class="carousel-slide">
                    <div class="event-box">
                        <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80" alt="Sourdough" class="event-image">
                        <div class="event-details">
                            <h3>Sourdough for Beginners</h3>
                            <p>Community Kitchen</p>
                            <div class="countdown-timer" data-date="2026-06-22T14:00:00"></div>
                            <button class="btn outline-btn" style="margin-top: 15px; padding: 5px 15px; font-size: 0.9em;" onclick="registerForEvent('Sourdough for Beginners')">Register Now</button>
                        </div>
                    </div>
                </div>

                <div class="carousel-slide">
                    <div class="event-box">
                        <img src="https://images.unsplash.com/photo-1596040033229-a9821ebd058d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80" alt="Spices" class="event-image">
                        <div class="event-details">
                            <h3>Global Spices Masterclass</h3>
                            <p>Live Webinar</p>
                            <div class="countdown-timer" data-date="2026-07-05T18:00:00"></div>
                            <button class="btn outline-btn" style="margin-top: 15px; padding: 5px 15px; font-size: 0.9em;" onclick="registerForEvent('Global Spices Masterclass')">Register Now</button>
                        </div>
                    </div>
                </div>

            </div>
            <button class="carousel-btn next-btn" onclick="moveCarousel(1)">&#10095;</button>
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

    <!-- Event Registration Form Modal -->
    <div id="eventFormModal" class="modal" style="display: none; align-items: center; justify-content: center;">
        <div class="modal-content" style="max-width: 400px; padding: 35px 30px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <span class="close-btn" onclick="document.getElementById('eventFormModal').style.display='none'" style="position: absolute; top: 15px; right: 20px; font-size: 24px; cursor: pointer;">&times;</span>
            <h2 style="margin-bottom: 5px; font-family: var(--font-heading); color: var(--text-dark); text-align: center;">Registration</h2>
            <h4 id="formEventNameDisplay" style="text-align: center; color: var(--primary-color); margin-bottom: 25px; font-size: 1.1em; font-weight: normal;"></h4>
            
            <form id="eventRegistrationForm">
                <input type="hidden" name="event_name" id="hiddenEventName">
                <div class="form-group" style="margin-bottom: 15px;">
                    <input type="text" name="username" placeholder="Pick a Username" class="form-control" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ccc; font-size: 1rem;" required>
                </div>
                <div class="form-group" style="margin-bottom: 25px;">
                    <input type="email" name="email" placeholder="Your Email Address" class="form-control" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ccc; font-size: 1rem;" required>
                </div>
                <button type="submit" id="submitEventBtn" class="btn primary-btn" style="width: 100%; border-radius: 50px; font-weight: 600; font-size: 1.1em; padding: 12px; cursor: pointer;">Complete Registration</button>
            </form>
        </div>
    </div>

    <!-- Event Success Modal -->
    <div id="eventModal" class="modal" style="display: none; align-items: center; justify-content: center;">
        <div class="modal-content" style="text-align: center; max-width: 400px; padding: 35px 30px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <span class="close-btn" onclick="document.getElementById('eventModal').style.display='none'" style="position: absolute; top: 15px; right: 20px; font-size: 24px; cursor: pointer;">&times;</span>
            <div id="eventModalIcon" style="font-size: 54px; color: #28a745; margin-bottom: 20px; line-height: 1;">&#10004;</div>
            <h2 id="eventModalTitle" style="margin-bottom: 15px; font-family: var(--font-heading); color: var(--text-dark);">Registered!</h2>
            <p id="eventModalMessage" style="color: #555; line-height: 1.6; margin-bottom: 25px; font-size: 1.05em;">You have successfully registered for the event.</p>
            <button class="btn primary-btn" style="width: 100%; border-radius: 50px; font-weight: 600; font-size: 1.1em; padding: 12px;" onclick="document.getElementById('eventModal').style.display='none'">Awesome!</button>
        </div>
    </div>

    <div id="cookieConsent" class="cookie-banner" style="display: none;">
        <div class="cookie-content">
            <p>We use cookies to improve your experience, serve personalized recipes, and analyze site traffic. By clicking "Accept All", you agree to our use of cookies.</p>
            <div class="cookie-buttons">
                <a href="privacy.php" class="cookie-link">Learn More</a>
                <button id="acceptCookies" class="btn primary-btn">Accept All</button>
            </div>
        </div>
    </div>

    <script src="main.js?v=<?php echo time(); ?>"></script>
</body>
</html>