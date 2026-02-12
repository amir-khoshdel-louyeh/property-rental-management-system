<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services</title>
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
        <h1>Services Management</h1>
        <p class="subtitle">Manage property-related services and utilities</p>
    </section>

    <section class="services-info">
        <h2>Services Information</h2>
        <p>The Services Management system allows you to:</p>
        <ul>
            <li>Add new services available for properties</li>
            <li>View all services offered in the system</li>
            <li>Delete service records when needed</li>
        </ul>
    </section>
    <h2>
        For Add a new Services <a href="Add_Services.php">click here</a><br><br>
        For Delete a Services <a href="Del_Services.php">click here</a><br><br>
        For Showing the list of all Services <a href="Show_Services.php">click here</a><br><br><br>

        <a href="index.php">Go back (to Home page)</a>
    
    </h2>
</body>
<?php
    include("Footer.html");
?>
</html>