<?php
    include("../config/Database_Manager.php");
    include("../config/Validation.php");
    include("Header.html");
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>new Property services</title>
    </head>
    <body>
        
    <h2>Add a new Property services</h2>
    <h3><< Please complete the form and press on Submit button >></h3><br>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
            <h2>
            
            property_id  : 
            <input type="number" name="property_id">*<br><br>
            service_id  : 
            <input type="number" name="service_id">*<br><br>
            
            
            <button type="submit">Submit</button><br>    
            </h2>
        </form>

        <a href="index.php">Back</a>
    </body>
</html>

<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        $property_id = $_POST['property_id'] ?? 0;
        $service_id = $_POST['service_id'] ?? 0;

        if (!validateNumber($property_id) || !validateNumber($service_id)) {
            echo "Invalid numeric input! <br>";
        } else {
            $sql = "INSERT INTO PropertyServices (property_id, service_id) 
                    VALUES (?, ?)";
            
            $result = executeQuery($conn, $sql, "ii", 
                [intval($property_id), intval($service_id)]);
            
            if ($result['success']) {
                echo "Successful";
            } else {
                echo "Try again! " . htmlspecialchars($result['error']); 
            }
        }
    }
?>

<?php
    include("Footer.html");
    mysqli_close($conn);
?>