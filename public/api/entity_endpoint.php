<?php

require_once __DIR__ . '/_common.php';
require_once __DIR__ . '/_auth.php';
require_once __DIR__ . '/entities_config.php';

function handleEntityRequest($entityKey) {
    global $conn;

    $configs = getEntityConfigs();
    if (!isset($configs[$entityKey])) {
        throw new NotFoundException('Unknown entity endpoint.');
    }

    $config = $configs[$entityKey];
    $table = $config['table'];
    $primaryKey = $config['primaryKey'];
    $fields = $config['fields'];
    $required = $config['required'];

    if (!isSafeIdentifier($table) || !isSafeIdentifier($primaryKey)) {
        throw new ConfigurationException('Unsafe entity configuration: ' . $entityKey);
    }

    $method = apiMethod();

    if ($method === 'GET') {
        handleRead($conn, $table, $primaryKey);
    }

    if ($method === 'POST') {
        apiRequireAuth();
        $data = apiInputData();
        handleCreate($conn, $table, $primaryKey, $fields, $required, $data);
    }

    if ($method === 'PUT' || $method === 'PATCH') {
        apiRequireAuth();
        $data = apiInputData();
        handleUpdate($conn, $table, $primaryKey, $fields, $data);
    }

    if ($method === 'DELETE') {
        apiRequireAuth();
        $data = apiInputData();
        handleDelete($conn, $table, $primaryKey, $data);
    }

    throw new MethodNotAllowedException();
}

function handleRead($conn, $table, $primaryKey) {
    $id = apiQueryId();

    if ($id !== null) {
        $sql = "SELECT * FROM {$table} WHERE {$primaryKey} = ?";
        $stmt = executePrepared($conn, $sql, 'i', [$id]);
        $rows = fetchAllFromStatement($stmt);

        if (empty($rows)) {
            throw new NotFoundException('Record not found.');
        }

        apiResponse(200, [
            'success' => true,
            'data' => $rows[0]
        ]);
    }

    $sql = "SELECT * FROM {$table}";
    $stmt = executePrepared($conn, $sql);
    $rows = fetchAllFromStatement($stmt);

    apiResponse(200, [
        'success' => true,
        'count' => count($rows),
        'data' => $rows
    ]);
}

function handleCreate($conn, $table, $primaryKey, $fields, $required, $data) {
    [$payload, $missing] = extractPayload($fields, $required, $data);

    if (!empty($missing)) {
        throw new ValidationException(
            'Missing required fields: ' . implode(', ', $missing) . '.',
            ['missing' => $missing]
        );
    }

    if (empty($payload)) {
        throw new BadRequestException(
            'No valid fields provided. Allowed fields: ' . implode(', ', $fields) . '.'
        );
    }

    $columns = array_keys($payload);
    $placeholders = implode(', ', array_fill(0, count($columns), '?'));
    $sql = "INSERT INTO {$table} (" . implode(', ', $columns) . ") VALUES ({$placeholders})";

    [$types, $params] = buildTypesAndParams($payload);
    executePrepared($conn, $sql, $types, $params);

    $newId = $conn->insert_id;
    $readSql = "SELECT * FROM {$table} WHERE {$primaryKey} = ?";
    $readStmt = executePrepared($conn, $readSql, 'i', [$newId]);
    $rows = fetchAllFromStatement($readStmt);

    apiResponse(201, [
        'success' => true,
        'message' => 'Record created successfully.',
        'data' => $rows[0] ?? [$primaryKey => $newId]
    ]);
}

function handleUpdate($conn, $table, $primaryKey, $fields, $data) {
    $id = apiQueryId();
    if ($id === null && isset($data[$primaryKey]) && is_numeric($data[$primaryKey])) {
        $id = intval($data[$primaryKey]);
    }

    if ($id === null) {
        throw new BadRequestException(
            "An id is required for update. Provide '{$primaryKey}' in query (?id=) or request body."
        );
    }

    [$payload, ] = extractPayload($fields, [], $data);

    if (empty($payload)) {
        throw new BadRequestException(
            'No updatable fields provided. Allowed fields: ' . implode(', ', $fields) . '.'
        );
    }

    $setParts = [];
    foreach (array_keys($payload) as $field) {
        $setParts[] = "{$field} = ?";
    }

    $sql = "UPDATE {$table} SET " . implode(', ', $setParts) . " WHERE {$primaryKey} = ?";
    [$types, $params] = buildTypesAndParams($payload);
    $types .= 'i';
    $params[] = $id;

    $stmt = executePrepared($conn, $sql, $types, $params);

    if ($stmt->affected_rows === 0) {
        $existsSql = "SELECT 1 FROM {$table} WHERE {$primaryKey} = ?";
        $existsStmt = executePrepared($conn, $existsSql, 'i', [$id]);
        $exists = fetchAllFromStatement($existsStmt);

        if (empty($exists)) {
            throw new NotFoundException('Record not found.');
        }
    }

    $readSql = "SELECT * FROM {$table} WHERE {$primaryKey} = ?";
    $readStmt = executePrepared($conn, $readSql, 'i', [$id]);
    $rows = fetchAllFromStatement($readStmt);

    apiResponse(200, [
        'success' => true,
        'message' => 'Record updated successfully.',
        'data' => $rows[0] ?? null
    ]);
}

function handleDelete($conn, $table, $primaryKey, $data) {
    $id = apiQueryId();
    if ($id === null && isset($data[$primaryKey]) && is_numeric($data[$primaryKey])) {
        $id = intval($data[$primaryKey]);
    }

    if ($id === null) {
        throw new BadRequestException(
            "An id is required for delete. Provide '{$primaryKey}' in query (?id=) or request body."
        );
    }

    $sql = "DELETE FROM {$table} WHERE {$primaryKey} = ?";
    $stmt = executePrepared($conn, $sql, 'i', [$id]);

    if ($stmt->affected_rows === 0) {
        throw new NotFoundException('Record not found.');
    }

    apiResponse(200, [
        'success' => true,
        'message' => 'Record deleted successfully.',
        'deleted_id' => $id
    ]);
}

function extractPayload($allowedFields, $requiredFields, $data) {
    $payload = [];

    foreach ($allowedFields as $field) {
        if (array_key_exists($field, $data)) {
            $payload[$field] = normalizeInputValue($data[$field]);
        }
    }

    $missing = [];
    foreach ($requiredFields as $requiredField) {
        if (!array_key_exists($requiredField, $payload) || $payload[$requiredField] === '' || $payload[$requiredField] === null) {
            $missing[] = $requiredField;
        }
    }

    return [$payload, $missing];
}

function normalizeInputValue($value) {
    if (is_string($value)) {
        $trimmed = trim($value);
        if (strtolower($trimmed) === 'null') {
            return null;
        }

        return $trimmed;
    }

    return $value;
}

function buildTypesAndParams($payload) {
    $types = '';
    $params = [];

    foreach ($payload as $value) {
        if (is_int($value)) {
            $types .= 'i';
        } else if (is_float($value)) {
            $types .= 'd';
        } else {
            $types .= 's';
            if ($value === null) {
                $value = null;
            } else {
                $value = (string)$value;
            }
        }

        $params[] = $value;
    }

    return [$types, $params];
}
