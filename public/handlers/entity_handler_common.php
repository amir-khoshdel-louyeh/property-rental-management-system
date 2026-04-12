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

function fetchEntitiesAdvanced($conn, $table, $searchableFields, $options = []) {
    $pagedData = fetchEntitiesAdvancedPaginated($conn, $table, $searchableFields, $options);
    if ($pagedData === false) {
        return false;
    }

    return $pagedData['result'];
}

function fetchEntitiesAdvancedPaginated($conn, $table, $searchableFields, $options = []) {
    if (!isSafeSqlIdentifier($table)) {
        return false;
    }

    $safeFields = [];
    foreach ($searchableFields as $field) {
        if (isSafeSqlIdentifier($field)) {
            $safeFields[] = $field;
        }
    }

    if (empty($safeFields)) {
        return false;
    }

    $whereParts = [];
    $types = '';
    $params = [];

    $globalQuery = trim((string)($options['q'] ?? ''));
    if ($globalQuery !== '') {
        $orParts = [];
        foreach ($safeFields as $field) {
            $orParts[] = "{$field} LIKE ?";
            $types .= 's';
            $params[] = '%' . $globalQuery . '%';
        }
        $whereParts[] = '(' . implode(' OR ', $orParts) . ')';
    }

    $columnFilters = is_array($options['columnFilters'] ?? null) ? $options['columnFilters'] : [];
    foreach ($columnFilters as $field => $value) {
        if (!in_array($field, $safeFields, true)) {
            continue;
        }

        $cleanValue = trim((string)$value);
        if ($cleanValue === '') {
            continue;
        }

        $whereParts[] = "{$field} LIKE ?";
        $types .= 's';
        $params[] = '%' . $cleanValue . '%';
    }

    $rangeFilters = is_array($options['rangeFilters'] ?? null) ? $options['rangeFilters'] : [];
    foreach ($rangeFilters as $field => $range) {
        if (!in_array($field, $safeFields, true) || !is_array($range)) {
            continue;
        }

        if (isset($range['min']) && trim((string)$range['min']) !== '') {
            $minValue = trim((string)$range['min']);
            if (is_numeric($minValue)) {
                $whereParts[] = "{$field} >= ?";
                $types .= strpos($minValue, '.') !== false ? 'd' : 'i';
                $params[] = strpos($minValue, '.') !== false ? floatval($minValue) : intval($minValue);
            }
        }

        if (isset($range['max']) && trim((string)$range['max']) !== '') {
            $maxValue = trim((string)$range['max']);
            if (is_numeric($maxValue)) {
                $whereParts[] = "{$field} <= ?";
                $types .= strpos($maxValue, '.') !== false ? 'd' : 'i';
                $params[] = strpos($maxValue, '.') !== false ? floatval($maxValue) : intval($maxValue);
            }
        }
    }

    $sortField = $options['sort'] ?? $safeFields[0];
    if (!in_array($sortField, $safeFields, true)) {
        $sortField = $safeFields[0];
    }

    $order = strtoupper((string)($options['order'] ?? 'ASC'));
    if ($order !== 'DESC') {
        $order = 'ASC';
    }

    $limit = isset($options['limit']) && is_numeric($options['limit']) ? intval($options['limit']) : 25;
    if ($limit < 1) {
        $limit = 1;
    }
    if ($limit > 500) {
        $limit = 500;
    }

    $page = isset($options['page']) && is_numeric($options['page']) ? intval($options['page']) : 1;
    if ($page < 1) {
        $page = 1;
    }

    $offset = ($page - 1) * $limit;

    $baseSql = "FROM {$table}";
    if (!empty($whereParts)) {
        $baseSql .= ' WHERE ' . implode(' AND ', $whereParts);
    }

    $countSql = "SELECT COUNT(*) AS total {$baseSql}";
    $countStmt = $conn->prepare($countSql);
    if (!$countStmt) {
        return false;
    }

    if (!empty($params)) {
        $countStmt->bind_param($types, ...$params);
    }

    if (!$countStmt->execute()) {
        return false;
    }

    $countResult = $countStmt->get_result();
    $countRow = $countResult ? $countResult->fetch_assoc() : null;
    $totalRows = intval($countRow['total'] ?? 0);
    $totalPages = $totalRows > 0 ? intval(ceil($totalRows / $limit)) : 1;

    if ($page > $totalPages) {
        $page = $totalPages;
        $offset = ($page - 1) * $limit;
    }

    $sql = "SELECT * {$baseSql} ORDER BY {$sortField} {$order} LIMIT {$limit} OFFSET {$offset}";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    if (!$stmt->execute()) {
        return false;
    }

    return [
        'result' => $stmt->get_result(),
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total_rows' => $totalRows,
            'total_pages' => $totalPages,
            'has_prev' => $page > 1,
            'has_next' => $page < $totalPages
        ]
    ];
}

function isSafeSqlIdentifier($identifier) {
    return preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $identifier) === 1;
}
