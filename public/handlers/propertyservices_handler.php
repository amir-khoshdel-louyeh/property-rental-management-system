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
        $property_id = $_POST['property_id'] ?? 0;
        $service_id = $_POST['service_id'] ?? 0;

        if (!validateNumber($property_id) || !validateNumber($service_id)) {
            $message = 'Invalid numeric input!';
            $message_type = 'error';
        } else {
            $sql = "INSERT INTO PropertyServices (property_id, service_id) 
                    VALUES (?, ?)";

            $response = executeWithMessage(
                $conn,
                $sql,
                "ii",
                [intval($property_id), intval($service_id)],
                'Service link added successfully!',
                'Try again! '
            );

            setHandlerMessage($message, $message_type, $response['message'], $response['type']);
        }
    }
    
    // Handle DELETE operation
    if ($action === 'delete') {
        $propertyservice_id = $_POST['propertyservice_id'] ?? 0;
        
        if (!validateNumber($propertyservice_id)) {
            $message = 'Invalid Property Service ID!';
            $message_type = 'error';
        } else {
            $response = deleteByIdWithMessage(
                $conn,
                'PropertyServices',
                'property_service_id',
                $propertyservice_id,
                'Property service mapping deleted successfully from the system!',
                'Error deleting property service: '
            );

            setHandlerMessage($message, $message_type, $response['message'], $response['type']);
        }
    }
}

// Get all property services for view tab
function getPropertyServices($conn) {
    return fetchAllEntities($conn, 'PropertyServices');
}
?>
