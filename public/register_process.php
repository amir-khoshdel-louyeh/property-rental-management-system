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
    header('Location: register.php');
    exit();
}

// Get form data
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validate input
if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    header('Location: register.php?error=Please fill in all fields');
    exit();
}

// Validate username length
if (strlen($username) < 3 || strlen($username) > 50) {
    header('Location: register.php?error=Username must be between 3 and 50 characters');
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: register.php?error=Invalid email format');
    exit();
}

// Validate password length
if (strlen($password) < 6) {
    header('Location: register.php?error=Password must be at least 6 characters');
    exit();
}

// Check if passwords match
if ($password !== $confirm_password) {
    header('Location: register.php?error=Passwords do not match');
    exit();
}

// Create User instance
$user = new User($conn);

// Attempt registration
$result = $user->register($username, $email, $password);

if ($result['success']) {
    // Redirect to login with success message
    header('Location: login.php?success=Registration successful! Please login.');
    exit();
} else {
    // Redirect back to register with error
    header('Location: register.php?error=' . urlencode($result['message']));
    exit();
}
?>
