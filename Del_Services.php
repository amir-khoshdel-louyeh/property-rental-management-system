<?php
    include("Header.html");
    include("Database_Manager.php");
?>


<!DOCTYPE html>
<html>
<head>
<title>Del Services</title>
</head>
<body>

    <h2>Delete Services Records</h2>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
        Enter Services ID to delete: <input type="text" name="Item_ID"><br><br>
        <button type="submit">Delete</button><br>    
        </form>

</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Item_ID = $_POST['Item_ID'];
        if ($Item_ID != NULL) {
            try {
                $stmt = $conn->prepare("DELETE FROM Propertyservices WHERE Service_ID = $Item_ID");
                $stmt->execute();
                echo "Service deleted successfully from Property_services table <br>";
            } catch(PDOException $e) {
                echo "Error deleting record: " . $e->getMessage();
            }


        } else {
            echo "Fill the form.";
        }
    }

?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Item_ID = $_POST['Item_ID'];
        if ($Item_ID != NULL) {
            try {
                $stmt = $conn->prepare("DELETE FROM Services WHERE Service_ID = $Item_ID");
                $stmt->execute();
                echo "Service deleted successfully from Services table <br>";
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