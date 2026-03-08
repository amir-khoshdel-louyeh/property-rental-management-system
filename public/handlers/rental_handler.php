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
        $renter_id = $_POST['renter_id'] ?? 0;
        $start_date = sanitizeText($_POST['start_date'] ?? '');
        $end_date = sanitizeText($_POST['end_date'] ?? '');
        $monthly_rent = $_POST['monthly_rent'] ?? 0;
        $security_deposit = $_POST['security_deposit'] ?? 0;
        
        if (!validateNumber($property_id) || !validateNumber($renter_id) || !validateNumber($monthly_rent) || !validateNumber($security_deposit)) {
            $message = 'Invalid numeric input!';
            $message_type = 'error';
        } else if (empty($start_date) || !validateDate($start_date)) {
            $message = 'Invalid start date (use YYYY-MM-DD)!';
            $message_type = 'error';
        } else if (empty($end_date) || !validateDate($end_date)) {
            $message = 'Invalid end date (use YYYY-MM-DD)!';
            $message_type = 'error';
        } else if ($start_date >= $end_date) {
            $message = 'Start date must be before end date!';
            $message_type = 'error';
        } else {
            $sql = "INSERT INTO Rental (property_id, renter_id, start_date, end_date, monthly_rent, security_deposit) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $result = executeQuery($conn, $sql, "iissss", 
                [intval($property_id), intval($renter_id), $start_date, $end_date, floatval($monthly_rent), floatval($security_deposit)]);
            
            if ($result['success']) {
                $message = 'Rental created successfully!';
                $message_type = 'success';
            } else {
                $message = 'Try again! ' . htmlspecialchars($result['error']);
                $message_type = 'error';
            }
        }
    }
    
    // Handle DELETE operation
    if ($action === 'delete') {
        $rental_id = $_POST['rental_id'] ?? 0;
        
        if (!validateNumber($rental_id)) {
            $message = 'Invalid Rental ID!';
            $message_type = 'error';
        } else {
            $rental_id = intval($rental_id);
            
            // Delete Payment records first
            $sql = "DELETE FROM Payment WHERE rental_id = ?";
            $result = executeQuery($conn, $sql, "i", [$rental_id]);
            
            if (!$result['success']) {
                $message = 'Error deleting payments: ' . htmlspecialchars($result['error']);
                $message_type = 'error';
            } else {
                // Then delete Rental
                $sql = "DELETE FROM Rental WHERE rental_id = ?";
                $result = executeQuery($conn, $sql, "i", [$rental_id]);
                
                if ($result['success']) {
                    $message = 'Rental deleted successfully from the system!';
                    $message_type = 'success';
                } else {
                    $message = 'Error deleting rental: ' . htmlspecialchars($result['error']);
                    $message_type = 'error';
                }
            }
        }
    }
}

// Get all rentals for view tab
function getRentals($conn) {
    $sql = "SELECT * FROM Rental";
    return $conn->query($sql);
}
?>
