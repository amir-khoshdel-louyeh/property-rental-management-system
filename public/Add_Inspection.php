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
        $property_id = $_POST['property_id'] ?? 0;
        $inspection_date = sanitizeText($_POST['inspection_date'] ?? '');
        $findings = sanitizeText($_POST['findings'] ?? '');
        $conducted_by = $_POST['conducted_by'] ?? 0;

        if (!validateNumber($property_id) || !validateNumber($conducted_by)) {
            echo "Invalid numeric input! <br>";
        } else if (empty($inspection_date) || !validateDate($inspection_date)) {
            echo "Invalid inspection date (use YYYY-MM-DD)! <br>";
        } else if (empty($findings)) {
            echo "Please Enter ALL necessary information! <br>";
        } else {
            $sql = "INSERT INTO Inspection (property_id, inspection_date, findings, conducted_by) 
                    VALUES (?, ?, ?, ?)";
            
            $result = executeQuery($conn, $sql, "issi", 
                [intval($property_id), $inspection_date, $findings, intval($conducted_by)]);
            
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