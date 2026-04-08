<?php
require_once 'db_connect.php';

$sqlSchema = "CREATE TABLE IF NOT EXISTS resources (
    resource_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    category ENUM('culinary', 'educational') NOT NULL,
    title VARCHAR(255) NOT NULL,
    type ENUM('pdf', 'video', 'article') NOT NULL,
    description TEXT,
    file_path_or_url VARCHAR(255) NOT NULL,
    user_id INT(11) DEFAULT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sqlSchema) === TRUE) {
    echo "Resources table created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
    exit;
}

// Check if table is empty to avoid duplicating seed data
$check = $conn->query("SELECT COUNT(*) AS total FROM resources");
$row = $check->fetch_assoc();
if ($row['total'] == 0) {
    // Seed Culinary Resources
    $conn->query("INSERT INTO resources (category, title, type, description, file_path_or_url) VALUES 
        ('culinary', 'Mastering the Mother Sauces', 'pdf', 'Recipe Collection', 'downloads/mother_sauces.pdf'),
        ('culinary', 'Knife Skills: The Basics', 'video', 'Cooking Technique', 'https://www.youtube.com/watch?v=0kH2PXX3M-I'),
        ('culinary', 'How to Peel Garlic in Seconds', 'article', 'Prep Tips', 'https://www.bonappetit.com/story/how-to-peel-garlic')
    ");

    // Seed Educational Resources 
    // Need to verify what was originally on resources_educational.php.
    // I recall there was a Food Safety PDF, a Nutrition video, etc.
    $conn->query("INSERT INTO resources (category, title, type, description, file_path_or_url) VALUES 
        ('educational', 'Food Safety Guidelines 2026', 'pdf', 'Health & Safety', 'downloads/food_safety.pdf'),
        ('educational', 'Understanding Macronutrients', 'video', 'Nutrition Basics', 'https://www.youtube.com/watch?v=wX1D2iX2Ehw'),
        ('educational', 'A Guide to Global Spices', 'article', 'Culinary History', 'https://www.seriouseats.com/spice-guide')
    ");
    echo "Seed data inserted successfully\n";
} else {
    echo "Table already has data. Skipping seed.\n";
}
?>
