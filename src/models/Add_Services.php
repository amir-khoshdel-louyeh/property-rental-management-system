<?php
    include("../../config/Database_Manager.php");
    include("../../config/Validation.php");
    include("../../src/views/layouts/Header.html");
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>new Services</title>
    </head>
    <body>
        
    <h2>Add a new Services</h2>
    <h3><< Please complete the form and press on Submit button >></h3><br>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
            <h2>
            service name  : 
            <input type="text" name="services_name">*<br><br>
            
            <button type="submit">Submit</button><br>    

            </h2>
        </form>

        <a href="index.php">Back</a>
    </body>
</html>

<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        $services_name = sanitizeText($_POST['services_name'] ?? '');

        if (empty($services_name)) {
            echo "Please Enter ALL necessary information! <br>";
        } else {
            $sql = "INSERT INTO services (services_name) 
                    VALUES (?)";
            
            $result = executeQuery($conn, $sql, "s", [$services_name]);
            
            if ($result['success']) {
                echo "Successful";
            } else {
                echo "Try again! " . htmlspecialchars($result['error']); 
            }
        }
    }

?>


<?php
    include("../../src/views/layouts/Footer.html");
    mysqli_close($conn);
?>


