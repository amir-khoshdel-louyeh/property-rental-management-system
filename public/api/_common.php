<?php

require_once __DIR__ . '/../../config/Database_Manager.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

function apiResponse($statusCode, $payload) {
    http_response_code($statusCode);
    echo json_encode($payload);
    exit;
}

function apiMethod() {
    return $_SERVER['REQUEST_METHOD'] ?? 'GET';
}

function apiJsonBody() {
    $raw = file_get_contents('php://input');
    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $decoded = json_decode($raw, true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
        apiResponse(400, [
            'success' => false,
            'error' => 'Invalid JSON body.'
        ]);
    }

    return $decoded;
}

function apiInputData() {
    $method = apiMethod();

    if ($method === 'POST') {
        if (!empty($_POST)) {
            return $_POST;
        }

        return apiJsonBody();
    }

    return apiJsonBody();
}

function apiQueryId() {
    if (!isset($_GET['id'])) {
        return null;
    }

    $id = $_GET['id'];
    if (!is_numeric($id)) {
        apiResponse(400, [
            'success' => false,
            'error' => 'Invalid id parameter.'
        ]);
    }

    return intval($id);
}

function isSafeIdentifier($identifier) {
    return preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $identifier) === 1;
}

function executePrepared($conn, $sql, $types = '', $params = []) {
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        apiResponse(500, [
            'success' => false,
            'error' => 'Query prepare failed: ' . $conn->error
        ]);
    }

    if ($types !== '' && !empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    if (!$stmt->execute()) {
        apiResponse(500, [
            'success' => false,
            'error' => 'Query execute failed: ' . $stmt->error
        ]);
    }

    return $stmt;
}

function fetchAllFromStatement($stmt) {
    $result = $stmt->get_result();
    $rows = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }

    return $rows;
}
