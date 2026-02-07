<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/tables.css">
    <link rel="stylesheet" href="css/forms.css">
    <link rel="stylesheet" href="css/utilities.css">
    <link rel="stylesheet" href="css/animations.css">
</head>
<body>
<?php
    include("Header.html");
?>
<main class="container">
    <section class="page-header">
        <h1>Rental Management</h1>
        <p class="subtitle">Manage all rental agreements and details</p>
    </section>

    <section class="rental-info">
        <h2>Rental Information</h2>
        <p>The Rental Management system allows you to:</p>
        <ul>
            <li>Create new rental agreements with start/end dates and terms</li>
            <li>View all active and past rentals in the system</li>
            <li>Delete rental records when agreements end</li>
        </ul>
    </section>
    <h2>
        For Add a new Rental <a href="Add_Rental.php">click here</a><br><br>
        For Delete a Rental <a href="Del_Rental.php">click here</a><br><br>
        For Showing the list of all Rental <a href="Show_Rental.php">click here</a><br><br><br>

        <a href="index.php">Go back (to Home page)</a>
    
    </h2>
</body>
<?php
    include("Footer.html");
?>
</html>