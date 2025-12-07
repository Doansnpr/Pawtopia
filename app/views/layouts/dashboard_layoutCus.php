<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['title'] ?? 'Dashboard'); ?></title>

<title><?= $data['title']; ?></title>

<title><?= $data['title']; ?></title>

 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<!-- ✅ Tambahkan SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: "Poppins", sans-serif;
            margin: 0;
            background-color: #fffaf0;
            display: flex;
            min-height: 100vh;
            color: #333;
            overflow-x: hidden; /* Mencegah scroll horizontal */
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background-color: #fff;
            border-right: 2px solid #f3b83f;
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease-in-out; /* Transisi halus */
            z-index: 1001; /* Di atas konten utama */
        }

        .sidebar .profile {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar .profile img {
            width: 100px;
            height: auto;
            object-fit: contain;
            margin-bottom: 10px;
        }

        /* MENU */
        .menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            color: #444;
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 8px;
            font-weight: 500;
            width: 100%;
            box-sizing: border-box;
            transition: background 0.3s;
        }

        .menu a:hover,
        .menu a.active {
            background-color: #f3b83f;
            color: white;
        }

        /* LOGOUT */
        .logout {
            text-align: center;
            padding: 15px;
            border-top: 1px solid #eee;
        }

        .logout a {
            display: inline-block;
            color: #f39c12;
            font-weight: bold;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 10px;
            border: 1px solid #f39c12;
            transition: all 0.3s;
        }

        .logout a:hover {
            background-color: #f39c12;
            color: white;
        }
        
        /* MAIN CONTENT */
        .main {
            margin-left: 250px; /* Sesuai lebar sidebar */
            padding: 20px;
            flex: 1;
            transition: margin-left 0.3s ease;
            box-sizing: border-box;
            width: calc(100% - 250px); /* Lebar sisa */
        }

        /* HEADER MOBILE (Hamburger) - Default Hidden */
        .mobile-header {
            display: none;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f3b83f;
        }


        .menu-toggle {
            font-size: 24px;
            background: none;
            border: none;
            cursor: pointer;
            color: #f3b83f;
        }

        /* OVERLAY (Background gelap saat sidebar terbuka di HP) */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        
        .overlay.active {
            display: block;
        }


        /* --- RESPONSIVE --- */
        @media (max-width: 768px) {
            /* Sidebar sembunyi ke kiri */
            .sidebar {
                transform: translateX(-100%);
                width: 260px;
            }
            
            /* Class aktif untuk memunculkan sidebar */
            .sidebar.active {
                transform: translateX(0);
            }

            /* Main content full width */
            .main {
                margin-left: 0;
                width: 100%;
                padding: 20px;
            }

            /* Tampilkan header mobile */
            .mobile-header {
                display: flex;
            }
        }
    </style>
</head>
<body>

    <div class="overlay" id="overlay"></div>

    <div class="sidebar" id="sidebar">
        <div>
            <div class="profile">
                <img src="<?= BASEURL; ?>/images/logo_paw.png" alt="logo">
            </div>

            <div class="menu">
                <a href="<?= BASEURL; ?>DashboardCustomer" class="<?= ($data['title'] ?? '') === 'Dashboard' ? 'active' : ''; ?>"> 
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="<?= BASEURL; ?>DashboardCustomer/profil" class="<?= ($data['title'] ?? '') === 'Profil Customer' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-user"></i>Profil</a>
                <a href="<?= BASEURL; ?>DashboardCustomer/penitipan" class="<?= ($data['title'] ?? '') === 'Cari Penitipan' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-magnifying-glass-location"></i> Cari Penitipan
                </a>
                <a href="<?= BASEURL; ?>DashboardCustomer/booking" class="<?= ($data['title'] ?? '') === 'Booking' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-receipt"></i> Booking
                </a>
                <a href="<?= BASEURL; ?>DashboardCustomer/status_penitipan" class="<?= ($data['title'] ?? '') === 'Status Penitipan' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-map-pin"></i> Status
                </a>
                <a href="<?= BASEURL; ?>DashboardCustomer/ulasan" class="<?= ($data['title'] ?? '') === 'Beri Ulasan' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-comment-dots"></i> Beri Ulasan
                </a>

            </div>
        </div>

        <div class="logout">
            <a href="<?= BASEURL; ?>auth/logout" id="btn-logout">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Keluar
            </a>
        </div>
    </div>

    <div class="main">
        <div class="mobile-header">
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
            <h3 style="margin:0; color:#333;">Pawtopia</h3>
            <div style="width: 24px;"></div> </div>

        <?php
        // ✅ Cek dan include view yang sesuai
        $pathFile = __DIR__ . '/../' . $data['content'] . '.php';
        if (!file_exists($pathFile)) {
            echo "<div class='dashboard-card' style='color:red;'>⚠️ File content tidak ditemukan: <br><small>$pathFile</small></div>";
        } else {
            include $pathFile;
        }
        ?>
    </div>

    <?php if (isset($_SESSION['flash'])): ?>
    <script>
        Swal.fire({
            title: "<?= $_SESSION['flash']['pesan']; ?>",
            text: "<?= $_SESSION['flash']['aksi']; ?>",
            icon: "<?= $_SESSION['flash']['tipe']; ?>",
            confirmButtonColor: "#f3b83f"
        });
    </script>
    <?php unset($_SESSION['flash']); endif; ?>

    <script>
        // --- LOGIC SIDEBAR MOBILE ---
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        // Buka Sidebar
        menuToggle.addEventListener('click', () => {
            sidebar.classList.add('active');
            overlay.classList.add('active');
        });

        // Tutup Sidebar (Klik Overlay)
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });

        // --- LOGIC LOGOUT ---
        const btnLogout = document.getElementById('btn-logout');
        if (btnLogout) {
            btnLogout.addEventListener('click', function(e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                Swal.fire({
                    title: 'Yakin ingin keluar?', 
                    text: "Sesi Anda akan segera berakhir.", 
                    icon: 'warning',
                    showCancelButton: true, 
                    confirmButtonColor: '#ff7675', 
                    cancelButtonColor: '#636e72',
                    confirmButtonText: 'Ya, Keluar', 
                    cancelButtonText: 'Batal', 
                    borderRadius: '15px'
                }).then((result) => {
                    if (result.isConfirmed) document.location.href = href;
                });
            });
        }
    </script>
</body>
</html>
