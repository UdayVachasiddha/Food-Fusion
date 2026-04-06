<?php
// register_event.php
require_once 'db_connect.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_name = $_POST['event_name'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';

    if (empty($event_name) || empty($username) || empty($email)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    // Create table if it doesn't exist
    $create_table_query = "CREATE TABLE IF NOT EXISTS event_registrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        event_name VARCHAR(255) NOT NULL,
        username VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->query($create_table_query);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO event_registrations (event_name, username, email) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sss", $event_name, $username, $email);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Successfully registered!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
    }
}
?>
