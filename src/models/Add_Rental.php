<?php
    include("Header.html");
    include("Database_Manager.php");
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>new Rental</title>
    </head>
    <body>
        
    <h2>Add a new Rental</h2>
    <h3><< Please complete the form and press on Submit button >></h3><br>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
            <h2>
            property_id : 
            <input type="number" name="property_id">*<br><br>
            renter_id : 
            <input type="number" name="renter_id">*<br><br>
            start date : 
            <input type="date" name="start_date">*<br><br>
            end date : 
            <input type="date" name="end_date">*<br><br>
            monthly rent price : 
            <input type="number" name="monthly_rent">*<br><br>
            security deposit : 
            <input type="number" name="security_deposit">*<br><br>


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
        $renter_id = $_POST['renter_id'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $monthly_rent = $_POST['monthly_rent'];
        $security_deposit = $_POST['security_deposit'];
        
        if ($property_id  != NULL && $renter_id  != NULL && $start_date  != NULL && $end_date  != NULL && $monthly_rent  != NULL && $security_deposit  != NULL)
        {
            $sql = "INSERT INTO Rental (property_id , renter_id , start_date , end_date , monthly_rent , security_deposit ) 
                VALUES ('$property_id', '$renter_id', '$start_date', '$end_date' , '$monthly_rent' , '$security_deposit')";

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