<?php
    include("../config/Database_Manager.php");
    include("../config/Validation.php");
    include("Header.html");
?>

<!DOCTYPE html>
<html>
<head>
<title>Delete Rental</title>
</head>
<body>

    <h2>Delete Rental Records</h2>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
        Enter Rental ID to delete: <input type="number" name="rental_id"><br><br>
        <button type="submit">Delete</button><br>    
    </form>

</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rental_id = $_POST['rental_id'] ?? 0;
    
    if (!validateNumber($rental_id)) {
        echo "Invalid Rental ID! <br>";
    } else {
        $rental_id = intval($rental_id);
        
        // Delete Payment records first
        $sql = "DELETE FROM Payment WHERE rental_id = ?";
        $result = executeQuery($conn, $sql, "i", [$rental_id]);
        
        if (!$result['success']) {
            echo "Error deleting payments: " . htmlspecialchars($result['error']) . "<br>";
        } else {
            echo "Payment records deleted successfully<br>";
            
            // Then delete Rental
            $sql = "DELETE FROM Rental WHERE rental_id = ?";
            $result = executeQuery($conn, $sql, "i", [$rental_id]);
            
            if ($result['success']) {
                echo "Rental deleted successfully<br>";
            } else {
                echo "Error deleting rental: " . htmlspecialchars($result['error']) . "<br>";
            }
        }
    }
}
?>

<?php
    include("Footer.html");
    mysqli_close($conn);
?>

?>

<?php
    include("footer.html");
    mysqli_close($conn);
?>