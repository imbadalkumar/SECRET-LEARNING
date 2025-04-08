<?php
session_start();                  // Start session
session_unset();                  // Unset all session variables
session_destroy();                // Destroy the session

// Optional: Delete session cookie from browser
setcookie(session_name(), '', time() - 3600, '/');

// Redirect to login page
header("Location: login.php");
exit();
