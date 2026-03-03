<?php
session_start();
require_once 'db_connect.php'; 

// 1. Base query to fetch all recipes
$query = "SELECT recipes.*, users.first_name, users.last_name FROM recipes LEFT JOIN users ON recipes.user_id = users.user_id";

// 2. Arrays to hold our dynamic filter conditions and parameters
$conditions = [];
$params = [];
$types = "";

// 3. Check if filters were submitted and add them to the query
if (isset($_GET['cuisine']) && $_GET['cuisine'] !== 'all') {
    $conditions[] = "cuisine_type = ?";
    $params[] = $_GET['cuisine'];
    $types .= "s";
}

if (isset($_GET['diet']) && $_GET['diet'] !== 'all') {
    $conditions[] = "dietary_preference = ?";
    $params[] = $_GET['diet'];
    $types .= "s";
}

if (isset($_GET['difficulty']) && $_GET['difficulty'] !== 'all') {
    $conditions[] = "difficulty_level = ?";
    $params[] = $_GET['difficulty'];
    $types .= "s";
}

// 4. If we have any conditions, append them to the base query with "WHERE" and "AND"
if (count($conditions) > 0) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

// 5. Always order by newest first at the very end
$query .= " ORDER BY recipes.created_at DESC";

// 6. Execute securely using prepared statements
$stmt = $conn->prepare($query);
if ($types) {
    // Dynamically bind the parameters if filters were used
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Helper function to keep dropdowns selected after page reload
function isSelected($filterName, $value) {
    return (isset($_GET[$filterName]) && $_GET[$filterName] === $value) ? 'selected' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Collection | FoodFusion</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

    <nav class="navbar">
        <div class="logo">FoodFusion</div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="recipes.php" style="color: var(--primary-color);">Recipes</a></li>
            <li><a href="community.php">Community</a></li>
            <li><a href="resources.php">Resources</a></li>
        </ul>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="btn outline-btn">Logout</a>
        <?php else: ?>
            <button id="openModalBtn" class="btn primary-btn">Join Us</button>
        <?php endif; ?>
    </nav>

    <header class="hero recipe-hero">
        <div class="hero-content">
            <h1>Curated Recipe Collection</h1>
            <p>Explore diverse flavors from around the globe, hand-picked by our culinary experts.</p>
        </div>
    </header>

    <section class="filter-section">
        <form method="GET" action="recipes.php" class="filter-container">
            <span><strong>Filter By:</strong></span>
            
            <select name="cuisine" class="filter-dropdown">
                <option value="all">All Cuisines</option>
                <option value="Italian" <?php echo isSelected('cuisine', 'Italian'); ?>>Italian</option>
                <option value="Thai" <?php echo isSelected('cuisine', 'Thai'); ?>>Thai</option>
                <option value="Indian" <?php echo isSelected('cuisine', 'Indian'); ?>>Indian</option>
                <option value="Mexican" <?php echo isSelected('cuisine', 'Mexican'); ?>>Mexican</option>
            </select>
            
            <select name="diet" class="filter-dropdown">
                <option value="all">Any Diet</option>
                <option value="Vegetarian" <?php echo isSelected('diet', 'Vegetarian'); ?>>Vegetarian</option>
                <option value="Vegan" <?php echo isSelected('diet', 'Vegan'); ?>>Vegan</option>
                <option value="High-Protein" <?php echo isSelected('diet', 'High-Protein'); ?>>High-Protein</option>
            </select>
            
            <select name="difficulty" class="filter-dropdown">
                <option value="all">Any Difficulty</option>
                <option value="Easy" <?php echo isSelected('difficulty', 'Easy'); ?>>Easy</option>
                <option value="Medium" <?php echo isSelected('difficulty', 'Medium'); ?>>Medium</option>
                <option value="Hard" <?php echo isSelected('difficulty', 'Hard'); ?>>Hard</option>
            </select>
            
            <button type="submit" class="btn primary-btn" style="padding: 8px 20px;">Apply Filters</button>
            
            <?php if(isset($_GET['cuisine']) || isset($_GET['diet']) || isset($_GET['difficulty'])): ?>
                <a href="recipes.php" class="btn outline-btn" style="padding: 8px 15px; text-decoration: none;">Clear</a>
            <?php endif; ?>
        </form>
    </section>

    <section class="recipe-collection">
        <div class="card-grid">
            
            <?php 
            // Check if we have recipes in the database
            if ($result->num_rows > 0) {
                // Loop through each row in the database and generate a card
                while($row = $result->fetch_assoc()) { 
            ?>
                <div class="card recipe-card">
                    <img src="https://images.unsplash.com/photo-1495521821757-a1efb6729352?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Recipe Image">
                    
                    <div class="card-content">
                        <div class="recipe-badges">
                            <span class="badge badge-cuisine"><?php echo htmlspecialchars($row['cuisine_type']); ?></span>
                            <span class="badge badge-diet"><?php echo htmlspecialchars($row['dietary_preference']); ?></span>
                            <span class="badge badge-difficulty <?php echo strtolower($row['difficulty_level']); ?>"><?php echo htmlspecialchars($row['difficulty_level']); ?></span>
                        </div>
                        
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        
                        <a href="view_recipe.php?id=<?php echo $row['recipe_id']; ?>" class="btn primary-btn full-width" style="margin-top: 15px; text-align: center; display: block; text-decoration: none; box-sizing: border-box;">View Recipe</a>
                    </div>
                </div>
            <?php 
                } 
            } else {
                echo "<p style='text-align: center; width: 100%;'>No recipes found in the database yet.</p>";
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