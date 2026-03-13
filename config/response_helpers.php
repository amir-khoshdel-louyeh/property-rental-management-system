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
