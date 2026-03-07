<?php
require_once '../config/Database_Manager.php';
require_once '../config/User.php';
require_once '../config/session.php';
require_once '../config/RoleConstants.php';
require_once '../config/guards/admin_guard.php';

// Handle role change requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_role') {
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        $error = 'Invalid security token';
    } else {
        $user_id = intval($_POST['user_id']);
        $new_role = $_POST['role'];
        
        $user = new User($conn);
        $result = $user->updateRole($user_id, $new_role);
        
        if ($result['success']) {
            $success = 'User role updated successfully';
        } else {
            $error = $result['message'];
        }
    }
}

// Get all users
$user = new User($conn);
$users_result = $user->getAllUsers();
$users = $users_result['users'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/tables.css">
    <link rel="stylesheet" href="css/forms.css">
</head>
<body>
    <?php include 'Header.php'; ?>
    
    <div class="container">
        <h2>Manage Users</h2>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <table class="table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Current Role</th>
                    <th>Change Role</th>
                    <th>Created At</th>
                    <th>Last Login</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user_row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user_row['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($user_row['username']); ?></td>
                        <td><?php echo htmlspecialchars($user_row['email']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo strtolower($user_row['role']); ?>">
                                <?php echo htmlspecialchars($user_row['role']); ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <?php echo getCSRFInput(); ?>
                                <input type="hidden" name="action" value="update_role">
                                <input type="hidden" name="user_id" value="<?php echo $user_row['user_id']; ?>">
                                <select name="role" onchange="this.form.submit()" class="form-select">
                                    <?php foreach (Role::getAllRoles() as $role): ?>
                                        <option value="<?php echo $role; ?>" 
                                            <?php echo ($role === $user_row['role']) ? 'selected' : ''; ?>>
                                            <?php echo $role; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>
                        <td><?php echo htmlspecialchars($user_row['created_at'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($user_row['last_login'] ?? 'Never'); ?></td>
                        <td>
                            <span class="badge <?php echo $user_row['is_active'] ? 'badge-success' : 'badge-danger'; ?>">
                                <?php echo $user_row['is_active'] ? 'Active' : 'Inactive'; ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if (empty($users)): ?>
            <p>No users found.</p>
        <?php endif; ?>
    </div>
    
    <?php include 'Footer.html'; ?>
</body>
</html>
