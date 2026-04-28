<?php
require_once '../config/guards/landlord_guard.php';
$pageTitle = 'Rental';
ob_start();
?>
<section class="page-header">
        <h1>Rental Management</h1>
        <p class="subtitle">Manage all rental agreements and details</p>
    </section>

    <section class="rental-info">
        <h2>Rental Information</h2>
        <p>The Rental Management system allows you to:</p>
        <ul>
            <li>Create new rental agreements with start/end dates and terms</li>
            <li>View all active and past rentals in the system</li>
            <li>Delete rental records when agreements end</li>
        </ul>
    </section>
    <section class="actions-section">
        <h2>Actions</h2>
        <div class="action-buttons">
            <div class="action-card add-action">
                <h3>Create New Rental</h3>
                <p>Set up a new rental agreement with all rental details</p>
                <a href="Rental.php" class="btn btn-primary">Manage Rentals</a>
            </div>

            <div class="action-card view-action">
                <h3>View All Rentals</h3>
                <p>Browse the complete list of all rental agreements</p>
                <a href="Rental.php" class="btn btn-info">Manage Rentals</a>
            </div>

            <div class="action-card delete-action">
                <h3>Delete Rental</h3>
                <p>Remove a rental agreement from the system</p>
                <a href="Rental.php" class="btn btn-danger">Manage Rentals</a>
            </div>
        </div>
    </section>

    <section class="navigation">
        <a href="index.php" class="btn btn-secondary">← Back to Home</a>
    </section>
<?php
$pageContent = ob_get_clean();
include __DIR__ . "/layouts/PageLayout.php";
