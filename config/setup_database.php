<?php
/**
 * Database Setup Script for User Authentication System
 * Run this file once to create the users table
 */

require_once 'Database_Manager.php';

echo "<h2>Database Setup - User Authentication System</h2>";

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE
)";

if (mysqli_query($conn, $sql)) {
    echo "<p style='color: green;'>✓ Users table created successfully or already exists.</p>";
} else {
    echo "<p style='color: red;'>✗ Error creating users table: " . mysqli_error($conn) . "</p>";
}

// Check if table exists
$result = mysqli_query($conn, "SHOW TABLES LIKE 'users'");
if (mysqli_num_rows($result) > 0) {
    echo "<p style='color: green;'>✓ Users table is available in the database.</p>";
    
    // Get table structure
    $structure = mysqli_query($conn, "DESCRIBE users");
    echo "<h3>Table Structure:</h3>";
    echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    while ($row = mysqli_fetch_assoc($structure)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Count users
    $count_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM users");
    $count = mysqli_fetch_assoc($count_result)['count'];
    echo "<p>Current number of registered users: <strong>" . $count . "</strong></p>";
} else {
    echo "<p style='color: red;'>✗ Users table was not found in the database.</p>";
}

echo "<hr>";
echo "<p><strong>Setup complete!</strong></p>";
echo "<p>You can now:</p>";
echo "<ul>";
echo "<li><a href='../public/register.php'>Register a new user</a></li>";
echo "<li><a href='../public/login.php'>Login</a></li>";
echo "<li><a href='../public/index.php'>Go to Homepage</a></li>";
echo "</ul>";
echo "<p><em>Note: For security reasons, consider deleting or restricting access to this setup file after use.</em></p>";
?>
