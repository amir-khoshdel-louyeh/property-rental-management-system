<?php
/**
 * Login Guard - Requires user to be logged in
 * Include this file at the top of pages that require login
 */

require_once __DIR__ . '/../session.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: ../public/login.php?error=Please login to access this page');
    exit();
}
?>
