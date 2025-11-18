<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $data['title'] ?? 'Pawtopia'; ?></title>

</head>
<body>
    <?php 
    
    $currentPage = $_SERVER['REQUEST_URI'];

    $excludePaths = [
        '/auth/login', 
        '/auth/register', 
        '/DashboardMitra',
        '/DashboardCustomer'
    ];

    $shouldExclude = FALSE;
    foreach ($excludePaths as $path) {
        if (str_contains($currentPage, $path)) {
            $shouldExclude = TRUE;
            break;
        }
    }

    if (!$shouldExclude) {
        require_once __DIR__ . '/header.php'; 
    }
    ?>

    <main class="container">
        <?php require_once $viewPath; ?>
    </main>

    <?php 
    if (!$shouldExclude) {
        require_once __DIR__ . '/footer.php'; 
    }
    ?>
</body>
</html>