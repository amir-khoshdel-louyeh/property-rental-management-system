<?php
require_once '../config/Database_Manager.php';
require_once '../config/User.php';
require_once '../config/session.php';
require_once '../config/validation_helpers.php';
require_once '../config/response_helpers.php';

startSession();

// Check if user is logged in
if (!isLoggedIn()) {
    respondError('Please login to access this page', 'login.php', 401);
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if (wantsJsonResponse()) {
        sendJson(405, [
            'success' => false,
            'message' => 'Method not allowed.'
        ]);
    }

    header('Location: change_password.php');
    exit();
}

// Verify CSRF token
if (!wantsJsonResponse() && (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token']))) {
    if (!wantsJsonResponse()) {
        respondError('Invalid security token. Please try again.', 'change_password.php', 403);
    }
}

// Get request data
$requestData = getRequestData();

// Get form data
$current_password = $requestData['current_password'] ?? '';
$new_password = $requestData['new_password'] ?? '';
$confirm_new_password = $requestData['confirm_new_password'] ?? '';

// Validate input
if (empty($current_password) || empty($new_password) || empty($confirm_new_password)) {
    respondValidationErrors('Please fill in all fields', 'change_password.php');
}

// Validate new password strength
$password_validation = validatePasswordStrength($new_password);
if (!$password_validation['valid']) {
    respondValidationErrors($password_validation['message'], 'change_password.php');
}

// Check if new passwords match
if ($new_password !== $confirm_new_password) {
    respondValidationErrors('New passwords do not match', 'change_password.php');
}

// Create User instance
$user = new User($conn);

// Attempt password change
$result = $user->changePassword(getCurrentUserId(), $current_password, $new_password);

if ($result['success']) {
    // Set flash message and redirect to profile
    setFlashMessage('success', $result['message']);
    respondSuccess($result['message'], 'profile.php');
} else {
    respondError($result['message'], 'change_password.php', 400);
}
?>
