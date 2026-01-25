<?php
    include("../../config/Database_Manager.php");
    include("../../config/Validation.php");
    include("../../src/views/layouts/Header.html");
?>

<!DOCTYPE html>
<html>
<head>
<title>Delete Property</title>
</head>
<body>

    <h2>Delete Property Records</h2>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
        Enter Property ID to delete: <input type="number" name="property_id"><br><br>
        <button type="submit">Delete</button><br>    
    </form>

</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $property_id = $_POST['property_id'] ?? 0;
    
    if (!validateNumber($property_id)) {
        echo "Invalid Property ID! <br>";
    } else {
        $property_id = intval($property_id);
        
        // Delete from related tables first (referential integrity)
        $tables_to_delete = ['Payment', 'Rental', 'PropertyServices', 'Inspection'];
        $all_success = true;
        
        foreach ($tables_to_delete as $table) {
            $sql = "DELETE FROM " . $table . " WHERE property_id = ?";
            $result = executeQuery($conn, $sql, "i", [$property_id]);
            
            if (!$result['success']) {
                echo "Error deleting from " . htmlspecialchars($table) . ": " . htmlspecialchars($result['error']) . "<br>";
                $all_success = false;
            } else {
                echo htmlspecialchars($table) . " records deleted successfully<br>";
            }
        }
        
        // Finally delete from Property table
        if ($all_success) {
            $sql = "DELETE FROM Property WHERE property_id = ?";
            $result = executeQuery($conn, $sql, "i", [$property_id]);
            
            if ($result['success']) {
                echo "Property deleted successfully from Property table<br>";
            } else {
                echo "Error deleting property: " . htmlspecialchars($result['error']) . "<br>";
            }
        }
    }
}
?>

<?php
    include("../../src/views/layouts/Footer.html");
    mysqli_close($conn);
?>