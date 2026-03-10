<?php

function setHandlerMessage(&$message, &$message_type, $text, $type) {
    $message = $text;
    $message_type = $type;
}

function executeWithMessage($conn, $sql, $types, $params, $successMessage, $errorPrefix) {
    $result = executeQuery($conn, $sql, $types, $params);

    if ($result['success']) {
        return [
            'success' => true,
            'message' => $successMessage,
            'type' => 'success'
        ];
    }

    return [
        'success' => false,
        'message' => $errorPrefix . htmlspecialchars($result['error']),
        'type' => 'error'
    ];
}

function deleteByIdWithMessage($conn, $table, $idColumn, $idValue, $successMessage, $errorPrefix) {
    if (!isSafeSqlIdentifier($table) || !isSafeSqlIdentifier($idColumn)) {
        return [
            'success' => false,
            'message' => 'Invalid delete configuration.',
            'type' => 'error'
        ];
    }

    $sql = "DELETE FROM {$table} WHERE {$idColumn} = ?";
    return executeWithMessage($conn, $sql, "i", [intval($idValue)], $successMessage, $errorPrefix);
}

function deleteDependenciesById($conn, $idValue, $dependencies) {
    $errors = [];

    foreach ($dependencies as $dependency) {
        $table = $dependency['table'];
        $column = $dependency['column'];
        $label = $dependency['label'] ?? $table;

        if (!isSafeSqlIdentifier($table) || !isSafeSqlIdentifier($column)) {
            $errors[] = 'Invalid delete configuration for ' . htmlspecialchars($label) . '.';
            continue;
        }

        $sql = "DELETE FROM {$table} WHERE {$column} = ?";
        $result = executeQuery($conn, $sql, "i", [intval($idValue)]);

        if (!$result['success']) {
            $errors[] = 'Error deleting from ' . htmlspecialchars($label) . ': ' . htmlspecialchars($result['error']);
        }
    }

    return [
        'success' => empty($errors),
        'errors' => $errors
    ];
}

function fetchAllEntities($conn, $table) {
    if (!isSafeSqlIdentifier($table)) {
        return false;
    }

    $sql = "SELECT * FROM {$table}";
    return $conn->query($sql);
}

function isSafeSqlIdentifier($identifier) {
    return preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $identifier) === 1;
}
