<?php
    include("Header.html");
    include("Database_Manager.php");
?>


<!DOCTYPE html>
<html>
<head>
<title>Del Inspection</title>
</head>
<body>

    <h2>Delete Inspection Records</h2>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
        Enter Inspection ID to delete: <input type="text" name="Item_ID"><br><br>
        <button type="submit">Delete</button><br>    
        </form>

</body>
</html>



<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Item_ID = $_POST['Item_ID'];
        if ($Item_ID != NULL) {
            try {
                $stmt = $conn->prepare("DELETE FROM Inspection WHERE Inspection_ID = $Item_ID");
                $stmt->execute();
                echo "Inspection deleted successfully from Inspection table <br>";
            } catch(PDOException $e) {
                echo "Error deleting record: " . $e->getMessage();
            }


        } else {
            echo "Fill the form.";
        }
    }

?>

<?php
    include("footer.html");
    mysqli_close($conn);
?>