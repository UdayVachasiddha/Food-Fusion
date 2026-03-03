<?php
session_start();
require_once 'db_connect.php';

// Fetch all resources from the database
$query = "SELECT * FROM resources ORDER BY created_at DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Culinary Resources | FoodFusion</title>
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

    <header class="hero resource-hero">
        <div class="hero-content">
            <h1>Culinary Resources</h1>
            <p>Expand your knowledge with our free guides, video tutorials, and educational infographics.</p>
        </div>
    </header>

    <section class="resource-collection">
        <div class="card-grid">
            
            <?php 
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) { 
                    
                    // Determine which icon/color to show based on file type
                    $file_icon = "📄"; // Default
                    $badge_class = "badge-pdf";
                    if ($row['file_type'] == 'Video') {
                        $file_icon = "▶️";
                        $badge_class = "badge-video";
                    } elseif ($row['file_type'] == 'Infographic') {
                        $file_icon = "📊";
                        $badge_class = "badge-info";
                    }
            ?>
                <div class="card resource-card">
                    <div class="resource-icon-wrapper">
                        <span class="resource-icon"><?php echo $file_icon; ?></span>
                    </div>
                    
                    <div class="card-content" style="text-align: center;">
                        <span class="badge <?php echo $badge_class; ?>" style="margin-bottom: 10px; display: inline-block;">
                            <?php echo htmlspecialchars($row['file_type']); ?>
                        </span>
                        
                        <p style="font-size: 0.8rem; color: #888; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">
                            <?php echo str_replace('_', ' ', htmlspecialchars($row['category'])); ?>
                        </p>
                        
                        <h3 style="margin-bottom: 15px; font-size: 1.3rem;"><?php echo htmlspecialchars($row['title']); ?></h3>
                        
                        <a href="<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank" class="btn outline-btn full-width">Access Resource</a>
                    </div>
                </div>
            <?php 
                } 
            } else {
                echo "<p style='text-align: center; width: 100%;'>No resources available at the moment.</p>";
            }
            ?>

        </div>
    </section>

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