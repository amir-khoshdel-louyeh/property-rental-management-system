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
        $rental_id = $_POST['rental_id'] ?? 0;
        $property_id = $_POST['property_id'] ?? 0;
        $amount = $_POST['amount'] ?? 0;
        $payment_date = sanitizeText($_POST['payment_date'] ?? '');

        if (!validateNumber($rental_id) || !validateNumber($property_id) || !validateNumber($amount)) {
            echo "Invalid numeric input! <br>";
        } else if (empty($payment_date) || !validateDate($payment_date)) {
            echo "Please enter a valid payment date (YYYY-MM-DD)! <br>";
        } else {
            $sql = "INSERT INTO Payment (rental_id, property_id, amount, payment_date) 
                    VALUES (?, ?, ?, ?)";
            
            $result = executeQuery($conn, $sql, "iiis", 
                [intval($rental_id), intval($property_id), floatval($amount), $payment_date]);
            
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