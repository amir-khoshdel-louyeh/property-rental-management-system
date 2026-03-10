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

            $response = executeWithMessage(
                $conn,
                $sql,
                "iiis",
                [intval($rental_id), intval($property_id), floatval($amount), $payment_date],
                'Payment recorded successfully!',
                'Try again! '
            );

            setHandlerMessage($message, $message_type, $response['message'], $response['type']);
        }
    }
    
    // Handle DELETE operation
    if ($action === 'delete') {
        $payment_id = $_POST['payment_id'] ?? 0;
        
        if (!validateNumber($payment_id)) {
            $message = 'Invalid Payment ID!';
            $message_type = 'error';
        } else {
            $response = deleteByIdWithMessage(
                $conn,
                'Payment',
                'payment_id',
                $payment_id,
                'Payment deleted successfully from the system!',
                'Error deleting payment: '
            );

            setHandlerMessage($message, $message_type, $response['message'], $response['type']);
        }
    }
}

// Get all payments for view tab
function getPayments($conn) {
    return fetchAllEntities($conn, 'Payment');
}
?>
