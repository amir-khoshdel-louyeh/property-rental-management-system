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
        $services_name = sanitizeText($_POST['services_name'] ?? '');

        if (empty($services_name)) {
            $message = 'Please enter a service name!';
            $message_type = 'error';
        } else {
            $sql = "INSERT INTO services (services_name) 
                    VALUES (?)";

            $response = executeWithMessage(
                $conn,
                $sql,
                "s",
                [$services_name],
                'Service added successfully!',
                'Try again! '
            );

            setHandlerMessage($message, $message_type, $response['message'], $response['type']);
        }
    }
    
    // Handle DELETE operation
    if ($action === 'delete') {
        $service_id = $_POST['service_id'] ?? 0;
        
        if (!validateNumber($service_id)) {
            $message = 'Invalid Service ID!';
            $message_type = 'error';
        } else {
            $service_id = intval($service_id);

            $dependenciesResult = deleteDependenciesById($conn, $service_id, [
                ['table' => 'PropertyServices', 'column' => 'service_id', 'label' => 'property services']
            ]);

            if (!$dependenciesResult['success']) {
                setHandlerMessage(
                    $message,
                    $message_type,
                    implode('<br>', $dependenciesResult['errors']),
                    'error'
                );
            } else {
                $response = deleteByIdWithMessage(
                    $conn,
                    'services',
                    'service_id',
                    $service_id,
                    'Service deleted successfully from the system!',
                    'Error deleting service: '
                );

                setHandlerMessage($message, $message_type, $response['message'], $response['type']);
            }
        }
    }
}

// Get all services for view tab
function getServices($conn) {
    return fetchAllEntities($conn, 'services');
}
?>
