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
    
    /**
     * Authenticate user login
     */
    public function login($username, $password) {
        $sql = "SELECT user_id, username, email, password_hash, is_active FROM users WHERE username = ?";
        $result = executeQuery($this->conn, $sql, "s", [$username]);
        
        if (!$result['success']) {
            return ['success' => false, 'message' => 'Login failed'];
        }
        
        $user_result = $result['stmt']->get_result();
        
        if ($user_result->num_rows === 0) {
            return ['success' => false, 'message' => 'Invalid username or password'];
        }
        
        $user = $user_result->fetch_assoc();
        
        if (!$user['is_active']) {
            return ['success' => false, 'message' => 'Account is inactive'];
        }
        
        if (password_verify($password, $user['password_hash'])) {
            // Update last login
            $update_sql = "UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE user_id = ?";
            executeQuery($this->conn, $update_sql, "i", [$user['user_id']]);
            
            return [
                'success' => true,
                'message' => 'Login successful',
                'user' => [
                    'user_id' => $user['user_id'],
                    'username' => $user['username'],
                    'email' => $user['email']
                ]
            ];
        } else {
            return ['success' => false, 'message' => 'Invalid username or password'];
        }
    }
    
    /**
     * Get user by ID
     */
    public function getUserById($user_id) {
        $sql = "SELECT user_id, username, email, created_at, last_login, is_active FROM users WHERE user_id = ?";
        $result = executeQuery($this->conn, $sql, "i", [$user_id]);
        
        if (!$result['success']) {
            return ['success' => false, 'message' => 'Failed to fetch user'];
        }
        
        $user_result = $result['stmt']->get_result();
        
        if ($user_result->num_rows === 0) {
            return ['success' => false, 'message' => 'User not found'];
        }
        
        $user = $user_result->fetch_assoc();
        
        return [
            'success' => true,
            'user' => $user
        ];
    }
}
?>
