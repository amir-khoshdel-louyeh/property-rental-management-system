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
    <section class="actions-section">
        <h2>Actions</h2>
        <div class="action-buttons">
            <div class="action-card add-action">
                <h3>Add New Inspection</h3>
                <p>Record a new property inspection with findings</p>
                <a href="Add_Inspection.php" class="btn btn-primary">Add Inspection</a>
            </div>

            <div class="action-card view-action">
                <h3>View All Inspections</h3>
                <p>Browse all inspection reports and history</p>
                <a href="Show_Inspection.php" class="btn btn-info">View Inspections</a>
            </div>

            <div class="action-card delete-action">
                <h3>Delete Inspection</h3>
                <p>Remove an inspection record from the system</p>
                <a href="Del_Inspection.php" class="btn btn-danger">Delete Inspection</a>
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