<?php
require_once '../config/Database_Manager.php';
require_once '../config/User.php';
require_once '../config/guards/admin_guard.php';

$user = new User($conn);
$users_result = $user->getAllUsers();
$users = $users_result['users'] ?? [];

$roleCounts = [
    'Admin' => 0,
    'Landlord' => 0,
    'Renter' => 0,
];

foreach ($users as $u) {
    if (isset($roleCounts[$u['role']])) {
        $roleCounts[$u['role']]++;
    }
}

$totalUsers = count($users);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/tables.css">
    <link rel="stylesheet" href="css/forms.css">
    <link rel="stylesheet" href="css/utilities.css">
</head>
<body>
    <?php include 'Header.php'; ?>

    <main class="container">
        <section class="page-header">
            <h1>Admin Dashboard</h1>
            <p class="subtitle">Manage the system, users, and access from one place.</p>
        </section>

        <section class="dashboard-cards">
            <div class="card summary-card">
                <h3>Total Users</h3>
                <p><?php echo htmlspecialchars($totalUsers); ?></p>
            </div>
            <div class="card summary-card">
                <h3>Admins</h3>
                <p><?php echo htmlspecialchars($roleCounts['Admin']); ?></p>
            </div>
            <div class="card summary-card">
                <h3>Landlords</h3>
                <p><?php echo htmlspecialchars($roleCounts['Landlord']); ?></p>
            </div>
            <div class="card summary-card">
                <h3>Renters</h3>
                <p><?php echo htmlspecialchars($roleCounts['Renter']); ?></p>
            </div>
        </section>

        <section class="dashboard-actions">
            <h2>Admin Actions</h2>
            <div class="action-buttons">
                <div class="action-card admin-action">
                    <h3>Manage Users</h3>
                    <p>View and update user roles, account status, and activity.</p>
                    <a href="manage_users.php" class="btn btn-primary">Manage Users</a>
                </div>
                <div class="action-card admin-action">
                    <h3>System Overview</h3>
                    <p>Access core management actions for the rental platform.</p>
                    <a href="manage_users.php" class="btn btn-secondary">View Users</a>
                </div>
            </div>
        </section>
    </main>

    <?php include 'Footer.html'; ?>
</body>
</html>
