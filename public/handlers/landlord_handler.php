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
        $first_name = sanitizeText($_POST['first_name'] ?? '');
        $last_name = sanitizeText($_POST['last_name'] ?? '');
        $phone_number = sanitizeText($_POST['phone_number'] ?? '');
        $email = sanitizeText($_POST['email'] ?? '');

        if (empty($first_name) || empty($last_name)) {
            $message = 'Please enter ALL necessary information (First Name and Last Name are required)!';
            $message_type = 'error';
        } else if (!empty($email) && !validateEmail($email)) {
            $message = 'Invalid email format!';
            $message_type = 'error';
        } else if (!empty($phone_number) && !validatePhone($phone_number)) {
            $message = 'Invalid phone number format!';
            $message_type = 'error';
        } else {
            $sql = "INSERT INTO Landlord (first_name, last_name, phone_number, email) 
                    VALUES (?, ?, ?, ?)";

            $response = executeWithMessage(
                $conn,
                $sql,
                "ssss",
                [$first_name, $last_name, $phone_number, $email],
                'Landlord added successfully!',
                'Try again! '
            );

            setHandlerMessage($message, $message_type, $response['message'], $response['type']);
        }
    }
    
    // Handle DELETE operation
    if ($action === 'delete') {
        $landlord_id = $_POST['landlord_id'] ?? 0;
        
        if (!validateNumber($landlord_id)) {
            $message = 'Invalid Landlord ID!';
            $message_type = 'error';
        } else {
            $landlord_id = intval($landlord_id);

            $dependenciesResult = deleteDependenciesById($conn, $landlord_id, [
                ['table' => 'Inspection', 'column' => 'conducted_by'],
                ['table' => 'Property', 'column' => 'landlord_id']
            ]);

            if ($dependenciesResult['success']) {
                $response = deleteByIdWithMessage(
                    $conn,
                    'Landlord',
                    'landlord_id',
                    $landlord_id,
                    'Landlord deleted successfully from the system!',
                    'Error deleting landlord: '
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

// Get all landlords for view tab
function getLandlords($conn) {
    return fetchAllEntities($conn, 'Landlord');
}
?>
