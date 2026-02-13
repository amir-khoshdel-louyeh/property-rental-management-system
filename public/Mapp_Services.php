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
    <section class="actions-section">
        <h2>Actions</h2>
        <div class="action-buttons">
            <div class="action-card add-action">
                <h3>Add New Service</h3>
                <p>Create a new service offering for properties</p>
                <a href="Add_Services.php" class="btn btn-primary">Add Service</a>
            </div>

            <div class="action-card view-action">
                <h3>View All Services</h3>
                <p>Browse the complete list of all available services</p>
                <a href="Show_Services.php" class="btn btn-info">View Services</a>
            </div>

            <div class="action-card delete-action">
                <h3>Delete Service</h3>
                <p>Remove a service record from the system</p>
                <a href="Del_Services.php" class="btn btn-danger">Delete Service</a>
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