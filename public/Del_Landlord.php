<?php
    include("../config/Database_Manager.php");
    include("../config/Validation.php");
    include("Header.html");
?>

<!DOCTYPE html>
<html>
<head>
<title>Delete Landlord</title>
</head>
<body>

    <h2>Delete Landlord Records</h2>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
        Enter Landlord ID to delete: <input type="number" name="landlord_id"><br><br>
        <button type="submit">Delete</button><br>    
    </form>

</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $landlord_id = $_POST['landlord_id'] ?? 0;
    
    if (!validateNumber($landlord_id)) {
        echo "Invalid Landlord ID! <br>";
    } else {
        $landlord_id = intval($landlord_id);
        
        // Delete related records first (referential integrity)
        $tables_to_delete = [
            ['table' => 'Inspection', 'column' => 'conducted_by'],
            ['table' => 'Property', 'column' => 'landlord_id']
        ];
        $all_success = true;
        
        foreach ($tables_to_delete as $delete_info) {
            $sql = "DELETE FROM " . $delete_info['table'] . " WHERE " . $delete_info['column'] . " = ?";
            $result = executeQuery($conn, $sql, "i", [$landlord_id]);
            
            if (!$result['success']) {
                echo "Error deleting from " . htmlspecialchars($delete_info['table']) . ": " . htmlspecialchars($result['error']) . "<br>";
                $all_success = false;
            } else {
                echo htmlspecialchars($delete_info['table']) . " records deleted successfully<br>";
            }
        }
        
        // Finally delete from Landlord table
        if ($all_success) {
            $sql = "DELETE FROM Landlord WHERE landlord_id = ?";
            $result = executeQuery($conn, $sql, "i", [$landlord_id]);
            
            if ($result['success']) {
                echo "Landlord deleted successfully from Landlord table<br>";
            } else {
                echo "Error deleting landlord: " . htmlspecialchars($result['error']) . "<br>";
            }
        }
    }
}
?>

<?php
    include("Footer.html");
    mysqli_close($conn);
?>