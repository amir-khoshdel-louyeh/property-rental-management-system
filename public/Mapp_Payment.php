
$pageTitle = 'Payment';
ob_start();
?>
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
    </section>
<?php
$pageContent = ob_get_clean();
include __DIR__ . "/layouts/PageLayout.php";
