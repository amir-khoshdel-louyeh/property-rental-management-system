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
    include("layouts/Header.html");
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
    <section class="actions-section">
        <h2>Actions</h2>
        <div class="action-buttons">
            <div class="action-card add-action">
                <h3>Add New Property</h3>
                <p>Register a new property with all relevant details</p>
                <a href="Property.php" class="btn btn-primary">Manage Properties</a>
            </div>

            <div class="action-card view-action">
                <h3>View All Properties</h3>
                <p>Browse the complete list of all properties</p>
                <a href="Property.php" class="btn btn-info">Manage Properties</a>
            </div>

            <div class="action-card delete-action">
                <h3>Delete Property</h3>
                <p>Remove a property record from the system</p>
                <a href="Property.php" class="btn btn-danger">Manage Properties</a>
            </div>
        </div>
    </section>

    <section class="navigation">
        <a href="index.php" class="btn btn-secondary">← Back to Home</a>
    </section></main>
</body>
<?php
    include("layouts/Footer.html");
?>
</html>