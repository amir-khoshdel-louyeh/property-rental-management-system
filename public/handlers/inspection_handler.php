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
            
            $result = executeQuery($conn, $sql, "issi", 
                [intval($property_id), $inspection_date, $findings, intval($conducted_by)]);
            
            if ($result['success']) {
                $message = 'Inspection recorded successfully!';
                $message_type = 'success';
            } else {
                $message = 'Try again! ' . htmlspecialchars($result['error']);
                $message_type = 'error';
            }
        }
    }
    
    // Handle DELETE operation
    if ($action === 'delete') {
        $inspection_id = $_POST['inspection_id'] ?? 0;
        
        if (!validateNumber($inspection_id)) {
            $message = 'Invalid Inspection ID!';
            $message_type = 'error';
        } else {
            $sql = "DELETE FROM Inspection WHERE inspection_id = ?";
            $result = executeQuery($conn, $sql, "i", [intval($inspection_id)]);
            
            if ($result['success']) {
                $message = 'Inspection deleted successfully from the system!';
                $message_type = 'success';
            } else {
                $message = 'Error deleting inspection: ' . htmlspecialchars($result['error']);
                $message_type = 'error';
            }
        }
    }
}

// Get all inspections for view tab
function getInspections($conn) {
    $sql = "SELECT * FROM Inspection";
    return $conn->query($sql);
}
?>
