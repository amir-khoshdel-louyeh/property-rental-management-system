<?php
require_once __DIR__ . '/_common.php';
require_once __DIR__ . '/../../config/User.php';
require_once __DIR__ . '/../../config/ApiToken.php';

if (apiMethod() !== 'POST') {
    apiResponse(405, [
        'success' => false,
        'error' => 'Method not allowed.'
    ]);
}

$data = apiInputData();
$username = trim($data['username'] ?? '');
$password = $data['password'] ?? '';
$deviceName = trim($data['device_name'] ?? 'mobile-app');

if ($username === '' || $password === '') {
    apiResponse(400, [
        'success' => false,
        'error' => 'Username and password are required.'
    ]);
}

$userService = new User($conn);
$loginResult = $userService->login($username, $password);

if (!$loginResult['success']) {
    apiResponse(401, [
        'success' => false,
        'error' => $loginResult['message']
    ]);
}

$tokenService = new ApiToken($conn);
$tokenResult = $tokenService->createToken($loginResult['user']['user_id'], $deviceName);

if (!$tokenResult['success']) {
    apiResponse(500, [
        'success' => false,
        'error' => $tokenResult['message']
    ]);
}

apiResponse(200, [
    'success' => true,
    'message' => 'Login successful.',
    'token' => $tokenResult['token'],
    'token_type' => 'Bearer',
    'expires_at' => $tokenResult['expires_at'],
    'user' => $loginResult['user']
]);
