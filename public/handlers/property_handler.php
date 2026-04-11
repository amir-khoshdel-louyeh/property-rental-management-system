<?php
require_once __DIR__ . '/../../config/Database_Manager.php';
require_once __DIR__ . '/../../config/Validation.php';
require_once __DIR__ . '/entity_handler_common.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Handle ADD operation
    if ($action === 'add') {
        $property_type = sanitizeText($_POST['property_type'] ?? '');
        $rent_sale = sanitizeText($_POST['rent_sale'] ?? '');
        $addres = sanitizeText($_POST['addres'] ?? '');
        $city = sanitizeText($_POST['city'] ?? '');
        $area_property = sanitizeText($_POST['area_property'] ?? '');
        $bedrooms = $_POST['bedrooms'] ?? 0;
        $price = $_POST['price'] ?? 0;
        $landlord_id = $_POST['landlord_id'] ?? 0;
        $descriptions = sanitizeText($_POST['descriptions'] ?? '');
        $zip_code = sanitizeText($_POST['zip_code'] ?? '');

        // Validate required fields
        if (empty($addres) || empty($city) || empty($zip_code) || empty($landlord_id)) {
            $message = 'Please enter ALL necessary information (Address, City, Zip Code, and Landlord ID are required)!';
            $message_type = 'error';
        } else if (!validateNumber($bedrooms) || !validateNumber($price) || !validateNumber($landlord_id)) {
            $message = 'Invalid numeric input!';
            $message_type = 'error';
        } else {
            $sql = "INSERT INTO Property (property_type, addres, city, area_property, bedrooms, descriptions, rent_sale, price, landlord_id, zip_code) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $response = executeWithMessage(
                $conn,
                $sql,
                "ssssissssi",
                [$property_type, $addres, $city, $area_property, intval($bedrooms),
                 $descriptions, $rent_sale, floatval($price), intval($landlord_id), $zip_code],
                'Property added successfully!',
                'Try again! '
            );

            setHandlerMessage($message, $message_type, $response['message'], $response['type']);
        }
    }
    
    // Handle DELETE operation
    if ($action === 'delete') {
        $property_id = $_POST['property_id'] ?? 0;
        
        if (!validateNumber($property_id)) {
            $message = 'Invalid Property ID!';
            $message_type = 'error';
        } else {
            $property_id = intval($property_id);

            $dependenciesResult = deleteDependenciesById($conn, $property_id, [
                ['table' => 'Payment', 'column' => 'property_id'],
                ['table' => 'Rental', 'column' => 'property_id'],
                ['table' => 'PropertyServices', 'column' => 'property_id'],
                ['table' => 'Inspection', 'column' => 'property_id']
            ]);

            if ($dependenciesResult['success']) {
                $response = deleteByIdWithMessage(
                    $conn,
                    'Property',
                    'property_id',
                    $property_id,
                    'Property deleted successfully from the system!',
                    'Error deleting property: '
                );

                setHandlerMessage($message, $message_type, $response['message'], $response['type']);
            } else {
                setHandlerMessage(
                    $message,
                    $message_type,
                    implode('<br>', $dependenciesResult['errors']),
                    'error'
                );
            }
        }
    }
}

// Get all properties for view tab
function getProperties($conn) {
    $searchOptions = [
        'q' => $_GET['q'] ?? '',
        'sort' => $_GET['sort'] ?? 'property_id',
        'order' => $_GET['order'] ?? 'ASC',
        'limit' => $_GET['limit'] ?? 200,
        'columnFilters' => [
            'city' => $_GET['city'] ?? '',
            'property_type' => $_GET['property_type'] ?? '',
            'rent_sale' => $_GET['rent_sale'] ?? ''
        ]
    ];

    return fetchEntitiesAdvanced(
        $conn,
        'Property',
        ['property_id', 'property_type', 'addres', 'city', 'area_property', 'bedrooms', 'descriptions', 'rent_sale', 'price', 'landlord_id', 'zip_code'],
        $searchOptions
    );
}
?>
