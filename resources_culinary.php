<?php
session_start();
require_once 'db_connect.php';
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
        <a href="index.php" class="logo" style="display: flex; align-items: center; text-decoration: none;"><img src="assets/logo.png" alt="FoodFusion Logo"></a>
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

    <!-- Culinary Resources Section -->
    <section class="resource-collection" style="padding: 4rem 20px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; max-width: 1200px; margin: 0 auto 2rem auto; flex-wrap: wrap; gap: 15px;">
            <div style="flex: 1; min-width: 300px;">
                <h2 class="section-title" style="margin-bottom: 10px; text-align: left;">Culinary Resources</h2>
                <p style="color: #666; font-size: 1.1rem; max-width: 700px;">
                    Providing community-driven recipe cards, cooking tutorials, and instructional videos on various cooking techniques and kitchen hacks.
                </p>
            </div>
            <div>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <button onclick="document.getElementById('uploadResourceModal').style.display='flex'" class="btn primary-btn" style="border-radius: 30px; box-shadow: 0 4px 15px rgba(230,57,70,0.3);">+ Add Resource</button>
                <?php else: ?>
                    <button onclick="alert('Please Login or Join Us to upload a resource!')" class="btn outline-btn" style="border-radius: 30px;">+ Add Resource</button>
                <?php endif; ?>
            </div>
        </div>

        <?php if(isset($_GET['success'])): ?>
            <div class="alert success" style="max-width: 1200px; margin: 0 auto 20px auto; padding: 15px; background: #e8f5e9; color: #2e7d32; border-radius: 8px; border-left: 5px solid #2e7d32;"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>
        <?php if(isset($_GET['error'])): ?>
            <div class="alert error" style="max-width: 1200px; margin: 0 auto 20px auto; padding: 15px; background: #ffebee; color: #c62828; border-radius: 8px; border-left: 5px solid #c62828;"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <div class="card-grid">
            <?php
            $resources_sql = "SELECT * FROM resources WHERE category = 'culinary' ORDER BY resource_id DESC";
            $resources_res = $conn->query($resources_sql);

            if ($resources_res && $resources_res->num_rows > 0) {
                while($row = $resources_res->fetch_assoc()) {
                    $type = $row['type'];
                    $title = htmlspecialchars($row['title']);
                    $desc = htmlspecialchars($row['description']);
                    $link = htmlspecialchars($row['file_path_or_url']);
                    
                    if($type === 'pdf') {
                         $badgeClass = 'badge-pdf'; $badgeText = 'PDF Card'; $icon = '📄'; $btnText = 'Download File'; $btnColor = 'var(--primary-color)'; $bgFade = 'rgba(230, 57, 70, 0.1)';
                    } elseif($type === 'video') {
                         $badgeClass = 'badge-video'; $badgeText = 'Video Tutorial'; $icon = '▶️'; $btnText = 'Watch Video'; $btnColor = '#2a9d8f'; $bgFade = 'rgba(42, 157, 143, 0.1)';
                    } else {
                         $badgeClass = ''; $badgeText = 'Article'; $icon = '💡'; $btnText = 'Read Article'; $btnColor = '#f4a261'; $bgFade = 'rgba(244, 162, 97, 0.1)';
                    }
            ?>
                <div class="card resource-card">
                    <div class="resource-icon-wrapper" style="background: <?php echo $bgFade; ?>; color: <?php echo $btnColor; ?>;">
                        <span class="resource-icon"><?php echo $icon; ?></span>
                    </div>
                    <div class="card-content" style="text-align: center;">
                        <span class="badge <?php echo $badgeClass; ?>" style="margin-bottom: 10px; display: inline-block; <?php echo ($type!='pdf' ? "background: $btnColor; color: white;" : ""); ?>"><?php echo $badgeText; ?></span>
                        <p style="font-size: 0.8rem; color: #888; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;"><?php echo $desc; ?></p>
                        <h3 style="margin-bottom: 15px; font-size: 1.3rem;"><?php echo $title; ?></h3>
                        <a href="<?php echo $link; ?>" <?php echo ($type === 'pdf' ? 'download' : 'target="_blank"'); ?> class="btn outline-btn full-width" style="text-align: center; display: inline-block; text-decoration: none; border-color: <?php echo $btnColor; ?>; color: <?php echo $btnColor; ?>; box-sizing: border-box;"><?php echo $btnText; ?></a>
                    </div>
                </div>
            <?php 
                }
            } else {
                echo "<p style='grid-column: 1 / -1; text-align: center; padding: 40px; background: #fff; border-radius: 10px;'>No resources found. Be the first to share one!</p>";
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

    <?php if(isset($_SESSION['user_id'])): ?>
    <div id="uploadResourceModal" class="modal" style="align-items: center; justify-content: center; z-index: 1000;">
        <div class="modal-content" style="max-width: 500px; padding: 40px; border-radius: 15px;">
            <span class="close-btn" onclick="document.getElementById('uploadResourceModal').style.display='none'" style="position:absolute; top:20px; right:25px;">&times;</span>
            <h2 style="font-family: var(--font-heading); color: var(--primary-color); margin-bottom: 5px;">Share a Resource</h2>
            <p style="margin-bottom: 25px; color: #666;">Help the community grow by sharing useful content.</p>
            
            <form action="submit_resource.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="category" value="culinary">
                <input type="hidden" name="return_url" value="resources_culinary.php">
                
                <input type="text" name="title" placeholder="Resource Title (e.g. Knife Skills 101)" required style="width: 100%; margin-bottom: 15px; padding: 12px; border-radius: 8px; border: 1px solid #ccc;">
                
                <input type="text" name="description" placeholder="Category Tag (e.g. Cooking Technique)" required style="width: 100%; margin-bottom: 15px; padding: 12px; border-radius: 8px; border: 1px solid #ccc;">
                
                <select name="type" id="resourceTypeSelect_cul" onchange="toggleResourceInput('cul')" required style="width: 100%; margin-bottom: 15px; padding: 12px; border-radius: 8px; border: 1px solid #ccc; background: white;">
                    <option value="" disabled selected>Select Resource Type</option>
                    <option value="video">Video Tutorial (YouTube/Vimeo)</option>
                    <option value="article">Web Article / Blog</option>
                    <option value="pdf">PDF Document Upload</option>
                </select>

                <div id="urlInputContainer_cul" style="display: none; margin-bottom: 15px;">
                    <input type="url" name="resource_url" id="resourceUrl_cul" placeholder="https://example.com/..." style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ccc;">
                </div>

                <div id="fileInputContainer_cul" style="display: none; margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; color: #555; font-weight: 500;">Select PDF File:</label>
                    <input type="file" name="resource_file" id="resourceFile_cul" accept="application/pdf" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc; background: #f9f9f9;">
                </div>

                <button type="submit" class="btn primary-btn full-width" style="border-radius: 30px; font-weight: 600; padding: 14px; margin-top: 5px;">Upload Resource</button>
            </form>
        </div>
    </div>
    <script>
        function toggleResourceInput(uid) {
            const type = document.getElementById('resourceTypeSelect_' + uid).value;
            const urlContainer = document.getElementById('urlInputContainer_' + uid);
            const urlInput = document.getElementById('resourceUrl_' + uid);
            const fileContainer = document.getElementById('fileInputContainer_' + uid);
            const fileInput = document.getElementById('resourceFile_' + uid);
            
            if (type === 'pdf') {
                urlContainer.style.display = 'none'; urlInput.required = false; urlInput.value = '';
                fileContainer.style.display = 'block'; fileInput.required = true;
            } else {
                fileContainer.style.display = 'none'; fileInput.required = false; fileInput.value = '';
                urlContainer.style.display = 'block'; urlInput.required = true;
            }
        }
    </script>
    <?php endif; ?>

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
