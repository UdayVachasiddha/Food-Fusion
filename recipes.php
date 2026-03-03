<?php
session_start();
require_once 'db_connect.php'; // Connect to our database!

// Fetch all curated recipes (where user_id is NULL)
// We will order them by newest first
$query = "SELECT * FROM recipes WHERE user_id IS NULL ORDER BY created_at DESC";
$result = $conn->query($query);
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
        <div class="filter-container">
            <span><strong>Filter By:</strong></span>
            <select class="filter-dropdown">
                <option value="all">All Cuisines</option>
                <option value="italian">Italian</option>
                <option value="thai">Thai</option>
                <option value="mexican">Mexican</option>
            </select>
            <select class="filter-dropdown">
                <option value="all">Any Diet</option>
                <option value="vegetarian">Vegetarian</option>
                <option value="vegan">Vegan</option>
                <option value="gluten-free">Gluten-Free</option>
            </select>
            <select class="filter-dropdown">
                <option value="all">Any Difficulty</option>
                <option value="easy">Easy</option>
                <option value="medium">Medium</option>
                <option value="hard">Hard</option>
            </select>
            <button class="btn primary-btn">Apply Filters</button>
        </div>
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