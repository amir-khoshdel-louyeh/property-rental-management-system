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
    <title>Inspection Management</title>
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
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            font-size: 1rem;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
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
            font-size: 0.9rem;
        }
        table th {
            background: #f8f9fa;
            padding: 0.75rem;
            text-align: left;
            font-weight: 600;
            color: #212529;
            border-bottom: 2px solid #dee2e6;
        }
        table td {
            padding: 0.75rem;
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
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<main class="container">
    <section class="page-header">
        <h1>Inspection Management</h1>
        <p class="subtitle">Track and manage property inspections</p>
    </section>

    <div class="tabs-container">
        <button class="tab-button active" onclick="openTab(event, 'view-tab')">View Inspections</button>
        <button class="tab-button" onclick="openTab(event, 'add-tab')">Add New Inspection</button>
        <button class="tab-button" onclick="openTab(event, 'delete-tab')">Delete Inspection</button>
    </div>

    <!-- VIEW TAB -->
    <section id="view-tab" class="tab-content active">
        <div class="form-section">
            <h2>Inspection Information</h2>
            <p>View all inspection reports and history:</p>
            
            <?php
                $sql = "SELECT * FROM Inspection";
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
                    echo '<div class="no-data">No inspections found in the system</div>';
                }
            ?>
        </div>
    </section>

    <!-- ADD TAB -->
    <section id="add-tab" class="tab-content">
        <div class="form-section">
            <h2>Add a New Inspection</h2>
            <p>Please complete the form and press the Submit button:</p>
            
            <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
                    $property_id = $_POST['property_id'] ?? 0;
                    $inspection_date = sanitizeText($_POST['inspection_date'] ?? '');
                    $findings = sanitizeText($_POST['findings'] ?? '');
                    $conducted_by = $_POST['conducted_by'] ?? 0;

                    if (!validateNumber($property_id) || !validateNumber($conducted_by)) {
                        echo '<div class="message error">Invalid numeric input!</div>';
                    } else if (empty($inspection_date) || !validateDate($inspection_date)) {
                        echo '<div class="message error">Invalid inspection date (use YYYY-MM-DD)!</div>';
                    } else if (empty($findings)) {
                        echo '<div class="message error">Please enter inspection findings!</div>';
                    } else {
                        $sql = "INSERT INTO Inspection (property_id, inspection_date, findings, conducted_by) 
                                VALUES (?, ?, ?, ?)";
                        
                        $result = executeQuery($conn, $sql, "issi", 
                            [intval($property_id), $inspection_date, $findings, intval($conducted_by)]);
                        
                        if ($result['success']) {
                            echo '<div class="message success">Inspection recorded successfully!</div>';
                        } else {
                            echo '<div class="message error">Try again! ' . htmlspecialchars($result['error']) . '</div>';
                        }
                    }
                }
            ?>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="action" value="add">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="property_id">Property ID: <span style="color: red;">*</span></label>
                        <input type="number" id="property_id" name="property_id" required>
                    </div>

                    <div class="form-group">
                        <label for="inspection_date">Inspection Date: <span style="color: red;">*</span></label>
                        <input type="date" id="inspection_date" name="inspection_date" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="findings">Findings: <span style="color: red;">*</span></label>
                    <textarea id="findings" name="findings" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="conducted_by">Conducted By (Landlord ID): <span style="color: red;">*</span></label>
                    <input type="number" id="conducted_by" name="conducted_by" required>
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
            <h2>Delete Inspection Records</h2>
            <p>Enter the Inspection ID to delete the record:</p>

            <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'delete') {
                    $inspection_id = $_POST['inspection_id'] ?? 0;
                    
                    if (!validateNumber($inspection_id)) {
                        echo '<div class="message error">Invalid Inspection ID!</div>';
                    } else {
                        $sql = "DELETE FROM Inspection WHERE inspection_id = ?";
                        $result = executeQuery($conn, $sql, "i", [intval($inspection_id)]);
                        
                        if ($result['success']) {
                            echo '<div class="message success">Inspection deleted successfully from the system!</div>';
                        } else {
                            echo '<div class="message error">Error deleting inspection: ' . htmlspecialchars($result['error']) . '</div>';
                        }
                    }
                }
            ?>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="action" value="delete">
                
                <div class="form-group">
                    <label for="inspection_id">Inspection ID: <span style="color: red;">*</span></label>
                    <input type="number" id="inspection_id" name="inspection_id" required>
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
