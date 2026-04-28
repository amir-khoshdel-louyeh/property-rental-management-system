<?php
require_once '../config/guards/landlord_guard.php';
$pageTitle = 'Property services';
ob_start();
?>
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
                <a href="Propertyservices.php" class="btn btn-primary">Manage Services</a>
            </div>

            <div class="action-card view-action">
                <h3>View All Property Services</h3>
                <p>Browse all service assignments for properties</p>
                <a href="Propertyservices.php" class="btn btn-info">Manage Services</a>
            </div>

            <div class="action-card delete-action">
                <h3>Delete Property Service</h3>
                <p>Remove a service link from a property</p>
                <a href="Propertyservices.php" class="btn btn-danger">Manage Services</a>
            </div>
        </div>
    </section>

    <section class="navigation">
        <a href="index.php" class="btn btn-secondary">← Back to Home</a>
    </section>
<?php
$pageContent = ob_get_clean();
include __DIR__ . "/layouts/PageLayout.php";
