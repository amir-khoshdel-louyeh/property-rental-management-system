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
    <section class="actions-section">
        <h2>Actions</h2>
        <div class="action-buttons">
            <div class="action-card add-action">
                <h3>Add Property Service</h3>
                <p>Link a service to a specific property</p>
                <a href="Add_Propertyservices.php" class="btn btn-primary">Add Service Link</a>
            </div>

            <div class="action-card view-action">
                <h3>View All Property Services</h3>
                <p>Browse all service assignments for properties</p>
                <a href="Show_Propertyservices.php" class="btn btn-info">View Mappings</a>
            </div>

            <div class="action-card delete-action">
                <h3>Delete Property Service</h3>
                <p>Remove a service link from a property</p>
                <a href="Del_Propertyservices.php" class="btn btn-danger">Delete Link</a>
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