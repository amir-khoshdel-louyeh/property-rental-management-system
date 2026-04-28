<?php
if (!isset($pageTitle) || empty($pageTitle)) {
    $pageTitle = 'Application Page';
}

if (!isset($pageStyles)) {
    $pageStyles = '';
}

if (!isset($pageContent)) {
    $pageContent = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <?php include __DIR__ . "/CommonHeadLinks.php"; ?>
    <?php if (!empty($pageStyles)): ?>
    <style>
    <?php echo $pageStyles; ?>
    </style>
    <?php endif; ?>
</head>
<body>
    <?php include __DIR__ . "/../Header.php"; ?>
    <main class="container">
        <?php echo $pageContent; ?>
    </main>
    <?php include __DIR__ . "/../Footer.html"; ?>
</body>
</html>
