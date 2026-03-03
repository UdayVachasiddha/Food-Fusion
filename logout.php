<?php
// 1. Resume the current session
session_start();

// 2. Unset all of the session variables to clear the data
$_SESSION = array();

// 3. Destroy the session completely on the server
session_destroy();

// 4. Redirect the user back to the homepage
header("Location: index.php");
exit();
?>