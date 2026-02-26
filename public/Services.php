<?php
    include("config/Database_Manager.php");
    include("config/Validation.php");
    include("layouts/Header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services Management</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/tables.css">
    <link rel="stylesheet" href="css/forms.css">
    <link rel="stylesheet" href="css/utilities.css">
    <link rel="stylesheet" href="css/animations.css">
    <style>
        .tabs-container {
            display: flex;
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 2rem;
            gap: 0;
        }
        .tab-button {
            padding: 1rem 2rem;
            border: none;
            background: #f8f9fa;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            color: #495057;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
            margin-bottom: -2px;
        }
        .tab-button:hover {
            background: #e9ecef;
            color: #212529;
        }
        .tab-button.active {
            background: #007bff;
            color: white;
            border-bottom-color: #007bff;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .form-section {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #212529;
        }
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            font-size: 1rem;
        }
        .form-group input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .form-buttons {
            display: flex;
            gap: 1rem;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background: #545b62;
        }
        .message {
            padding: 1rem;
            border-radius: 0.25rem;
            margin-bottom: 1.5rem;
        }
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        table th {
            background: #f8f9fa;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #212529;
            border-bottom: 2px solid #dee2e6;
        }
        table td {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
        }
        table tr:hover {
            background: #f8f9fa;
        }
        .no-data {
            padding: 2rem;
            text-align: center;
            color: #6c757d;
            font-size: 1.1rem;
        }
        .back-link {
            display: inline-block;
            margin-top: 2rem;
            padding: 0.75rem 1.5rem;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 0.25rem;
            transition: background 0.3s ease;
        }
        .back-link:hover {
            background: #545b62;
        }
    </style>
</head>
<body>

<main class="container">
    <section class="page-header">
        <h1>Services Management</h1>
        <p class="subtitle">Manage property-related services and utilities</p>
    </section>

    <div class="tabs-container">
        <button class="tab-button active" onclick="openTab(event, 'view-tab')">View Services</button>
        <button class="tab-button" onclick="openTab(event, 'add-tab')">Add New Service</button>
        <button class="tab-button" onclick="openTab(event, 'delete-tab')">Delete Service</button>
    </div>

    <!-- VIEW TAB -->
    <section id="view-tab" class="tab-content active">
        <div class="form-section">
            <h2>Services Information</h2>
            <p>View all services offered in the system:</p>
            
            <?php
                $sql = "SELECT * FROM services";
                $result = $conn->query($sql);

                if ($result === FALSE) {
                    echo '<div class="message error">Error querying database: ' . htmlspecialchars($conn->error) . '</div>';
                } elseif ($result->num_rows > 0) {
                    echo "<table>";
                    echo "<tr>";
                    $fields = $result->fetch_fields();
                    foreach ($fields as $field) {
                        echo "<th>" . htmlspecialchars($field->name) . "</th>";
                    }
                    echo "</tr>";

                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        foreach ($row as $value) {
                            echo "<td>" . htmlspecialchars($value) . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo '<div class="no-data">No services found in the system</div>';
                }
            ?>
        </div>
    </section>

    <!-- ADD TAB -->
    <section id="add-tab" class="tab-content">
        <div class="form-section">
            <h2>Add a New Service</h2>
            <p>Please complete the form and press the Submit button:</p>
            
            <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
                    $services_name = sanitizeText($_POST['services_name'] ?? '');

                    if (empty($services_name)) {
                        echo '<div class="message error">Please enter a service name!</div>';
                    } else {
                        $sql = "INSERT INTO services (services_name) 
                                VALUES (?)";
                        
                        $result = executeQuery($conn, $sql, "s", [$services_name]);
                        
                        if ($result['success']) {
                            echo '<div class="message success">Service added successfully!</div>';
                        } else {
                            echo '<div class="message error">Try again! ' . htmlspecialchars($result['error']) . '</div>';
                        }
                    }
                }
            ?>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="action" value="add">
                
                <div class="form-group">
                    <label for="services_name">Service Name: <span style="color: red;">*</span></label>
                    <input type="text" id="services_name" name="services_name" required>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-secondary">Clear</button>
                </div>
            </form>
        </div>
    </section>

    <!-- DELETE TAB -->
    <section id="delete-tab" class="tab-content">
        <div class="form-section">
            <h2>Delete Service Records</h2>
            <p>Enter the Service ID to delete the record:</p>

            <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'delete') {
                    $service_id = $_POST['service_id'] ?? 0;
                    
                    if (!validateNumber($service_id)) {
                        echo '<div class="message error">Invalid Service ID!</div>';
                    } else {
                        $service_id = intval($service_id);
                        
                        // Delete PropertyServices records first
                        $sql = "DELETE FROM PropertyServices WHERE service_id = ?";
                        $result = executeQuery($conn, $sql, "i", [$service_id]);
                        
                        if (!$result['success']) {
                            echo '<div class="message error">Error deleting property services: ' . htmlspecialchars($result['error']) . '</div>';
                        } else {
                            echo '<div class="message success">Property services records deleted successfully</div>';
                            
                            // Then delete Service
                            $sql = "DELETE FROM services WHERE service_id = ?";
                            $result = executeQuery($conn, $sql, "i", [$service_id]);
                            
                            if ($result['success']) {
                                echo '<div class="message success">Service deleted successfully from the system!</div>';
                            } else {
                                echo '<div class="message error">Error deleting service: ' . htmlspecialchars($result['error']) . '</div>';
                            }
                        }
                    }
                }
            ?>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="action" value="delete">
                
                <div class="form-group">
                    <label for="service_id">Service ID: <span style="color: red;">*</span></label>
                    <input type="number" id="service_id" name="service_id" required>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Delete</button>
                    <button type="reset" class="btn btn-secondary">Clear</button>
                </div>
            </form>
        </div>
    </section>

    <a href="index.php" class="back-link">← Back to Home</a>
</main>

<script>
function openTab(evt, tabName) {
    // Hide all tab contents
    const tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(tab => tab.classList.remove('active'));
    
    // Remove active class from all buttons
    const buttons = document.querySelectorAll('.tab-button');
    buttons.forEach(button => button.classList.remove('active'));
    
    // Show the selected tab and mark button as active
    document.getElementById(tabName).classList.add('active');
    evt.currentTarget.classList.add('active');
}
</script>

</body>
<?php
    include("layouts/Footer.html");
    mysqli_close($conn);
?>
</html>
