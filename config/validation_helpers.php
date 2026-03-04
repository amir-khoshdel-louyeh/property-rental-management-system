<?php
/**
 * Validation Helper Functions
 */

/**
 * Validate password strength
 * Returns array with 'valid' boolean and 'message' string
 */
function validatePasswordStrength($password, $min_length = 6) {
    if (strlen($password) < $min_length) {
        return [
            'valid' => false,
            'message' => "Password must be at least {$min_length} characters long"
        ];
    }
    
    // Check for at least one number or special character (optional, can be enabled)
    // Uncomment the following lines for stricter password requirements
    /*
    if (!preg_match('/[0-9]/', $password)) {
        return [
            'valid' => false,
            'message' => 'Password must contain at least one number'
        ];
    }
    */
    
    return ['valid' => true, 'message' => 'Password is valid'];
}

/**
 * Sanitize username input
 */
function sanitizeUsername($username) {
    // Remove any non-alphanumeric characters except underscore and hyphen
    return preg_replace('/[^a-zA-Z0-9_-]/', '', trim($username));
}

/**
 * Validate username format
 */
function validateUsername($username, $min_length = 3, $max_length = 50) {
    $sanitized = sanitizeUsername($username);
    
    if (strlen($sanitized) < $min_length) {
        return [
            'valid' => false,
            'message' => "Username must be at least {$min_length} characters long"
        ];
    }
    
    if (strlen($sanitized) > $max_length) {
        return [
            'valid' => false,
            'message' => "Username must not exceed {$max_length} characters"
        ];
    }
    
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $sanitized)) {
        return [
            'valid' => false,
            'message' => 'Username can only contain letters, numbers, underscores, and hyphens'
        ];
    }
    
    return ['valid' => true, 'message' => 'Username is valid', 'sanitized' => $sanitized];
}

/**
 * Validate email format
 */
function validateEmail($email) {
    $email = trim($email);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return [
            'valid' => false,
            'message' => 'Invalid email format'
        ];
    }
    
    // Additional check for disposable email domains (optional)
    // You can add a list of disposable email domains to block
    
    return ['valid' => true, 'message' => 'Email is valid', 'sanitized' => $email];
}

/**
 * Prevent SQL injection by escaping special characters (additional layer)
 */
function escapeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}
?>
