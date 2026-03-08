<?php
require_once __DIR__ . '/../../config/Database_Manager.php';
require_once __DIR__ . '/../../config/Validation.php';

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
            
            $result = executeQuery($conn, $sql, "ssss", 
                [$first_name, $last_name, $phone_number, $email]);
            
            if ($result['success']) {
                $message = 'Landlord added successfully!';
                $message_type = 'success';
            } else {
                $message = 'Try again! ' . htmlspecialchars($result['error']);
                $message_type = 'error';
            }
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
            
            // Delete related records first (referential integrity)
            $tables_to_delete = [
                ['table' => 'Inspection', 'column' => 'conducted_by'],
                ['table' => 'Property', 'column' => 'landlord_id']
            ];
            $all_success = true;
            $delete_messages = [];
            
            foreach ($tables_to_delete as $delete_info) {
                $sql = "DELETE FROM " . $delete_info['table'] . " WHERE " . $delete_info['column'] . " = ?";
                $result = executeQuery($conn, $sql, "i", [$landlord_id]);
                
                if (!$result['success']) {
                    $delete_messages[] = 'Error deleting from ' . htmlspecialchars($delete_info['table']) . ': ' . htmlspecialchars($result['error']);
                    $all_success = false;
                } else {
                    $delete_messages[] = htmlspecialchars($delete_info['table']) . ' records deleted successfully';
                }
            }
            
            // Finally delete from Landlord table
            if ($all_success) {
                $sql = "DELETE FROM Landlord WHERE landlord_id = ?";
                $result = executeQuery($conn, $sql, "i", [$landlord_id]);
                
                if ($result['success']) {
                    $message = 'Landlord deleted successfully from the system!';
                    $message_type = 'success';
                } else {
                    $message = 'Error deleting landlord: ' . htmlspecialchars($result['error']);
                    $message_type = 'error';
                }
            } else {
                $message = implode('<br>', $delete_messages);
                $message_type = 'error';
            }
        }
    }
}

// Get all landlords for view tab
function getLandlords($conn) {
    $sql = "SELECT * FROM Landlord";
    return $conn->query($sql);
}
?>
