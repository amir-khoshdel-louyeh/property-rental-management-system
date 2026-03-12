<?php
require_once __DIR__ . '/_common.php';
require_once __DIR__ . '/../../config/ApiToken.php';

function apiGetBearerToken() {
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (stripos($header, 'Bearer ') !== 0) {
        return null;
    }

    return trim(substr($header, 7));
}

function apiRequireAuth() {
    global $conn;

    $token = apiGetBearerToken();
    if ($token === null || $token === '') {
        apiResponse(401, [
            'success' => false,
            'error' => 'Missing Bearer token.'
        ]);
    }

    $tokenService = new ApiToken($conn);
    $result = $tokenService->getUserFromToken($token);

    if (!$result['success']) {
        apiResponse(401, [
            'success' => false,
            'error' => $result['message']
        ]);
    }

    return [
        'token' => $token,
        'user' => $result['user']
    ];
}

function apiRequireRole($currentRole, $allowedRoles) {
    if (!in_array($currentRole, $allowedRoles, true)) {
        apiResponse(403, [
            'success' => false,
            'error' => 'Insufficient permissions.'
        ]);
    }
}
