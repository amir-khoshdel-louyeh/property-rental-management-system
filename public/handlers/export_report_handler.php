<?php

function getEntityExportConfigs() {
    return [
        'landlords' => [
            'label' => 'Landlords',
            'table' => 'Landlord',
            'primaryKey' => 'landlord_id'
        ],
        'renters' => [
            'label' => 'Renters',
            'table' => 'Renter',
            'primaryKey' => 'renter_id'
        ],
        'properties' => [
            'label' => 'Properties',
            'table' => 'Property',
            'primaryKey' => 'property_id'
        ],
        'rentals' => [
            'label' => 'Rentals',
            'table' => 'Rental',
            'primaryKey' => 'rental_id'
        ],
        'payments' => [
            'label' => 'Payments',
            'table' => 'Payment',
            'primaryKey' => 'payment_id'
        ],
        'services' => [
            'label' => 'Services',
            'table' => 'services',
            'primaryKey' => 'service_id'
        ],
        'inspections' => [
            'label' => 'Inspections',
            'table' => 'Inspection',
            'primaryKey' => 'inspection_id'
        ],
        'propertyservices' => [
            'label' => 'Property Services',
            'table' => 'PropertyServices',
            'primaryKey' => 'property_service_id'
        ]
    ];
}

function fetchRowsForEntityExport($conn, $entityKey, $limit = 5000) {
    $configs = getEntityExportConfigs();
    if (!isset($configs[$entityKey])) {
        return [
            'success' => false,
            'error' => 'Unknown entity selected.'
        ];
    }

    $config = $configs[$entityKey];
    $table = $config['table'];
    $primaryKey = $config['primaryKey'];

    if (!isSafeExportIdentifier($table) || !isSafeExportIdentifier($primaryKey)) {
        return [
            'success' => false,
            'error' => 'Invalid export configuration.'
        ];
    }

    $safeLimit = intval($limit);
    if ($safeLimit < 1) {
        $safeLimit = 1;
    }
    if ($safeLimit > 20000) {
        $safeLimit = 20000;
    }

    $sql = "SELECT * FROM {$table} ORDER BY {$primaryKey} ASC LIMIT {$safeLimit}";
    $result = $conn->query($sql);

    if ($result === false) {
        return [
            'success' => false,
            'error' => $conn->error
        ];
    }

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    return [
        'success' => true,
        'rows' => $rows,
        'label' => $config['label']
    ];
}

function streamCsvDownload($filename, $rows) {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '.csv"');

    $out = fopen('php://output', 'w');
    fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

    if (empty($rows)) {
        fputcsv($out, ['No data']);
        fclose($out);
        return;
    }

    fputcsv($out, array_keys($rows[0]));
    foreach ($rows as $row) {
        fputcsv($out, $row);
    }

    fclose($out);
}

function streamExcelDownload($filename, $rows) {
    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '.xls"');

    echo "\xEF\xBB\xBF";

    if (empty($rows)) {
        echo "No data\n";
        return;
    }

    $headers = array_keys($rows[0]);
    echo implode("\t", $headers) . "\n";

    foreach ($rows as $row) {
        $cells = [];
        foreach ($headers as $header) {
            $value = isset($row[$header]) ? (string)$row[$header] : '';
            $value = str_replace(["\t", "\r", "\n"], ' ', $value);
            $cells[] = $value;
        }
        echo implode("\t", $cells) . "\n";
    }
}

function buildSystemSummaryLines($conn) {
    $lines = [];

    $countQueries = [
        'Total Landlords' => 'SELECT COUNT(*) AS c FROM Landlord',
        'Total Renters' => 'SELECT COUNT(*) AS c FROM Renter',
        'Total Properties' => 'SELECT COUNT(*) AS c FROM Property',
        'Total Rentals' => 'SELECT COUNT(*) AS c FROM Rental',
        'Total Payments' => 'SELECT COUNT(*) AS c FROM Payment',
        'Total Services' => 'SELECT COUNT(*) AS c FROM services',
        'Total Inspections' => 'SELECT COUNT(*) AS c FROM Inspection'
    ];

    foreach ($countQueries as $label => $sql) {
        $result = $conn->query($sql);
        $value = 0;
        if ($result !== false) {
            $row = $result->fetch_assoc();
            $value = intval($row['c'] ?? 0);
        }
        $lines[] = $label . ': ' . $value;
    }

    $financialQueries = [
        'Total Monthly Rent (active records)' => 'SELECT COALESCE(SUM(monthly_rent), 0) AS total FROM Rental',
        'Total Security Deposits' => 'SELECT COALESCE(SUM(security_deposit), 0) AS total FROM Rental',
        'Total Payments Amount' => 'SELECT COALESCE(SUM(amount), 0) AS total FROM Payment'
    ];

    foreach ($financialQueries as $label => $sql) {
        $result = $conn->query($sql);
        $value = 0.0;
        if ($result !== false) {
            $row = $result->fetch_assoc();
            $value = floatval($row['total'] ?? 0);
        }
        $lines[] = $label . ': ' . number_format($value, 2);
    }

    $lines[] = 'Generated at: ' . date('Y-m-d H:i:s');

    return $lines;
}

