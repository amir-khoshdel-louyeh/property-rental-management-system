<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
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
        <h1>Payment Management</h1>
        <p class="subtitle">Track and manage all rental payments</p>
    </section>

    <section class="payment-info">
        <h2>Payment Information</h2>
        <p>The Payment Management system allows you to:</p>
        <ul>
            <li>Record new rental payments with date and amount</li>
            <li>View all payment history and transactions</li>
            <li>Delete payment records when needed</li>
        </ul>
    </section>
    <section class="actions-section">
        <h2>Actions</h2>
        <div class="action-buttons">
            <div class="action-card add-action">
                <h3>Record New Payment</h3>
                <p>Add a new payment transaction to the system</p>
                <a href="Payment.php" class="btn btn-primary">Manage Payments</a>
            </div>

            <div class="action-card view-action">
                <h3>View All Payments</h3>
                <p>Browse the complete payment history and transactions</p>
                <a href="Payment.php" class="btn btn-info">Manage Payments</a>
            </div>

            <div class="action-card delete-action">
                <h3>Delete Payment</h3>
                <p>Remove a payment record from the system</p>
                <a href="Payment.php" class="btn btn-danger">Manage Payments</a>
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