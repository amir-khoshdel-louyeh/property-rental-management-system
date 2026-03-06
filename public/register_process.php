<?php
require_once '../config/Database_Manager.php';
require_once '../config/User.php';
require_once '../config/session.php';
require_once '../config/validation_helpers.php';

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

// Verify CSRF token
if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
    header('Location: register.php?error=Invalid security token. Please try again.');
    exit();
}

// Get form data
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$role = trim($_POST['role'] ?? 'Renter'); // Default to Renter

// Validate role
$valid_roles = ['Admin', 'Landlord', 'Renter'];
if (!in_array($role, $valid_roles)) {
    $role = 'Renter'; // Default to Renter if invalid role provided
}

// Validate input
if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    header('Location: register.php?error=Please fill in all fields');
    exit();
}

// Validate username
$username_validation = validateUsername($username);
if (!$username_validation['valid']) {
    header('Location: register.php?error=' . urlencode($username_validation['message']));
    exit();
}
$username = $username_validation['sanitized'];

// Validate email format
$email_validation = validateEmail($email);
if (!$email_validation['valid']) {
    header('Location: register.php?error=' . urlencode($email_validation['message']));
    exit();
}
$email = $email_validation['sanitized'];

// Validate password strength
$password_validation = validatePasswordStrength($password);
if (!$password_validation['valid']) {
    header('Location: register.php?error=' . urlencode($password_validation['message']));
    exit();
}

// Check if passwords match
if ($password !== $confirm_password) {
    header('Location: register.php?error=Passwords do not match');
    exit();
}

// Create User instance
$user = new User($conn);

// Attempt registration with role
$result = $user->register($username, $email, $password, $role);

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
