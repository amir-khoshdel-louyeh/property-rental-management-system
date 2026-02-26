<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME - Property & Rental Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/tables.css">
    <link rel="stylesheet" href="css/forms.css">
    <link rel="stylesheet" href="css/utilities.css">
    <link rel="stylesheet" href="css/animations.css">
    <style>
        .tabs-container {
            display: flex;
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 2rem;
            gap: 0;
        }
        .tab-button {
            padding: 1rem 2rem;
            border: none;
            background: #f8f9fa;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            color: #495057;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
            margin-bottom: -2px;
        }
        .tab-button:hover {
            background: #e9ecef;
            color: #212529;
        }
        .tab-button.active {
            background: #007bff;
            color: white;
            border-bottom-color: #007bff;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .entity-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        .entity-card {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s ease;
        }
        .entity-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        .entity-card h3 {
            margin: 0 0 0.5rem 0;
            color: #212529;
            font-size: 1.1rem;
        }
        .entity-card a {
            display: inline-block;
            margin-top: 0.75rem;
            padding: 0.5rem 1rem;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 0.25rem;
            transition: background 0.3s ease;
        }
        .entity-card a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
<?php
    include("layouts/Header.php");
?>
<main class="container">
    <section class="page-header">
        <h1>Property & Rental Management System</h1>
        <p class="subtitle">Comprehensive solution for managing properties, rentals, landlords, renters, and more</p>
    </section>

    <section class="home-intro">
        <h2>Welcome to the System</h2>
        <p>This system provides a complete platform for managing all aspects of your property rental business. Use the tabs below to add new data, view existing records, or delete outdated information.</p>
    </section>

    <div class="tabs-container">
        <button class="tab-button active" onclick="openTab(event, 'insert-tab')">Add New Data</button>
        <button class="tab-button" onclick="openTab(event, 'view-tab')">View Database</button>
        <button class="tab-button" onclick="openTab(event, 'delete-tab')">Delete Records</button>
    </div>

    <!-- INSERT TAB -->
    <section id="insert-tab" class="tab-content active">
        <h2>Add New Data</h2>
        <p>Create new records for properties, landlords, renters, and more. Click on any entity below to add a new record:</p>
        <div class="entity-links">
            <div class="entity-card">
                <h3>Landlord</h3>
                <p>Add a new landlord to the system</p>
                <a href="Add_Landlord.php">Add Landlord</a>
            </div>
            <div class="entity-card">
                <h3>Renter</h3>
                <p>Add a new renter to the system</p>
                <a href="Add_Renter.php">Add Renter</a>
            </div>
            <div class="entity-card">
                <h3>Property</h3>
                <p>Add a new property to the system</p>
                <a href="Add_Property.php">Add Property</a>
            </div>
            <div class="entity-card">
                <h3>Rental</h3>
                <p>Add a new rental to the system</p>
                <a href="Add_Rental.php">Add Rental</a>
            </div>
            <div class="entity-card">
                <h3>Payment</h3>
                <p>Add a new payment to the system</p>
                <a href="Add_Payment.php">Add Payment</a>
            </div>
            <div class="entity-card">
                <h3>Services</h3>
                <p>Add a new service to the system</p>
                <a href="Add_Services.php">Add Services</a>
            </div>
            <div class="entity-card">
                <h3>Property Services</h3>
                <p>Add a new property service to the system</p>
                <a href="Add_Propertyservices.php">Add Property Services</a>
            </div>
            <div class="entity-card">
                <h3>Inspection</h3>
                <p>Add a new inspection to the system</p>
                <a href="Add_Inspection.php">Add Inspection</a>
            </div>
        </div>
    </section>

    <!-- VIEW TAB -->
    <section id="view-tab" class="tab-content">
        <h2>View Database</h2>
        <p>Browse and review all existing records in the system. Click on any entity below to view its records:</p>
        <div class="entity-links">
            <div class="entity-card">
                <h3>Landlord</h3>
                <p>View all landlords in the system</p>
                <a href="Show_Landlord.php">View Landlords</a>
            </div>
            <div class="entity-card">
                <h3>Renter</h3>
                <p>View all renters in the system</p>
                <a href="Show_Renter.php">View Renters</a>
            </div>
            <div class="entity-card">
                <h3>Property</h3>
                <p>View all properties in the system</p>
                <a href="Show_Property.php">View Properties</a>
            </div>
            <div class="entity-card">
                <h3>Rental</h3>
                <p>View all rentals in the system</p>
                <a href="Show_Rental.php">View Rentals</a>
            </div>
            <div class="entity-card">
                <h3>Payment</h3>
                <p>View all payments in the system</p>
                <a href="Show_Payment.php">View Payments</a>
            </div>
            <div class="entity-card">
                <h3>Services</h3>
                <p>View all services in the system</p>
                <a href="Show_Services.php">View Services</a>
            </div>
            <div class="entity-card">
                <h3>Property Services</h3>
                <p>View all property services in the system</p>
                <a href="Show_Propertyservices.php">View Property Services</a>
            </div>
            <div class="entity-card">
                <h3>Inspection</h3>
                <p>View all inspections in the system</p>
                <a href="Show_Inspection.php">View Inspections</a>
            </div>
            <div class="entity-card">
                <h3>Database Overview</h3>
                <p>View ALL tables in the system at once</p>
                <a href="Database_overview.php">View All Tables</a>
            </div>
        </div>
    </section>

    <!-- DELETE TAB -->
    <section id="delete-tab" class="tab-content">
        <h2>Delete Records</h2>
        <p>Remove outdated or unwanted records from the system. Click on any entity below to delete a record:</p>
        <div class="entity-links">
            <div class="entity-card">
                <h3>Landlord</h3>
                <p>Delete a landlord from the system</p>
                <a href="Del_Landlord.php">Delete Landlord</a>
            </div>
            <div class="entity-card">
                <h3>Renter</h3>
                <p>Delete a renter from the system</p>
                <a href="Del_Renter.php">Delete Renter</a>
            </div>
            <div class="entity-card">
                <h3>Property</h3>
                <p>Delete a property from the system</p>
                <a href="Del_Property.php">Delete Property</a>
            </div>
            <div class="entity-card">
                <h3>Rental</h3>
                <p>Delete a rental from the system</p>
                <a href="Del_Rental.php">Delete Rental</a>
            </div>
            <div class="entity-card">
                <h3>Payment</h3>
                <p>Delete a payment from the system</p>
                <a href="Del_Payment.php">Delete Payment</a>
            </div>
            <div class="entity-card">
                <h3>Services</h3>
                <p>Delete a service from the system</p>
                <a href="Del_Services.php">Delete Services</a>
            </div>
            <div class="entity-card">
                <h3>Property Services</h3>
                <p>Delete a property service from the system</p>
                <a href="Del_Propertyservices.php">Delete Property Services</a>
            </div>
            <div class="entity-card">
                <h3>Inspection</h3>
                <p>Delete an inspection from the system</p>
                <a href="Del_Inspection.php">Delete Inspection</a>
            </div>
        </div>
    </section>
</main>

<script>
function openTab(evt, tabName) {
    // Hide all tab contents
    const tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(tab => tab.classList.remove('active'));
    
    // Remove active class from all buttons
    const buttons = document.querySelectorAll('.tab-button');
    buttons.forEach(button => button.classList.remove('active'));
    
    // Show the selected tab and mark button as active
    document.getElementById(tabName).classList.add('active');
    evt.currentTarget.classList.add('active');
}
</script>

</body>
<?php
    include("layouts/Footer.html");
?>
</html>