<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
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
        <h1>Property & Rental Management System</h1>
        <p class="subtitle">Comprehensive solution for managing properties, rentals, landlords, renters, and more</p>
    </section>

    <section class="home-intro">
        <h2>Welcome to the System</h2>
        <p>This system provides a complete platform for managing all aspects of your property rental business. Navigate through the different sections using the menu above or the action cards below to get started.</p>
    </section>
    <section class="actions-section">
        <h2>Quick Actions</h2>
        <div class="action-buttons">
            <div class="action-card add-action">
                <h3>Add New Data</h3>
                <p>Create new records for properties, landlords, renters, and more</p>
                <a href="Path_Insert.php" class="btn btn-primary">Go to Insert</a>
            </div>

            <div class="action-card view-action">
                <h3>View Database</h3>
                <p>Browse and review all existing records in the system</p>
                <a href="Path_View.php" class="btn btn-info">Go to View</a>
            </div>

            <div class="action-card delete-action">
                <h3>Delete Records</h3>
                <p>Remove outdated or unwanted records from the system</p>
                <a href="Path_Delete.php" class="btn btn-danger">Go to Delete</a>
            </div>
        </div>
    </section>
</main>


</body>
<?php
    include("Footer.html");
?>
</html>