<?php
    include("../config/Database_Manager.php");
    include("../config/Validation.php");
    include("Header.html");
?>

<!DOCTYPE html>
<html>
<head>
<title>Delete Renter</title>
</head>
<body>

    <h2>Delete Renter Records</h2>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
        Enter Renter ID to delete: <input type="number" name="renter_id"><br><br>
        <button type="submit">Delete</button><br>    
    </form>

</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $renter_id = $_POST['renter_id'] ?? 0;
    
    if (!validateNumber($renter_id)) {
        echo "Invalid Renter ID! <br>";
    } else {
        $renter_id = intval($renter_id);
        
        // Delete Rental records first
        $sql = "DELETE FROM Rental WHERE renter_id = ?";
        $result = executeQuery($conn, $sql, "i", [$renter_id]);
        
        if (!$result['success']) {
            echo "Error deleting rentals: " . htmlspecialchars($result['error']) . "<br>";
        } else {
            echo "Rental records deleted successfully<br>";
            
            // Then delete Renter
            $sql = "DELETE FROM Renter WHERE renter_id = ?";
            $result = executeQuery($conn, $sql, "i", [$renter_id]);
            
            if ($result['success']) {
                echo "Renter deleted successfully<br>";
            } else {
                echo "Error deleting renter: " . htmlspecialchars($result['error']) . "<br>";
            }
        }
    }
}
?>

<?php
    include("Footer.html");
    mysqli_close($conn);
?>