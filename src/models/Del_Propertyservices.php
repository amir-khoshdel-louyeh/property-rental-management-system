<?php
    include("../../config/Database_Manager.php");
    include("../../config/Validation.php");
    include("../../src/views/layouts/Header.html");
?>

<!DOCTYPE html>
<html>
<head>
<title>Delete PropertyServices</title>
</head>
<body>

    <h2>Delete Property Services Records</h2>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
        Enter PropertyService ID to delete: <input type="number" name="propertyservice_id"><br><br>
        <button type="submit">Delete</button><br>    
    </form>

</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $propertyservice_id = $_POST['propertyservice_id'] ?? 0;
    
    if (!validateNumber($propertyservice_id)) {
        echo "Invalid PropertyService ID! <br>";
    } else {
        $sql = "DELETE FROM PropertyServices WHERE property_service_id = ?";
        $result = executeQuery($conn, $sql, "i", [intval($propertyservice_id)]);
        
        if ($result['success']) {
            echo "Property service deleted successfully<br>";
        } else {
            echo "Error deleting property service: " . htmlspecialchars($result['error']) . "<br>";
        }
    }
}
?>

<?php
    include("../../src/views/layouts/Footer.html");
    mysqli_close($conn);
?>