<?php
/**
 * Session Management Utilities
 */

// Start session if not already started
function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        // Set secure session parameters
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_strict_mode', 1);
        ini_set('session.cookie_samesite', 'Strict');
        
        // Start the session
        session_start();
    }
}

// Check if user is logged in
function isLoggedIn() {
    startSession();
    return isset($_SESSION['user_id']) && isset($_SESSION['username']);
}

// Get current user ID
function getCurrentUserId() {
    startSession();
    return $_SESSION['user_id'] ?? null;
}

// Get current username
function getCurrentUsername() {
    startSession();
    return $_SESSION['username'] ?? null;
}

// Get current user email
function getCurrentUserEmail() {
    startSession();
    return $_SESSION['email'] ?? null;
}

// Set user session
function setUserSession($user_id, $username, $email) {
    startSession();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
}

// Destroy user session (logout)
function destroyUserSession() {
    startSession();
    $_SESSION = [];
    session_destroy();
}

// Require login (redirect to login page if not logged in)
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php?error=Please login to access this page');
        exit();
    }
}

// Regenerate session ID for security
function regenerateSession() {
    startSession();
    session_regenerate_id(true);
}

// Check for session timeout (30 minutes of inactivity)
function checkSessionTimeout($timeout = 1800) {
    startSession();
    
    if (isset($_SESSION['last_activity'])) {
        $elapsed = time() - $_SESSION['last_activity'];
        
        if ($elapsed > $timeout) {
            destroyUserSession();
            header('Location: login.php?error=Session expired due to inactivity');
            exit();
        }
    }
    
    $_SESSION['last_activity'] = time();
}

// Set flash message
function setFlashMessage($type, $message) {
    startSession();
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

// Get and clear flash message
function getFlashMessage() {
    startSession();
    
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $flash;
    }
    
    return null;
}

// Generate CSRF token
function generateCSRFToken() {
    startSession();
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function verifyCSRFToken($token) {
    startSession();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Get CSRF token input field HTML
function getCSRFInput() {
    $token = generateCSRFToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}

// Check login attempts rate limiting
function checkLoginAttempts($username, $max_attempts = 5, $lockout_time = 900) {
    startSession();
    $key = 'login_attempts_' . md5($username);
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 0, 'first_attempt' => time()];
    }
    
    $attempts = $_SESSION[$key];
    
    // Reset if lockout time has passed
    if (time() - $attempts['first_attempt'] > $lockout_time) {
        $_SESSION[$key] = ['count' => 0, 'first_attempt' => time()];
        return true;
    }
    
    // Check if max attempts reached
    if ($attempts['count'] >= $max_attempts) {
        $remaining_time = $lockout_time - (time() - $attempts['first_attempt']);
        $minutes = ceil($remaining_time / 60);
        return ['locked' => true, 'minutes' => $minutes];
    }
    
    return true;
}

// Record failed login attempt
function recordFailedLogin($username) {
    startSession();
    $key = 'login_attempts_' . md5($username);
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 1, 'first_attempt' => time()];
    } else {
        $_SESSION[$key]['count']++;
    }
}

// Clear login attempts on successful login
function clearLoginAttempts($username) {
    startSession();
    $key = 'login_attempts_' . md5($username);
    unset($_SESSION[$key]);
}
?>
