<?php
// Example of protecting a page with role-based access
// Copy and modify this template for pages that need role protection

require_once '../config/session.php';
require_once '../config/guards/admin_guard.php';
// For other roles, use:
// require_once '../config/guards/landlord_guard.php';
// require_once '../config/guards/renter_guard.php';
// require_once '../config/guards/login_guard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page Example</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
</head>
<body>
    <?php include 'Header.php'; ?>
    
    <div class="container">
        <h2>Admin Only Page</h2>
        <p>This page is only accessible to administrators.</p>
        
        <p>Current User: <strong><?php echo htmlspecialchars(getCurrentUsername()); ?></strong></p>
        <p>Role: <strong><?php echo htmlspecialchars(getCurrentUserRole()); ?></strong></p>
    </div>
    
    <?php include 'Footer.html'; ?>
</body>
</html>
