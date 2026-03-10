<?php
require_once __DIR__ . '/../../config/Database_Manager.php';
require_once __DIR__ . '/../../config/Validation.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Handle ADD operation
    if ($action === 'add') {
        $rental_id = $_POST['rental_id'] ?? 0;
        $property_id = $_POST['property_id'] ?? 0;
        $amount = $_POST['amount'] ?? 0;
        $payment_date = sanitizeText($_POST['payment_date'] ?? '');

        if (!validateNumber($rental_id) || !validateNumber($property_id) || !validateNumber($amount)) {
            $message = 'Invalid numeric input!';
            $message_type = 'error';
        } else if (empty($payment_date) || !validateDate($payment_date)) {
            $message = 'Please enter a valid payment date (YYYY-MM-DD)!';
            $message_type = 'error';
        } else {
            $sql = "INSERT INTO Payment (rental_id, property_id, amount, payment_date) 
                    VALUES (?, ?, ?, ?)";
            
            $result = executeQuery($conn, $sql, "iiis", 
                [intval($rental_id), intval($property_id), floatval($amount), $payment_date]);
            
            if ($result['success']) {
                $message = 'Payment recorded successfully!';
                $message_type = 'success';
            } else {
                $message = 'Try again! ' . htmlspecialchars($result['error']);
                $message_type = 'error';
            }
        }
    }
    
    // Handle DELETE operation
    if ($action === 'delete') {
        $payment_id = $_POST['payment_id'] ?? 0;
        
        if (!validateNumber($payment_id)) {
            $message = 'Invalid Payment ID!';
            $message_type = 'error';
        } else {
            $sql = "DELETE FROM Payment WHERE payment_id = ?";
            $result = executeQuery($conn, $sql, "i", [intval($payment_id)]);
            
            if ($result['success']) {
                $message = 'Payment deleted successfully from the system!';
                $message_type = 'success';
            } else {
                $message = 'Error deleting payment: ' . htmlspecialchars($result['error']);
                $message_type = 'error';
            }
        }
    }
}

// Get all payments for view tab
function getPayments($conn) {
    $sql = "SELECT * FROM Payment";
    return $conn->query($sql);
}
?>
