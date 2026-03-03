<?php
require_once '../config/Database_Manager.php';
require_once '../config/User.php';
require_once '../config/session.php';

startSession();

// Check if user is logged in
requireLogin();

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: change_password.php');
    exit();
}

// Verify CSRF token
if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
    header('Location: change_password.php?error=Invalid security token. Please try again.');
    exit();
}

// Get form data
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_new_password = $_POST['confirm_new_password'] ?? '';

// Validate input
if (empty($current_password) || empty($new_password) || empty($confirm_new_password)) {
    header('Location: change_password.php?error=Please fill in all fields');
    exit();
}

// Validate new password length
if (strlen($new_password) < 6) {
    header('Location: change_password.php?error=New password must be at least 6 characters');
    exit();
}

// Check if new passwords match
if ($new_password !== $confirm_new_password) {
    header('Location: change_password.php?error=New passwords do not match');
    exit();
}

// Create User instance
$user = new User($conn);

// Attempt password change
$result = $user->changePassword(getCurrentUserId(), $current_password, $new_password);

if ($result['success']) {
    // Set flash message and redirect to profile
    setFlashMessage('success', $result['message']);
    header('Location: profile.php');
    exit();
} else {
    // Redirect back with error
    header('Location: change_password.php?error=' . urlencode($result['message']));
    exit();
}
?>
