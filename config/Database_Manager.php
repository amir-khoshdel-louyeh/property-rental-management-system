<?php

require_once __DIR__ . '/ErrorHandler.php';

AppErrorHandler::init(AppErrorHandler::detectContext());

    $db_server = getenv('DB_SERVER') ?: 'localhost';
    $db_user = getenv('DB_USER') ?: 'root';
    $db_pass = getenv('DB_PASS') ?: '';
    $db_name = getenv('DB_NAME') ?: 'project_database';
    $conn = "";
    $error_log = [];

    try{
    $conn = mysqli_connect( $db_server,
                            $db_user,
                            $db_pass,
                            $db_name) ;
    
    if (!$conn) {
        throw new DatabaseException("Connection failed: " . mysqli_connect_error());
    }
    }
    catch(DatabaseException $e)
    {
        AppErrorHandler::logError("Database connection failed", [
            'error' => $e->getMessage()
        ]);
        throw $e;
    }
    catch(Exception $e)
    {
        AppErrorHandler::logError("Database connection error", [
            'error' => $e->getMessage()
        ]);
        throw new DatabaseException("Database connection failed: " . $e->getMessage(), 0, $e);
    }
    if($conn)
    {
        //echo "** DataBase is Connected **<br>"; 
    }

    /**
     * Execute a prepared statement with parameters
     * @param mysqli $connection Database connection
     * @param string $sql SQL query with placeholders (?)
     * @param string $types Parameter types (e.g., "sss" for 3 strings)
     * @param array $params Array of parameter values
     * @return mysqli_stmt The executed statement
     * @throws DatabaseException If prepare or execute fails
     */
    function executeQuery($connection, $sql, $types = "", $params = []) {
        $stmt = $connection->prepare($sql);
        
        if (!$stmt) {
            AppErrorHandler::logError("Query prepare failed", [
                'sql' => $sql,
                'error' => $connection->error
            ]);
            throw new DatabaseException("Query prepare failed: " . $connection->error);
        }
        
        // Bind parameters if provided
        if (!empty($params) && !empty($types)) {
            if (!$stmt->bind_param($types, ...$params)) {
                AppErrorHandler::logError("Parameter binding failed", [
                    'types' => $types,
                    'error' => $stmt->error
                ]);
                throw new DatabaseException("Parameter binding failed: " . $stmt->error);
            }
        }
        
        if (!$stmt->execute()) {
            AppErrorHandler::logError("Query execute failed", [
                'sql' => $sql,
                'error' => $stmt->error
            ]);
            throw new DatabaseException("Query execute failed: " . $stmt->error);
        }
        
        return $stmt;
    }

    /**
     * Fetch all results from a prepared statement
     * @param mysqli_stmt $stmt Prepared statement
     * @return array Array of all result rows
     */
    function fetchAllResults($stmt) {
        $result = $stmt->get_result();
        $data = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        return $data;
    }

    /**
     * Fetch one result from a prepared statement
     * @param mysqli_stmt $stmt Prepared statement
     * @return array Single result row
     */
    function fetchOneResult($stmt) {
        $result = $stmt->get_result();
        return $result ? $result->fetch_assoc() : null;
    }

?>