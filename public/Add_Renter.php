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
        <title>new Renter</title>
    </head>
    <body>
        
    <h2>Add a new Renter</h2>
    <h3><< Please complete the form and press on Submit button >></h3><br>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
            <h2>
            first name : 
            <input type="text" name="first_name">*<br><br>
            last name : 
            <input type="text" name="last_name">*<br><br>
            phone number : 
            <input type="number" name="phone_number"><br><br>
            email : 
            <input type="email" name="email"><br><br>
            date of birth : 
            <input type="date" name="date_of_birth"><br><br>

            <button type="submit">Submit</button><br>    
            </h2>
        </form>

        <a href="index.php">Back</a>
    </body>
</html>

<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        $first_name = sanitizeText($_POST['first_name'] ?? '');
        $last_name = sanitizeText($_POST['last_name'] ?? '');
        $phone_number = sanitizeText($_POST['phone_number'] ?? '');
        $email = sanitizeText($_POST['email'] ?? '');
        $date_of_birth = sanitizeText($_POST['date_of_birth'] ?? '');

        if (empty($first_name) || empty($last_name)) {
            echo "Please Enter ALL necessary information! <br>";
        } else if (!empty($email) && !validateEmail($email)) {
            echo "Invalid email format! <br>";
        } else if (!empty($phone_number) && !validatePhone($phone_number)) {
            echo "Invalid phone number format! <br>";
        } else if (!empty($date_of_birth) && !validateDate($date_of_birth)) {
            echo "Invalid date format (use YYYY-MM-DD)! <br>";
        } else {
            $sql = "INSERT INTO Renter (first_name, last_name, phone_number, email, date_of_birth) 
                    VALUES (?, ?, ?, ?, ?)";
            
            $result = executeQuery($conn, $sql, "sssss", 
                [$first_name, $last_name, $phone_number, $email, $date_of_birth]);
            
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