<?php
    include("Header.html");
    include("Database_Manager.php");
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>new Inspection</title>
    </head>
    <body>
        
    <h2>Add a new Inspection</h2>
    <h3><< Please complete the form and press on Submit button >></h3><br>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
            <h2>
            property_id : 
            <input type="text" name="property_id">*<br><br>
            inspection date : 
            <input type="date" name="inspection_date">*<br><br>
            findings : 
            <input type="text" name="findings">*<br><br>
            conducted by (Landlord_id): 
            <input type="number" name="conducted_by">*<br><br>
            
            <button type="submit">Submit</button><br>    
            </h2>
        </form>
        <a href="index.php">Back</a>

    </body>
</html>

<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        $property_id = $_POST['property_id'];
        $inspection_date = $_POST['inspection_date'];
        $findings = $_POST['findings'];
        $conducted_by = $_POST['conducted_by'];

        
        if ($property_id  != NULL && $inspection_date  != NULL && $findings  != NULL && $conducted_by  != NULL)
        {
            $sql = "INSERT INTO Inspection (property_id , inspection_date , findings , conducted_by) 
                VALUES ('$property_id', '$inspection_date', '$findings', '$conducted_by')";

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