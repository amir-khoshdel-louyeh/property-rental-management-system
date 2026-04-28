<?php
require_once '../config/guards/landlord_guard.php';
$pageTitle = 'Renter';
ob_start();
?>
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
                <a href="Renter.php" class="btn btn-primary">Manage Renters</a>
            </div>

            <div class="action-card view-action">
                <h3>View All Renters</h3>
                <p>Browse the complete list of all registered renters</p>
                <a href="Renter.php" class="btn btn-info">Manage Renters</a>
            </div>

            <div class="action-card delete-action">
                <h3>Delete Renter</h3>
                <p>Remove a renter record from the system</p>
                <a href="Renter.php" class="btn btn-danger">Manage Renters</a>
            </div>
        </div>
    </section>

    <section class="navigation">
        <a href="index.php" class="btn btn-secondary">← Back to Home</a>
    </section>
<?php
$pageContent = ob_get_clean();
include __DIR__ . "/layouts/PageLayout.php";
