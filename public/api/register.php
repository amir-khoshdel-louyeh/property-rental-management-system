<?php
require_once __DIR__ . '/_common.php';
require_once __DIR__ . '/../../config/User.php';
require_once __DIR__ . '/../../config/validation_helpers.php';
require_once __DIR__ . '/../../config/RoleConstants.php';

if (apiMethod() !== 'POST') {
    throw new MethodNotAllowedException();
}

$data = apiInputData();
$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
$confirmPassword = $data['confirm_password'] ?? '';
$role = trim($data['role'] ?? Role::RENTER);

if ($username === '' || $email === '' || $password === '' || $confirmPassword === '') {
    throw new BadRequestException('username, email, password, and confirm_password are required.');
}

if (!Role::isValid($role)) {
    $role = Role::RENTER;
}

$usernameValidation = validateUsername($username);
if (!$usernameValidation['valid']) {
    throw new ValidationException($usernameValidation['message']);
}
$username = $usernameValidation['sanitized'];

$emailValidation = validateEmail($email);
if (!$emailValidation['valid']) {
    throw new ValidationException($emailValidation['message']);
}
$email = $emailValidation['sanitized'];

$passwordValidation = validatePasswordStrength($password);
if (!$passwordValidation['valid']) {
    throw new ValidationException($passwordValidation['message']);
}

if ($password !== $confirmPassword) {
    throw new ValidationException('Passwords do not match.');
}

$userService = new User($conn);
$registerResult = $userService->register($username, $email, $password, $role);

if (!$registerResult['success']) {
    AppErrorHandler::logWarning('Registration failed', ['message' => $registerResult['message']]);
    throw new BadRequestException($registerResult['message']);
}

apiResponse(201, [
    'success' => true,
    'message' => 'Registration successful.'
]);
