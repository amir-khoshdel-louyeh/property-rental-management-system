<?php

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

        set_error_handler([self::class, 'handlePhpError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);

        self::$initialized = true;
    }

    public static function setContext($context) {
        self::$context = $context;
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

        self::respond(500, [
            'success' => false,
            'message' => 'An unexpected error occurred. Please try again later.'
        ]);
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
        self::logMessage($message, [
            'type' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ]);
    }

    private static function logMessage($message, $context = []) {
        if (!empty($context)) {
            $message .= ' | context=' . json_encode($context);
        }

        error_log($message);
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

        header('Content-Type: text/plain; charset=utf-8');
        echo $payload['message'] ?? 'Unexpected error.';
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
