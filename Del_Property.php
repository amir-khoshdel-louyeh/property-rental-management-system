<?php
    include("Header.html");
    include("Database_Manager.php");
?>


<!DOCTYPE html>
<html>
<head>
<title>Del Property</title>
</head>
<body>

    <h2>Delete Property Records</h2>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
        Enter Property ID to delete: <input type="text" name="Item_ID"><br><br>
        <button type="submit">Delete</button><br>    
        </form>

</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Item_ID = $_POST['Item_ID'];
        if ($Item_ID != NULL) {
            try {
                $stmt = $conn->prepare("DELETE FROM Payment WHERE Property_ID = $Item_ID");
                $stmt->execute();
                echo "Rental deleted successfully from Payment table <br>";
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
                $stmt = $conn->prepare("DELETE FROM Rental WHERE Property_ID = $Item_ID");
                $stmt->execute();
                echo "Property deleted successfully from Rental table <br>";
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
                $stmt = $conn->prepare("DELETE FROM Propertyservices WHERE Property_ID = $Item_ID");
                $stmt->execute();
                echo "Property deleted successfully from Propertyservices table <br>";
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
                $stmt = $conn->prepare("DELETE FROM Inspection WHERE Property_ID = $Item_ID");
                $stmt->execute();
                echo "Property deleted successfully from Inspection table <br>";
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
                $stmt = $conn->prepare("DELETE FROM Property WHERE Property_ID = $Item_ID");
                $stmt->execute();
                echo "Property deleted successfully from Property table <br>";
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