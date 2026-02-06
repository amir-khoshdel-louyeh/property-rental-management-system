<?php
    include("../config/Database_Manager.php");
    include("../config/Validation.php");
    include("Header.html");
?>

<!DOCTYPE html>
<html>
<head>
<title>Delete Payment</title>
</head>
<body>

    <h2>Delete Payment Records</h2>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
        Enter Payment ID to delete: <input type="number" name="payment_id"><br><br>
        <button type="submit">Delete</button><br>    
    </form>

</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_id = $_POST['payment_id'] ?? 0;
    
    if (!validateNumber($payment_id)) {
        echo "Invalid Payment ID! <br>";
    } else {
        $sql = "DELETE FROM Payment WHERE payment_id = ?";
        $result = executeQuery($conn, $sql, "i", [intval($payment_id)]);
        
        if ($result['success']) {
            echo "Payment deleted successfully<br>";
        } else {
            echo "Error deleting payment: " . htmlspecialchars($result['error']) . "<br>";
        }
    }
}
?>

<?php
    include("Footer.html");
    mysqli_close($conn);
?>