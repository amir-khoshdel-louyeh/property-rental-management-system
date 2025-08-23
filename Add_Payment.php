<?php
    include("Header.html");
    include("Database_Manager.php");
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>new Payment</title>
    </head>
    <body>
        
    <h2>Add a new Payment</h2>
    <h3><< Please complete the form and press on Submit button >></h3><br>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
            <h2>
                
            rental_id : 
            <input type="number" name="rental_id">*<br><br>
            property_id : 
            <input type="number" name="property_id">*<br><br>
            amount : 
            <input type="number" name="amount">*<br><br>
            payment_date : 
            <input type="date" name="payment_date">*<br><br><br>
            
            
            <button type="submit">Submit</button><br>    
            </h2>
        </form>

        <a href="index.php">Back</a>
        
    </body>
</html>

<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        $rental_id = $_POST['rental_id'];
        $property_id =$_POST['property_id'];
        $amount = $_POST['amount'];
        $payment_date = $_POST['payment_date'];
        

        if ($rental_id  != NULL && $amount  != NULL && $payment_date != NULL && $property_id != NULL)
        {
            $sql = "INSERT INTO Payment (rental_id , property_id , amount , payment_date) 
                VALUES ('$rental_id','$property_id', '$amount', '$payment_date')";

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