<?php
session_start(); // Start the session

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page
header('Location: login.php'); // Change 'login.php' to your actual login page
exit; // Always exit after a header redirect
?>
