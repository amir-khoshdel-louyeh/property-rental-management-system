<?php
    include("config/Database_Manager.php");
    include("config/Validation.php");
    include("handlers/property_handler.php");
    include("layouts/Header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Management</title>
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
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            font-size: 1rem;
        }
        .form-group input:focus,
        .form-group select:focus,
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
        .search-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin: 1rem 0;
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
            .search-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<main class="container">
    <section class="page-header">
        <h1>Property Management</h1>
        <p class="subtitle">Manage all properties and their details</p>
    </section>

    <div class="tabs-container">
        <button class="tab-button active" onclick="openTab(event, 'view-tab')">View Properties</button>
        <button class="tab-button" onclick="openTab(event, 'add-tab')">Add New Property</button>
        <button class="tab-button" onclick="openTab(event, 'delete-tab')">Delete Property</button>
    </div>

    <!-- VIEW TAB -->
    <section id="view-tab" class="tab-content active">
        <div class="form-section">
            <h2>Property Information</h2>
            <p>View all properties in your portfolio:</p>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                <div class="search-grid">
                    <div class="form-group">
                        <label for="q">Global Search</label>
                        <input type="text" id="q" name="q" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>" placeholder="Address, city, description...">
                    </div>

                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($_GET['city'] ?? ''); ?>" placeholder="Filter by city">
                    </div>

                    <div class="form-group">
                        <label for="property_type_filter">Property Type</label>
                        <input type="text" id="property_type_filter" name="property_type" value="<?php echo htmlspecialchars($_GET['property_type'] ?? ''); ?>" placeholder="House, Apartment...">
                    </div>

                    <div class="form-group">
                        <label for="rent_sale_filter">Rent or Sale</label>
                        <input type="text" id="rent_sale_filter" name="rent_sale" value="<?php echo htmlspecialchars($_GET['rent_sale'] ?? ''); ?>" placeholder="Rent or Sale">
                    </div>

                    <div class="form-group">
                        <label for="sort">Sort By</label>
                        <select id="sort" name="sort">
                            <option value="property_id" <?php echo (($_GET['sort'] ?? '') === 'property_id') ? 'selected' : ''; ?>>Property ID</option>
                            <option value="city" <?php echo (($_GET['sort'] ?? '') === 'city') ? 'selected' : ''; ?>>City</option>
                            <option value="property_type" <?php echo (($_GET['sort'] ?? '') === 'property_type') ? 'selected' : ''; ?>>Property Type</option>
                            <option value="price" <?php echo (($_GET['sort'] ?? '') === 'price') ? 'selected' : ''; ?>>Price</option>
                            <option value="landlord_id" <?php echo (($_GET['sort'] ?? '') === 'landlord_id') ? 'selected' : ''; ?>>Landlord ID</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="order">Sort Order</label>
                        <select id="order" name="order">
                            <option value="ASC" <?php echo (strtoupper($_GET['order'] ?? 'ASC') === 'ASC') ? 'selected' : ''; ?>>Ascending</option>
                            <option value="DESC" <?php echo (strtoupper($_GET['order'] ?? '') === 'DESC') ? 'selected' : ''; ?>>Descending</option>
                        </select>
                    </div>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="Property.php" class="btn btn-secondary" style="text-decoration: none; display: inline-flex; align-items: center;">Reset</a>
                </div>
            </form>
            
            <?php
                $result = getProperties($conn);

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
                    echo '<div class="no-data">No properties found in the system</div>';
                }
            ?>
        </div>
    </section>

    <!-- ADD TAB -->
    <section id="add-tab" class="tab-content">
        <div class="form-section">
            <h2>Add a New Property</h2>
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
                        <label for="property_type">Property Type: <span style="color: red;">*</span></label>
                        <select id="property_type" name="property_type" required>
                            <option value="House">House</option>
                            <option value="Apartment">Apartment</option>
                            <option value="Garden">Garden</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="rent_sale">Rent or Sale: <span style="color: red;">*</span></label>
                        <select id="rent_sale" name="rent_sale" required>
                            <option value="Rent">Rent</option>
                            <option value="Sale">Sale</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="addres">Address: <span style="color: red;">*</span></label>
                        <input type="text" id="addres" name="addres" required>
                    </div>

                    <div class="form-group">
                        <label for="city">City: <span style="color: red;">*</span></label>
                        <input type="text" id="city" name="city" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="zip_code">Zip Code: <span style="color: red;">*</span></label>
                        <input type="text" id="zip_code" name="zip_code" required>
                    </div>

                    <div class="form-group">
                        <label for="area_property">Area (m²):</label>
                        <input type="text" id="area_property" name="area_property">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="bedrooms">Number of Bedrooms:</label>
                        <input type="number" id="bedrooms" name="bedrooms" min="0">
                    </div>

                    <div class="form-group">
                        <label for="price">Price:</label>
                        <input type="number" id="price" name="price" min="0" step="0.01">
                    </div>
                </div>

                <div class="form-group">
                    <label for="landlord_id">Landlord ID: <span style="color: red;">*</span></label>
                    <input type="number" id="landlord_id" name="landlord_id" required>
                </div>

                <div class="form-group">
                    <label for="descriptions">Descriptions:</label>
                    <textarea id="descriptions" name="descriptions" rows="4"></textarea>
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
            <h2>Delete Property Records</h2>
            <p>Enter the Property ID to delete the record:</p>

            <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'delete' && !empty($message)) {
                    echo '<div class="message ' . $message_type . '">' . $message . '</div>';
                }
            ?>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="action" value="delete">
                
                <div class="form-group">
                    <label for="property_id">Property ID: <span style="color: red;">*</span></label>
                    <input type="number" id="property_id" name="property_id" required>
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
