<?php
    include("config/Database_Manager.php");
    include("config/Validation.php");
    include("handlers/renter_handler.php");
    include("layouts/Header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renter Management</title>
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
        .pagination-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }
        .pagination-links {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .pagination-link {
            padding: 0.5rem 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            text-decoration: none;
            color: #212529;
            background: #fff;
        }
        .pagination-link.active {
            background: #007bff;
            color: #fff;
            border-color: #007bff;
        }
        .pagination-link.disabled {
            pointer-events: none;
            color: #6c757d;
            background: #f8f9fa;
        }
        .pagination-meta {
            color: #495057;
            font-size: 0.95rem;
        }
        @media (max-width: 768px) {
            .search-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<main class="container">
    <section class="page-header">
        <h1>Renter Management</h1>
        <p class="subtitle">Manage all renter information and details</p>
    </section>

    <div class="tabs-container">
        <button class="tab-button active" onclick="openTab(event, 'view-tab')">View Renters</button>
        <button class="tab-button" onclick="openTab(event, 'add-tab')">Add New Renter</button>
        <button class="tab-button" onclick="openTab(event, 'delete-tab')">Delete Renter</button>
    </div>

    <!-- VIEW TAB -->
    <section id="view-tab" class="tab-content active">
        <div class="form-section">
            <h2>Renter Information</h2>
            <p>View all registered renters in the system:</p>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                <div class="search-grid">
                    <div class="form-group">
                        <label for="q">Global Search</label>
                        <input type="text" id="q" name="q" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>" placeholder="Name, phone, email...">
                    </div>

                    <div class="form-group">
                        <label for="first_name_filter">First Name</label>
                        <input type="text" id="first_name_filter" name="first_name" value="<?php echo htmlspecialchars($_GET['first_name'] ?? ''); ?>" placeholder="Filter first name">
                    </div>

                    <div class="form-group">
                        <label for="last_name_filter">Last Name</label>
                        <input type="text" id="last_name_filter" name="last_name" value="<?php echo htmlspecialchars($_GET['last_name'] ?? ''); ?>" placeholder="Filter last name">
                    </div>

                    <div class="form-group">
                        <label for="email_filter">Email</label>
                        <input type="text" id="email_filter" name="email" value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>" placeholder="Filter email">
                    </div>

                    <div class="form-group">
                        <label for="sort">Sort By</label>
                        <select id="sort" name="sort">
                            <option value="renter_id" <?php echo (($_GET['sort'] ?? '') === 'renter_id') ? 'selected' : ''; ?>>Renter ID</option>
                            <option value="first_name" <?php echo (($_GET['sort'] ?? '') === 'first_name') ? 'selected' : ''; ?>>First Name</option>
                            <option value="last_name" <?php echo (($_GET['sort'] ?? '') === 'last_name') ? 'selected' : ''; ?>>Last Name</option>
                            <option value="email" <?php echo (($_GET['sort'] ?? '') === 'email') ? 'selected' : ''; ?>>Email</option>
                            <option value="date_of_birth" <?php echo (($_GET['sort'] ?? '') === 'date_of_birth') ? 'selected' : ''; ?>>Date of Birth</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="order">Sort Order</label>
                        <select id="order" name="order">
                            <option value="ASC" <?php echo (strtoupper($_GET['order'] ?? 'ASC') === 'ASC') ? 'selected' : ''; ?>>Ascending</option>
                            <option value="DESC" <?php echo (strtoupper($_GET['order'] ?? '') === 'DESC') ? 'selected' : ''; ?>>Descending</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="limit">Rows Per Page</label>
                        <select id="limit" name="limit">
                            <option value="10" <?php echo (($_GET['limit'] ?? '25') === '10') ? 'selected' : ''; ?>>10</option>
                            <option value="25" <?php echo (($_GET['limit'] ?? '25') === '25') ? 'selected' : ''; ?>>25</option>
                            <option value="50" <?php echo (($_GET['limit'] ?? '') === '50') ? 'selected' : ''; ?>>50</option>
                            <option value="100" <?php echo (($_GET['limit'] ?? '') === '100') ? 'selected' : ''; ?>>100</option>
                        </select>
                    </div>
                </div>

                <input type="hidden" name="page" value="1">

                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="Renter.php" class="btn btn-secondary" style="text-decoration: none; display: inline-flex; align-items: center;">Reset</a>
                </div>
            </form>
            
            <?php
                $result = getRenters($conn);
                $pagination = getRenterPagination();

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

                    $currentPage = intval($pagination['page'] ?? 1);
                    $totalPages = intval($pagination['total_pages'] ?? 1);
                    $totalRows = intval($pagination['total_rows'] ?? 0);

                    $baseParams = $_GET;
                    unset($baseParams['page']);

                    $prevClass = $currentPage <= 1 ? 'pagination-link disabled' : 'pagination-link';
                    $nextClass = $currentPage >= $totalPages ? 'pagination-link disabled' : 'pagination-link';
                    $prevUrl = htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query(array_merge($baseParams, ['page' => max(1, $currentPage - 1)])));
                    $nextUrl = htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query(array_merge($baseParams, ['page' => min($totalPages, $currentPage + 1)])));

                    echo '<div class="pagination-bar">';
                    echo '<div class="pagination-meta">Total: ' . $totalRows . ' records | Page ' . $currentPage . ' of ' . $totalPages . '</div>';
                    echo '<div class="pagination-links">';
                    echo '<a class="' . $prevClass . '" href="' . $prevUrl . '">Prev</a>';

                    for ($i = 1; $i <= $totalPages; $i++) {
                        if ($i === 1 || $i === $totalPages || abs($i - $currentPage) <= 1) {
                            $pageClass = $i === $currentPage ? 'pagination-link active' : 'pagination-link';
                            $pageUrl = htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query(array_merge($baseParams, ['page' => $i])));
                            echo '<a class="' . $pageClass . '" href="' . $pageUrl . '">' . $i . '</a>';
                        }
                    }

                    echo '<a class="' . $nextClass . '" href="' . $nextUrl . '">Next</a>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo '<div class="no-data">No renters found in the system</div>';
                }
            ?>
        </div>
    </section>

    <!-- ADD TAB -->
    <section id="add-tab" class="tab-content">
        <div class="form-section">
            <h2>Add a New Renter</h2>
            <p>Please complete the form and press the Submit button:</p>
            
            <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add' && !empty($message)) {
                    echo '<div class="message ' . $message_type . '">' . $message . '</div>';
                }
            ?>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="action" value="add">
                
                <div class="form-group">
                    <label for="first_name">First Name: <span style="color: red;">*</span></label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name: <span style="color: red;">*</span></label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>

                <div class="form-group">
                    <label for="phone_number">Phone Number:</label>
                    <input type="text" id="phone_number" name="phone_number">
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email">
                </div>

                <div class="form-group">
                    <label for="date_of_birth">Date of Birth:</label>
                    <input type="date" id="date_of_birth" name="date_of_birth">
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
            <h2>Delete Renter Records</h2>
            <p>Enter the Renter ID to delete the record:</p>

            <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'delete' && !empty($message)) {
                    echo '<div class="message ' . $message_type . '">' . $message . '</div>';
                }
            ?>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="action" value="delete">
                
                <div class="form-group">
                    <label for="renter_id">Renter ID: <span style="color: red;">*</span></label>
                    <input type="number" id="renter_id" name="renter_id" required>
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
