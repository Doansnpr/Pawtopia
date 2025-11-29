<?php
// BYPASS DEV CEPAT (hapus nanti pas produksi)
session_start();
$_SESSION['login_admin'] = true;
$nama_user = "kapron";
$foto_user = "https://via.placeholder.com/50/fd7e14/white?text=User"; // ganti foto asli kalau ada
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin - PawTopia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%); font-family: 'Segoe UI', sans-serif; min-height: 100vh; }
        .sidebar {
            position: fixed; top: 0; left: 0; height: 100%; width: 260px;
            background: linear-gradient(180deg, #f1f3f5 0%, #e9ecef 100%);
            padding-top: 20px; box-shadow: 2px 0 10px rgba(0,0,0,0.1); z-index: 1000;
        }
        .sidebar .nav-link {
            color: #495057; padding: 14px 25px; margin: 8px 15px;
            border-radius: 12px; font-weight: 500; display: flex; align-items: center;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: #ced4da; color: #212529;
        }
        .sidebar .nav-link img { width: 24px; height: 24px; margin-right: 15px; }
        .main-content { margin-left: 260px; padding: 40px; }
        .card-stat {
            background: #ffffff; border-radius: 20px; padding: 20px 30px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08); text-align: center; transition: 0.3s;
        }
        .card-stat:hover { transform: translateY(-8px); box-shadow: 0 15px 35px rgba(0,0,0,0.12); }
        .card-stat h5 { font-size: 1.1rem; color: #6c757d; margin-bottom: 10px; }
        .card-stat .big-number { font-size: 3rem; font-weight: bold; color: #212529; }
        .wave {
            position: absolute; bottom: 0; left: 0; width: 100%; height: 150px;
            background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg"><path fill="%23fd7e1433" fill-opacity="0.2" d="M0,160L48,176C96,192,192,224,288,213.3C384,203,480,149,576,133.3C672,117,768,139,864,170.7C960,203,1056,245,1152,245.3C1248,245,1344,203,1392,181.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') bottom no-repeat;
            background-size: cover;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark">PawTopia</h2>
        </div>

        <div class="d-flex align-items-center mx-4 mb-4 p-3 bg-white rounded-3 shadow-sm">
            <img src="<?= $foto_user ?>" class="rounded-circle me-3" width="50" height="50" alt="User">
            <div>
                <strong class="d-block"><?= $nama_user ?></strong>
                <small class="text-muted">Admin</small>
            </div>
        </div>

        <ul class="nav flex-column px-2">
            <li class="nav-item"><a href="dashboard_admin.php" class="nav-link active"><img src="https://img.icons8.com/ios-filled/50/495057/dashboard-layout.png" alt="Dashboard"> Dashboard</a></li>
            <li class="nav-item"><a href="manajemen_pengguna/Manajemen_Pengguna.php" class="nav-link"><img src="https://img.icons8.com/ios-filled/50/495057/user-group-man-woman.png" alt="Pengguna"> Manajemen Pengguna</a></li>
            <li class="nav-item"><a href="#" class="nav-link"><img src="https://img.icons8.com/ios-filled/50/495057/transaction-list.png" alt="Transaksi"> Monitoring Transaksi</a></li>
            <li class="nav-item"><a href="#" class="nav-link"><img src="https://img.icons8.com/ios-filled/50/495057/report-card.png" alt="Laporan"> Laporan Sistem</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content position-relative">
        <div class="d-flex justify-content-end mb-4">
            <img src="../../../public/images/logo_paw.png" alt="PawTopia Logo" height="80">
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card-stat">
                    <h5>Total Mitra Aktif</h5>
                    <div class="big-number">2</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-stat">
                    <h5>Total Transaksi Bulan Ini</h5>
                    <div class="big-number">15</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-stat">
                    <h5>Kamar Terpakai / Tersedia</h5>
                    <div class="big-number">8 / 20</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-stat">
                    <h5>Pengguna Terdaftar</h5>
                    <div class="big-number">45</div>
                </div>
            </div>
        </div>

        <!-- Gelombang dekorasi di bawah -->
        <div class="wave"></div>

        <!-- Tombol Keluar -->
        <div class="position-absolute bottom-0 start-0 ms-4 mb-4">
            <a href="#" class="text-decoration-none text-secondary fs-5">
                ← Keluar
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>