<?php
require_once __DIR__ . '/_common.php';

apiResponse(200, [
    'success' => true,
    'service' => 'property-rental-management-system-api',
    'version' => '1.0',
    'endpoints' => [
        'landlords' => '/api/landlords.php',
        'renters' => '/api/renters.php',
        'properties' => '/api/properties.php',
        'rentals' => '/api/rentals.php',
        'payments' => '/api/payments.php',
        'services' => '/api/services.php',
        'inspections' => '/api/inspections.php',
        'propertyservices' => '/api/propertyservices.php'
    ]
]);
