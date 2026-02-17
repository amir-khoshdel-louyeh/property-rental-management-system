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
    <section class="actions-section">
        <h2>Actions</h2>
        <div class="action-buttons">
            <div class="action-card add-action">
                <h3>Add New Landlord</h3>
                <p>Create a new landlord record with contact information</p>
                <a href="Landlord.php" class="btn btn-primary">Manage Landlords</a>
            </div>

            <div class="action-card view-action">
                <h3>View All Landlords</h3>
                <p>Browse the complete list of all registered landlords</p>
                <a href="Landlord.php" class="btn btn-info">Manage Landlords</a>
            </div>

            <div class="action-card delete-action">
                <h3>Delete Landlord</h3>
                <p>Remove a landlord record from the system</p>
                <a href="Landlord.php" class="btn btn-danger">Manage Landlords</a>
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