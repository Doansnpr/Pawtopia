<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $title ?? 'Dashboard Mitra'; ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* --- TEMA GLOBAL --- */
        :root {
            --primary-orange: #FF9F43;
            --primary-orange-light: #FFF2E3;
            --text-dark: #2D3436;
            --text-grey: #636E72;
            --bg-color: #F8F9FD;
            --white: #FFFFFF;
            --shadow-soft: 0 5px 15px rgba(0, 0, 0, 0.05);
            --danger-red: #ff7675;
            --danger-light: #ffecec;
            --header-height: 80px; /* Variabel tinggi header */
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-dark);
            overflow-x: hidden; 
        }

        /* --- HEADER NAVIGATION --- */
        .header-bar {
            background-color: #ffffff;
            display: flex; justify-content: space-between; align-items: center;
            padding: 0 40px; 
            height: var(--header-height); 
            position: fixed;
            top: 0; left: 0; right: 0; z-index: 1000;
            box-shadow: var(--shadow-soft);
            width: 100%;
        }
    
        .notif-icon {
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; position: relative;
            width: 40px; height: 40px; margin-right: 10px;
        }
        .notif-icon:hover { color: var(--primary-orange); transform: scale(1.1); }

        /* Badge Merah (Angka) */
        .notif-badge {
            position: absolute; top: -2px; right: -2px;
            background-color: #ff7675; color: white;
            font-size: 0.65rem; font-weight: 700;
            width: 18px; height: 18px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid #fff;
        }

        /* Dropdown Notifikasi (Desktop Default) */
        .notif-dropdown {
            display: none; 
            position: absolute; top: 60px; right: -80px;
            width: 320px;
            background: var(--white); border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            border: 1px solid #f0f0f0; z-index: 1002;
            overflow: hidden; animation: slideDown 0.2s ease-out;
        }
        .notif-dropdown.show { display: block; }

        .notif-header {
            padding: 15px; background: #fafafa; 
            border-bottom: 1px solid #eee; font-weight: 700; font-size: 0.9rem;
            display: flex; justify-content: space-between; align-items: center;
        }
        .mark-read { font-size: 0.75rem; color: var(--primary-orange); text-decoration: none; }

        .notif-list { list-style: none; margin: 0; padding: 0; max-height: 300px; overflow-y: auto; }
        
        .notif-item {
            display: flex; gap: 12px; padding: 15px;
            border-bottom: 1px solid #f9f9f9; transition: 0.2s;
            text-decoration: none; color: var(--text-dark); align-items: flex-start;
        }
        .notif-item:hover { background-color: #fff8e1; }
        .notif-item:last-child { border-bottom: none; }

        .status-icon-circle {
            width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: 1rem;
        }
        .bg-blue-soft { background: #e3f2fd; color: #0984e3; }
        .bg-green-soft { background: #e0f9f4; color: #00b894; }
        .bg-red-soft { background: #ffecec; color: #d63031; }

        .notif-content h5 { margin: 0 0 4px 0; font-size: 0.85rem; font-weight: 600; }
        .notif-content p { margin: 0 0 5px 0; font-size: 0.75rem; color: var(--text-grey); }
        .notif-time { font-size: 0.7rem; color: #aaa; display: block; }

        .notif-footer { padding: 10px; text-align: center; border-top: 1px solid #eee; background: #fff; }
        .view-all-btn { font-size: 0.8rem; font-weight: 600; color: var(--text-dark); text-decoration: none; }
        .view-all-btn:hover { color: var(--primary-orange); }

        .notif-list::-webkit-scrollbar { width: 5px; }
        .notif-list::-webkit-scrollbar-thumb { background: #ddd; border-radius: 10px; }

        /* LOGO */
        .logo { display: flex; align-items: center; height: 100%; }
        .logo img { height: 100px; width: auto; object-fit: contain; transition: transform 0.3s; }
        
        /* NAVIGASI DESKTOP */
        .nav-links {
            display: flex; gap: 10px; align-items: center;
            background: #fff; padding: 5px; border-radius: 50px;
        }
        .nav-item {
            text-decoration: none; color: var(--text-grey);
            padding: 10px 20px; border-radius: 30px;
            transition: all 0.3s ease; font-size: 0.95rem; font-weight: 500;
            display: flex; align-items: center; gap: 8px; white-space: nowrap;
        }
        .nav-item:hover { color: var(--primary-orange); background-color: var(--primary-orange-light); }
        .nav-item.active {
            background: linear-gradient(135deg, #FF9F43, #FF7F50);
            color: #fff; font-weight: 600;
            box-shadow: 0 4px 15px rgba(255, 159, 67, 0.3); transform: translateY(-1px);
        }

        /* HEADER RIGHT & PROFILE */
        .header-right { display: flex; align-items: center; gap: 15px; height: 100%; }
        .notif-wrapper, .profile-wrapper { position: relative; display: flex; align-items: center; height: 100%; }

        .profile-pic {
            width: 45px; height: 45px;
            background-color: #eee; border-radius: 50%;
            border: 2px solid var(--primary-orange);
            background-size: cover; background-position: center;
            cursor: pointer; transition: 0.3s; flex-shrink: 0;
        }
        .profile-pic:hover { transform: scale(1.05); }

        /* DROPDOWN MENU PROFILE */
        .dropdown-menu {
            display: none; position: absolute; top: 65px; right: 0;
            width: 220px; background: var(--white); border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow: hidden;
            border: 1px solid #f0f0f0; animation: slideDown 0.2s ease-out; z-index: 1001;
        }
        .dropdown-menu.show { display: block; }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-header { padding: 15px 20px; border-bottom: 1px solid #f1f2f6; background-color: #fafafa; }
        .dropdown-header h4 { margin: 0; font-size: 0.95rem; color: var(--text-dark); }
        .dropdown-header p { margin: 2px 0 0 0; font-size: 0.75rem; color: var(--text-grey); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .dropdown-item {
            display: flex; align-items: center; gap: 10px; padding: 12px 20px;
            color: var(--text-grey); text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.2s;
        }
        .dropdown-item:hover { background-color: var(--primary-orange-light); color: var(--primary-orange); padding-left: 25px; }
        .dropdown-item.logout { color: var(--danger-red); border-top: 1px solid #f1f2f6; }
        .dropdown-item.logout:hover { background-color: var(--danger-light); color: #d63031; }

        /* CONTENT WRAPPER */
        .content-wrapper {
            max-width: 1450px; margin: 100px auto 30px auto; 
            background-color: var(--white); border-radius: 20px; padding: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); min-height: 80vh;
        }

        /* --- RESPONSIVE MOBILE CSS (Bagian Penting) --- */
        @media (max-width: 900px) {
            /* 1. Header Layout */
            .header-bar {
                height: 70px;
                padding: 0 15px;
            }

            .logo img {
                height: 40px; /* Logo lebih kecil */
            }

            /* 2. Navigasi Pindah ke Bawah (Bottom Bar) */
            .nav-links {
                position: fixed; 
                bottom: 0; left: 0; right: 0;
                background: white; 
                display: flex;
                justify-content: space-around; /* Jarak merata */
                align-items: center;
                padding: 10px 5px 15px 5px; /* Sedikit padding bawah untuk HP modern */
                box-shadow: 0 -5px 20px rgba(0,0,0,0.08); 
                border-top: 1px solid #eee; 
                z-index: 2000;
                border-radius: 20px 20px 0 0;
                width: 100%;
                margin: 0;
            }

            .nav-item {
                flex-direction: column; 
                font-size: 0.7rem; /* Font kecil */
                padding: 8px 5px; 
                gap: 5px; 
                border-radius: 10px;
                background: transparent;
                flex: 1; /* Lebar merata */
                text-align: center;
            }

            .nav-item i { font-size: 1.2rem; margin-bottom: 2px; }
            .nav-item span { display: block; }
            
            /* Active State di Mobile */
            .nav-item.active {
                background: transparent; 
                color: var(--primary-orange);
                box-shadow: none;
                transform: none;
            }
            .nav-item.active i { transform: translateY(-3px); transition: 0.3s; }

            /* 3. Dropdown Menjadi Fixed/Tengah di Layar */
            .notif-dropdown {
                position: fixed;
                top: 80px; 
                left: 50%;
                transform: translateX(-50%);
                width: 90vw; /* Lebar 90% layar */
                max-width: 400px;
                right: auto;
            }

            .dropdown-menu {
                position: fixed;
                top: 80px;
                left: 50%;
                transform: translateX(-50%);
                width: 90vw;
                max-width: 300px;
                right: auto;
            }

            /* 4. Penyesuaian Konten */
            .content-wrapper {
                margin-top: 85px; /* Jarak dari header */
                margin-bottom: 90px; /* PENTING: Jarak bawah agar tidak tertutup nav bar */
                padding: 15px;
                border-radius: 15px;
            }

            /* Badge Notifikasi */
            .notif-badge { top: 0; right: 0; }
        }
        
        /* Untuk layar sangat kecil (iPhone SE / Galaxy Fold) */
        @media (max-width: 380px) {
            .logo img { height: 35px; }
            .nav-item span { font-size: 0.6rem; }
            .notif-icon { margin-right: 5px; }
            .header-right { gap: 10px; }
        }
    </style>
</head>
<body>

<header class="header-bar">
    <div class="logo">  
         <img src="<?= BASEURL; ?>/images/logo_pawtopia.png" alt="Pawtopia Logo">
    </div>
    
    <nav class="nav-links">
        <a href="?page=dashboard" class="nav-item <?= ($_GET['page'] ?? 'dashboard') === 'dashboard' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i> <span>Home</span> </a>
        <a href="?page=profil" class="nav-item <?= ($_GET['page'] ?? '') === 'profil' ? 'active' : ''; ?>">
            <i class="fas fa-user-circle"></i> <span>Profil</span>
        </a>
        <a href="?page=reservasi" class="nav-item <?= ($_GET['page'] ?? '') === 'reservasi' ? 'active' : ''; ?>">
            <i class="fas fa-calendar-alt"></i> <span>Booking</span>
        </a>
        <a href="?page=status" class="nav-item <?= ($_GET['page'] ?? '') === 'status' ? 'active' : ''; ?>">
            <i class="fas fa-cat"></i> <span>Status</span>
        </a>
        <a href="?page=ulasan" class="nav-item <?= ($_GET['page'] ?? '') === 'ulasan' ? 'active' : ''; ?>">
            <i class="fas fa-comment"></i> <span>Ulasan</span>
        </a>
        <a href="?page=laporan" class="nav-item <?= ($_GET['page'] ?? '') === 'laporan' ? 'active' : ''; ?>">
            <i class="fas fa-chart-pie"></i> <span>Laporan</span>
        </a>
    </nav>

    <div class="header-right">
        <div class="notif-wrapper" id="notifWrapper">
            <div class="notif-icon" id="notifBtn">
                <i class="fas fa-bell" style="font-size: 1.4rem; color: var(--text-grey);"></i>
                <?php if (!empty($data['notif_count']) && $data['notif_count'] > 0): ?>
                    <span class="notif-badge" id="notifBadge"><?= $data['notif_count'] > 9 ? '9+' : $data['notif_count'] ?></span>
                <?php endif; ?>
            </div>

            <div class="notif-dropdown" id="notifDropdown">
                <div class="notif-header">
                    <span>Notifikasi</span>
                </div>
                
                <div class="notif-list">
                    <?php if (empty($data['notifications'])): ?>
                        <div style="padding: 20px; text-align: center; color: #aaa; font-size: 0.85rem;">
                            <i class="far fa-bell-slash" style="font-size: 1.5rem; margin-bottom: 5px;"></i><br>
                            Tidak ada notifikasi baru
                        </div>
                    <?php else: ?>
                        <?php foreach ($data['notifications'] as $notif): 
                            // Default value
                            $statusClass = 'bg-blue-soft'; 
                            $iconClass = 'fa-calendar-plus'; 
                            $pesan = 'Booking baru masuk';

                            // LOGIC PERBAIKAN STATUS
                            if ($notif['status'] == 'Menunggu Konfirmasi') { 
                                $statusClass = 'bg-blue-soft'; 
                                $iconClass = 'fa-user-clock'; 
                                $pesan = 'Menunggu konfirmasi Anda'; 
                            } 
                            elseif ($notif['status'] == 'Verifikasi DP') { // <--- UBAH INI (Sesuai DB)
                                $statusClass = 'bg-green-soft'; 
                                $iconClass = 'fa-money-bill-wave'; 
                                $pesan = 'Pembayaran DP perlu diverifikasi'; 
                            } 
                            elseif ($notif['status'] == 'Dibatalkan') { 
                                $statusClass = 'bg-red-soft'; 
                                $iconClass = 'fa-times'; 
                                $pesan = 'Booking telah dibatalkan'; 
                            }
                        ?>
                        <a href="?page=reservasi" class="notif-item">
                            <div class="status-icon-circle <?= $statusClass ?>">
                                <i class="fas <?= $iconClass ?>"></i>
                            </div>
                            <div class="notif-content">
                                <h5><?= htmlspecialchars($notif['nama_lengkap']) ?></h5>
                                <p><?= $pesan ?></p>
                                <span class="notif-time"><i class="far fa-clock"></i> <?= date('d M, H:i', strtotime($notif['tgl_booking'])) ?></span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="notif-footer">
                    <a href="?page=reservasi" class="view-all-btn">Lihat Semua <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="profile-wrapper">
           <?php 
                $fotoName = $data['mitra_profile']['foto_profil'] ?? ''; 
                $pathUpload  = '/pawtopia/public/uploads/mitra/';
                $pathDefault = BASEURL . '/images/profile_placeholder.jpg'; 
                if (!empty($fotoName)) {
                    $fotoUrl = $pathUpload . htmlspecialchars($fotoName);
                } else {
                    $fotoUrl = $pathDefault;
                }
                $finalFotoUrl = $fotoUrl . '?v=' . time();
            ?>

            <div class="profile-pic" 
                id="profileBtn" 
                style="background-image: url('<?= $finalFotoUrl ?>');"> 
            </div>

            <div class="dropdown-menu" id="profileDropdown">
                <div class="dropdown-header">
                    <h4>Halo, <?= explode(' ', $data['mitra_profile']['nama_pemilik'] ?? 'Mitra')[0]; ?>! 👋</h4>
                    <p style="font-weight: 600; color: var(--primary-orange);">
                        <?= $data['mitra_profile']['nama_petshop'] ?? 'Petshop'; ?>
                    </p>
                </div>
                
                <a href="?page=profil" class="dropdown-item"><i class="fas fa-user-cog"></i> Edit Profil</a>
                <a href="<?= BASEURL; ?>auth/logout" class="dropdown-item logout" id="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </a>
            </div>
        </div>
    </div>
</header>

<div class="content-wrapper">
    <?php 
        if (isset($content)) {
            $full_path = '../app/views/' . $content . '.php';
            if (file_exists($full_path)) {
                require_once $full_path; 
            } else {
                echo "<div style='color:red; text-align:center;'>File konten tidak ditemukan: $full_path</div>";
            }
        }
    ?>
</div>

<?php if (isset($_SESSION['flash'])): ?>
<script>
    Swal.fire({
        title: "<?= $_SESSION['flash']['pesan']; ?>",
        text: "<?= $_SESSION['flash']['aksi']; ?>",
        icon: "<?= $_SESSION['flash']['tipe']; ?>",
        confirmButtonColor: "#FF9F43",
        borderRadius: "15px"
    });
</script>
<?php unset($_SESSION['flash']); endif; ?>


<script>
    const notifBtn = document.getElementById('notifBtn');
    const notifDropdown = document.getElementById('notifDropdown');
    const profileBtn = document.getElementById('profileBtn');
    const profileDropdown = document.getElementById('profileDropdown');

    // Toggle Notifikasi
    notifBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        notifDropdown.classList.toggle('show');
        profileDropdown.classList.remove('show');
    });

    // Toggle Profil
    profileBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        profileDropdown.classList.toggle('show');
        notifDropdown.classList.remove('show');
    });

    // Klik di luar menutup dropdown
    window.addEventListener('click', function(e) {
        if (!document.getElementById('notifWrapper').contains(e.target)) {
            notifDropdown.classList.remove('show');
        }
        if (!document.querySelector('.profile-wrapper').contains(e.target)) {
            profileDropdown.classList.remove('show');
        }
    });

    const btnLogout = document.getElementById('btn-logout');
    if (btnLogout) {
        btnLogout.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            Swal.fire({
                title: 'Yakin ingin keluar?', icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#ff7675', cancelButtonColor: '#636e72',
                confirmButtonText: 'Ya', cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) document.location.href = href;
            });
        });
    }
</script>

</body>
</html>