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
        <a href="index.php" class="logo" style="display: flex; align-items: center; text-decoration: none;"><img src="assets/logo.png" alt="FoodFusion Logo"></a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="recipes.php">Recipes</a></li>
            <li><a href="community.php">Community</a></li>
            <li><a href="resources.php" style="color: var(--primary-color);">Resources</a></li>
        </ul>
        <div style="display: flex; align-items: center; gap: 10px;">
            <button id="theme-toggle" class="theme-switch" aria-label="Toggle Dark Mode">
                <svg class="sun-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                <svg class="moon-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
            </button>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="btn outline-btn">Logout</a>
            <?php else: ?>
                <button id="openModalBtn" class="btn primary-btn">Join Us</button>
            <?php endif; ?>
        </div>
    </nav>

    <header class="hero resource-hero"
        style="background-image: linear-gradient(rgba(38,70,83,0.7), rgba(38,70,83,0.7)), url('https://images.unsplash.com/photo-1466611653911-95081537e5b7?auto=format&fit=crop&w=1950&q=80');">
        <div class="hero-content">
            <h1>Educational Resources</h1>
            <p>Learn more about renewable energy, sustainability, and how we can power a greener future together.</p>
        </div>
    </header>

    <!-- Add Resource Button -->
    <div style="max-width:1200px; margin: 2rem auto 0; padding: 0 20px; display:flex; justify-content:flex-end;">
        <?php if(isset($_SESSION['user_id'])): ?>
            <button onclick="document.getElementById('uploadResourceModal_edu').style.display='flex'" class="btn primary-btn" style="border-radius:30px; background:#264653; border-color:#264653; box-shadow:0 4px 15px rgba(38,70,83,0.3);">+ Add Resource</button>
        <?php else: ?>
            <button onclick="alert('Please Login or Join Us to upload a resource!')" class="btn outline-btn" style="border-radius:30px; color:#264653; border-color:#264653;">+ Add Resource</button>
        <?php endif; ?>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <div style="max-width:1200px; margin:1rem auto 0; padding:0 20px;"><div style="padding:15px; background:#e8f5e9; color:#2e7d32; border-radius:8px; border-left:5px solid #2e7d32;"><?php echo htmlspecialchars($_GET['success']); ?></div></div>
    <?php endif; ?>
    <?php if(isset($_GET['error'])): ?>
        <div style="max-width:1200px; margin:1rem auto 0; padding:0 20px;"><div style="padding:15px; background:#ffebee; color:#c62828; border-radius:8px; border-left:5px solid #c62828;"><?php echo htmlspecialchars($_GET['error']); ?></div></div>
    <?php endif; ?>

    <!-- ====================================================== -->
    <!--  SECTION 1: PDF GUIDES                                  -->
    <!-- ====================================================== -->
    <section style="padding:4rem 20px 3rem; background:var(--bg-color);">
        <div style="max-width:1200px; margin:0 auto 2.5rem;">
            <p style="color:#2a9d8f; font-weight:600; text-transform:uppercase; letter-spacing:2px; font-size:0.8rem; margin-bottom:6px;">EXPERT LIBRARY</p>
            <h2 style="font-family:var(--font-heading); font-size:2rem; color:var(--text-primary); margin-bottom:10px;">Downloadable Educational Guides</h2>
            <p style="color:var(--text-secondary); font-size:1rem; max-width:650px;">Downloadable guides on nutrition, global food safety, and sustainability practices.</p>
        </div>
        <?php
        $pdf_gradients = [
            'linear-gradient(135deg, #264653 0%, #2a9d8f 100%)',
            'linear-gradient(135deg, #2a9d8f 0%, #1b7268 100%)',
            'linear-gradient(135deg, #457b9d 0%, #1d3557 100%)',
            'linear-gradient(135deg, #6d6875 0%, #b5838d 100%)',
            'linear-gradient(135deg, #3d405b 0%, #81b29a 100%)',
            'linear-gradient(135deg, #e9c46a 0%, #f4a261 100%)',
        ];
        $pdf_res = $conn->query("SELECT * FROM resources WHERE category = 'educational' AND type = 'pdf' ORDER BY resource_id DESC");
        if ($pdf_res && $pdf_res->num_rows > 0):
            $pdfIdx = 0;
        ?>
        <div style="max-width:1200px; margin:0 auto; display:grid; grid-template-columns:repeat(auto-fill, minmax(240px, 1fr)); gap:28px;">
        <?php while($row = $pdf_res->fetch_assoc()):
            $grad     = $pdf_gradients[$pdfIdx % count($pdf_gradients)]; $pdfIdx++;
            $t_js     = addslashes($row['title']);
            $t        = htmlspecialchars($row['title']);
            $short    = htmlspecialchars($row['short_desc'] ?? $row['description'] ?? '');
            $link_raw = $row['file_path_or_url'];       // raw — for JS onclick
            $link     = htmlspecialchars($link_raw);    // escaped — for HTML attrs
            $hasThumb = !empty($row['thumbnail_url']);
        ?>
        <div class="card" style="padding:0; overflow:hidden; border-radius:14px; display:flex; flex-direction:column; cursor:pointer;"
             onclick="openResourceViewer('pdf','<?php echo addslashes($link_raw); ?>','<?php echo $t_js; ?>')">
            <?php if($hasThumb): ?>
            <div style="height:170px; overflow:hidden;">
                <img src="<?php echo htmlspecialchars($row['thumbnail_url']); ?>" alt="<?php echo $t; ?>" style="width:100%;height:100%;object-fit:cover;transition:transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
            </div>
            <?php else: ?>
            <div style="height:170px; background:<?php echo $grad; ?>; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:10px; position:relative; overflow:hidden;">
                <svg width="46" height="46" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.9)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                <span style="color:rgba(255,255,255,0.8);font-size:0.7rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;">PDF GUIDE</span>
                <div style="position:absolute;bottom:-40px;right:-40px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,0.08);"></div>
                <div style="position:absolute;top:-30px;left:-30px;width:90px;height:90px;border-radius:50%;background:rgba(255,255,255,0.06);"></div>
            </div>
            <?php endif; ?>
            <div style="padding:18px 20px 20px; flex:1; display:flex; flex-direction:column;">
                <h3 style="font-size:1.05rem; font-family:var(--font-heading); color:var(--text-primary); margin-bottom:8px; line-height:1.4;"><?php echo $t; ?></h3>
                <?php if(!empty($short)): ?><p style="font-size:0.87rem; color:var(--text-secondary); line-height:1.5; margin-bottom:15px; flex:1;"><?php echo $short; ?></p><?php endif; ?>
                <span style="display:inline-flex; align-items:center; gap:6px; color:#2a9d8f; font-size:0.85rem; font-weight:600; margin-top:auto;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="8 17 12 21 16 17"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29"/></svg>
                    Open / Download
                </span>
            </div>
        </div>
        <?php endwhile; ?>
        </div>
        <?php else: ?>
        <p style="text-align:center; color:var(--text-secondary); padding:40px; max-width:1200px; margin:0 auto;">No PDF guides yet. Add the first one!</p>
        <?php endif; ?>
    </section>

    <!-- ====================================================== -->
    <!--  SECTION 2: VIDEO SERIES                                -->
    <!-- ====================================================== -->
    <section style="padding:3rem 20px 5rem; background:var(--bg-base);">
        <div style="max-width:1200px; margin:0 auto 2.5rem;">
            <p style="color:#264653; font-weight:600; text-transform:uppercase; letter-spacing:2px; font-size:0.8rem; margin-bottom:6px;">WATCH &amp; LEARN</p>
            <h2 style="font-family:var(--font-heading); font-size:2rem; color:var(--text-primary); margin-bottom:10px;">Educational Video Series</h2>
            <p style="color:var(--text-secondary); font-size:1rem; max-width:650px;">In-depth documentary series and explainers on global food systems, safety, and sustainability.</p>
        </div>
        <?php
        $vid_res = $conn->query("SELECT * FROM resources WHERE category = 'educational' AND type = 'video' ORDER BY resource_id DESC");
        if ($vid_res && $vid_res->num_rows > 0):
        ?>
        <div style="max-width:1200px; margin:0 auto; display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:28px;">
        <?php while($row = $vid_res->fetch_assoc()):
            $t_js     = addslashes($row['title']);
            $t        = htmlspecialchars($row['title']);
            $short    = htmlspecialchars($row['short_desc'] ?? $row['description'] ?? '');
            $link_raw = $row['file_path_or_url'];       // raw — for JS onclick & regex
            $link     = htmlspecialchars($link_raw);    // escaped — for HTML attrs
            $thumb    = !empty($row['thumbnail_url']) ? $row['thumbnail_url'] : '';
            // Extract YouTube thumbnail from the RAW url (not html-encoded)
            if (empty($thumb) && preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/', $link_raw, $m)) {
                $thumb = 'https://img.youtube.com/vi/' . $m[1] . '/hqdefault.jpg';
            }
        ?>
        <div class="card" style="padding:0; overflow:hidden; border-radius:14px; display:flex; flex-direction:column;">
            <div style="position:relative; height:185px; overflow:hidden; cursor:pointer; background:#111;"
                 onclick="openResourceViewer('video','<?php echo addslashes($link_raw); ?>','<?php echo $t_js; ?>')">
                <?php if(!empty($thumb)): ?>
                <img src="<?php echo htmlspecialchars($thumb); ?>" alt="<?php echo $t; ?>" style="width:100%;height:100%;object-fit:cover;transition:transform 0.3s;opacity:0.92;" onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'">
                <?php else: ?>
                <div style="width:100%;height:100%;background:linear-gradient(135deg,#264653,#2a9d8f);display:flex;align-items:center;justify-content:center;">
                    <svg width="50" height="50" viewBox="0 0 24 24" fill="rgba(255,255,255,0.4)"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                </div>
                <?php endif; ?>
                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,0.2);">
                    <div style="width:54px;height:54px;background:rgba(255,255,255,0.92);border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 20px rgba(0,0,0,0.35);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="#264653"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                    </div>
                </div>
            </div>
            <div style="padding:18px 20px 20px; flex:1; display:flex; flex-direction:column;">
                <h3 style="font-size:1.05rem; font-family:var(--font-heading); color:var(--text-primary); margin-bottom:8px; line-height:1.4;"><?php echo $t; ?></h3>
                <?php if(!empty($short)): ?><p style="font-size:0.87rem; color:var(--text-secondary); line-height:1.5; margin-bottom:15px; flex:1;"><?php echo $short; ?></p><?php endif; ?>
                <div style="display:flex; align-items:center; gap:15px; margin-top:auto;">
                    <button onclick="openResourceViewer('video','<?php echo addslashes($link_raw); ?>','<?php echo $t_js; ?>')"
                            style="background:none;border:none;padding:0;cursor:pointer;display:inline-flex;align-items:center;gap:6px;color:#264653;font-size:0.85rem;font-weight:600;font-family:var(--font-body);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                        Watch Now
                    </button>
                    <a href="<?php echo $link; ?>" download="<?php echo $t; ?>" style="text-decoration:none; display:inline-flex; align-items:center; gap:6px; color:#e63946; font-size:0.85rem; font-weight:600; font-family:var(--font-body);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        Download
                    </a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
        </div>
        <?php else: ?>
        <p style="text-align:center; color:var(--text-secondary); padding:40px; max-width:1200px; margin:0 auto;">No video series yet. Add the first one!</p>
        <?php endif; ?>
    </section>

    <!-- ===== In-Page Resource Viewer Modal ===== -->
    <div id="resourceViewerModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:9999; align-items:center; justify-content:center; padding:20px;">
        <div style="background:var(--bg-surface); border-radius:16px; width:100%; max-width:900px; max-height:90vh; display:flex; flex-direction:column; overflow:hidden; box-shadow:0 25px 60px rgba(0,0,0,0.5);">
            <div style="display:flex; align-items:center; justify-content:space-between; padding:18px 25px; border-bottom:1px solid var(--border-subtle);">
                <h3 id="viewerTitle" style="font-family:var(--font-heading); color:var(--text-primary); margin:0; font-size:1.2rem;"></h3>
                <button onclick="closeResourceViewer()" style="background:none;border:none;cursor:pointer;color:var(--text-secondary);font-size:1.8rem;line-height:1;padding:0;">&times;</button>
            </div>
            <div id="viewerContent" style="flex:1; overflow:auto;">
                <iframe id="resourceViewerFrame" src="" style="width:100%;height:75vh;border:none;display:none;"></iframe>
                <video id="resourceVideoPlayer" controls style="width:100%;max-height:75vh;display:none;background:#000;"></video>
            </div>
        </div>
    </div>
    <script>
        function openResourceViewer(type, link, title) {
            document.getElementById('viewerTitle').textContent = title;
            const iframe = document.getElementById('resourceViewerFrame');
            const video = document.getElementById('resourceVideoPlayer');
            
            iframe.style.display = 'none';
            iframe.src = '';
            video.style.display = 'none';
            video.src = '';

            if (type === 'video') {
                const isYouTube = link.includes('youtube.com') || link.includes('youtu.be');
                if (isYouTube) {
                    let url = link;
                    try {
                        const u = new URL(link);
                        let id = u.searchParams.get('v');
                        if (!id && u.hostname === 'youtu.be') id = u.pathname.slice(1);
                        if (id) url = 'https://www.youtube.com/embed/' + id + '?autoplay=1';
                    } catch(e) {}
                    iframe.src = url;
                    iframe.style.display = 'block';
                } else {
                    video.src = link;
                    video.style.display = 'block';
                    video.play();
                }
            } else {
                iframe.src = link;
                iframe.style.display = 'block';
            }
            
            document.getElementById('resourceViewerModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        function closeResourceViewer() {
            document.getElementById('resourceViewerFrame').src = '';
            document.getElementById('resourceVideoPlayer').pause();
            document.getElementById('resourceVideoPlayer').src = '';
            document.getElementById('resourceViewerModal').style.display = 'none';
            document.body.style.overflow = '';
        }
        document.getElementById('resourceViewerModal').addEventListener('click', function(e) { if(e.target===this) closeResourceViewer(); });
    </script>

    <footer class="site-footer">
        <div class="footer-content" style="align-items: center;">
            <div class="footer-logo" style="display: flex; align-items: center; margin-bottom: 20px;"><img src="assets/logo.png" alt="FoodFusion Logo" style="height: 65px; width: auto; filter: drop-shadow(0px 2px 4px rgba(0,0,0,0.2));"></div>
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
    <div id="uploadResourceModal_edu" class="modal" style="align-items: center; justify-content: center; z-index: 1000;">
        <div class="modal-content" style="max-width: 520px; padding: 40px; border-radius: 15px;">
            <span class="close-btn" onclick="document.getElementById('uploadResourceModal_edu').style.display='none'" style="position:absolute; top:20px; right:25px;">&times;</span>
            <h2 style="font-family: var(--font-heading); color: #264653; margin-bottom: 5px;">Share a Resource</h2>
            <p style="margin-bottom: 25px; color: #666;">Help the community grow by sharing educational content.</p>
            <form action="submit_resource.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="category" value="educational">
                <input type="hidden" name="return_url" value="resources_educational.php">
                <input type="text" name="title" placeholder="Resource Title (e.g. Food Safety Guide 2026)" required style="width:100%; margin-bottom:12px; padding:12px; border-radius:8px; border:1px solid #ccc; box-sizing:border-box;">
                <textarea name="short_desc" placeholder="Short description shown on the card (1–2 sentences)" rows="2" style="width:100%; margin-bottom:12px; padding:12px; border-radius:8px; border:1px solid #ccc; box-sizing:border-box; resize:vertical; font-family:inherit;"></textarea>
                <input type="hidden" name="description" value="Resource">
                <select name="type" id="resourceTypeSelect_edu" onchange="toggleEduInput()" required style="width:100%; margin-bottom:12px; padding:12px; border-radius:8px; border:1px solid #ccc; background:white; box-sizing:border-box;">
                    <option value="" disabled selected>Select Resource Type</option>
                    <option value="video">🎬 Video (YouTube link or File upload)</option>
                    <option value="pdf">📄 PDF Document Upload</option>
                </select>
                <div id="eduVideoContainer" style="display:none; margin-bottom:12px;">
                    <input type="url" name="resource_url" id="eduResourceUrl" placeholder="YouTube URL (optional if uploading file)" style="width:100%; padding:12px; border-radius:8px; border:1px solid #ccc; box-sizing:border-box; margin-bottom:10px;">
                    <label style="display:block; margin-bottom:6px; color:#555; font-weight:500;">Or Upload Video File:</label>
                    <input type="file" name="video_file" id="eduResourceFileVideo" accept="video/mp4,video/webm,video/ogg,video/quicktime" style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc; background:#f9f9f9; box-sizing:border-box; margin-bottom:10px;">
                    <input type="url" name="thumbnail_url" id="eduThumbUrl" placeholder="Custom Thumbnail URL (optional)" style="width:100%; padding:12px; border-radius:8px; border:1px solid #ccc; box-sizing:border-box;">
                </div>
                <div id="eduPdfContainer" style="display:none; margin-bottom:12px;">
                    <label style="display:block; margin-bottom:6px; color:#555; font-weight:500;">Select PDF File:</label>
                    <input type="file" name="pdf_file" id="eduResourceFile" accept="application/pdf" style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc; background:#f9f9f9; box-sizing:border-box;">
                </div>
                <button type="submit" class="btn" style="border-radius:30px; font-weight:600; padding:14px; margin-top:5px; width:100%; background:#264653; color:white; border:none; cursor:pointer;">Upload Resource</button>
            </form>
        </div>
    </div>
    <script>
        function toggleEduInput() {
            const type = document.getElementById('resourceTypeSelect_edu').value;
            const vC = document.getElementById('eduVideoContainer');
            const pC = document.getElementById('eduPdfContainer');
            const vUrl = document.getElementById('eduResourceUrl');
            const pFileV = document.getElementById('eduResourceFileVideo');
            const pFileP = document.getElementById('eduResourceFile');
            
            if (type === 'video') {
                vC.style.display = 'block';
                pC.style.display = 'none';
                pFileP.required = false; pFileP.value = '';
                // Logic: either URL or File should be provided
            } else {
                pC.style.display = 'block';
                pFileP.required = true;
                vC.style.display = 'none';
                vUrl.value = ''; pFileV.value = '';
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