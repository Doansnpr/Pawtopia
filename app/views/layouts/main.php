<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $data['title'] ?? 'Pawtopia'; ?></title>
</head>
<body>
    <?php 
    // Jika halaman bukan login/register, tampilkan header
    $currentPage = $_SERVER['REQUEST_URI'];
    if (!str_contains($currentPage, '/auth/login') && !str_contains($currentPage, '/auth/register')) {
        require_once __DIR__ . '/header.php'; 
    }
    ?>

    <main class="container">
        <?php require_once $viewPath; ?>
    </main>

    <?php 
    // Jika halaman bukan login/register, tampilkan footer
    if (!str_contains($currentPage, '/auth/login') && !str_contains($currentPage, '/auth/register')) {
        require_once __DIR__ . '/footer.php'; 
    }
    ?>
</body>
</html>
