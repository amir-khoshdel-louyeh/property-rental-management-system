<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property services</title>
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
        <h1>Property Services Management</h1>
        <p class="subtitle">Link and manage services for properties</p>
    </section>

    <section class="propertyservices-info">
        <h2>Property Services Information</h2>
        <p>The Property Services Management system allows you to:</p>
        <ul>
            <li>Map and assign services to specific properties</li>
            <li>View all service assignments for properties</li>
            <li>Delete property service links when needed</li>
        </ul>
    </section>
    <h2>
        For Add a new Property services <a href="Add_Propertyservices.php">click here</a><br><br>
        For Delete a Property services <a href="Del_Propertyservices.php">click here</a><br><br>
        For Showing the list of all Property services <a href="Show_Propertyservices.php">click here</a><br><br><br>

        <a href="index.php">Go back (to Home page)</a>
    
    </h2>
</body>
<?php
    include("Footer.html");
?>
</html>