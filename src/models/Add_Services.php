<?php
    include("Header.html");
    include("Database_Manager.php");
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
        $services_name  = $_POST['services_name'];
        

        
        if ($services_name != NULL)
        {
            $sql = "INSERT INTO services (services_name) 
                VALUES ('$services_name')";

            try {
                mysqli_query($conn, $sql);
                echo "Successful";
            } 
            catch (mysqli_sql_exception $e) 
            {
                echo "Try again! " . $e->getMessage(); 
            }
        }
        else
        {
            echo "Please Enter ALL nessesery informations ! <br>";
        }
        
    }

?>


<?php
    include("Footer.html");
    mysqli_close($conn);
?>


