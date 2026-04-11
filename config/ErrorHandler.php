<?php

require_once __DIR__ . '/Exceptions.php';
require_once __DIR__ . '/DebugLogger.php';
require_once __DIR__ . '/ErrorPageRenderer.php';

class AppErrorHandler {
    private static $initialized = false;
    private static $context = 'web';

    public static function detectContext() {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        $requestedWith = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';

        if (strpos($scriptName, '/api/') !== false) {
            return 'api';
        }

        if (stripos($accept, 'application/json') !== false) {
            return 'api';
        }

        if (stripos($contentType, 'application/json') !== false) {
            return 'api';
        }

        if (strtolower($requestedWith) === 'xmlhttprequest') {
            return 'api';
        }

        return 'web';
    }

    public static function init($context = null) {
        if ($context !== null) {
            self::$context = $context;
        }

        if (self::$initialized) {
            return;
        }

        DebugLogger::init($context ?? self::$context);

        set_error_handler([self::class, 'handlePhpError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);

        self::$initialized = true;
    }

    public static function setContext($context) {
        self::$context = $context;
        DebugLogger::setContext($context);
    }

    public static function fail($statusCode, $publicMessage, $exception = null, $extra = []) {
        if ($exception instanceof Throwable) {
            self::logThrowable($exception, $publicMessage);
        }

        self::respond($statusCode, [
            'success' => false,
            'message' => $publicMessage
        ] + $extra);
    }

    public static function forbidden($message = 'Access Denied.') {
        self::respond(403, [
            'success' => false,
            'message' => $message
        ]);
    }

    public static function handlePhpError($severity, $message, $file, $line) {
        if (!(error_reporting() & $severity)) {
            return false;
        }

        throw new ErrorException($message, 0, $severity, $file, $line);
    }

    public static function handleException($exception) {
        self::logThrowable($exception, 'Unhandled exception');

        // Determine status code
        $statusCode = 500;
        $publicMessage = 'An unexpected error occurred. Please try again later.';
        $extra = [];
        
        // Handle custom exceptions
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        }
        
        if (method_exists($exception, 'getUserMessage')) {
            $publicMessage = $exception->getUserMessage();
        } elseif ($exception instanceof AppException) {
            $publicMessage = $exception->getMessage();
        }
        
        // Add validation errors if applicable
        if (method_exists($exception, 'getErrors')) {
            $errors = $exception->getErrors();
            if (!empty($errors)) {
                $extra['errors'] = $errors;
            }
        }

        self::respond($statusCode, array_merge([
            'success' => false,
            'message' => $publicMessage
        ], $extra));
    }

    public static function handleShutdown() {
        $error = error_get_last();

        if ($error === null) {
            return;
        }

        $fatalTypes = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR];
        if (!in_array($error['type'], $fatalTypes, true)) {
            return;
        }

        self::logMessage(
            'Fatal error: ' . $error['message'],
            [
                'file' => $error['file'],
                'line' => $error['line']
            ]
        );

        if (!headers_sent()) {
            self::respond(500, [
                'success' => false,
                'message' => 'A fatal error occurred. Please try again later.'
            ]);
        }
    }

    private static function logThrowable($exception, $prefix = '') {
        $message = ($prefix !== '' ? $prefix . ': ' : '') . $exception->getMessage();
        $context = [
            'type' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ];

        self::logMessage($message, $context);
        DebugLogger::error($message, $context);
    }

    private static function logMessage($message, $context = []) {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message";
        
        if (!empty($context)) {
            $logMessage .= ' | context=' . json_encode($context);
        }

        error_log($logMessage);
        
        // Also log to file if configured
        $logFile = getenv('ERROR_LOG_FILE');
        if ($logFile && is_writable(dirname($logFile))) {
            error_log($logMessage . PHP_EOL, 3, $logFile);
        }
    }
    
    public static function logInfo($message, $context = []) {
        self::logMessage("[INFO] $message", $context);
        DebugLogger::info($message, $context);
    }
    
    public static function logWarning($message, $context = []) {
        self::logMessage("[WARNING] $message", $context);
        DebugLogger::warning($message, $context);
    }
    
    public static function logError($message, $context = []) {
        self::logMessage("[ERROR] $message", $context);
        DebugLogger::error($message, $context);
    }

    private static function respond($statusCode, $payload) {
        if (headers_sent()) {
            return;
        }

        http_response_code($statusCode);

        if (self::shouldRespondWithJson()) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($payload);
            exit;
        }

        header('Content-Type: text/html; charset=utf-8');
        $message = isset($payload['message']) ? (string)$payload['message'] : 'Unexpected error.';
        $details = [];
        if (isset($payload['errors'])) {
            $details['errors'] = $payload['errors'];
        }
        if (isset($payload['missing'])) {
            $details['missing'] = $payload['missing'];
        }

        ErrorPageRenderer::render((int)$statusCode, $message, DebugLogger::getRequestId(), $details);
        exit;
    }

    private static function shouldRespondWithJson() {
        if (self::$context === 'api') {
            return true;
        }

        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        $requestedWith = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
        $format = $_GET['format'] ?? '';

        if (stripos($accept, 'application/json') !== false) {
            return true;
        }

        if (stripos($contentType, 'application/json') !== false) {
            return true;
        }

        if (strtolower($requestedWith) === 'xmlhttprequest') {
            return true;
        }

        return strtolower($format) === 'json';
    }
}
