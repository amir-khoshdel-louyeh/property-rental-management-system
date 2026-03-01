<?php
/**
 * Session Management Utilities
 */

// Start session if not already started
function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
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
?>
