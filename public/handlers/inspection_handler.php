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
        $inspection_date = sanitizeText($_POST['inspection_date'] ?? '');
        $findings = sanitizeText($_POST['findings'] ?? '');
        $conducted_by = $_POST['conducted_by'] ?? 0;

        if (!validateNumber($property_id) || !validateNumber($conducted_by)) {
            $message = 'Invalid numeric input!';
            $message_type = 'error';
        } else if (empty($inspection_date) || !validateDate($inspection_date)) {
            $message = 'Invalid inspection date (use YYYY-MM-DD)!';
            $message_type = 'error';
        } else if (empty($findings)) {
            $message = 'Please enter inspection findings!';
            $message_type = 'error';
        } else {
            $sql = "INSERT INTO Inspection (property_id, inspection_date, findings, conducted_by) 
                    VALUES (?, ?, ?, ?)";

            $response = executeWithMessage(
                $conn,
                $sql,
                "issi",
                [intval($property_id), $inspection_date, $findings, intval($conducted_by)],
                'Inspection recorded successfully!',
                'Try again! '
            );

            setHandlerMessage($message, $message_type, $response['message'], $response['type']);
        }
    }
    
    // Handle DELETE operation
    if ($action === 'delete') {
        $inspection_id = $_POST['inspection_id'] ?? 0;
        
        if (!validateNumber($inspection_id)) {
            $message = 'Invalid Inspection ID!';
            $message_type = 'error';
        } else {
            $response = deleteByIdWithMessage(
                $conn,
                'Inspection',
                'inspection_id',
                $inspection_id,
                'Inspection deleted successfully from the system!',
                'Error deleting inspection: '
            );

            setHandlerMessage($message, $message_type, $response['message'], $response['type']);
        }
    }
}

// Get all inspections for view tab
function getInspections($conn) {
    return fetchAllEntities($conn, 'Inspection');
}
?>
