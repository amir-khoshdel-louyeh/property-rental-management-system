<?php
    include("config/Database_Manager.php");
    include("config/Validation.php");
    include("handlers/rental_handler.php");
    require_once '../config/guards/landlord_guard.php';
?>
$pageTitle = 'Rental Management';
$pageStyles = <<<'CSS'
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
CSS;
ob_start();
?>
<section class="page-header">
        <h1>Rental Management</h1>
        <p class="subtitle">Manage all rental agreements and details</p>
    </section>

    <div class="tabs-container">
        <button class="tab-button active" onclick="openTab(event, 'view-tab')">View Rentals</button>
        <button class="tab-button" onclick="openTab(event, 'add-tab')">Create New Rental</button>
        <button class="tab-button" onclick="openTab(event, 'delete-tab')">Delete Rental</button>
    </div>

    <!-- VIEW TAB -->
    <section id="view-tab" class="tab-content active">
        <div class="form-section">
            <h2>Rental Information</h2>
            <p>View all active and past rentals in the system:</p>
            
            <?php
                $result = getRentals($conn);

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
                    echo '<div class="no-data">No rentals found in the system</div>';
                }
            ?>
        </div>
    </section>

    <!-- ADD TAB -->
    <section id="add-tab" class="tab-content">
        <div class="form-section">
            <h2>Create a New Rental</h2>
            <p>Please complete the form and press the Submit button:</p>
            
            <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add' && !empty($message)) {
                    echo '<div class="message ' . $message_type . '">' . $message . '</div>';
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
                        <label for="renter_id">Renter ID: <span style="color: red;">*</span></label>
                        <input type="number" id="renter_id" name="renter_id" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="start_date">Start Date: <span style="color: red;">*</span></label>
                        <input type="date" id="start_date" name="start_date" required>
                    </div>

                    <div class="form-group">
                        <label for="end_date">End Date: <span style="color: red;">*</span></label>
                        <input type="date" id="end_date" name="end_date" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="monthly_rent">Monthly Rent: <span style="color: red;">*</span></label>
                        <input type="number" id="monthly_rent" name="monthly_rent" min="0" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="security_deposit">Security Deposit: <span style="color: red;">*</span></label>
                        <input type="number" id="security_deposit" name="security_deposit" min="0" step="0.01" required>
                    </div>
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
            <h2>Delete Rental Records</h2>
            <p>Enter the Rental ID to delete the record:</p>

            <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'delete' && !empty($message)) {
                    echo '<div class="message ' . $message_type . '">' . $message . '</div>';
                }
            ?>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="action" value="delete">
                
                <div class="form-group">
                    <label for="rental_id">Rental ID: <span style="color: red;">*</span></label>
                    <input type="number" id="rental_id" name="rental_id" required>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Delete</button>
                    <button type="reset" class="btn btn-secondary">Clear</button>
                </div>
            </form>
        </div>
    </section>

    <a href="index.php" class="back-link">← Back to Home</a>
<?php
$pageContent = ob_get_clean();
include __DIR__ . "/layouts/PageLayout.php";
