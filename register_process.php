<?php
// Start session to pass success/error messages back to the user
session_start();

// Bring in our database connection
require_once 'db_connect.php';

// Check if the form was actually submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Grab and sanitize the inputs
    // trim() removes accidental extra spaces from the beginning or end
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 2. Basic Validation: Ensure nothing is empty
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: index.php"); // Kick them back to the homepage
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: index.php");
        exit();
    }

    // 3. Check if the email already exists in our database
    $stmt_check = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result(); // Store the result so we can count the rows

    if ($stmt_check->num_rows > 0) {
        // ERROR: The email is already taken! 
        // Send them back to the homepage and attach the error to the URL.
        $stmt_check->close();
        header("Location: index.php?error=An account with this email already exists. Please log in.");
        exit();
    }
    $stmt_check->close();

    // 4. Hash the password
    // NEVER store plain-text passwords. This creates a secure, salted hash.
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // 5. Insert the new user into the database
    $insert_stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password_hash) VALUES (?, ?, ?, ?)");
    $insert_stmt->bind_param("ssss", $first_name, $last_name, $email, $password_hash);

    if ($insert_stmt->execute()) {
        // Success! Set a message and redirect to the login page
        $_SESSION['success'] = "Registration successful! Welcome to FoodFusion, please log in.";
        header("Location: login.php");
        exit();
    } else {
        // Something went wrong with the database insertion
        $_SESSION['error'] = "Oops! Something went wrong. Please try again.";
        header("Location: index.php");
        exit();
    }

    // Example of a failed login in your PHP file
    if ($password_is_wrong) {
    // This sends the user back to the homepage and attaches the error to the URL!
    header("Location: index.php?error=Invalid email or password. Please try again.");
    exit();
    }

    // Clean up
    $insert_stmt->close();
    $conn->close();

} else {
    // If someone tries to type 'register_process.php' directly into their URL bar
    header("Location: index.php");
    exit();
}
?>