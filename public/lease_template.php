<?php
include('config/Database_Manager.php');
include('layouts/Header.php');
require_once __DIR__ . '/handlers/export_report_handler.php';

$rentalId = $_GET['rental_id'] ?? '';
$leaseData = null;
$error = '';

if ($rentalId !== '') {
    if (!is_numeric($rentalId)) {
        $error = 'Rental ID must be numeric.';
    } else {
        $leaseData = fetchLeaseTemplateData($conn, intval($rentalId));
        if (!$leaseData) {
            $error = 'No rental record found for the given ID.';
        }
    }
}

if (isset($_GET['download']) && $_GET['download'] === 'pdf' && $leaseData) {
    $title = 'Lease Agreement - Rental #' . $leaseData['rental_id'];
    $lines = [
        'LEASE AGREEMENT TEMPLATE',
        'Agreement Date: ' . date('Y-m-d'),
        '',
        'Landlord: ' . $leaseData['landlord_first_name'] . ' ' . $leaseData['landlord_last_name'],
        'Landlord Contact: ' . ($leaseData['landlord_email'] ?: '-') . ' / ' . ($leaseData['landlord_phone'] ?: '-'),
        '',
        'Tenant: ' . $leaseData['renter_first_name'] . ' ' . $leaseData['renter_last_name'],
        'Tenant Contact: ' . ($leaseData['renter_email'] ?: '-') . ' / ' . ($leaseData['renter_phone'] ?: '-'),
        '',
        'Property: ' . $leaseData['property_type'] . ', ' . $leaseData['addres'] . ', ' . $leaseData['city'] . ' ' . $leaseData['zip_code'],
        'Lease Period: ' . $leaseData['start_date'] . ' to ' . $leaseData['end_date'],
        'Monthly Rent: ' . number_format((float)$leaseData['monthly_rent'], 2),
        'Security Deposit: ' . number_format((float)$leaseData['security_deposit'], 2),
        '',
        'Terms:',
        '- Rent is due on the first day of each month.',
        '- Tenant must maintain the property in good condition.',
        '- Landlord handles structural repairs unless damage is tenant-caused.',
        '- Any lease changes must be in writing and signed by both parties.',
        '',
        'Landlord Signature: ______________________',
        'Tenant Signature: ________________________'
    ];

    streamSimplePdf('lease_agreement_' . $leaseData['rental_id'], $title, $lines);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lease Agreement Template</title>
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
        .error {
            color: #842029;
            background: #f8d7da;
            border: 1px solid #f5c2c7;
            padding: 0.75rem 1rem;
            border-radius: 0.25rem;
            margin-top: 1rem;
        }
        .lease {
            margin-top: 1rem;
            line-height: 1.6;
            white-space: normal;
        }
        .lease h3 {
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
        .signature-row {
            margin-top: 2rem;
            display: flex;
            justify-content: space-between;
            gap: 1rem;
        }
        .signature-box {
            width: 48%;
            border-top: 1px solid #333;
            padding-top: 0.5rem;
        }
    </style>
</head>
<body>
<main class="container">
    <section class="page-header">
        <h1>Lease Agreement Template</h1>
        <p class="subtitle">Generate a lease template from an existing rental record.</p>
    </section>

    <section class="panel">
        <form action="lease_template.php" method="get">
            <div class="form-group">
                <label for="rental_id">Rental ID</label>
                <input type="number" id="rental_id" name="rental_id" min="1" required value="<?php echo htmlspecialchars((string)$rentalId); ?>">
            </div>
            <div class="actions">
                <button class="btn" type="submit">Load Template</button>
            </div>
        </form>

        <?php if ($error !== ''): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($leaseData): ?>
            <div class="actions">
                <a class="btn secondary" href="lease_template.php?<?php echo htmlspecialchars(http_build_query(['rental_id' => $leaseData['rental_id'], 'download' => 'pdf'])); ?>">Download Lease PDF</a>
            </div>

            <div class="lease">
                <h3>Lease Agreement</h3>
                <p>This Lease Agreement is made between <strong><?php echo htmlspecialchars($leaseData['landlord_first_name'] . ' ' . $leaseData['landlord_last_name']); ?></strong> (Landlord) and <strong><?php echo htmlspecialchars($leaseData['renter_first_name'] . ' ' . $leaseData['renter_last_name']); ?></strong> (Tenant).</p>

                <p><strong>Property:</strong> <?php echo htmlspecialchars($leaseData['property_type'] . ', ' . $leaseData['addres'] . ', ' . $leaseData['city'] . ' ' . $leaseData['zip_code']); ?></p>
                <p><strong>Lease Term:</strong> <?php echo htmlspecialchars($leaseData['start_date']); ?> to <?php echo htmlspecialchars($leaseData['end_date']); ?></p>
                <p><strong>Monthly Rent:</strong> <?php echo htmlspecialchars(number_format((float)$leaseData['monthly_rent'], 2)); ?></p>
                <p><strong>Security Deposit:</strong> <?php echo htmlspecialchars(number_format((float)$leaseData['security_deposit'], 2)); ?></p>

                <h3>Terms and Conditions</h3>
                <p>1. Rent is due on the first day of each month.</p>
                <p>2. Tenant agrees to maintain the premises and report damages promptly.</p>
                <p>3. Landlord is responsible for structural and major system repairs, unless caused by tenant negligence.</p>
                <p>4. Any modifications to this agreement must be in writing and signed by both parties.</p>

                <div class="signature-row">
                    <div class="signature-box">Landlord Signature</div>
                    <div class="signature-box">Tenant Signature</div>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <a href="index.php" class="back-link">Back to Home</a>
</main>
</body>
<?php
include('layouts/Footer.html');
mysqli_close($conn);
?>
</html>
