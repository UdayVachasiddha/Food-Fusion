<?php
// Set the header to tell the browser we are sending JSON data back
header('Content-Type: application/json');
session_start();
require_once 'db_connect.php';

// 1. Check if user is actually logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized. Please log in.']);
    exit();
}

// 2. Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $title = trim($_POST['title']);
    $image_url = trim($_POST['image_url'] ?? '');
    $description = trim($_POST['description']);
    $cuisine = trim($_POST['cuisine_type']);
    $diet = trim($_POST['dietary_preference']);
    $difficulty = $_POST['difficulty_level'];
    $instructions = trim($_POST['instructions']);

    // Basic Validation
    if (empty($title) || empty($description) || empty($instructions) || empty($difficulty)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
        exit();
    }

    // 3. Ensure image_url column exists (fails silently if it already exists)
    $conn->query("ALTER TABLE recipes ADD COLUMN image_url VARCHAR(255) DEFAULT NULL");

    // 4. Insert into database using Prepared Statements
    $stmt = $conn->prepare("INSERT INTO recipes (user_id, title, image_url, description, cuisine_type, dietary_preference, difficulty_level, instructions) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
        exit();
    }

    $stmt->bind_param("isssssss", $user_id, $title, $image_url, $description, $cuisine, $diet, $difficulty, $instructions);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Recipe published successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>