<?php
session_start();
require_once 'db_connect.php';

// Fetch ALL posts for this dedicated feed page
$feed_query = "SELECT cp.*, u.first_name, u.last_name 
               FROM community_posts cp 
               JOIN users u ON cp.user_id = u.user_id 
               ORDER BY cp.created_at DESC";
$feed_result = $conn->query($feed_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Community Posts | FoodFusion</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="logo" style="display: flex; align-items: center; text-decoration: none;"><img src="assets/logo.png" alt="FoodFusion Logo" style="height: 55px; width: auto;"></a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
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

    <header class="hero" style="height: 20vh; background: linear-gradient(rgba(29, 53, 87, 0.9), rgba(29, 53, 87, 0.9));">
        <div class="hero-content">
            <h1 style="font-size: 2.5rem;">The Community Feed</h1>
        </div>
    </header>

    <main style="max-width: 800px; margin: 4rem auto; padding: 0 20px;">
        <div style="margin-bottom: 2rem;">
            <a href="community.php" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">&larr; Back to Submission Form</a>
        </div>

        <?php if ($feed_result && $feed_result->num_rows > 0): ?>
            <?php while($post = $feed_result->fetch_assoc()): ?>
                <div class="post-card" style="background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 25px; border-left: 5px solid var(--primary-color);">
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px;">
                        <div>
                            <span style="background: #fef2f2; color: #dc2626; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase;">
                                <?php echo htmlspecialchars($post['post_type']); ?>
                            </span>
                            <span style="margin-left: 10px; font-size: 0.9rem; color: #666; font-weight: 600;">
                                By <?php echo htmlspecialchars($post['first_name'] . ' ' . $post['last_name']); ?>
                            </span>
                        </div>
                        <span style="font-size: 0.85rem; color: #999;">
                            <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                        </span>
                    </div>

                    <h3 style="margin-bottom: 15px; font-size: 1.4rem; color: var(--text-dark);">
                        <?php echo htmlspecialchars($post['title']); ?>
                    </h3>
                    
                    <p style="color: #444; line-height: 1.7; font-size: 1rem;">
                        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                    </p>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </main>

    <script src="main.js?v=<?php echo time(); ?>"></script>
</body>
</html>