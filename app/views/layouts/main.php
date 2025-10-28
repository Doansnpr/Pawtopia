<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $data['title'] ?? 'Pawtopia'; ?></title>
    <link rel="stylesheet" href="<?= BASEURL ?>/css/style.css">
</head>
<body>
    <?php require_once 'header.php'; ?>

    <main class="container">
        <?php require_once $viewPath; ?>
    </main>

    <?php require_once 'footer.php'; ?>
</body>
</html>
