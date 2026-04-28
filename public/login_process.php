<?php
require_once '../config/Database_Manager.php';
require_once '../config/User.php';
require_once '../config/session.php';
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

    header('Location: login.php');
    exit();
}

// Verify CSRF token
if (!wantsJsonResponse() && (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token']))) {
    if (!wantsJsonResponse()) {
        respondError('Invalid security token. Please try again.', 'login.php', 403);
    }
}

// Get request data
$requestData = getRequestData();

// Get form data
$username = trim($requestData['username'] ?? '');
$password = $requestData['password'] ?? '';

DebugLogger::info('Login attempt received', [
    'username' => $username,
    'has_password' => $password !== ''
]);

// Validate input
if (empty($username) || empty($password)) {
    $missingFields = [];
    if (empty($username)) {
        $missingFields[] = 'username';
    }
    if (empty($password)) {
        $missingFields[] = 'password';
    }

    respondValidationErrors('Missing required fields: ' . implode(', ', $missingFields) . '.', 'login.php', [
        'missing' => $missingFields
    ]);
}

// Check rate limiting
$rate_check = checkLoginAttempts($username);
if (is_array($rate_check) && isset($rate_check['locked'])) {
    respondError(
        'Too many failed attempts. Please try again in ' . $rate_check['minutes'] . ' minutes.',
        'login.php',
        429,
        ['minutes' => $rate_check['minutes']]
    );
}

// Create User instance
$user = new User($conn);

// Attempt login
$result = $user->login($username, $password);

if ($result['success']) {
    DebugLogger::info('Login successful', [
        'user_id' => $result['user']['user_id'] ?? null,
        'username' => $result['user']['username'] ?? $username
    ]);

    // Clear login attempts on successful login
    clearLoginAttempts($username);
    
    // Set session with role
    setUserSession(
        $result['user']['user_id'],
        $result['user']['username'],
        $result['user']['email'],
        $result['user']['role']
    );
    
    // Regenerate session ID for security
    regenerateSession();

    respondSuccess(
        'Welcome back, ' . $result['user']['username'],
        $result['user']['role'] === 'Admin' ? 'admin_dashboard.php' : 'index.php',
        200,
        ['user' => $result['user']]
    );
} else {
    // Record failed login attempt
    recordFailedLogin($username);

    DebugLogger::warning('Login failed', [
        'username' => $username,
        'reason' => $result['message'] ?? 'Unknown'
    ]);
    
    respondError($result['message'], 'login.php', 401);
}
?>
