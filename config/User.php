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
    
    /**
     * Get user by username
     */
    public function getUserByUsername($username) {
        $sql = "SELECT user_id, username, email, created_at, last_login, is_active FROM users WHERE username = ?";
        $result = executeQuery($this->conn, $sql, "s", [$username]);
        
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
    
    /**
     * Update user email
     */
    public function updateEmail($user_id, $new_email) {
        // Check if email already exists for another user
        $sql = "SELECT user_id FROM users WHERE email = ? AND user_id != ?";
        $result = executeQuery($this->conn, $sql, "si", [$new_email, $user_id]);
        
        if ($result['success'] && $result['stmt']->get_result()->num_rows > 0) {
            return ['success' => false, 'message' => 'Email already in use'];
        }
        
        // Update email
        $sql = "UPDATE users SET email = ? WHERE user_id = ?";
        $result = executeQuery($this->conn, $sql, "si", [$new_email, $user_id]);
        
        if ($result['success']) {
            return ['success' => true, 'message' => 'Email updated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to update email'];
        }
    }
    
    /**
     * Change user password
     */
    public function changePassword($user_id, $old_password, $new_password) {
        // Verify old password
        $sql = "SELECT password_hash FROM users WHERE user_id = ?";
        $result = executeQuery($this->conn, $sql, "i", [$user_id]);
        
        if (!$result['success']) {
            return ['success' => false, 'message' => 'Failed to verify password'];
        }
        
        $user_result = $result['stmt']->get_result();
        
        if ($user_result->num_rows === 0) {
            return ['success' => false, 'message' => 'User not found'];
        }
        
        $user = $user_result->fetch_assoc();
        
        if (!password_verify($old_password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }
        
        // Update password
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password_hash = ? WHERE user_id = ?";
        $result = executeQuery($this->conn, $sql, "si", [$new_password_hash, $user_id]);
        
        if ($result['success']) {
            return ['success' => true, 'message' => 'Password changed successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to change password'];
        }
    }
    
    /**
     * Deactivate user account
     */
    public function deactivateAccount($user_id) {
        $sql = "UPDATE users SET is_active = FALSE WHERE user_id = ?";
        $result = executeQuery($this->conn, $sql, "i", [$user_id]);
        
        if ($result['success']) {
            return ['success' => true, 'message' => 'Account deactivated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to deactivate account'];
        }
    }
    
    /**
     * Activate user account
     */
    public function activateAccount($user_id) {
        $sql = "UPDATE users SET is_active = TRUE WHERE user_id = ?";
        $result = executeQuery($this->conn, $sql, "i", [$user_id]);
        
        if ($result['success']) {
            return ['success' => true, 'message' => 'Account activated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to activate account'];
        }
    }
}
?>
