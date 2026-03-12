<?php
require_once 'Database_Manager.php';

echo "<h2>Database Setup - API Tokens</h2>";

$sql = "CREATE TABLE IF NOT EXISTS api_tokens (
    token_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token_hash CHAR(64) NOT NULL UNIQUE,
    token_name VARCHAR(100) DEFAULT 'mobile-app',
    expires_at DATETIME NOT NULL,
    last_used_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_expires_at (expires_at),
    CONSTRAINT fk_api_tokens_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
)";

if (mysqli_query($conn, $sql)) {
    echo "<p style='color: green;'>API tokens table created successfully or already exists.</p>";
} else {
    echo "<p style='color: red;'>Error creating api_tokens table: " . mysqli_error($conn) . "</p>";
}

$cleanup_sql = "DELETE FROM api_tokens WHERE expires_at < NOW()";
if (mysqli_query($conn, $cleanup_sql)) {
    $deleted = mysqli_affected_rows($conn);
    echo "<p style='color: blue;'>Expired token cleanup completed. Removed {$deleted} rows.</p>";
} else {
    echo "<p style='color: orange;'>Cleanup warning: " . mysqli_error($conn) . "</p>";
}

?>
