<?php

/**
 * Validate and sanitize user input
 */

/**
 * Validate email address
 * @param string $email Email to validate
 * @return bool True if valid email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (basic validation for digits and hyphens)
 * @param string $phone Phone number to validate
 * @return bool True if valid phone format
 */
function validatePhone($phone) {
    return preg_match('/^[0-9\-\+\(\)\s]{7,}$/', $phone) === 1;
}
