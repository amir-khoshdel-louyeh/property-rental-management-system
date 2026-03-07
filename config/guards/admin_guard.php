<?php
/**
 * Admin Guard - Requires Admin role
 * Include this file at the top of admin pages
 */

require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/../RoleConstants.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: ../public/login.php?error=Please login to access this page');
    exit();
}

// Check if user is Admin
if (!isAdmin()) {
    header('Location: ../public/index.php?error=Access denied. This page is for administrators only.');
    exit();
}
?>
