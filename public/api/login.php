<?php
require_once __DIR__ . '/_common.php';
require_once __DIR__ . '/../../config/User.php';
require_once __DIR__ . '/../../config/ApiToken.php';

if (apiMethod() !== 'POST') {
    throw new MethodNotAllowedException();
}

$data = apiInputData();
$username = trim($data['username'] ?? '');
$password = $data['password'] ?? '';
$deviceName = trim($data['device_name'] ?? 'mobile-app');

if ($username === '' || $password === '') {
    throw new BadRequestException('Username and password are required.');
}

$userService = new User($conn);
$loginResult = $userService->login($username, $password);

if (!$loginResult['success']) {
    throw new AuthenticationException($loginResult['message']);
}

$tokenService = new ApiToken($conn);
$tokenResult = $tokenService->createToken($loginResult['user']['user_id'], $deviceName);

if (!$tokenResult['success']) {
    AppErrorHandler::logError('Token creation failed', ['error' => $tokenResult['message']]);
    throw new AppException('Failed to create authentication token.');
}

apiResponse(200, [
    'success' => true,
    'message' => 'Login successful.',
    'token' => $tokenResult['token'],
    'token_type' => 'Bearer',
    'expires_at' => $tokenResult['expires_at'],
    'user' => $loginResult['user']
]);
