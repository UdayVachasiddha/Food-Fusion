<?php
$host = "localhost"; 
$username = "root";      
$password = "Uday@2006";  
$dbname = "foodfusion";  

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// --- SELF-EXECUTING MIGRATION (Resources table) ---
$conn->query("CREATE TABLE IF NOT EXISTS resources (
    resource_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    category ENUM('culinary', 'educational') NOT NULL,
    title VARCHAR(255) NOT NULL,
    type ENUM('pdf', 'video', 'article') NOT NULL,
    description TEXT,
    file_path_or_url VARCHAR(255) NOT NULL,
    user_id INT(11) DEFAULT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// --- SELF-HEALING SCHEMA SYNC ---
$colCheck = $conn->query("SHOW COLUMNS FROM resources LIKE 'file_path_or_url'");
if ($colCheck && $colCheck->num_rows == 0) {
    // The table exists but came from an outdated/incompatible schema. Flush and rebuild.
    $conn->query("DROP TABLE IF EXISTS resources");
    
    $conn->query("CREATE TABLE resources (
        resource_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        category ENUM('culinary', 'educational') NOT NULL,
        title VARCHAR(255) NOT NULL,
        type ENUM('pdf', 'video', 'article') NOT NULL,
        description TEXT,
        file_path_or_url VARCHAR(255) NOT NULL,
        user_id INT(11) DEFAULT NULL,
        uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $conn->query("INSERT INTO resources (category, title, type, description, file_path_or_url) VALUES 
        ('culinary', 'Mastering the Mother Sauces', 'pdf', 'Recipe Collection', 'downloads/mother_sauces.pdf'),
        ('culinary', 'Knife Skills: The Basics', 'video', 'Cooking Technique', 'https://www.youtube.com/watch?v=0kH2PXX3M-I'),
        ('culinary', 'How to Peel Garlic in Seconds', 'article', 'Prep Tips', 'https://www.bonappetit.com/story/how-to-peel-garlic'),
        ('educational', 'Food Safety Guidelines 2026', 'pdf', 'Health & Safety', 'downloads/food_safety.pdf'),
        ('educational', 'Understanding Macronutrients', 'video', 'Nutrition Basics', 'https://www.youtube.com/watch?v=wX1D2iX2Ehw'),
        ('educational', 'A Guide to Global Spices', 'article', 'Culinary History', 'https://www.seriouseats.com/spice-guide')
    ");
}

$check = $conn->query("SELECT COUNT(*) AS total FROM resources");
if ($check) {
    $row = $check->fetch_assoc();
    if ($row['total'] == 0) {
        $conn->query("INSERT INTO resources (category, title, type, description, file_path_or_url) VALUES 
            ('culinary', 'Mastering the Mother Sauces', 'pdf', 'Recipe Collection', 'downloads/mother_sauces.pdf'),
            ('culinary', 'Knife Skills: The Basics', 'video', 'Cooking Technique', 'https://www.youtube.com/watch?v=0kH2PXX3M-I'),
            ('culinary', 'How to Peel Garlic in Seconds', 'article', 'Prep Tips', 'https://www.bonappetit.com/story/how-to-peel-garlic'),
            ('educational', 'Food Safety Guidelines 2026', 'pdf', 'Health & Safety', 'downloads/food_safety.pdf'),
            ('educational', 'Understanding Macronutrients', 'video', 'Nutrition Basics', 'https://www.youtube.com/watch?v=wX1D2iX2Ehw'),
            ('educational', 'A Guide to Global Spices', 'article', 'Culinary History', 'https://www.seriouseats.com/spice-guide')
        ");
    }
}
?>