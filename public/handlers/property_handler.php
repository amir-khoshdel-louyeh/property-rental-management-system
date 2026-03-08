<?php
require_once __DIR__ . '/../../config/Database_Manager.php';
require_once __DIR__ . '/../../config/Validation.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Handle ADD operation
    if ($action === 'add') {
        $property_type = sanitizeText($_POST['property_type'] ?? '');
        $rent_sale = sanitizeText($_POST['rent_sale'] ?? '');
        $addres = sanitizeText($_POST['addres'] ?? '');
        $city = sanitizeText($_POST['city'] ?? '');
        $area_property = sanitizeText($_POST['area_property'] ?? '');
        $bedrooms = $_POST['bedrooms'] ?? 0;
        $price = $_POST['price'] ?? 0;
        $landlord_id = $_POST['landlord_id'] ?? 0;
        $descriptions = sanitizeText($_POST['descriptions'] ?? '');
        $zip_code = sanitizeText($_POST['zip_code'] ?? '');

        // Validate required fields
        if (empty($addres) || empty($city) || empty($zip_code) || empty($landlord_id)) {
            $message = 'Please enter ALL necessary information (Address, City, Zip Code, and Landlord ID are required)!';
            $message_type = 'error';
        } else if (!validateNumber($bedrooms) || !validateNumber($price) || !validateNumber($landlord_id)) {
            $message = 'Invalid numeric input!';
            $message_type = 'error';
        } else {
            $sql = "INSERT INTO Property (property_type, addres, city, area_property, bedrooms, descriptions, rent_sale, price, landlord_id, zip_code) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $result = executeQuery($conn, $sql, "ssssissssi", 
                [$property_type, $addres, $city, $area_property, intval($bedrooms), 
                 $descriptions, $rent_sale, floatval($price), intval($landlord_id), $zip_code]);
            
            if ($result['success']) {
                $message = 'Property added successfully!';
                $message_type = 'success';
            } else {
                $message = 'Try again! ' . htmlspecialchars($result['error']);
                $message_type = 'error';
            }
        }
    }
    
    // Handle DELETE operation
    if ($action === 'delete') {
        $property_id = $_POST['property_id'] ?? 0;
        
        if (!validateNumber($property_id)) {
            $message = 'Invalid Property ID!';
            $message_type = 'error';
        } else {
            $property_id = intval($property_id);
            
            // Delete from related tables first (referential integrity)
            $tables_to_delete = ['Payment', 'Rental', 'PropertyServices', 'Inspection'];
            $all_success = true;
            $delete_messages = [];
            
            foreach ($tables_to_delete as $table) {
                $sql = "DELETE FROM " . $table . " WHERE property_id = ?";
                $result = executeQuery($conn, $sql, "i", [$property_id]);
                
                if (!$result['success']) {
                    $delete_messages[] = 'Error deleting from ' . htmlspecialchars($table) . ': ' . htmlspecialchars($result['error']);
                    $all_success = false;
                } else {
                    $delete_messages[] = htmlspecialchars($table) . ' records deleted successfully';
                }
            }
            
            // Finally delete from Property table
            if ($all_success) {
                $sql = "DELETE FROM Property WHERE property_id = ?";
                $result = executeQuery($conn, $sql, "i", [$property_id]);
                
                if ($result['success']) {
                    $message = 'Property deleted successfully from the system!';
                    $message_type = 'success';
                } else {
                    $message = 'Error deleting property: ' . htmlspecialchars($result['error']);
                    $message_type = 'error';
                }
            } else {
                $message = implode('<br>', $delete_messages);
                $message_type = 'error';
            }
        }
    }
}

// Get all properties for view tab
function getProperties($conn) {
    $sql = "SELECT * FROM Property";
    return $conn->query($sql);
}
?>
