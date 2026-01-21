<?php

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
        throw new Exception("Connection failed: " . mysqli_connect_error());
    }
    }
    catch(Exception $e)
    {
        $error_log[] = "Database Error: " . $e->getMessage();
        error_log("Database Connection Error: " . $e->getMessage());
        die("Database connection failed. Please contact support.");
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
     * @return array Result array with success status and statement
     */
    function executeQuery($connection, $sql, $types = "", $params = []) {
        try {
            $stmt = $connection->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $connection->error);
            }
            
            // Bind parameters if provided
            if (!empty($params) && !empty($types)) {
                $stmt->bind_param($types, ...$params);
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
            return [
                'success' => true,
                'stmt' => $stmt,
                'error' => null
            ];
        } catch (Exception $e) {
            error_log("Query Error: " . $e->getMessage());
            return [
                'success' => false,
                'stmt' => null,
                'error' => $e->getMessage()
            ];
        }
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