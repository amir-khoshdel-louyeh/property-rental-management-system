<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renter</title>
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
        <h1>Renter Management</h1>
        <p class="subtitle">Manage all renter information and details</p>
    </section>

    <section class="renter-info">
        <h2>Renter Information</h2>
        <p>The Renter Management system allows you to:</p>
        <ul>
            <li>Add new renters with personal and contact information</li>
            <li>View all registered renters in the system</li>
            <li>Delete renter records when needed</li>
        </ul>
    </section>
    <h2>
        For Add a new Renter <a href="Add_Renter.php">click here</a><br><br>
        For Delete a Renter <a href="Del_Renter.php">click here</a><br><br>
        For Showing the list of all Renter <a href="Show_Renter.php">click here</a><br><br><br>

        <a href="index.php">Go back (to Home page)</a>
    
    </h2>
</body>
<?php
    include("Footer.html");
?>
</html>