<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['title'] ?? 'Dashboard'); ?> - Pawtopia</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #f3b83f;
            --primary-dark: #d99f28;
            --primary-light: #dfd4b5ff;
            --text-dark: #333;
            --text-gray: #666;
            --bg-color: #fff3cd; /* Background sedikit lebih terang */
            --sidebar-width: 260px; /* Variabel lebar sidebar */
            --radius: 16px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: "Poppins", sans-serif;
            margin: 0;
            background-color: var(--bg-color);
            display: flex; 
            min-height: 100vh;
            color: var(--text-dark);
        }

        /* --- SIDEBAR --- */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: var(--sidebar-width);
            background-color: #ffffff;
            padding: 25px 20px;
            z-index: 1000;
            border-right: 1px solid #eee;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .sidebar .profile {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .sidebar .profile img {
            width: 120px; /* Logo sedikit lebih besar */
            transition: transform 0.3s;
        }
        
        .sidebar .profile img:hover { transform: scale(1.05); }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 20px;
            color: var(--text-gray);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .menu a:hover {
            background-color: var(--primary-light);
            color: var(--primary-dark);
        }

        .menu a.active {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 15px rgba(243, 184, 63, 0.3);
        }
        
        .menu a.active i { color: white; }
        .menu a i { width: 20px; text-align: center; font-size: 1.1rem; }

        .logout { margin-top: auto; padding-top: 20px; border-top: 1px solid #f0f0f0; }

        .logout a {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: #ef4444;
            font-weight: 600;
            text-decoration: none;
            padding: 12px;
            border-radius: 12px;
            background: #fef2f2;
            transition: 0.3s;
        }

        .logout a:hover { background: #fee2e2; }

        .main {
            /* Hapus width: 100%; dan ganti dengan calc() */
            margin-left: var(--sidebar-width); /* Geser sejauh sidebar */
            width: calc(100% - var(--sidebar-width)); /* KRITIS: Sisa lebar layar - lebar sidebar */
            padding: 50px 50px; 
            min-height: 100vh;
            transition: all 0.3s ease;
            overflow-x: hidden; /* Tambahkan ini untuk jaga-jaga, menghilangkan scroll horizontal */
        }
        /* Responsive Tablet/Mobile */
        @media (max-width: 992px) {
            :root { --sidebar-width: 80px; }
            .sidebar .menu a span, .sidebar .profile img, .logout a span { display: none; }
            .sidebar .profile::after { content: '\f1b0'; font-family: "Font Awesome 6 Free"; font-weight: 900; font-size: 24px; color: var(--primary); }
            .menu a { justify-content: center; padding: 15px; }
            .main { padding: 20px; }
        }

        @media (max-width: 768px) {
            body { flex-direction: column; }
            .sidebar {
                width: 100%;
                height: auto;
                position: sticky;
                top: 0;
                flex-direction: row;
                align-items: center;
                padding: 10px 20px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                border-bottom: 2px solid var(--primary);
                border-right: none;
                overflow-x: auto;
            }
            .sidebar .profile { margin: 0; display: block; }
            .sidebar .profile img { display: block; width: 40px; }
            .sidebar .profile::after { display: none; }
            
            .menu { flex-direction: row; gap: 5px; margin: 0 15px; }
            .menu a { padding: 10px; font-size: 1.2rem; background: transparent !important; color: #aaa; box-shadow: none !important; }
            .menu a.active { color: var(--primary); }
            
            .logout { display: none; } /* Hide logout on mobile nav for space, add to menu usually */
            
            .main { margin-left: 0; padding: 20px 20px; }
        }
        
        .swal-rounded { border-radius: 20px !important; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="profile">
            <img src="<?= BASEURL; ?>/images/logo_paw.png" alt="Pawtopia">
        </div>

        <div class="menu">
            <a href="<?= BASEURL; ?>/DashboardCustomer" class="<?= ($data['title'] ?? '') === 'Dashboard' ? 'active' : ''; ?>"> 
                <i class="fas fa-home"></i> <span>Dashboard</span>
            </a>
            <a href="#"><i class="fa-solid fa-user"></i> <span>Profil</span></a>
            <a href="<?= BASEURL; ?>/DashboardCustomer/Penitipan" class="<?= ($data['title'] ?? '') === 'Cari Penitipan' ? 'active' : ''; ?>">
                <i class="fa-solid fa-magnifying-glass-location"></i> <span>Cari Penitipan</span>
            </a>
            <a href="<?= BASEURL; ?>/DashboardCustomer/Booking" class="<?= ($data['title'] ?? '') === 'Booking' ? 'active' : ''; ?>">
                <i class="fa-solid fa-receipt"></i> <span>Booking</span>
            </a>
            <a href="<?= BASEURL; ?>/DashboardCustomer/status_penitipan" class="<?= ($data['title'] ?? '') === 'Status' ? 'active' : ''; ?>">
                <i class="fa-solid fa-map-pin"></i> <span>Status</span>
            </a>
            <a href="<?= BASEURL; ?>/DashboardCustomer/ulasan" class="<?= ($data['title'] ?? '') === 'Beri Ulasan' ? 'active' : ''; ?>">
                <i class="fa-solid fa-comment-dots"></i> <span>Ulasan</span>
            </a>
        </div>

        <div class="logout">
            <a href="<?= BASEURL; ?>/home">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> <span>Keluar</span>
            </a>
        </div>
    </div>

    <div class="main">
        <?php
        $pathFile = __DIR__ . '/../' . ($data['content'] ?? 'dashboard_content') . '.php';
        if (isset($data['content']) && file_exists($pathFile)) {
            include $pathFile;
        } else {
            echo "<div style='padding:2rem; text-align:center;'>Content not found</div>";
        }
        ?>
    </div>

    <?php if (isset($_SESSION['flash'])): ?>
    <script>
    Swal.fire({
        title: "<?= $_SESSION['flash']['pesan']; ?>",
        text: "<?= $_SESSION['flash']['aksi']; ?>",
        icon: "<?= $_SESSION['flash']['tipe']; ?>",
        confirmButtonColor: "#f3b83f",
        customClass: { popup: 'swal-rounded' }
    });
    </script>
    <?php unset($_SESSION['flash']); endif; ?>

</body>
</html>