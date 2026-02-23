<?php
require_once '../config/Database_Manager.php';
require_once '../config/User.php';
require_once '../config/session.php';

startSession();

// Check if already logged in
if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit();
}

// Get form data
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Validate input
if (empty($username) || empty($password)) {
    header('Location: login.php?error=Please fill in all fields');
    exit();
}

// Create User instance
$user = new User($conn);

// Attempt login
$result = $user->login($username, $password);

if ($result['success']) {
    // Set session
    setUserSession(
        $result['user']['user_id'],
        $result['user']['username'],
        $result['user']['email']
    );
    
    // Redirect to home
    header('Location: index.php?success=Welcome back, ' . htmlspecialchars($result['user']['username']));
    exit();
} else {
    // Redirect back to login with error
    header('Location: login.php?error=' . urlencode($result['message']));
    exit();
}
?>
