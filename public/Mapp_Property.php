<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property</title>
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
        <h1>Property Management</h1>
        <p class="subtitle">Manage all properties and their details</p>
    </section>

    <section class="property-info">
        <h2>Property Information</h2>
        <p>The Property Management system allows you to:</p>
        <ul>
            <li>Add new properties with location, type, and pricing details</li>
            <li>View all properties in your portfolio</li>
            <li>Delete property records from the system</li>
        </ul>
    </section>
    <h2>
        For Add a new Property <a href="Add_Property.php">click here</a><br><br>
        For Delete a Property <a href="Del_Property.php">click here</a><br><br>
        For Showing the list of all Property <a href="Show_Property.php">click here</a><br><br><br>

        <a href="index.php">Go back (to Home page)</a>
    
    </h2>
</body>
<?php
    include("Footer.html");
?>
</html>