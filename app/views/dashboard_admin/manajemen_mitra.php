<?php
// BYPASS DEV CEPAT (hapus nanti pas produksi)
session_start();
$_SESSION['login_admin'] = true;

// data user sidebar (sementara hardcode)
$nama_user = "Elyazid";
$foto_user = "https://via.placeholder.com/50/fd7e14/white?text=User";

// koneksi database
require_once __DIR__ . '/../../core/Database.php';

$db   = new Database();
$conn = $db->getConnection();

$sql    = "SELECT * FROM mitra";
$result = $conn->query($sql);
if (!$result) {
    die("Query error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Pengguna (Admin) - PawTopia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .sidebar {
            position: fixed; top: 0; left: 0; height: 100%; width: 260px;
            background: linear-gradient(180deg, #f1f3f5 0%, #e9ecef 100%);
            padding-top: 20px; box-shadow: 2px 0 10px rgba(0,0,0,0.1); z-index: 1000;
        }
        .sidebar .nav-link  {
            color: #495057; padding: 14px 25px; margin: 8px 15px;
            border-radius: 12px; font-weight: 500; display: flex; align-items: center;
        }
        .sidebar .nav-link:hover {
            background-color: #ced4da; color: #212529;
        }
        .sidebar .nav-link img { width: 24px; height: 24px; margin-right: 15px; }
        .main-content { margin-left: 260px; padding: 30px; }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="text-center mb-5">
        <img src="/Pawtopia/public/images/logo_paw.png" alt="PawTopia Logo" height="60">
    </div>

    <div class="d-flex align-items-center mx-4 mb-4 p-3 bg-white rounded-3 shadow-sm">
        <img src="<?= $foto_user ?>" class="rounded-circle me-3" width="50" height="50" alt="User">
        <div>
            <strong class="d-block"><?= $nama_user ?></strong>
            <small class="text-muted">Admin</small>
        </div>
    </div>

    <ul class="nav flex-column px-2">
        <li class="nav-item">
            <a href="Dashboard_Admin.php" class="nav-link">
                <img src="/Pawtopia/public/images/icon_dashboard.png" alt="Dashboard">
                Dashboard
            </a>
        </li>
        <li class="nav-item">
        <a href="Manajemen_Pengguna.php" class="nav-link">
            <img src="/Pawtopia/public/images/manajemen_pengguna.png" alt="Pengguna">
            Manajemen Pengguna
        </a>
    </li>

         <li class="nav-item">
        <a href="Manajemen_Mitra.php"
           class="nav-link"
           style="background-color:#FAA433; color:#212529;">
            <img src="/Pawtopia/public/images/icon_mitra.png" alt="Mitra">
            Manajemen Mitra
        </a>
    </li>
        <li class="nav-item">
            <a href="Monitoring_Transaksi.php" class="nav-link">
                <img src="/Pawtopia/public/images/monitoring_transaksi.png" alt="Transaksi">
                Monitoring Transaksi
            </a>
        </li>
        <li class="nav-item">
            <a href="Laporan_Sistem.php" class="nav-link">
                <img src="/Pawtopia/public/images/laporan_sistem.png" alt="Laporan">
                Laporan Sistem
            </a>
        </li>
    </ul>
</div>

<div class="main-content">

    <!-- Bar atas: tombol + logo, DI LUAR card -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="Tambah_Pengguna.php"
           class="btn btn-warning text-white px-4 py-2 fw-bold"
           style="white-space: nowrap;">
            <i class="fas fa-plus me-2"></i> Tambah Pengguna
        </a>
    </div>

    <!-- Card putih berisi cari + tabel -->
    <div class="bg-white rounded-4 shadow p-4">

        <div class="mb-4">
            <label class="form-label fw-bold">Cari pengguna :</label>
            <input type="text" class="form-control form-control-lg rounded-pill"
                   placeholder="Masukkan nama / email / no HP">
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>alamat</th>
                        <th>No HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $no = 1;
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama_petshop']); ?></td>
                        <td><?= htmlspecialchars($row['alamat']); ?></td>
                        <td><?= htmlspecialchars($row['no_hp']); ?></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                        type="button" data-bs-toggle="dropdown">
                                    Aksi
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Tolak</a></li>
                                    <li><a class="dropdown-item" href="#">Terima</a></li>
                                    <li><a class="dropdown-item" href="#">Menunggu Pembayaran</a></li>
                                    <li><a class="dropdown-item" href="#">selesai</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            Belum ada data pengguna. Silakan tambah pengguna baru.
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <a href="logout.php" class="text-decoration-none text-secondary">
                ← Keluar
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
</body>
</html>
