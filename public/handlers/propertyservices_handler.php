<?php
require_once __DIR__ . '/../../config/Database_Manager.php';
require_once __DIR__ . '/../../config/Validation.php';

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
            
            $result = executeQuery($conn, $sql, "ii", 
                [intval($property_id), intval($service_id)]);
            
            if ($result['success']) {
                $message = 'Service link added successfully!';
                $message_type = 'success';
            } else {
                $message = 'Try again! ' . htmlspecialchars($result['error']);
                $message_type = 'error';
            }
        }
    }
    
    // Handle DELETE operation
    if ($action === 'delete') {
        $propertyservice_id = $_POST['propertyservice_id'] ?? 0;
        
        if (!validateNumber($propertyservice_id)) {
            $message = 'Invalid Property Service ID!';
            $message_type = 'error';
        } else {
            $sql = "DELETE FROM PropertyServices WHERE property_service_id = ?";
            $result = executeQuery($conn, $sql, "i", [intval($propertyservice_id)]);
            
            if ($result['success']) {
                $message = 'Property service mapping deleted successfully from the system!';
                $message_type = 'success';
            } else {
                $message = 'Error deleting property service: ' . htmlspecialchars($result['error']);
                $message_type = 'error';
            }
        }
    }
}

// Get all property services for view tab
function getPropertyServices($conn) {
    $sql = "SELECT * FROM PropertyServices";
    return $conn->query($sql);
}
?>
