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
