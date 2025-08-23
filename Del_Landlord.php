<?php
    include("Header.html");
    include("Database_Manager.php");
?>


<!DOCTYPE html>
<html>
<head>
<title>Del LandLord</title>
</head>
<body>

    <h2>Delete LandLord Records</h2>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
        Enter LandLord ID to delete: <input type="text" name="Item_ID"><br><br>
        <button type="submit">Delete</button><br>    
        </form>

</body>
</html>


<?php
/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Item_ID = $_POST['Item_ID'];
        if ($Item_ID != NULL) {
            try {
                $stmt = $conn->prepare("DELETE FROM Payment WHERE Landlord_ID = $Item_ID");
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
                $stmt = $conn->prepare("DELETE FROM Rental WHERE Landlord_ID = $Item_ID");
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
                $stmt = $conn->prepare("DELETE FROM PropertyServices WHERE Landlord_ID = $Item_ID");
                $stmt->execute();
                echo "Property deleted successfully from Property Services table <br>";
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
                $stmt = $conn->prepare("DELETE FROM Inspection WHERE Landlord_ID = $Item_ID");
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
                $stmt = $conn->prepare("DELETE FROM Property WHERE Landlord_ID = $Item_ID");
                $stmt->execute();
                echo "Landlord deleted successfully from Property table <br>";
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
                    $stmt = $conn->prepare("DELETE FROM LandLord WHERE Landlord_ID = $Item_ID");
                    $stmt->execute();
                    echo "LandLord deleted successfully from LandLord table <br>";
                } catch(PDOException $e) {
                    echo "Error deleting record: " . $e->getMessage();
                }

            } else {
                echo "Fill the form.";
            }
        }
*/
?>



<?php
    include("footer.html");
    mysqli_close($conn);
?>