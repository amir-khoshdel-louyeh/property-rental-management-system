<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Property & Rental Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/forms.css">
    <link rel="stylesheet" href="css/utilities.css">
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
    <?php 
    require_once '../config/session.php';
    require_once '../config/Database_Manager.php';
    require_once '../config/User.php';
    
    // Check if user is logged in
    requireLogin();
    
    include 'Header.php';
    
    $user = new User($conn);
    $result = $user->getUserById(getCurrentUserId());
    $user_data = $result['user'] ?? [];
    ?>
    
    <div class="container">
        <div class="form-container">
            <h2>User Profile</h2>
            
            <?php
            $flash = getFlashMessage();
            if ($flash) {
                $alert_type = $flash['type'] === 'success' ? 'alert-success' : 'alert-error';
                echo '<div class="alert ' . $alert_type . '">' . htmlspecialchars($flash['message']) . '</div>';
            }
            ?>
            
            <div class="profile-info">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($user_data['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user_data['email']); ?></p>
                <p><strong>Member Since:</strong> <?php echo date('F j, Y', strtotime($user_data['created_at'])); ?></p>
                <p><strong>Last Login:</strong> <?php echo $user_data['last_login'] ? date('F j, Y g:i A', strtotime($user_data['last_login'])) : 'Never'; ?></p>
                <p><strong>Account Status:</strong> <?php echo $user_data['is_active'] ? '<span style="color: green;">Active</span>' : '<span style="color: red;">Inactive</span>'; ?></p>
            </div>
            
            <div style="margin-top: 2rem;">
                <a href="change_password.php" class="btn btn-primary">Change Password</a>
            </div>
        </div>
    </div>
    
    <?php include 'Footer.html'; ?>
</body>
</html>
