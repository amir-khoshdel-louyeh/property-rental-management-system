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
        $property_id = $_POST['property_id'] ?? 0;
        $renter_id = $_POST['renter_id'] ?? 0;
        $start_date = sanitizeText($_POST['start_date'] ?? '');
        $end_date = sanitizeText($_POST['end_date'] ?? '');
        $monthly_rent = $_POST['monthly_rent'] ?? 0;
        $security_deposit = $_POST['security_deposit'] ?? 0;
        
        if (!validateNumber($property_id) || !validateNumber($renter_id) || !validateNumber($monthly_rent) || !validateNumber($security_deposit)) {
            echo "Invalid numeric input! <br>";
        } else if (empty($start_date) || !validateDate($start_date)) {
            echo "Invalid start date (use YYYY-MM-DD)! <br>";
        } else if (empty($end_date) || !validateDate($end_date)) {
            echo "Invalid end date (use YYYY-MM-DD)! <br>";
        } else if ($start_date >= $end_date) {
            echo "Start date must be before end date! <br>";
        } else {
            $sql = "INSERT INTO Rental (property_id, renter_id, start_date, end_date, monthly_rent, security_deposit) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $result = executeQuery($conn, $sql, "iisss", 
                [intval($property_id), intval($renter_id), $start_date, $end_date, floatval($monthly_rent), floatval($security_deposit)]);
            
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