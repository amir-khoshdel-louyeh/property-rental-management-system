<?php
require_once __DIR__ . '/_common.php';
require_once __DIR__ . '/../../config/User.php';
require_once __DIR__ . '/../../config/validation_helpers.php';
require_once __DIR__ . '/../../config/RoleConstants.php';

if (apiMethod() !== 'POST') {
    apiResponse(405, [
        'success' => false,
        'error' => 'Method not allowed.'
    ]);
}

$data = apiInputData();
$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
$confirmPassword = $data['confirm_password'] ?? '';
$role = trim($data['role'] ?? Role::RENTER);

if ($username === '' || $email === '' || $password === '' || $confirmPassword === '') {
    apiResponse(400, [
        'success' => false,
        'error' => 'username, email, password, and confirm_password are required.'
    ]);
}

if (!Role::isValid($role)) {
    $role = Role::RENTER;
}

$usernameValidation = validateUsername($username);
if (!$usernameValidation['valid']) {
    apiResponse(422, [
        'success' => false,
        'error' => $usernameValidation['message']
    ]);
}
$username = $usernameValidation['sanitized'];

$emailValidation = validateEmail($email);
if (!$emailValidation['valid']) {
    apiResponse(422, [
        'success' => false,
        'error' => $emailValidation['message']
    ]);
}
$email = $emailValidation['sanitized'];

$passwordValidation = validatePasswordStrength($password);
if (!$passwordValidation['valid']) {
    apiResponse(422, [
        'success' => false,
        'error' => $passwordValidation['message']
    ]);
}

if ($password !== $confirmPassword) {
    apiResponse(422, [
        'success' => false,
        'error' => 'Passwords do not match.'
    ]);
}

$userService = new User($conn);
$registerResult = $userService->register($username, $email, $password, $role);

if (!$registerResult['success']) {
    apiResponse(409, [
        'success' => false,
        'error' => $registerResult['message']
    ]);
}

apiResponse(201, [
    'success' => true,
    'message' => 'Registration successful.'
]);
