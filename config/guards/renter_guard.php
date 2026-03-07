<?php
/**
 * Renter Guard - Requires Renter role
 * Include this file at the top of renter pages
 */

require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/../RoleConstants.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: ../public/login.php?error=Please login to access this page');
    exit();
}

// Check if user is Renter
if (!hasRole(Role::RENTER)) {
    header('Location: ../public/index.php?error=Access denied. This page is for renters only.');
    exit();
}
?>