function streamSimplePdf($filename, $title, $lines) {
    $pdfContent = buildSimplePdfContent($title, $lines);

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '.pdf"');
    header('Content-Length: ' . strlen($pdfContent));

    echo $pdfContent;
}

function buildSimplePdfContent($title, $lines) {
    $escapedTitle = escapePdfText($title);
    $contentLines = ['BT', '/F1 16 Tf', '50 800 Td', '(' . $escapedTitle . ') Tj'];

    $y = 780;
    foreach ($lines as $line) {
        if ($y < 60) {
            break;
        }

        $escapedLine = escapePdfText($line);
        $contentLines[] = 'BT';
        $contentLines[] = '/F1 11 Tf';
        $contentLines[] = '50 ' . $y . ' Td';
        $contentLines[] = '(' . $escapedLine . ') Tj';
        $contentLines[] = 'ET';
        $y -= 16;
    }

    $stream = implode("\n", $contentLines) . "\nET";

    $objects = [];
    $objects[] = '1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj';
    $objects[] = '2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj';
    $objects[] = '3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 5 0 R >> >> /Contents 4 0 R >> endobj';
    $objects[] = '4 0 obj << /Length ' . strlen($stream) . ' >> stream' . "\n" . $stream . 'endstream endobj';
    $objects[] = '5 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj';

    $pdf = "%PDF-1.4\n";
    $offsets = [0];

    foreach ($objects as $object) {
        $offsets[] = strlen($pdf);
        $pdf .= $object . "\n";
    }

    $xrefOffset = strlen($pdf);
    $pdf .= 'xref' . "\n";
    $pdf .= '0 6' . "\n";
    $pdf .= '0000000000 65535 f ' . "\n";

    for ($i = 1; $i <= 5; $i++) {
        $pdf .= sprintf('%010d 00000 n ', $offsets[$i]) . "\n";
    }

    $pdf .= 'trailer << /Size 6 /Root 1 0 R >>' . "\n";
    $pdf .= 'startxref' . "\n";
    $pdf .= $xrefOffset . "\n";
    $pdf .= '%%EOF';

    return $pdf;
}

function fetchLeaseTemplateData($conn, $rentalId) {
    $sql = "
        SELECT
            r.rental_id,
            r.start_date,
            r.end_date,
            r.monthly_rent,
            r.security_deposit,
            p.property_id,
            p.property_type,
            p.addres,
            p.city,
            p.zip_code,
            t.renter_id,
            t.first_name AS renter_first_name,
            t.last_name AS renter_last_name,
            t.email AS renter_email,
            t.phone_number AS renter_phone,
            l.landlord_id,
            l.first_name AS landlord_first_name,
            l.last_name AS landlord_last_name,
            l.email AS landlord_email,
            l.phone_number AS landlord_phone
        FROM Rental r
        INNER JOIN Property p ON p.property_id = r.property_id
        INNER JOIN Renter t ON t.renter_id = r.renter_id
        INNER JOIN Landlord l ON l.landlord_id = p.landlord_id
        WHERE r.rental_id = ?
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return null;
    }

    $id = intval($rentalId);
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        return null;
    }

    $result = $stmt->get_result();
    if (!$result) {
        return null;
    }

    return $result->fetch_assoc();
}

function isSafeExportIdentifier($identifier) {
    return preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $identifier) === 1;
}

function escapePdfText($value) {
    $escaped = str_replace('\\', '\\\\', (string)$value);
    $escaped = str_replace('(', '\\(', $escaped);
    $escaped = str_replace(')', '\\)', $escaped);
    $escaped = preg_replace('/[^\x20-\x7E]/', ' ', $escaped);
    return $escaped;
}
