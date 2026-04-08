<?php
session_start();
require_once 'db_connect.php';

// 1. Security Check: Ensure the user is actually logged in before posting!
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?error=" . urlencode("You must be logged in to share with the community."));
    exit();
}

// 2. Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Grab the logged-in user's ID securely from the session
    $user_id = $_SESSION['user_id'];
    
    // Grab and sanitize the inputs from the form
    $post_type = trim($_POST['post_type']);
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // 3. Basic Validation: Ensure no empty blanks slipped through
    if (empty($title) || empty($content) || empty($post_type)) {
        header("Location: community.php?error=" . urlencode("All fields are required."));
        exit();
    }

    // 4. Secure Database Insertion (Prepared Statement)
    // This inserts the post and links it directly to the user who wrote it
    $stmt = $conn->prepare("INSERT INTO community_posts (user_id, post_type, title, content) VALUES (?, ?, ?, ?)");
    
    // "isss" stands for: Integer (user_id), String (post_type), String (title), String (content)
    $stmt->bind_param("isss", $user_id, $post_type, $title, $content);

    if ($stmt->execute()) {
        // Success! Send them back to the community page with a dynamic success message
        $stmt->close();
        $conn->close();
        header("Location: community.php?success=" . urlencode("Your " . strtolower($post_type) . " was published successfully!"));
        exit();
    } else {
        // Database error catch-all
        $stmt->close();
        $conn->close();
        header("Location: community.php?error=" . urlencode("Something went wrong while saving your post. Please try again."));
        exit();
    }
} else {
    // Kick out anyone trying to type submit_post.php directly into their URL bar
    header("Location: community.php");
    exit();
}
?>