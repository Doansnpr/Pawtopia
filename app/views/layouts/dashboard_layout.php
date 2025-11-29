<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SaaSBox Dashboard Mitra'; ?></title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- ✅ Tambahkan SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
<style>
    :root {
        --primary-blue: #f5990fff;
        --light-bg: #f9fafb;
        --main-bg: #ffffff;
        --border-color: #e5e7eb;
        --text-dark: #3f2512ff;
        --text-gray: #7e7c72ff;
        --placeholder-gray: #e5e7eb;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Inter', sans-serif;
    }

    body {
        background-color: var(--light-bg);
    }

    .header-bar {
        background-color: var(--main-bg);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 30px;
        border-bottom: 1px solid var(--border-color);
        height: 70px;
        overflow: hidden;
        position: fixed;
        top: 0;       
        left: 0;      
        right: 0;     
        width: 100%;  
        z-index: 1000;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
    
    .content-wrapper {
        max-width: 1400px;
        margin: 80px auto 20px auto; 
        background-color: var(--main-bg);
        border-radius: 12px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        min-height: 80vh;
    }

    .logo {
        display: flex;
        align-items: center;
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-dark);
    }

    .logo img {
        height: 120px; 
        width: 100px; 
    }

    .logo span {
        color: var(--primary-blue);
    }

    .nav-links {
        display: flex;
        gap: 25px;
        align-items: center;
        flex-grow: 1;
        justify-content: center;
    }

    .nav-item {
        text-decoration: none;
        color: var(--text-gray);
        padding: 8px 12px;
        border-radius: 6px;
        transition: background-color 0.2s, color 0.2s;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .nav-item.active {
        background-color: var(--primary-blue);
        color: #fff;
        font-weight: 500;
    }

    .nav-item:not(.active):hover {
        color: var(--text-dark);
    }
    
    .header-right {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .header-icon {
        font-size: 1.1rem;
        color: var(--text-gray);
        cursor: pointer;
    }

    .profile-pic {
        width: 40px;
        height: 40px;
        background-color: var(--placeholder-gray);
        border-radius: 50%;
        border: 1px solid var(--primary-blue);
    }
    
    .dashboard-content {
        padding: 30px;
        display: grid;
        gap: 20px;
        grid-template-columns: repeat(3, 1fr); 
        grid-template-rows: auto 1fr auto;
    }

    @media (max-width: 900px) {
        .nav-links {
            display: none; 
        }
        .dashboard-content {
            grid-template-columns: 1fr;
        }
    }
    </style>

</head>
<body>

<header class="header-bar main-navigation-bar">
    <div class="logo">  
         <img src="<?= BASEURL; ?>/images/logo_pawtopia.png" alt="">
    </div>
    
    <nav class="nav-links">
        <a href="?page=dashboard" class="nav-item <?= ($_GET['page'] ?? 'dashboard') === 'dashboard' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="?page=profil" class="nav-item <?= ($_GET['page'] ?? '') === 'profil' ? 'active' : ''; ?>" >
            <i class="fa-solid fa-user"></i> Profil
        </a>
        <a href="?page=reservasi" class="nav-item <?= ($_GET['page'] ?? '') === 'reservasi' ? 'active' : ''; ?>">
            <i class="fas fa-calendar-alt nav-item-icon"></i> Booking
        </a>
        <a href="?page=status" class="nav-item <?= ($_GET['page'] ?? '') === 'status' ? 'active' : ''; ?>">
            <i class="fas fa-cat"></i> Status
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-comment"></i> Ulasan
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-file"></i> Laporan
        </a>
    </nav>

    <div class="header-right">
        <i class="fas fa-bell header-icon"></i>
        <div class="profile-pic"></div>
    </div>
</header>

<div class="content-wrapper">
    <?php 
        if (isset($content)) {
            $full_path = '../app/views/' . $content . '.php';
            if (file_exists($full_path)) {
                require_once $full_path; 
            } else {
                 echo "Error: File Konten tidak ditemukan di jalur: " . htmlspecialchars($full_path);
            }
        }
    ?>
</div>

<!-- ✅ Tambah script pop-up SweetAlert -->
<?php if (isset($_SESSION['flash'])): ?>
<script>
Swal.fire({
    title: "<?= $_SESSION['flash']['pesan']; ?>",
    text: "<?= $_SESSION['flash']['aksi']; ?>",
    icon: "<?= $_SESSION['flash']['tipe']; ?>",
    confirmButtonColor: "#f5990f"
});
</script>
<?php unset($_SESSION['flash']); endif; ?>

</body>
</html>
