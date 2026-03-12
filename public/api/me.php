<?php
require_once __DIR__ . '/_auth.php';

if (apiMethod() !== 'GET') {
    apiResponse(405, [
        'success' => false,
        'error' => 'Method not allowed.'
    ]);
}

$auth = apiRequireAuth();

apiResponse(200, [
    'success' => true,
    'user' => $auth['user']
]);
