<?php
session_start();
require_once 'db_connect.php';

// 1. Check if an ID was passed in the URL and if it's a valid number
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // If someone tries to just type view_recipe.php with no ID, kick them back
    header("Location: recipes.php");
    exit();
}

$recipe_id = $_GET['id'];

// 2. Fetch the recipe AND the author's name (if it was submitted by a user)
$stmt = $conn->prepare("
    SELECT recipes.*, users.first_name, users.last_name 
    FROM recipes 
    LEFT JOIN users ON recipes.user_id = users.user_id 
    WHERE recipe_id = ?
");
$stmt->bind_param("i", $recipe_id);
$stmt->execute();
$result = $stmt->get_result();

// 3. Check if the recipe actually exists in the database
if ($result->num_rows === 0) {
    echo "<h2 style='text-align:center; margin-top: 50px;'>Recipe not found! <a href='recipes.php'>Go back.</a></h2>";
    exit();
}

$recipe = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($recipe['title']); ?> | FoodFusion</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="logo" style="display: flex; align-items: center; text-decoration: none;"><img src="assets/logo.png" alt="FoodFusion Logo" style="height: 55px; width: auto;"></a>
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

    <main class="single-recipe-container">
        
        <a href="recipes.php" class="back-link">&larr; Back to Recipes</a>

        <div class="single-recipe-header">
            <h1><?php echo htmlspecialchars($recipe['title']); ?></h1>
            
            <p class="recipe-author">
                <?php if ($recipe['user_id'] === NULL): ?>
                    Curated by <strong>FoodFusion Experts</strong>
                <?php else: ?>
                    Submitted by <strong><?php echo htmlspecialchars($recipe['first_name'] . " " . $recipe['last_name']); ?></strong>
                <?php endif; ?>
            </p>

            <div class="recipe-badges justify-center">
                <span class="badge badge-cuisine"><?php echo htmlspecialchars($recipe['cuisine_type']); ?></span>
                <span class="badge badge-diet"><?php echo htmlspecialchars($recipe['dietary_preference']); ?></span>
                <span class="badge badge-difficulty <?php echo strtolower($recipe['difficulty_level']); ?>"><?php echo htmlspecialchars($recipe['difficulty_level']); ?></span>
            </div>
            
            <p class="single-recipe-desc"><?php echo htmlspecialchars($recipe['description']); ?></p>
        </div>

        <hr class="recipe-divider">

        <div class="recipe-instructions-block">
            <h2>Instructions</h2>
            <div class="instructions-text">
                <?php echo nl2br(htmlspecialchars($recipe['instructions'])); ?>
            </div>
        </div>

    </main>

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