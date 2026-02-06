<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="../public/css/header.css">
    <link rel="stylesheet" href="../public/css/tables.css">
    <link rel="stylesheet" href="../public/css/forms.css">
    <link rel="stylesheet" href="../public/css/utilities.css">
    <link rel="stylesheet" href="../public/css/animations.css">
</head>
<body>
<?php
    include("../views/layouts/Header.html");
?>
    <h1>Delete part: <br><br></h1>
    
    <h2>
        For Delete a Landlord <a href="../models/Del_Landlord.php">click here</a><br><br>
        For Delete a Renter <a href="../models/Del_Renter.php">click here</a><br><br>
        For Delete a Property <a href="../models/Del_Property.php">click here</a><br><br>
        For Delete a Rental <a href="../models/Del_Rental.php">click here</a><br><br>
        For Delete a Payment <a href="../models/Del_Payment.php">click here</a><br><br>
        For Delete a Services <a href="../models/Del_Services.php">click here</a><br><br>
        For Delete a Property Services <a href="../models/Del_Propertyservices.php">click here</a><br><br>
        For Delete a Inspection <a href="../models/Del_Inspection.php">click here</a><br><br>
    </h2>

    <a href="index.php">Go back (to Home page)</a>
</body>
<?php
    include("../views/layouts/Footer.html");
?>
</html>
