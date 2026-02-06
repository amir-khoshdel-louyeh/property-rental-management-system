<?php
    include("../config/Database_Manager.php");
    include("../config/Validation.php");
    include("Header.html");
?>

<!DOCTYPE html>
<html>
<head>
<title>Delete Services</title>
</head>
<body>

    <h2>Delete Services Records</h2>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
        Enter Service ID to delete: <input type="number" name="service_id"><br><br>
        <button type="submit">Delete</button><br>    
    </form>

</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_id = $_POST['service_id'] ?? 0;
    
    if (!validateNumber($service_id)) {
        echo "Invalid Service ID! <br>";
    } else {
        $service_id = intval($service_id);
        
        // Delete PropertyServices records first
        $sql = "DELETE FROM PropertyServices WHERE service_id = ?";
        $result = executeQuery($conn, $sql, "i", [$service_id]);
        
        if (!$result['success']) {
            echo "Error deleting property services: " . htmlspecialchars($result['error']) . "<br>";
        } else {
            echo "Property services records deleted successfully<br>";
            
            // Then delete Service
            $sql = "DELETE FROM services WHERE service_id = ?";
            $result = executeQuery($conn, $sql, "i", [$service_id]);
            
            if ($result['success']) {
                echo "Service deleted successfully<br>";
            } else {
                echo "Error deleting service: " . htmlspecialchars($result['error']) . "<br>";
            }
        }
    }
}
?>

<?php
    include("Footer.html");
    mysqli_close($conn);
?>;
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