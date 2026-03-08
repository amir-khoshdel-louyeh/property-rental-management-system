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
        $date_of_birth = sanitizeText($_POST['date_of_birth'] ?? '');

        if (empty($first_name) || empty($last_name)) {
            $message = 'Please enter ALL necessary information (First Name and Last Name are required)!';
            $message_type = 'error';
        } else if (!empty($email) && !validateEmail($email)) {
            $message = 'Invalid email format!';
            $message_type = 'error';
        } else if (!empty($phone_number) && !validatePhone($phone_number)) {
            $message = 'Invalid phone number format!';
            $message_type = 'error';
        } else if (!empty($date_of_birth) && !validateDate($date_of_birth)) {
            $message = 'Invalid date format (use YYYY-MM-DD)!';
            $message_type = 'error';
        } else {
            $sql = "INSERT INTO Renter (first_name, last_name, phone_number, email, date_of_birth) 
                    VALUES (?, ?, ?, ?, ?)";
            
            $result = executeQuery($conn, $sql, "sssss", 
                [$first_name, $last_name, $phone_number, $email, $date_of_birth]);
            
            if ($result['success']) {
                $message = 'Renter added successfully!';
                $message_type = 'success';
            } else {
                $message = 'Try again! ' . htmlspecialchars($result['error']);
                $message_type = 'error';
            }
        }
    }
    
    // Handle DELETE operation
    if ($action === 'delete') {
        $renter_id = $_POST['renter_id'] ?? 0;
        
        if (!validateNumber($renter_id)) {
            $message = 'Invalid Renter ID!';
            $message_type = 'error';
        } else {
            $renter_id = intval($renter_id);
            
            // Delete Rental records first
            $sql = "DELETE FROM Rental WHERE renter_id = ?";
            $result = executeQuery($conn, $sql, "i", [$renter_id]);
            
            if (!$result['success']) {
                $message = 'Error deleting rentals: ' . htmlspecialchars($result['error']);
                $message_type = 'error';
            } else {
                // Then delete Renter
                $sql = "DELETE FROM Renter WHERE renter_id = ?";
                $result = executeQuery($conn, $sql, "i", [$renter_id]);
                
                if ($result['success']) {
                    $message = 'Renter deleted successfully from the system!';
                    $message_type = 'success';
                } else {
                    $message = 'Error deleting renter: ' . htmlspecialchars($result['error']);
                    $message_type = 'error';
                }
            }
        }
    }
}

// Get all renters for view tab
function getRenters($conn) {
    $sql = "SELECT * FROM Renter";
    return $conn->query($sql);
}
?>
