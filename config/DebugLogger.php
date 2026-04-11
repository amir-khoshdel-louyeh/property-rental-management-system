<?php

class DebugLogger {
    private static $initialized = false;
    private static $requestId = null;
    private static $context = 'app';

    const LEVEL_DEBUG = 10;
    const LEVEL_INFO = 20;
    const LEVEL_WARNING = 30;
    const LEVEL_ERROR = 40;

    public static function init($context = 'app') {
        if (self::$initialized) {
            if ($context !== '') {
                self::$context = $context;
            }
            return;
        }

        self::$context = $context !== '' ? $context : 'app';
        self::$requestId = self::resolveRequestId();
        self::$initialized = true;
    }

    public static function setContext($context) {
        self::$context = $context !== '' ? $context : self::$context;
    }

    public static function getRequestId() {
        if (!self::$initialized) {
            self::init();
        }

        return self::$requestId;
    }

    public static function debug($message, $data = []) {
        self::write(self::LEVEL_DEBUG, 'DEBUG', $message, $data);
    }

    public static function info($message, $data = []) {
        self::write(self::LEVEL_INFO, 'INFO', $message, $data);
    }

    public static function warning($message, $data = []) {
        self::write(self::LEVEL_WARNING, 'WARNING', $message, $data);
    }

    public static function error($message, $data = []) {
        self::write(self::LEVEL_ERROR, 'ERROR', $message, $data);
    }

    private static function write($level, $label, $message, $data) {
        if (!self::isEnabled() || !self::levelAllowed($level)) {
            return;
        }

        if (!self::$initialized) {
            self::init();
        }

        $entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level' => $label,
            'context' => self::$context,
            'request_id' => self::$requestId,
            'message' => $message
        ];

        if (!empty($data)) {
            $entry['data'] = $data;
        }

        $line = json_encode($entry);
        if ($line === false) {
            $line = '{"timestamp":"' . date('Y-m-d H:i:s') . '","level":"ERROR","message":"Failed to encode log entry"}';
        }

        $line .= PHP_EOL;

        $file = self::logFile();
        $dir = dirname($file);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        if (is_writable($dir)) {
            error_log($line, 3, $file);
        } else {
            error_log(trim($line));
        }
    }

    private static function isEnabled() {
        $value = strtolower((string)(getenv('DEBUG_LOG_ENABLED') ?: 'false'));
        return in_array($value, ['1', 'true', 'yes', 'on'], true);
    }

    private static function levelAllowed($level) {
        $minLevel = (int)(getenv('DEBUG_LOG_LEVEL') ?: self::LEVEL_DEBUG);
        return $level >= $minLevel;
    }

    private static function resolveRequestId() {
        $incoming = $_SERVER['HTTP_X_REQUEST_ID'] ?? '';
        if ($incoming !== '') {
            return preg_replace('/[^A-Za-z0-9._-]/', '', $incoming);
        }

        return bin2hex(random_bytes(8));
    }

    private static function logFile() {
        $configured = getenv('DEBUG_LOG_FILE');
        if ($configured && trim($configured) !== '') {
            return $configured;
        }

        return __DIR__ . '/../logs/debug.log';
    }
}
