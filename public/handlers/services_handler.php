<?php
require_once __DIR__ . '/../../config/Database_Manager.php';
require_once __DIR__ . '/../../config/Validation.php';

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
            
            $result = executeQuery($conn, $sql, "s", [$services_name]);
            
            if ($result['success']) {
                $message = 'Service added successfully!';
                $message_type = 'success';
            } else {
                $message = 'Try again! ' . htmlspecialchars($result['error']);
                $message_type = 'error';
            }
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
            
            // Delete PropertyServices records first
            $sql = "DELETE FROM PropertyServices WHERE service_id = ?";
            $result = executeQuery($conn, $sql, "i", [$service_id]);
            
            if (!$result['success']) {
                $message = 'Error deleting property services: ' . htmlspecialchars($result['error']);
                $message_type = 'error';
            } else {
                // Then delete Service
                $sql = "DELETE FROM services WHERE service_id = ?";
                $result = executeQuery($conn, $sql, "i", [$service_id]);
                
                if ($result['success']) {
                    $message = 'Service deleted successfully from the system!';
                    $message_type = 'success';
                } else {
                    $message = 'Error deleting service: ' . htmlspecialchars($result['error']);
                    $message_type = 'error';
                }
            }
        }
    }
}

// Get all services for view tab
function getServices($conn) {
    $sql = "SELECT * FROM services";
    return $conn->query($sql);
}
?>
