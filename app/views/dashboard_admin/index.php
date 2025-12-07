<?php
// Pastikan variabel page terisi
$page = $_GET['page'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $data['title'] ?? 'Dashboard Admin'; ?> - PawTopia</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-orange: #fd7e14;
            --primary-orange-hover: #e36d0d;
            --bg-light: #f3f4f6;
            --sidebar-width: 250px;
        }

        body { 
            background-color: var(--bg-light); 
            font-family: 'Inter', 'Segoe UI', sans-serif;
            min-height: 100vh; 
            overflow-x: hidden;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            position: fixed; top: 0; left: 0; height: 100vh; 
            width: var(--sidebar-width);
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            z-index: 1000;
            display: flex; flex-direction: column; padding: 20px;
            transition: all 0.3s ease-in-out; /* Tambah transisi halus */
        }

        .logo-area { text-align: center; margin-bottom: 25px; }
        .user-profile-mini { 
            display: flex; align-items: center; background: #fff7ed; 
            padding: 10px 15px; border-radius: 12px; margin-bottom: 20px; 
        }

        /* Nav Link */
        .nav-custom .nav-link {
            color: #4b5563; padding: 10px 15px; margin-bottom: 5px; 
            border-radius: 8px; font-weight: 500; display: flex; align-items: center;
            transition: all 0.2s; font-size: 0.95rem;
        }
        .nav-custom .nav-link:hover {
            background-color: #fff7ed; color: var(--primary-orange);
        }
        .nav-custom .nav-link.active {
            background-color: var(--primary-orange); color: white;
            box-shadow: 0 2px 5px rgba(253, 126, 20, 0.2);
        }
        .nav-custom .nav-link i { width: 24px; margin-right: 12px; font-size: 1.1rem; }

        .logout-btn { margin-top: auto; border-top: 1px solid #f3f4f6; padding-top: 15px; }
        .logout-btn a { 
            color: #ef4444; font-weight: 600; text-decoration: none; 
            display: flex; align-items: center; padding: 10px; border-radius: 8px;
        }
        .logout-btn a:hover { background-color: #fef2f2; }

        /* --- MAIN CONTENT --- */
        .main-content { 
            margin-left: var(--sidebar-width); 
            padding: 20px;
            min-height: 100vh;
            width: calc(100% - var(--sidebar-width));
            transition: all 0.3s ease-in-out; /* Tambah transisi */
        }
        
        .page-header {
            display: flex; justify-content: space-between; align-items: center; 
            margin-bottom: 25px;
        }
        .page-title { 
            font-weight: 800; color: #1f2937; font-size: 1.5rem; letter-spacing: -0.5px; 
        }

        .content-wrapper {
            background: white; padding: 25px; border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05); width: 100%;
        }

        .card-stat {
            background: white; border-radius: 16px; padding: 25px; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #f3f4f6;
            height: 100%; transition: transform 0.2s;
        }
        .card-stat:hover { transform: translateY(-3px); border-color: #fd7e14; }
        .card-stat .big-number { font-size: 2.5rem; font-weight: 700; color: #111827; margin-top: 5px; }
        .card-stat h5 { font-size: 0.9rem; color: #6b7280; font-weight: 600; text-transform: uppercase; }
        .icon-stat { font-size: 1.8rem; color: var(--primary-orange); margin-bottom: 15px; }

        /* --- RESPONSIVE MOBILE (LAYAR < 992px) --- */
        @media (max-width: 991.98px) {
            /* Sembunyikan sidebar ke kiri layar */
            .sidebar {
                left: -250px; 
            }
            /* Class ini ditambahkan lewat JS saat tombol menu dipencet */
            .sidebar.active {
                left: 0;
            }
            
            /* Konten jadi full width */
            .main-content {
                margin-left: 0;
                width: 100%;
            }

            /* Overlay hitam transparan saat menu terbuka */
            .sidebar-overlay {
                display: none;
                position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(0,0,0,0.5); z-index: 999;
            }
            .sidebar-overlay.active {
                display: block;
            }
        }
    </style>
</head>
<body>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <nav class="sidebar" id="sidebar">
        <div class="logo-area">
             <img src="<?= BASEURL; ?>/images/logo_paw.png" alt="PawTopia" height="60">
        </div>

        <div class="user-profile-mini">
            <div style="line-height: 1.2;">
                <strong class="d-block text-dark"><?= explode(' ', $data['admin_info']['nama_lengkap'])[0] ?? 'Admin'; ?></strong>
                <small style="color: var(--primary-orange); font-size: 0.8rem;">Admin</small>
            </div>
        </div>

        <div class="nav-custom d-flex flex-column mb-auto">
            <a href="<?= BASEURL; ?>/DashboardAdmin?page=dashboard" class="nav-link <?= $page == 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            
            <a href="<?= BASEURL; ?>/DashboardAdmin?page=manajemen_pengguna" class="nav-link <?= $page == 'manajemen_pengguna' ? 'active' : '' ?>">
                <i class="fas fa-users"></i> Pengguna
            </a>
            
            <a href="<?= BASEURL; ?>/DashboardAdmin?page=manajemen_mitra" class="nav-link <?= $page == 'manajemen_mitra' ? 'active' : '' ?>">
                <i class="fas fa-store"></i> Mitra Petshop
            </a>
            <a href="<?= BASEURL; ?>/DashboardAdmin?page=laporan" class="nav-link <?= $page == 'laporan' ? 'active' : '' ?>">
                <i class="fas fa-file-alt"></i> Laporan Sistem
            </a>
        </div>

        <div class="logout-btn">
            <a href="<?= BASEURL; ?>auth/logout" onclick="return confirm('Yakin ingin keluar?');">
                <i class="fas fa-sign-out-alt me-2"></i> Keluar
            </a>
        </div>
    </nav>

    <main class="main-content">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-warning border-0 me-3 d-lg-none" id="sidebarToggle">
                    <i class="fas fa-bars fa-lg text-dark"></i>
                </button>

                <div>
                    <h2 class="page-title mb-0"><?= strtoupper($data['title']); ?></h2>
                    <p class="text-muted mb-0 d-none d-md-block">Selamat datang kembali, Admin!</p>
                </div>
            </div>
            <div class="text-muted d-none d-sm-block">
                <i class="far fa-calendar-alt me-2"></i> <?= date('d M Y'); ?>
            </div>
        </div>

        <?php if ($page === 'dashboard'): ?>
            
            <div class="row g-4 mb-5">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card-stat">
                        <i class="fas fa-handshake icon-stat"></i>
                        <h5>Mitra Aktif</h5>
                        <div class="big-number"><?= $data['stats']['total_mitra'] ?? '0'; ?></div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card-stat">
                        <i class="fas fa-shopping-cart icon-stat"></i>
                        <h5>Transaksi</h5>
                        <div class="big-number"><?= $data['stats']['total_transaksi'] ?? '0'; ?></div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card-stat">
                        <i class="fas fa-door-open icon-stat"></i>
                        <h5>Kamar Terisi</h5>
                        <div class="big-number">8 <span class="fs-6 text-muted fw-normal">/ 20</span></div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card-stat">
                        <i class="fas fa-users icon-stat"></i>
                        <h5>Total Pengguna</h5>
                        <div class="big-number"><?= $data['stats']['total_user'] ?? '0'; ?></div>
                    </div>
                </div>
            </div>

            <div class="alert alert-success d-flex align-items-center shadow-sm border-0 rounded-4 p-4" style="background-color: #d1e7dd; color: #0f5132;">
                <i class="fas fa-check-circle fs-3 me-3"></i>
                <div>
                    <h6 class="fw-bold mb-1">Sistem Berjalan Normal</h6>
                    <p class="mb-0 small">Semua layanan PawTopia beroperasi dengan baik hari ini.</p>
                </div>
            </div>

        <?php else: ?>

            <div class="content-wrapper">
                <?php 
                    if (isset($data['content_view'])) {
                        $full_path = '../app/views/' . $data['content_view'] . '.php';
                        
                        if (file_exists($full_path)) {
                            require_once $full_path; 
                        } else {
                            echo '<div class="text-center py-5">
                                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                    <h5 class="text-muted">File View Tidak Ditemukan</h5>
                                    <p class="text-muted small">Path: '.$data['content_view'].'</p>
                                  </div>';
                        }
                    }
                ?>
            </div>

        <?php endif; ?>
    </main>

    <?php if (isset($_SESSION['flash'])): ?>
    <script>
    Swal.fire({
        title: "<?= $_SESSION['flash']['pesan'] ?? 'Notifikasi'; ?>",
        text: "<?= $_SESSION['flash']['aksi'] ?? ''; ?>",
        icon: "<?= $_SESSION['flash']['tipe'] ?? 'info'; ?>",
        confirmButtonColor: "#fd7e14"
    });
    </script>
    <?php unset($_SESSION['flash']); endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        // Fungsi buka/tutup
        function toggleSidebar() {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        }

        // Event listener tombol
        sidebarToggle.addEventListener('click', toggleSidebar);
        
        // Tutup sidebar jika overlay diklik (klik di luar menu)
        sidebarOverlay.addEventListener('click', toggleSidebar);
    </script>
</body>
</html>