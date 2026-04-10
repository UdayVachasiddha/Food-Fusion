<?php
session_start();
require_once 'db_connect.php';

// Safe column migration: check INFORMATION_SCHEMA before altering (compatible with older MySQL/MariaDB)
$db_name = $conn->query("SELECT DATABASE()")->fetch_row()[0];

$check_thumb = $conn->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$db_name' AND TABLE_NAME='resources' AND COLUMN_NAME='thumbnail_url'")->fetch_row()[0];
if ($check_thumb == 0) {
    $conn->query("ALTER TABLE resources ADD COLUMN thumbnail_url VARCHAR(500) DEFAULT NULL");
}

$check_short = $conn->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$db_name' AND TABLE_NAME='resources' AND COLUMN_NAME='short_desc'")->fetch_row()[0];
if ($check_short == 0) {
    $conn->query("ALTER TABLE resources ADD COLUMN short_desc VARCHAR(300) DEFAULT NULL");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Enforce Login
    if (!isset($_SESSION['user_id'])) {
        $r = $_POST['return_url'] ?? 'resources.php';
        header("Location: " . $r . "?error=" . urlencode("Must be logged in to upload resources."));
        exit;
    }

    $title       = $conn->real_escape_string(trim($_POST['title']));
    $category    = $conn->real_escape_string($_POST['category']);
    $type        = $conn->real_escape_string($_POST['type']); // only 'pdf' or 'video'
    $description = $conn->real_escape_string(trim($_POST['description'] ?? ''));
    $short_desc  = $conn->real_escape_string(trim($_POST['short_desc'] ?? ''));
    $return_url  = $_POST['return_url'] ?? 'resources.php';
    $user_id     = $_SESSION['user_id'];

    $file_path_or_url = "";
    $thumbnail_url    = "";

    if ($type === 'pdf') {
        // Handle PDF file upload
        if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
            $upload_dir = 'downloads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

            $file_name   = preg_replace("/[^a-zA-Z0-9.-]/", "_", basename($_FILES["pdf_file"]["name"]));
            $target_file = $upload_dir . time() . "_" . $file_name;

            if (move_uploaded_file($_FILES["pdf_file"]["tmp_name"], $target_file)) {
                $file_path_or_url = $target_file;
            } else {
                header("Location: $return_url?error=" . urlencode("Server failed to save the uploaded PDF file."));
                exit;
            }
        } else {
            header("Location: $return_url?error=" . urlencode("A valid PDF file is required."));
            exit;
        }

        // PDF thumbnail is pre-defined (set by user or left empty for auto-gradient)
        $thumbnail_url = $conn->real_escape_string(trim($_POST['thumbnail_url'] ?? ''));

    } elseif ($type === 'video') {
        // Handle Video (File Upload or URL)
        if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] == 0) {
            $upload_dir = 'downloads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

            $file_name   = preg_replace("/[^a-zA-Z0-9.-]/", "_", basename($_FILES["video_file"]["name"]));
            $target_file = $upload_dir . time() . "_" . $file_name;

            // Simple extension check
            $ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed = ['mp4', 'webm', 'ogg', 'mov'];
            
            if (in_array($ext, $allowed)) {
                if (move_uploaded_file($_FILES["video_file"]["tmp_name"], $target_file)) {
                    $file_path_or_url = $target_file;
                } else {
                    header("Location: $return_url?error=" . urlencode("Server failed to save the uploaded video file."));
                    exit;
                }
            } else {
                header("Location: $return_url?error=" . urlencode("Invalid video file format. Allowed: .mp4, .webm, .ogg, .mov"));
                exit;
            }
        } else {
            // Fallback to URL
            $url = filter_var($_POST['resource_url'], FILTER_SANITIZE_URL);
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                $file_path_or_url = $conn->real_escape_string($url);
            } else {
                header("Location: $return_url?error=" . urlencode("A valid video URL or file is required."));
                exit;
            }
        }

        // Video thumbnail: user-provided URL, or auto-extract from YouTube if blank
        $thumb_input = trim($_POST['thumbnail_url'] ?? '');
        if (!empty($thumb_input) && filter_var($thumb_input, FILTER_VALIDATE_URL)) {
            $thumbnail_url = $conn->real_escape_string($thumb_input);
        } else {
            // Auto-extract YouTube thumbnail
            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/', $file_path_or_url, $m)) {
                $thumbnail_url = 'https://img.youtube.com/vi/' . $m[1] . '/hqdefault.jpg';
            }
        }

    } else {
        header("Location: $return_url?error=" . urlencode("Invalid resource type."));
        exit;
    }

    $sql  = "INSERT INTO resources (category, title, type, description, short_desc, file_path_or_url, thumbnail_url, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $category, $title, $type, $description, $short_desc, $file_path_or_url, $thumbnail_url, $user_id);

    if ($stmt->execute()) {
        header("Location: $return_url?success=" . urlencode("Your resource has been shared with the community!"));
    } else {
        header("Location: $return_url?error=" . urlencode("Database error: " . $conn->error));
    }
    $stmt->close();
}
?>
