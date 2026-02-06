<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landlord</title>
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
        <h1>Landlord Management</h1>
        <p class="subtitle">Manage all landlord information and details</p>
    </section>

    <section class="landlord-info">
        <h2>Landlord Information</h2>
        <p>The Landlord Management system allows you to:</p>
        <ul>
            <li>Add new landlords with contact information</li>
            <li>View all registered landlords in the system</li>
            <li>Delete landlord records when needed</li>
        </ul>
    </section>
    <h2>
        For Add a new Landlord <a href="Add_Landlord.php">click here</a><br><br>
        For Delete a Landlord <a href="Del_Landlord.php">click here</a><br><br>
        For Showing the list of all Landlord <a href="Show_Landlord.php">click here</a><br><br><br>

        <a href="index.php">Go back (to Home page)</a>
    
    </h2>
</body>
<?php
    include("Footer.html");
?>
</html>