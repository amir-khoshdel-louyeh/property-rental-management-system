<?php
    include("../config/Database_Manager.php");
    include("../config/Validation.php");
    include("Header.html");
?>

<!DOCTYPE html>
<html>
<head>
<title>Delete Inspection</title>
</head>
<body>

    <h2>Delete Inspection Records</h2>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
        Enter Inspection ID to delete: <input type="number" name="inspection_id"><br><br>
        <button type="submit">Delete</button><br>    
    </form>

</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inspection_id = $_POST['inspection_id'] ?? 0;
    
    if (!validateNumber($inspection_id)) {
        echo "Invalid Inspection ID! <br>";
    } else {
        $sql = "DELETE FROM Inspection WHERE inspection_id = ?";
        $result = executeQuery($conn, $sql, "i", [intval($inspection_id)]);
        
        if ($result['success']) {
            echo "Inspection deleted successfully<br>";
        } else {
            echo "Error deleting inspection: " . htmlspecialchars($result['error']) . "<br>";
        }
    }
}
?>

<?php
    include("Footer.html");
    mysqli_close($conn);
?>