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
    <section class="actions-section">
        <h2>Actions</h2>
        <div class="action-buttons">
            <div class="action-card add-action">
                <h3>Create New Rental</h3>
                <p>Set up a new rental agreement with all rental details</p>
                <a href="Add_Rental.php" class="btn btn-primary">Add Rental</a>
            </div>

            <div class="action-card view-action">
                <h3>View All Rentals</h3>
                <p>Browse the complete list of all rental agreements</p>
                <a href="Show_Rental.php" class="btn btn-info">View Rentals</a>
            </div>

            <div class="action-card delete-action">
                <h3>Delete Rental</h3>
                <p>Remove a rental agreement from the system</p>
                <a href="Del_Rental.php" class="btn btn-danger">Delete Rental</a>
            </div>
        </div>
    </section>

    <section class="navigation">
        <a href="index.php" class="btn btn-secondary">← Back to Home</a>
    </section></main>
</body>
<?php
    include("Footer.html");
?>
</html>