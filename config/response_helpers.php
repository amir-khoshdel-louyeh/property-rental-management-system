<?php

function wantsJsonResponse() {
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

function sendJson($statusCode, $payload) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload);
    exit();
}

function getRequestData() {
    if (!empty($_POST)) {
        return $_POST;
    }

    $raw = file_get_contents('php://input');
    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $decoded = json_decode($raw, true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
        sendJson(400, [
            'success' => false,
            'message' => 'Invalid JSON request body.'
        ]);
    }

    return $decoded;
}

function respondError($message, $redirectPath, $statusCode = 400, $extra = []) {
    if (wantsJsonResponse()) {
        sendJson($statusCode, array_merge([
            'success' => false,
            'message' => $message
        ], $extra));
    }

    header('Location: ' . $redirectPath . '?error=' . urlencode($message));
    exit();
}

function respondSuccess($message, $redirectPath, $statusCode = 200, $extra = []) {
    if (wantsJsonResponse()) {
        sendJson($statusCode, array_merge([
            'success' => true,
            'message' => $message
        ], $extra));
    }

    $separator = strpos($redirectPath, '?') === false ? '?' : '&';
    header('Location: ' . $redirectPath . $separator . 'success=' . urlencode($message));
    exit();
}

function respondValidationErrors($message, $redirectPath, $errors = []) {
    respondError($message, $redirectPath, 422, [
        'errors' => $errors
    ]);
}
