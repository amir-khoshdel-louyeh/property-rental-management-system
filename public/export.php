<?php
include('config/Database_Manager.php');
include('layouts/Header.php');
require_once __DIR__ . '/handlers/export_report_handler.php';

$entityKey = $_GET['entity'] ?? 'properties';
$format = strtolower($_GET['format'] ?? '');
$limit = $_GET['limit'] ?? 5000;
$error = '';

if ($format === 'csv' || $format === 'excel') {
    $exportData = fetchRowsForEntityExport($conn, $entityKey, $limit);

    if (!$exportData['success']) {
        $error = $exportData['error'];
    } else {
        $filename = strtolower(str_replace(' ', '_', $exportData['label'])) . '_' . date('Ymd_His');
        if ($format === 'csv') {
            streamCsvDownload($filename, $exportData['rows']);
            exit;
        }

        streamExcelDownload($filename, $exportData['rows']);
        exit;
    }
}

$configs = getEntityExportConfigs();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Export</title>
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
        .btn.secondary {
            background: #198754;
        }
        .error {
            color: #842029;
            background: #f8d7da;
            border: 1px solid #f5c2c7;
            padding: 0.75rem 1rem;
            border-radius: 0.25rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
<main class="container">
    <section class="page-header">
        <h1>Export Data</h1>
        <p class="subtitle">Download records as CSV or Excel-compatible files.</p>
    </section>

    <?php if ($error !== ''): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <section class="panel">
        <form action="export.php" method="get">
            <div class="form-group">
                <label for="entity">Entity</label>
                <select id="entity" name="entity">
                    <?php foreach ($configs as $key => $config): ?>
                        <option value="<?php echo htmlspecialchars($key); ?>" <?php echo $entityKey === $key ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($config['label']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="limit">Max Rows</label>
                <input type="number" id="limit" name="limit" min="1" max="20000" value="<?php echo htmlspecialchars((string)$limit); ?>">
            </div>

            <div class="actions">
                <button class="btn" type="submit" name="format" value="csv">Download CSV</button>
                <button class="btn secondary" type="submit" name="format" value="excel">Download Excel (.xls)</button>
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
