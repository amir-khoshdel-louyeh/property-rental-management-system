<?php
require_once __DIR__ . '/../../config/Database_Manager.php';
require_once __DIR__ . '/../../config/Validation.php';
require_once __DIR__ . '/entity_handler_common.php';

$message = '';
$message_type = '';
$renter_pagination = [];

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

            $response = executeWithMessage(
                $conn,
                $sql,
                "sssss",
                [$first_name, $last_name, $phone_number, $email, $date_of_birth],
                'Renter added successfully!',
                'Try again! '
            );

            setHandlerMessage($message, $message_type, $response['message'], $response['type']);
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

            $dependenciesResult = deleteDependenciesById($conn, $renter_id, [
                ['table' => 'Rental', 'column' => 'renter_id', 'label' => 'rentals']
            ]);

            if (!$dependenciesResult['success']) {
                setHandlerMessage(
                    $message,
                    $message_type,
                    implode('<br>', $dependenciesResult['errors']),
                    'error'
                );
            } else {
                $response = deleteByIdWithMessage(
                    $conn,
                    'Renter',
                    'renter_id',
                    $renter_id,
                    'Renter deleted successfully from the system!',
                    'Error deleting renter: '
                );

                setHandlerMessage($message, $message_type, $response['message'], $response['type']);
            }
        }
    }
}

// Get all renters for view tab
function getRenters($conn) {
    global $renter_pagination;

    $searchOptions = [
        'q' => $_GET['q'] ?? '',
        'sort' => $_GET['sort'] ?? 'renter_id',
        'order' => $_GET['order'] ?? 'ASC',
        'limit' => $_GET['limit'] ?? 25,
        'page' => $_GET['page'] ?? 1,
        'columnFilters' => [
            'first_name' => $_GET['first_name'] ?? '',
            'last_name' => $_GET['last_name'] ?? '',
            'email' => $_GET['email'] ?? ''
        ]
    ];

    $data = fetchEntitiesAdvancedPaginated(
        $conn,
        'Renter',
        ['renter_id', 'first_name', 'last_name', 'phone_number', 'email', 'date_of_birth'],
        $searchOptions
    );

    if ($data === false) {
        $renter_pagination = [];
        return false;
    }

    $renter_pagination = $data['pagination'];
    return $data['result'];
}

function getRenterPagination() {
    global $renter_pagination;
    return $renter_pagination;
}
?>
