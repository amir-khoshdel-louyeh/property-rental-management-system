<?php
require_once '../config/session.php';

// Start session
startSession();

// Destroy session
destroyUserSession();

// Redirect to login page
header('Location: login.php?success=You have been logged out successfully');
exit();
?>
