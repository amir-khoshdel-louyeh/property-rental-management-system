<?php
    include("Header.html");
    include("Database_Manager.php");
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
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $phone_number = $_POST['phone_number'];
        $email = $_POST['email'];
        $date_of_birth = $_POST['date_of_birth'];

        
        if ($first_name  != NULL && $last_name  != NULL)
        {
            $sql = "INSERT INTO Renter (first_name , last_name , phone_number , email , date_of_birth) 
                VALUES ('$first_name', '$last_name', '$phone_number', '$email' , '$date_of_birth')";

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