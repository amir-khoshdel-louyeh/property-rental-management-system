<?php

/**
 * Base exception class for application-specific exceptions
 */
class AppException extends Exception {
    protected $statusCode = 500;
    protected $userMessage;
    
    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->userMessage = $message;
    }
    
    public function getStatusCode() {
        return $this->statusCode;
    }
    
    public function getUserMessage() {
        return $this->userMessage;
    }
}

/**
 * Exception for validation errors
 */
class ValidationException extends AppException {
    protected $statusCode = 422;
    protected $errors = [];
    
    public function __construct($message = "Validation failed", $errors = [], $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }
    
    public function getErrors() {
        return $this->errors;
    }
    
    public function setErrors(array $errors) {
        $this->errors = $errors;
        return $this;
    }
}

/**
 * Exception for database errors
 */
class DatabaseException extends AppException {
    protected $statusCode = 500;
    protected $userMessage = "A database error occurred. Please try again later.";
    
    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
        // Don't expose internal database errors to users
        $this->userMessage = "A database error occurred. Please try again later.";
    }
}

/**
 * Exception for authentication failures
 */
class AuthenticationException extends AppException {
    protected $statusCode = 401;
    
    public function __construct($message = "Authentication failed", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * Exception for authorization failures
 */
class AuthorizationException extends AppException {
    protected $statusCode = 403;
    
    public function __construct($message = "Access denied", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * Exception for resource not found errors
 */
class NotFoundException extends AppException {
    protected $statusCode = 404;
    
    public function __construct($message = "Resource not found", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * Exception for invalid input/bad requests
 */
class BadRequestException extends AppException {
    protected $statusCode = 400;
    
    public function __construct($message = "Bad request", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * Exception for method not allowed errors
 */
class MethodNotAllowedException extends AppException {
    protected $statusCode = 405;
    
    public function __construct($message = "Method not allowed", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * Exception for configuration errors
 */
class ConfigurationException extends AppException {
    protected $statusCode = 500;
    protected $userMessage = "A configuration error occurred. Please contact support.";
    
    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
        // Don't expose configuration details to users
        $this->userMessage = "A configuration error occurred. Please contact support.";
    }
}
