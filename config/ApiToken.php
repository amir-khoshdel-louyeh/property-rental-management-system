<?php
require_once 'Database_Manager.php';

class ApiToken {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    public function createToken($userId, $name = 'mobile-app', $expiresInSeconds = 2592000) {
        $plainToken = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $plainToken);
        $expiresAt = date('Y-m-d H:i:s', time() + $expiresInSeconds);

        $sql = "INSERT INTO api_tokens (user_id, token_hash, token_name, expires_at, last_used_at) VALUES (?, ?, ?, ?, NOW())";
        $result = executeQuery($this->conn, $sql, 'isss', [intval($userId), $tokenHash, $name, $expiresAt]);

        if (!$result['success']) {
            return [
                'success' => false,
                'message' => 'Failed to create API token.'
            ];
        }

        return [
            'success' => true,
            'token' => $plainToken,
            'expires_at' => $expiresAt
        ];
    }

    public function getUserFromToken($plainToken) {
        if (empty($plainToken)) {
            return [
                'success' => false,
                'message' => 'Missing token.'
            ];
        }

        $tokenHash = hash('sha256', $plainToken);
        $sql = "SELECT t.token_id, t.user_id, t.expires_at, u.username, u.email, u.role, u.is_active
                FROM api_tokens t
                INNER JOIN users u ON u.user_id = t.user_id
                WHERE t.token_hash = ?
                LIMIT 1";

        $result = executeQuery($this->conn, $sql, 's', [$tokenHash]);
        if (!$result['success']) {
            return [
                'success' => false,
                'message' => 'Token lookup failed.'
            ];
        }

        $row = $result['stmt']->get_result()->fetch_assoc();
        if (!$row) {
            return [
                'success' => false,
                'message' => 'Invalid token.'
            ];
        }

        if (!$row['is_active']) {
            return [
                'success' => false,
                'message' => 'User account is inactive.'
            ];
        }

        if (strtotime($row['expires_at']) < time()) {
            $this->revokeToken($plainToken);
            return [
                'success' => false,
                'message' => 'Token expired.'
            ];
        }

        $touchSql = "UPDATE api_tokens SET last_used_at = NOW() WHERE token_id = ?";
        executeQuery($this->conn, $touchSql, 'i', [intval($row['token_id'])]);

        return [
            'success' => true,
            'user' => [
                'user_id' => intval($row['user_id']),
                'username' => $row['username'],
                'email' => $row['email'],
                'role' => $row['role']
            ]
        ];
    }

    public function revokeToken($plainToken) {
        $tokenHash = hash('sha256', $plainToken);
        $sql = "DELETE FROM api_tokens WHERE token_hash = ?";
        $result = executeQuery($this->conn, $sql, 's', [$tokenHash]);

        return [
            'success' => $result['success']
        ];
    }
}
?>
