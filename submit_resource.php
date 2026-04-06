<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Enforce Login
    if (!isset($_SESSION['user_id'])) {
        $r = $_POST['return_url'] ?? 'resources.php';
        header("Location: " . $r . "?error=" . urlencode("Must be logged in to upload resources."));
        exit;
    }

    $title = $conn->real_escape_string($_POST['title']);
    $category = $conn->real_escape_string($_POST['category']);
    $type = $conn->real_escape_string($_POST['type']);
    $description = $conn->real_escape_string($_POST['description']);
    $return_url = $_POST['return_url'] ?? 'resources.php';
    $user_id = $_SESSION['user_id'];

    $file_path_or_url = "";

    // Process PDF Physical Upload
    if ($type === 'pdf') {
        if (isset($_FILES['resource_file']) && $_FILES['resource_file']['error'] == 0) {
            $upload_dir = 'downloads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            
            $file_name = preg_replace("/[^a-zA-Z0-9.-]/", "_", basename($_FILES["resource_file"]["name"]));
            $target_file = $upload_dir . time() . "_" . $file_name;

            if (move_uploaded_file($_FILES["resource_file"]["tmp_name"], $target_file)) {
                $file_path_or_url = $target_file;
            } else {
                header("Location: $return_url?error=" . urlencode("Server failed to save the uploaded PDF file."));
                exit;
            }
        } else {
            header("Location: $return_url?error=" . urlencode("A valid PDF file is strictly required."));
            exit;
        }
    } else {
        // Process YouTube/Article URLs
        $url = filter_var($_POST['resource_url'], FILTER_SANITIZE_URL);
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $file_path_or_url = $conn->real_escape_string($url);
        } else {
            header("Location: $return_url?error=" . urlencode("A valid HTTP/HTTPS URL is required."));
            exit;
        }
    }

    $sql = "INSERT INTO resources (category, title, type, description, file_path_or_url, user_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $category, $title, $type, $description, $file_path_or_url, $user_id);
    
    if ($stmt->execute()) {
        header("Location: $return_url?success=" . urlencode("Your resource has been shared successfully with the community!"));
    } else {
        header("Location: $return_url?error=" . urlencode("Database insertion error: " . $conn->error));
    }
    $stmt->close();
}
?>
