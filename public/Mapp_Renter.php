<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renter</title>
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
        <h1>Renter Management</h1>
        <p class="subtitle">Manage all renter information and details</p>
    </section>

    <section class="renter-info">
        <h2>Renter Information</h2>
        <p>The Renter Management system allows you to:</p>
        <ul>
            <li>Add new renters with personal and contact information</li>
            <li>View all registered renters in the system</li>
            <li>Delete renter records when needed</li>
        </ul>
    </section>
    <section class="actions-section">
        <h2>Actions</h2>
        <div class="action-buttons">
            <div class="action-card add-action">
                <h3>Add New Renter</h3>
                <p>Register a new renter with personal information</p>
                <a href="Add_Renter.php" class="btn btn-primary">Add Renter</a>
            </div>

            <div class="action-card view-action">
                <h3>View All Renters</h3>
                <p>Browse the complete list of all registered renters</p>
                <a href="Show_Renter.php" class="btn btn-info">View Renters</a>
            </div>

            <div class="action-card delete-action">
                <h3>Delete Renter</h3>
                <p>Remove a renter record from the system</p>
                <a href="Del_Renter.php" class="btn btn-danger">Delete Renter</a>
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