<?php
include('config/Database_Manager.php');
include('layouts/Header.php');
require_once __DIR__ . '/handlers/export_report_handler.php';

$type = $_GET['type'] ?? 'system';

if (isset($_GET['download']) && $_GET['download'] === 'pdf') {
    $lines = [];
    $title = 'System Summary Report';

    if ($type === 'finance') {
        $title = 'Financial Summary Report';
        $allLines = buildSystemSummaryLines($conn);
        foreach ($allLines as $line) {
            if (strpos($line, 'Total Monthly Rent') === 0 || strpos($line, 'Total Security Deposits') === 0 || strpos($line, 'Total Payments Amount') === 0 || strpos($line, 'Generated at:') === 0) {
                $lines[] = $line;
            }
        }
    } else {
        $lines = buildSystemSummaryLines($conn);
    }

    streamSimplePdf(strtolower(str_replace(' ', '_', $title)) . '_' . date('Ymd_His'), $title, $lines);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Reports</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/forms.css">
    <link rel="stylesheet" href="css/utilities.css">
    <style>
        .panel {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-top: 1rem;
        }
        .actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.25rem;
            border-radius: 0.25rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            color: #fff;
            background: #007bff;
        }
    </style>
</head>
<body>
<main class="container">
    <section class="page-header">
        <h1>PDF Reports</h1>
        <p class="subtitle">Generate downloadable PDF summaries for operations and finance.</p>
    </section>

    <section class="panel">
        <form action="reports.php" method="get">
            <div class="form-group">
                <label for="type">Report Type</label>
                <select id="type" name="type">
                    <option value="system" <?php echo $type === 'system' ? 'selected' : ''; ?>>System Summary</option>
                    <option value="finance" <?php echo $type === 'finance' ? 'selected' : ''; ?>>Financial Summary</option>
                </select>
            </div>

            <div class="actions">
                <button class="btn" type="submit" name="download" value="pdf">Download PDF</button>
            </div>
        </form>
    </section>

    <a href="index.php" class="back-link">Back to Home</a>
</main>
</body>
<?php
include('layouts/Footer.html');
mysqli_close($conn);
?>
</html>
