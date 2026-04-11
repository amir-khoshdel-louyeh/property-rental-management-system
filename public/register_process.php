<?php
require_once '../config/Database_Manager.php';
require_once '../config/User.php';
require_once '../config/session.php';
require_once '../config/validation_helpers.php';
require_once '../config/response_helpers.php';
require_once '../config/DebugLogger.php';

DebugLogger::init('web-auth');

startSession();

// Check if already logged in
if (isLoggedIn()) {
    respondSuccess('Already logged in.', 'index.php', 200, [
        'user_id' => getCurrentUserId(),
        'username' => getCurrentUsername(),
        'email' => getCurrentUserEmail(),
        'role' => getCurrentUserRole()
    ]);
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if (wantsJsonResponse()) {
        sendJson(405, [
            'success' => false,
            'message' => 'Method not allowed.'
        ]);
    }

    header('Location: register.php');
    exit();
}

// Verify CSRF token
if (!wantsJsonResponse() && (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token']))) {
    if (!wantsJsonResponse()) {
        respondError('Invalid security token. Please try again.', 'register.php', 403);
    }
}

// Get request data
$requestData = getRequestData();

// Get form data
$username = trim($requestData['username'] ?? '');
$email = trim($requestData['email'] ?? '');
$password = $requestData['password'] ?? '';
$confirm_password = $requestData['confirm_password'] ?? '';
$role = trim($requestData['role'] ?? 'Renter'); // Default to Renter

DebugLogger::info('Registration attempt received', [
    'username' => $username,
    'email' => $email,
    'role' => $role
]);

// Validate role
$valid_roles = ['Admin', 'Landlord', 'Renter'];
if (!in_array($role, $valid_roles)) {
    $role = 'Renter'; // Default to Renter if invalid role provided
}

// Validate input
if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    $missingFields = [];
    if (empty($username)) {
        $missingFields[] = 'username';
    }
    if (empty($email)) {
        $missingFields[] = 'email';
    }
    if (empty($password)) {
        $missingFields[] = 'password';
    }
    if (empty($confirm_password)) {
        $missingFields[] = 'confirm_password';
    }

    respondValidationErrors('Missing required fields: ' . implode(', ', $missingFields) . '.', 'register.php', [
        'missing' => $missingFields
    ]);
}

// Validate username
$username_validation = validateUsername($username);
if (!$username_validation['valid']) {
    respondValidationErrors($username_validation['message'], 'register.php');
}
$username = $username_validation['sanitized'];

// Validate email format
$email_validation = validateEmail($email);
if (!$email_validation['valid']) {
    respondValidationErrors($email_validation['message'], 'register.php');
}
$email = $email_validation['sanitized'];

// Validate password strength
$password_validation = validatePasswordStrength($password);
if (!$password_validation['valid']) {
    respondValidationErrors($password_validation['message'], 'register.php');
}

// Check if passwords match
if ($password !== $confirm_password) {
    respondValidationErrors('Passwords do not match', 'register.php');
}

// Create User instance
$user = new User($conn);

// Attempt registration with role
$result = $user->register($username, $email, $password, $role);

if ($result['success']) {
    DebugLogger::info('Registration successful', [
        'username' => $username,
        'email' => $email,
        'role' => $role
    ]);

    respondSuccess('Registration successful! Please login.', 'login.php', 201);
} else {
    DebugLogger::warning('Registration failed', [
        'username' => $username,
        'email' => $email,
        'reason' => $result['message'] ?? 'Unknown'
    ]);

    respondError($result['message'], 'register.php', 409);
}
?>
