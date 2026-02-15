<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspection</title>
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
        <h1>Inspection Management</h1>
        <p class="subtitle">Track and manage property inspections</p>
    </section>

    <section class="inspection-info">
        <h2>Inspection Information</h2>
        <p>The Inspection Management system allows you to:</p>
        <ul>
            <li>Record new property inspections with findings and details</li>
            <li>View all inspection reports and history</li>
            <li>Delete inspection records when needed</li>
        </ul>
    </section>
    <h2>
        For Add a new Inspection <a href="Add_Inspection.php">click here</a><br><br>
        For Delete a Inspection <a href="Del_Inspection.php">click here</a><br><br>
        For Showing the list of all Inspections <a href="Show_Inspection.php">click here</a><br><br><br>

        <a href="index.php">Go back (to Home page)</a>
    
    </h2>
</body>
<?php
    include("Footer.html");
?>
</html>