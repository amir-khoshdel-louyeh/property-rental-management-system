<?php
require_once __DIR__ . '/_auth.php';

if (apiMethod() !== 'POST') {
    apiResponse(405, [
        'success' => false,
        'error' => 'Method not allowed.'
    ]);
}

global $conn;
$auth = apiRequireAuth();

$tokenService = new ApiToken($conn);
$revokeResult = $tokenService->revokeToken($auth['token']);

if (!$revokeResult['success']) {
    apiResponse(500, [
        'success' => false,
        'error' => 'Failed to revoke token.'
    ]);
}

apiResponse(200, [
    'success' => true,
    'message' => 'Logged out successfully.'
]);
