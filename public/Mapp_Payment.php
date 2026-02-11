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
    include("Header.html");
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
    <h2>
        For Add a new Payment <a href="Add_Payment.php">click here</a><br><br>
        For Delete a Payment <a href="Del_Payment.php">click here</a><br><br>
        For Showing the list of all Payment <a href="Show_Payment.php">click here</a><br><br><br>

        <a href="index.php">Go back (to Home page)</a>
    
    </h2>
</body>
<?php
    include("Footer.html");
?>
</html>