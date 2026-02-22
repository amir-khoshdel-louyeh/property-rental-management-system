<?php
require_once 'Database_Manager.php';

class User {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    /**
     * Register a new user
     */
    public function register($username, $email, $password) {
        // Check if username already exists
        $sql = "SELECT user_id FROM users WHERE username = ?";
        $result = executeQuery($this->conn, $sql, "s", [$username]);
        
        if ($result['success'] && $result['stmt']->get_result()->num_rows > 0) {
            return ['success' => false, 'message' => 'Username already exists'];
        }
        
        // Check if email already exists
        $sql = "SELECT user_id FROM users WHERE email = ?";
        $result = executeQuery($this->conn, $sql, "s", [$email]);
        
        if ($result['success'] && $result['stmt']->get_result()->num_rows > 0) {
            return ['success' => false, 'message' => 'Email already exists'];
        }
        
        // Hash password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $sql = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
        $result = executeQuery($this->conn, $sql, "sss", [$username, $email, $password_hash]);
        
        if ($result['success']) {
            return ['success' => true, 'message' => 'Registration successful'];
        } else {
            return ['success' => false, 'message' => 'Registration failed'];
        }
    }
}
?>
